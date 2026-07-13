<?php

namespace App\Http\Controllers\Dashboard\WorkFlow;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;

// Illuminate\Support
use Illuminate\Support\Facades\Auth;

// App\Models
use App\Models\WorkFlow\Invoice;
use App\Models\WorkFlow\Accountant;
use App\Models\WorkFlow\JcfStatus;
use App\Models\WorkFlow\JobRequest;
use App\Models\WorkFlow\MailCenter;
use App\Models\WorkFlow\Bank;
use App\Models\WorkFlow\BankTransaction;
use App\Models\WorkFlow\Payment;
use App\Models\WorkFlow\Qutation;
use App\Models\Persons\Client;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

// Other
use DB, DataTables, Storage;

class InvoiceController extends Controller
{
		public $page_name = 'Invoice';

		private function normalizeMoney($value): float
		{
				if ($value === null) {
						return 0.0;
				}

				if (is_numeric($value)) {
						return round((float)$value, 2);
				}

				$normalized = preg_replace('/[^\d\.\-]/', '', (string)$value);
				if ($normalized === '' || $normalized === '-' || $normalized === '.') {
						return 0.0;
				}

				return round((float)$normalized, 2);
		}

		private function normalizeRate($value): float
		{
				return max(0, round($this->normalizeMoney($value), 2));
		}

		private function normalizeDateInput($value): ?string
		{
				if ($value === null) {
						return null;
				}

				$value = trim((string)$value);
				if ($value === '') {
						return null;
				}

				$formats = ['d-m-Y', 'Y-m-d', 'd/m/Y', 'Y/m/d', 'd.m.Y', 'm/d/Y'];
				foreach ($formats as $format) {
						try {
								$parsed = Carbon::createFromFormat($format, $value);
								if ($parsed !== false) {
										return $parsed->format('Y-m-d');
								}
						} catch (\Throwable $e) {
								// try next format
						}
				}

				try {
						return Carbon::parse($value)->format('Y-m-d');
				} catch (\Throwable $e) {
						return null;
				}
		}

		private function nextPaymentCode(): string
		{
				$prefix = 'PAY-'.date('y').'-';
				$lastCode = Payment::where('code', 'like', $prefix.'%')
						->orderByDesc('id')
						->value('code');

				$next = 1;
				if ($lastCode && preg_match('/(\d+)$/', $lastCode, $matches)) {
						$next = ((int)$matches[1]) + 1;
				}

				return $prefix.str_pad((string)$next, 3, '0', STR_PAD_LEFT);
		}

		private function nullableString($value): ?string
		{
				if ($value === null) {
						return null;
				}

				$value = trim((string)$value);
				return $value === '' ? null : $value;
		}

		private function getBankOptions(): array
		{
				return Bank::query()
						->where('is_active', 1)
						->orderBy('code', 'desc')
						->get(['id', 'code', 'bank_name', 'account_name', 'currency', 'current_balance'])
						->mapWithKeys(function (Bank $bank) {
								$label = trim($bank->code.' - '.$bank->bank_name.' - '.$bank->account_name);
								$label .= ' ('.$bank->currency.' '.number_format((float)$bank->current_balance, 2, '.', '').')';
								return [$bank->id => $label];
						})
						->toArray();
		}

		private function syncInlinePayment(Request $request, Invoice $invoice): void
		{
				if (!$request->boolean('already_paid')) {
						return;
				}

				$payment = null;
				$existingPaymentId = (int)$request->input('existing_payment_id');
				if ($existingPaymentId > 0) {
						$payment = Payment::where('id', $existingPaymentId)
								->where('invoice_id', $invoice->id)
								->first();
				}

				if (!$payment) {
						$payment = $invoice->payments()->orderBy('id')->first();
				}

				$paymentDate = $this->normalizeDateInput($request->input('payment_date'))
						?: Carbon::now()->format('Y-m-d');
				$paymentAmountInput = $request->input('payment_amount');
				$paymentAmount = $paymentAmountInput === null || trim((string)$paymentAmountInput) === ''
						? $this->normalizeMoney($invoice->total)
						: $this->normalizeMoney($paymentAmountInput);
				$bankAccountId = (int)$request->input('payment_bank_account_id');
				if ($bankAccountId > 0) {
						$bank = Bank::find($bankAccountId);
						if (!$bank || (int)$bank->is_active !== 1) {
								throw ValidationException::withMessages([
										'payment_bank_account_id' => 'Selected bank account is not active.',
								]);
						}
				} else {
						$bankAccountId = null;
				}
				$payload = [
						'job_request_id' => $invoice->job_request_id,
						'invoice_id' => $invoice->id,
						'bank_account_id' => $bankAccountId,
						'payment_date' => $paymentDate,
						'amount' => $paymentAmount,
						'method' => $this->nullableString($request->input('payment_method')),
						'reference_no' => $this->nullableString($request->input('payment_reference_no')),
						'status' => $this->nullableString($request->input('payment_status')) ?: 'received',
						'note' => $this->nullableString($request->input('payment_note')),
				];

				if ($payment) {
						$payment->fill($payload + ['updated' => 1]);
						$payment->save();
						$this->syncInlinePaymentBankTransaction($payment);
						return;
				}

				$payment = Payment::create($payload + [
						'code' => $this->nextPaymentCode(),
						'user_id' => Auth::id(),
						'approved_by' => null,
						'sync' => 0,
						'updated' => null,
				]);
				$this->syncInlinePaymentBankTransaction($payment);
		}

		private function syncInlinePaymentBankTransaction(Payment $payment): void
		{
				$transaction = BankTransaction::query()
						->where('payment_id', $payment->id)
						->where('source_type', 'payment_auto')
						->orderByDesc('id')
						->first();

				$targetBankId = (int)($payment->bank_account_id ?? 0);
				$targetAmount = round((float)($payment->amount ?? 0), 2);
				$targetDate = $payment->payment_date ?: Carbon::today()->format('Y-m-d');
				$direction = $this->resolvePaymentDirection($payment);
				$category = $direction === 'in' ? 'deposit' : 'withdraw';
				$targetStatus = in_array((string)$payment->status, ['approved', 'received'], true) ? 'pending_approval' : 'draft';
				$targetCounterAccountId = $this->defaultCounterAccountIdByDirection($direction);

				if ($targetBankId <= 0 || $targetAmount <= 0) {
						if ($transaction) {
								if ((int)$transaction->is_posted === 1) {
										throw ValidationException::withMessages([
												'payment_bank_account_id' => 'Cannot unlink payment bank because linked posted bank transaction exists.',
										]);
								}
								$bankId = (int)$transaction->bank_account_id;
								$transaction->delete();
								$this->recalculateBankBalanceById($bankId);
						}
						return;
				}

				$targetBank = Bank::find($targetBankId);
				if (!$targetBank || (int)$targetBank->is_active !== 1) {
						throw ValidationException::withMessages([
								'payment_bank_account_id' => 'Selected bank account is not active.',
						]);
				}

				$oldBankId = $transaction ? (int)$transaction->bank_account_id : 0;
				if ($transaction && (int)$transaction->is_posted === 1) {
						$postedFieldsChanged = (
								$oldBankId !== $targetBankId ||
								round((float)$transaction->amount, 2) !== $targetAmount ||
								(string)$transaction->direction !== (string)$direction
						);

						if ($postedFieldsChanged) {
								throw ValidationException::withMessages([
										'payment_bank_account_id' => 'Cannot change bank/amount/direction because linked bank transaction is posted.',
								]);
						}

						return;
				}

				$payload = [
						'bank_account_id' => $targetBankId,
						'job_request_id' => $payment->job_request_id,
						'direction' => $direction,
						'category' => $category,
						'source_type' => 'payment_auto',
						'transaction_date' => $targetDate,
						'amount' => $targetAmount,
						'reference_no' => $payment->reference_no ?: $payment->code,
						'status' => $targetStatus,
						'note' => 'Auto-created from Invoice Inline Payment '.$payment->code,
						'is_posted' => 0,
						'approved_by' => null,
						'approved_at' => null,
						'counter_account_id' => $targetCounterAccountId,
						'accountant_id' => null,
						'updated' => 1,
				];

				if (!$transaction) {
						BankTransaction::create(array_merge($payload, [
								'code' => $this->nextBankTransactionCode(),
								'payment_id' => $payment->id,
								'user_id' => Auth::id(),
								'sync' => 0,
								'updated' => null,
						]));
				} else {
						$transaction->update($payload);
				}

				$this->recalculateBankBalanceById($targetBankId);
				if ($oldBankId > 0 && $oldBankId !== $targetBankId) {
						$this->recalculateBankBalanceById($oldBankId);
				}
		}

		private function resolvePaymentDirection(Payment $payment): string
		{
				$jobRequest = $payment->jobRequest;
				if ($jobRequest && !empty($jobRequest->supplier_id) && empty($jobRequest->client_id)) {
						return 'out';
				}

				return 'in';
		}

		private function defaultCounterAccountIdByDirection(string $direction): ?int
		{
				$code = $direction === 'out' ? '2100' : '1200';
				$account = DB::table('chart_accounts')
						->where('code', $code)
						->where('is_active', 1)
						->first(['id']);

				if ($account) {
						return (int)$account->id;
				}

				$fallbackType = $direction === 'out' ? 'liability' : 'asset';
				$fallback = DB::table('chart_accounts')
						->where('type', $fallbackType)
						->where('is_active', 1)
						->orderBy('level')
						->orderBy('id')
						->first(['id']);

				return $fallback ? (int)$fallback->id : null;
		}

		private function recalculateBankBalanceById(int $bankId): void
		{
				if ($bankId <= 0) {
						return;
				}

				$bank = Bank::find($bankId);
				if (!$bank) {
						return;
				}

				$postedIn = $bank->transactions()->where('is_posted', 1)->where('direction', 'in')->sum('amount');
				$postedOut = $bank->transactions()->where('is_posted', 1)->where('direction', 'out')->sum('amount');
				$newBalance = round((float)$bank->opening_balance + (float)$postedIn - (float)$postedOut, 2);

				DB::table('bank_accounts')
						->where('id', $bank->id)
						->update([
								'current_balance' => $newBalance,
								'updated_at' => now(),
						]);
		}

		private function nextBankTransactionCode(): string
		{
				$prefix = 'BTX-'.date('y').'-';
				$lastCode = BankTransaction::where('code', 'like', $prefix.'%')
						->orderByDesc('id')
						->value('code');

				$next = 1;
				if ($lastCode && preg_match('/(\d+)$/', $lastCode, $matches)) {
						$next = ((int)$matches[1]) + 1;
				}

				return $prefix.str_pad((string)$next, 4, '0', STR_PAD_LEFT);
		}

    public function __construct()
    {
	      $this->middleware('auth');
	      $this->authorizeResource(Invoice::class, 'invoice');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
			$invoiceCompanyType = $request->get('type') ? $request->get('type') : Invoice::$INVOICE_RSE_TYPE;
	      $invoices = Invoice::query()->where('invoice_company_type', $invoiceCompanyType)->count();
				$jcfCreateOptions = JobRequest::query()
						->with(['client:id,name', 'supplier:id,name'])
						->whereNotNull('client_id')
						->whereHas('qutation')
						->whereDoesntHave('invoice')
						->orderBy('code', 'desc')
						->get(['id', 'code', 'client_id', 'supplier_id'])
						->mapWithKeys(function (JobRequest $jobRequest) {
								$ownerName = $jobRequest->client->name ?? $jobRequest->supplier->name ?? '';
								$label = $jobRequest->code . ($ownerName !== '' ? ' - ' . $ownerName : '');
								return [$jobRequest->id => $label];
						})
						->toArray();
				return view('layouts.work-flow.invoice.index', [
					'page_name' => $this->page_name('All', $this->page_name),
					'invoices' => $invoices,
					'invoice_company_type' => $invoiceCompanyType,
					'jcf_create_options' => $jcfCreateOptions,
				]);
    }

		public function getDataForDataTable(Request $request)
		{
				$invoiceCompanyType = $request->get('invoice_company_type') ? $request->get('invoice_company_type') : Invoice::$INVOICE_RSE_TYPE;
				$data = Invoice::query()->where('invoice_company_type', $invoiceCompanyType)
								->leftJoin('users', 'invoices.user_id', '=', 'users.id')
								->leftJoin('employees', 'employees.id', '=', 'users.employee_id')
								->leftJoin('job_requests', 'job_requests.id', '=', 'invoices.job_request_id')
                                ->leftJoin('client_departments', 'job_requests.client_department_id', '=', 'client_departments.id')
                                ->leftJoin('clients', 'job_requests.client_id', '=', 'clients.id')
								->leftJoin('suppliers', 'job_requests.supplier_id', '=', 'suppliers.id')
								->select([
										'invoices.id as invoice_id',
										'invoices.code as code',
										'invoices.cpo as cpo',
										'invoices.total as total',
										'invoices.type as currency',
										'employees.name as employee',
										'job_requests.id as job_request_id',
										'job_requests.code as job_request_code',
										'job_requests.client_id as client_id',
										'job_requests.supplier_id as supplier_id',
										'job_requests.deploc as deploc',
										'client_departments.name as client_department',
										'suppliers.name as sup',
										'clients.name as cli',
								]);

				return  Datatables::of($data)
									->addColumn('job_request_code', function($row){
												$report_code = "";
												if(Auth::user()->can('view', JobRequest::find($row->job_request_id)))
												{
														$report_code .= "<a href=".route('jobRequest.show', $row->job_request_id).">";
												}

												$report_code .= $row->job_request_code;

												if(Auth::user()->can('view', JobRequest::find($row->job_request_id)))
												{
														$report_code .= "</a>";
												}
												return $report_code;
										})
									->addColumn('client', function ($row) {
												$client = "";
												if ($row->client_id != null && $row->supplier_id == null)
												{
														if (Auth::user()->can('view', Client::find($row->client_id)))
														{
																$client .= "<a href=".route('client.show', $row->client_id).">";
														}

														$client .= $row->cli;

														if (Auth::user()->can('view', Client::find($row->client_id)))
														{
																$client .= "</a>";
														}
												}
												else
												{
														$client .= $row->sup;
												}
												return $client;
									})
								->addColumn('action', function ($row) use ($invoiceCompanyType){
											$btn = "";

											if (Auth::user()->can('update', Invoice::find($row->invoice_id)))
											{
													$btn .= '<a href="'.route('invoice.invoiceWithJobRequestNewEdit', $row->job_request_id).'" class="btn btn-icon btn-info mr-0"><i class="la la-pencil"></i></a>';
											}

											if (Auth::user()->can('delete', Invoice::find($row->invoice_id)))
											{
													$btn .= '<button type="button" data-id="'.$row->invoice_id.'" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>';
											}

											$btn .= '<div class="btn-group ml-0">
																	<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"></button>
																	<div class="dropdown-menu">';

											if (Auth::user()->can('create', MailCenter::class) && Auth::user()->can('view', Invoice::find($row->invoice_id)))
											{
													$btn .= '<a class="dropdown-item" href="'.route('mailCenter.compose.related', ['relatedType' => 'invoice', 'relatedId' => $row->invoice_id]).'">Send via Rig MailCenter</a>';
											}

											if (Auth::user()->can('create', Accountant::class))
											{
													$btn .= '<a class="dropdown-item" href="'.route('accountant.accountantWithJobRequest', [
															'jobRequest' => $row->job_request_id,
															'invoice_id' => $row->invoice_id,
															'entry_type' => 'invoice',
															'amount' => number_format((float)$row->total, 2, '.', ''),
															'currency' => $row->currency,
															'reference_no' => $row->code,
													]).'">Create Accountant Entry</a>';
											}

											if (Auth::user()->can('create', MailCenter::class) && Auth::user()->can('view', Invoice::find($row->invoice_id)) || Auth::user()->can('create', Accountant::class))
											{
													$btn .= '<div class="dropdown-divider"></div>';
											}

											if (Storage::disk('public')->exists('pdf/workflow/invoice/'.$invoiceCompanyType.'/'.$row->code.'.pdf'))
											{
													$btn .= '<a class="dropdown-item" target="_blank" href="'.URL('storage/pdf/workflow/invoice/'.$invoiceCompanyType.'/'.$row->code.'.pdf').'">Download PDF</a>';
											}
											else
											{
													$btn .= '<a class="dropdown-item" href="'.route('invoice.show', $row->invoice_id).'">Upload PDF</a>';
											}
											$btn .= '</div></div>';
											if ($btn === "")
											{
													return $btn;
											}
											return '<div class="wf-inline-actions">'.$btn.'</div>';
									})
								->editColumn('code', function($row){
											$report_code = "";
											if(Auth::user()->can('view', Invoice::find($row->invoice_id)))
											{
													$report_code .= "<a href=".route('invoice.show', $row->invoice_id).">";
											}

											$report_code .= $row->code;

											if(Auth::user()->can('view', Invoice::find($row->invoice_id)))
											{
													$report_code .= "</a>";
											}
											return $report_code;
									})
								->orderColumn('code', 'invoices.code $1')
								->orderColumn('job_request_code', 'job_requests.code $1')
								->orderColumn('client', 'COALESCE(clients.name, suppliers.name) $1')
								->orderColumn('cpo', 'invoices.cpo $1')
								->orderColumn('client_department', 'client_departments.name $1')
								->orderColumn('deploc', 'job_requests.deploc $1')
								->orderColumn('employee', 'employees.name $1')
								->filterColumn('code', function($query, $keyword){
											$query->whereRaw("invoices.code like ?", ["%{$keyword}%"]);
									})
								->filterColumn('job_request_code', function($query, $keyword){
											$query->whereRaw("job_requests.code like ?", ["%{$keyword}%"]);
									})
								->filterColumn('client', function($query, $keyword){
											$query->whereRaw("COALESCE(clients.name, suppliers.name) like ?", ["%{$keyword}%"]);
									})
								->filterColumn('deploc', function($query, $keyword){
									$query->whereRaw("job_requests.deploc like ?", ["%{$keyword}%"]);
								})
                                ->filterColumn('client_department', function ($query, $keyword) {
                                    $query->whereRaw("client_departments.name like ?", ["%{$keyword}%"]);
                                })
								->filterColumn('employee', function($query, $keyword){
											$query->whereRaw("employees.name like ?", ["%{$keyword}%"]);
									})
								
								->rawColumns(['code', 'job_request_code', 'client', 'action'])
								->make(true);
		}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      	return redirect()->route('jobRequest.index');
    }

		public function invoiceWithJobRequestNew(Request $request, JobRequest $jobRequest)
    {
				$qutation = '';
				if ($jobRequest->qutation)
				{
						$qutation = $jobRequest->qutation;
				}
	      return view('layouts.work-flow.invoice.add', [
						'page_name' => $this->page_name(0, $this->page_name),
						'job_request_id' => $jobRequest->id,
						'job_request_code' => $jobRequest->code,
						'qutation' => $qutation,
						'invoice_company_type' => $request->get('invoice_company_type'),
						'existing_payment' => null,
						'linked_payment_count' => 0,
						'bank_options' => $this->getBankOptions(),
				]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
			$invoiceCompanyType = $request->invoice_company_type ? $request->invoice_company_type : Invoice::$INVOICE_RSE_TYPE;
			$last = Invoice::where('invoice_company_type', $invoiceCompanyType)->orderBy('code', 'desc')->latest()->first();
			$last_id = "";
			if (!$last || !str_contains($last->code, substr(date('Y'), 1)))
			{
					$last_id = 'INV-'.substr(date('Y'), 1).'-001';
			}
			else
			{
					$last_id = (int)substr($last->code,8);
					$new_id = $last_id+1;
					$last_id = 'INV-'.substr(date('Y'), 1).'-'.($new_id > 999 ? $new_id : str_pad($new_id, 3,'0',STR_PAD_LEFT));
			}

      $contract = '';

      if (isset($request->contract))
			{
        	$contract = $request->contract;
      }
			else
			{
        	$contract = NULL;
      }

			$subTotal = $this->normalizeMoney($request->subtotal);
			$taxAmount = $this->normalizeMoney($request->tax);
			$withholdingRate = $this->normalizeRate($request->discountTaxAfter);
			$withholdingAmount = round(($subTotal * $withholdingRate) / 100, 2);
			$totalAmount = round(($subTotal + $taxAmount) - $withholdingAmount, 2);

      $store_invoice = DB::transaction(function () use (
					$invoiceCompanyType,
					$last_id,
					$request,
					$contract,
					$subTotal,
					$withholdingRate,
					$taxAmount,
					$totalAmount
			) {
					$invoice = Invoice::create([
							'invoice_company_type' => $invoiceCompanyType,
			        'code' => $last_id,
			        'cpo' => $request->cpo,
			        'contract' => $contract,
			        'job_request_id' => $request->csd,
			        'items' => json_encode($request['items']),
			        'terms' => json_encode($request['payments']),
			        'sub_total' => $subTotal,
			        'withholding' => $withholdingRate,
			        'tax' => $taxAmount,
			        'total' => $totalAmount,
			        'type' => $request->type,
			        'user_id' => Auth::id(),
			        'sync' => 0,
		      ]);

					$this->syncInlinePayment($request, $invoice);

					return $invoice;
			});
      if ($store_invoice)
			{
	        JcfStatus::where('job_request_id', $request->csd)->update(['comment' => 'cm']);
	        return response()->json([
	            'success' => $this->action_message(0, $this->page_name)
	        ]);
      }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
				return view('layouts.work-flow.invoice.show', [
		        'page_name' => $this->page_name,
		        'page_text' => 'Invoice No. : '.$invoice->code,
		        'invoice' => $invoice,
		        'person_make_report' => '',
		        'person_make_report_desc' => '',
		        'report_created_at' => '',
		        'esign' => '',
		        'pdfurl' => 'storage/pdf/workflow/invoice/'.$invoice->invoice_company_type.'/'.$invoice->code.'.pdf',
		        'for_approve_url' => '',
						'imageurl' => $invoice->code,
		        'folder' => 'pdf/workflow/invoice/'. $invoice->invoice_company_type,
		        'user_id_approved' => '',
		        'hasPermission' => '',
		        'mailcenter_compose_url' => route('mailCenter.compose.related', ['relatedType' => 'invoice', 'relatedId' => $invoice->id]),
		        'iso_number' => 'Form # RSE-GF-04 - ISSUE 06 / Nov 2021 ',
		        'page_number' => '1 of 1'
	      ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */

		public function invoiceWithJobRequestNewEdit(JobRequest $jobRequest)
		{
				$qutation = '';
				if ($jobRequest->qutation)
				{
					 $qutation = $jobRequest->qutation;
				}
				$invoice = $jobRequest->invoice;
				return view('layouts.work-flow.invoice.edit', [
					 'page_name' => $this->page_name(1, $this->page_name),
					 'job_request_id' => $jobRequest->id,
					 'job_request_code' => $jobRequest->code,
					 'qutation' => $qutation,
					 'invoice' => $invoice,
					 'existing_payment' => $invoice ? $invoice->payments()->orderBy('id')->first() : null,
					 'linked_payment_count' => $invoice ? $invoice->payments()->count() : 0,
					 'bank_options' => $this->getBankOptions(),
				]);
		}

    public function edit(Invoice $invoice)
    {
      	return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
	      $contract = '';
	      if (isset($request->contract))
				{
	        	$contract = $request->contract;
	      }
				else
				{
	        	$contract = $invoice->contract;
	      }

				$subTotal = $this->normalizeMoney($request->subtotal);
				$taxAmount = $this->normalizeMoney($request->tax);
				$withholdingRate = $this->normalizeRate($request->discountTaxAfter);
				$withholdingAmount = round(($subTotal * $withholdingRate) / 100, 2);
				$totalAmount = round(($subTotal + $taxAmount) - $withholdingAmount, 2);

	      $update_invoice = DB::transaction(function () use ($request, $invoice, $contract, $subTotal, $taxAmount, $withholdingRate, $totalAmount) {
						$updated = Invoice::where('id', $invoice->id)->update([
			        'cpo' => $request->cpo,
			        'contract' => $contract,
			        'items' => json_encode($request['items']),
			        'terms' => json_encode($request['payments']),
			        'sub_total' => $subTotal,
			        'tax' => $taxAmount,
			        'withholding' => $withholdingRate,
			        'total' => $totalAmount,
			        'type' => $request->type,
			        // 'user_id' => Auth::id(),
			        'created_at' => date('Y-m-d', strtotime($request->date)),
			      ]);

						if ($updated) {
								$invoice->refresh();
								$this->syncInlinePayment($request, $invoice);
						}

						return $updated;
				});
	      if ($update_invoice)
				{
		        return response()->json([
		            'success' => $this->action_message(1, $this->page_name)
		        ]);
	      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
				$remove = $invoice->forceDelete();
				if ($remove)
				{
						DB::table('jcf_statuses')->where('job_request_id', $invoice->jobRequest->id)->update(['comment' => null]);
						$pdf = 'invoice/'.$invoice->code.'.pdf';
						$snap = 'images/invoice/'.$invoice->code;
						$remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
						return response()->json([
								'success' => $remove_snap_shots_pdf.$this->action_message(2, $this->page_name)
						]);
				}
    }
}

<?php

namespace App\Http\Controllers\Dashboard\WorkFlow;

use App\Http\Controllers\Controller;
use App\Models\Persons\Client;
use App\Models\WorkFlow\Accountant;
use App\Models\WorkFlow\Bank;
use App\Models\WorkFlow\BankTransaction;
use App\Models\WorkFlow\Invoice;
use App\Models\WorkFlow\JobRequest;
use App\Models\WorkFlow\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use DataTables;

class PaymentController extends Controller
{
    public $page_name = 'Payment';

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Payment::class, 'payment');
    }

    public function index(Request $request)
    {
        $selectedJobRequestId = (int)$request->get('job_request_id');
        $paymentQuery = Payment::query();
        if ($selectedJobRequestId > 0) {
            $paymentQuery->where('job_request_id', $selectedJobRequestId);
        }

        return view('layouts.work-flow.payment.index', [
            'page_name' => $this->page_name('All', $this->page_name),
            'payments' => $paymentQuery->count(),
            'jcf_create_options' => $this->getJobRequestOptions(),
            'selected_job_request_id' => $selectedJobRequestId,
        ]);
    }

    public function getDataForDataTable(Request $request)
    {
        $selectedJobRequestId = (int)$request->get('job_request_id');

        $data = Payment::query()
            ->leftJoin('users', 'payments.user_id', '=', 'users.id')
            ->leftJoin('employees', 'employees.id', '=', 'users.employee_id')
            ->leftJoin('job_requests', 'job_requests.id', '=', 'payments.job_request_id')
            ->leftJoin('clients', 'job_requests.client_id', '=', 'clients.id')
            ->leftJoin('suppliers', 'job_requests.supplier_id', '=', 'suppliers.id')
            ->leftJoin('invoices', 'invoices.id', '=', 'payments.invoice_id')
            ->leftJoin('bank_accounts', 'bank_accounts.id', '=', 'payments.bank_account_id')
            ->select([
                'payments.id as payment_id',
                'payments.code as code',
                'payments.payment_date as payment_date',
                'payments.amount as amount',
                'payments.method as method',
                'payments.reference_no as reference_no',
                'payments.status as status',
                'employees.name as employee',
                'job_requests.id as job_request_id',
                'job_requests.code as job_request_code',
                'job_requests.client_id as client_id',
                'job_requests.supplier_id as supplier_id',
                'clients.name as cli',
                'suppliers.name as sup',
                'invoices.id as invoice_id',
                'invoices.code as invoice_code',
                'invoices.type as invoice_currency',
                'bank_accounts.code as bank_code',
                'bank_accounts.bank_name as bank_name',
                'bank_accounts.account_name as bank_account_name',
                'bank_accounts.currency as bank_currency',
            ]);

        if ($selectedJobRequestId > 0) {
            $data->where('payments.job_request_id', $selectedJobRequestId);
        }

        return Datatables::of($data)
            ->addColumn('job_request_code', function ($row) {
                if (!$row->job_request_id || !Auth::user()->can('view', JobRequest::find($row->job_request_id))) {
                    return $row->job_request_code ?: '-';
                }

                return '<a href="'.route('jobRequest.show', $row->job_request_id).'">'.$row->job_request_code.'</a>';
            })
            ->addColumn('client', function ($row) {
                if ($row->client_id != null && $row->supplier_id == null) {
                    if ($row->client_id && Auth::user()->can('view', Client::find($row->client_id))) {
                        return '<a href="'.route('client.show', $row->client_id).'">'.$row->cli.'</a>';
                    }
                    return $row->cli ?: '-';
                }

                return $row->sup ?: '-';
            })
            ->addColumn('invoice_code', function ($row) {
                if (!$row->invoice_id || !$row->invoice_code) {
                    return '-';
                }
                $invoice = Invoice::find($row->invoice_id);
                if (!$invoice || !Auth::user()->can('view', $invoice)) {
                    return $row->invoice_code;
                }
                return '<a href="'.route('invoice.show', $row->invoice_id).'">'.$row->invoice_code.'</a>';
            })
            ->addColumn('bank_account', function ($row) {
                if (!$row->bank_code && !$row->bank_name && !$row->bank_account_name) {
                    return '-';
                }

                $parts = [];
                if (!empty($row->bank_code)) {
                    $parts[] = $row->bank_code;
                }
                if (!empty($row->bank_name)) {
                    $parts[] = $row->bank_name;
                }
                if (!empty($row->bank_account_name)) {
                    $parts[] = $row->bank_account_name;
                }

                return implode(' - ', $parts);
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                $payment = Payment::find($row->payment_id);
                if (!$payment) {
                    return '';
                }

                if (Auth::user()->can('update', $payment)) {
                    $btn .= '<a href="'.route('payment.edit', $row->payment_id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                }

                if (Auth::user()->can('create', Accountant::class) && $row->job_request_id) {
                    $btn .= '<a href="'.route('accountant.accountantWithJobRequest', [
                        'jobRequest' => $row->job_request_id,
                        'invoice_id' => $row->invoice_id,
                        'payment_id' => $row->payment_id,
                        'entry_type' => 'payment',
                        'amount' => number_format((float)$row->amount, 2, '.', ''),
                        'currency' => $row->invoice_currency ?: ($row->bank_currency ?: 'USD'),
                        'reference_no' => $row->reference_no ?: $row->code,
                    ]).'" class="btn btn-icon btn-secondary mr-1"><i class="la la-calculator"></i></a>';
                }

                if (Auth::user()->can('delete', $payment)) {
                    $btn .= '<button type="button" data-id="'.$row->payment_id.'" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>';
                }

                if ($btn === '') {
                    return '';
                }

                return '<div class="wf-inline-actions">'.$btn.'</div>';
            })
            ->editColumn('code', function ($row) {
                $payment = Payment::find($row->payment_id);
                if (!$payment || !Auth::user()->can('view', $payment)) {
                    return $row->code;
                }
                return '<a href="'.route('payment.show', $row->payment_id).'">'.$row->code.'</a>';
            })
            ->editColumn('payment_date', function ($row) {
                return $this->formatDateOutput($row->payment_date);
            })
            ->editColumn('amount', function ($row) {
                return number_format((float)$row->amount, 2, '.', '');
            })
            ->orderColumn('code', 'payments.code $1')
            ->orderColumn('job_request_code', 'job_requests.code $1')
            ->orderColumn('client', 'COALESCE(clients.name, suppliers.name) $1')
            ->orderColumn('invoice_code', 'invoices.code $1')
            ->orderColumn('bank_account', 'bank_accounts.code $1')
            ->orderColumn('payment_date', 'payments.payment_date $1')
            ->orderColumn('amount', 'payments.amount $1')
            ->orderColumn('method', 'payments.method $1')
            ->orderColumn('status', 'payments.status $1')
            ->orderColumn('employee', 'employees.name $1')
            ->filterColumn('code', function ($query, $keyword) {
                $query->whereRaw('payments.code like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('job_request_code', function ($query, $keyword) {
                $query->whereRaw('job_requests.code like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('client', function ($query, $keyword) {
                $query->whereRaw('COALESCE(clients.name, suppliers.name) like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('invoice_code', function ($query, $keyword) {
                $query->whereRaw('invoices.code like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('bank_account', function ($query, $keyword) {
                $query->whereRaw(
                    'CONCAT(COALESCE(bank_accounts.code, \'\'), \' \', COALESCE(bank_accounts.bank_name, \'\'), \' \', COALESCE(bank_accounts.account_name, \'\')) like ?',
                    ["%{$keyword}%"]
                );
            })
            ->filterColumn('payment_date', function ($query, $keyword) {
                $query->whereRaw('payments.payment_date like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('amount', function ($query, $keyword) {
                $query->whereRaw('payments.amount like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('method', function ($query, $keyword) {
                $query->whereRaw('payments.method like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->whereRaw('payments.status like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('employee', function ($query, $keyword) {
                $query->whereRaw('employees.name like ?', ["%{$keyword}%"]);
            })
            ->rawColumns(['code', 'job_request_code', 'client', 'invoice_code', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('layouts.work-flow.payment.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'job_request' => null,
            'job_request_options' => $this->getJobRequestOptions(),
            'invoice_options' => [],
            'bank_options' => $this->getBankOptions(),
        ]);
    }

    public function paymentWithJobRequest(JobRequest $jobRequest)
    {
        $this->authorize('create', Payment::class);

        return view('layouts.work-flow.payment.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'job_request' => $jobRequest,
            'job_request_options' => $this->getJobRequestOptions(),
            'invoice_options' => $this->getInvoiceOptions($jobRequest->id),
            'bank_options' => $this->getBankOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $payload = $this->validatePayload($request);
        $payment = DB::transaction(function () use ($payload) {
            $payment = Payment::create([
                'code' => $this->nextCode(),
                'job_request_id' => $payload['job_request_id'],
                'invoice_id' => $payload['invoice_id'],
                'bank_account_id' => $payload['bank_account_id'],
                'payment_date' => $payload['payment_date'],
                'amount' => $payload['amount'],
                'method' => $payload['method'],
                'reference_no' => $payload['reference_no'],
                'status' => $payload['status'],
                'note' => $payload['note'],
                'user_id' => Auth::id(),
                'approved_by' => null,
                'sync' => 0,
                'updated' => null,
            ]);

            $this->syncAutoBankTransactionForPayment($payment);

            return $payment;
        });

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => $this->action_message(0, $this->page_name)]);
        }

        return redirect()->route('payment.show', $payment->id);
    }

    public function show(Payment $payment)
    {
        return view('layouts.work-flow.payment.show', [
            'page_name' => $this->page_name,
            'payment' => $payment->load(['jobRequest.client', 'jobRequest.supplier', 'invoice', 'bank', 'user.employee']),
        ]);
    }

    public function edit(Payment $payment)
    {
        return view('layouts.work-flow.payment.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'payment' => $payment,
            'job_request_options' => $this->getJobRequestOptions(),
            'invoice_options' => $this->getInvoiceOptions((int)$payment->job_request_id),
            'bank_options' => $this->getBankOptions(),
            'payment_date' => $this->formatDateOutput($payment->payment_date),
        ]);
    }

    public function update(Request $request, Payment $payment)
    {
        $payload = $this->validatePayload($request);
        $updated = DB::transaction(function () use ($payment, $payload) {
            $updated = Payment::where('id', $payment->id)->update([
                'job_request_id' => $payload['job_request_id'],
                'invoice_id' => $payload['invoice_id'],
                'bank_account_id' => $payload['bank_account_id'],
                'payment_date' => $payload['payment_date'],
                'amount' => $payload['amount'],
                'method' => $payload['method'],
                'reference_no' => $payload['reference_no'],
                'status' => $payload['status'],
                'note' => $payload['note'],
                'updated' => 1,
            ]);

            $payment->refresh();
            $this->syncAutoBankTransactionForPayment($payment);

            return $updated;
        });

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => $this->action_message(1, $this->page_name)]);
        }

        if ($updated) {
            return redirect()->route('payment.show', $payment->id);
        }

        return back();
    }

    public function destroy(Payment $payment)
    {
        $removed = DB::transaction(function () use ($payment) {
            $relatedTransactions = BankTransaction::query()
                ->where('payment_id', $payment->id)
                ->get();

            foreach ($relatedTransactions as $transaction) {
                if ((int)$transaction->is_posted === 1) {
                    throw ValidationException::withMessages([
                        'payment' => 'Cannot delete payment linked to posted bank transaction '.$transaction->code.'.',
                    ]);
                }
            }

            $affectedBankIds = $relatedTransactions
                ->pluck('bank_account_id')
                ->filter()
                ->unique()
                ->values()
                ->all();

            if ($relatedTransactions->count() > 0) {
                BankTransaction::query()->where('payment_id', $payment->id)->delete();
            }

            $removed = $payment->forceDelete();
            foreach ($affectedBankIds as $bankId) {
                $this->recalculateBankBalanceById((int)$bankId);
            }

            return $removed;
        });

        if ($removed) {
            return response()->json(['success' => $this->action_message(2, $this->page_name)]);
        }

        return response()->json(['success' => 'Unable to delete this Payment.'], 422);
    }

    private function validatePayload(Request $request): array
    {
        $payload = $request->validate([
            'job_request_id' => ['required', 'integer', 'exists:job_requests,id'],
            'invoice_id' => ['nullable', 'integer', 'exists:invoices,id'],
            'bank_account_id' => ['nullable', 'integer', 'exists:bank_accounts,id'],
            'payment_date' => ['nullable', 'string'],
            'amount' => ['nullable'],
            'method' => ['nullable', 'string', 'max:191'],
            'reference_no' => ['nullable', 'string', 'max:191'],
            'status' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string'],
        ]);

        if (!empty($payload['invoice_id'])) {
            $invoice = Invoice::where('id', $payload['invoice_id'])
                ->where('job_request_id', $payload['job_request_id'])
                ->first();
            if (!$invoice) {
                throw ValidationException::withMessages([
                    'invoice_id' => 'Selected invoice does not belong to selected JCF.',
                ]);
            }
        }

        if (!empty($payload['bank_account_id'])) {
            $bank = Bank::find($payload['bank_account_id']);
            if (!$bank || (int)$bank->is_active !== 1) {
                throw ValidationException::withMessages([
                    'bank_account_id' => 'Selected bank account is not active.',
                ]);
            }
        }

        $payload['payment_date'] = $this->normalizeDateInput($payload['payment_date'] ?? null);
        $payload['amount'] = $this->normalizeAmount($payload['amount'] ?? null);
        $payload['invoice_id'] = $payload['invoice_id'] ?? null;
        $payload['bank_account_id'] = $payload['bank_account_id'] ?? null;
        $payload['status'] = $payload['status'] ?: 'pending';

        return $payload;
    }

    private function normalizeAmount($value): float
    {
        if ($value === null || $value === '') {
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

    private function normalizeDateInput($value)
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

    private function formatDateOutput($value, $format = 'd-m-Y')
    {
        if ($value === null || $value === '') {
            return '-';
        }

        try {
            return Carbon::parse($value)->format($format);
        } catch (\Throwable $e) {
            return '-';
        }
    }

    private function nextCode(): string
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

    private function syncAutoBankTransactionForPayment(Payment $payment): void
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
                        'bank_account_id' => 'Cannot unlink payment from bank because linked bank transaction is already posted.',
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
                'bank_account_id' => 'Selected bank account is not active.',
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
                    'bank_account_id' => 'Cannot change bank account/amount/direction because linked bank transaction is posted.',
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
            'note' => $this->buildAutoPaymentBankNote($payment),
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

    private function buildAutoPaymentBankNote(Payment $payment): string
    {
        $note = 'Auto-created from Payment '.$payment->code;
        if (!empty($payment->jobRequest?->code)) {
            $note .= ' ('.$payment->jobRequest->code.')';
        }

        return $note;
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

    private function getJobRequestOptions(): array
    {
        return JobRequest::query()
            ->with(['client:id,name', 'supplier:id,name'])
            ->orderBy('code', 'desc')
            ->get(['id', 'code', 'client_id', 'supplier_id'])
            ->mapWithKeys(function (JobRequest $jobRequest) {
                $ownerName = $jobRequest->client->name ?? $jobRequest->supplier->name ?? '';
                $label = $jobRequest->code.($ownerName !== '' ? ' - '.$ownerName : '');
                return [$jobRequest->id => $label];
            })
            ->toArray();
    }

    private function getInvoiceOptions(?int $jobRequestId): array
    {
        if (!$jobRequestId) {
            return [];
        }

        return Invoice::query()
            ->where('job_request_id', $jobRequestId)
            ->orderBy('code', 'desc')
            ->get(['id', 'code', 'invoice_company_type'])
            ->mapWithKeys(function (Invoice $invoice) {
                return [$invoice->id => $invoice->code.' ('.$invoice->invoice_company_type.')'];
            })
            ->toArray();
    }
}

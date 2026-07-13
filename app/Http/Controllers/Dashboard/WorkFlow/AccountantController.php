<?php

namespace App\Http\Controllers\Dashboard\WorkFlow;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Http\Controllers\Controller;
use App\Models\Persons\Client;
use App\Models\WorkFlow\Accountant;
use App\Models\WorkFlow\AccountantAudit;
use App\Models\WorkFlow\AccountantEntryLine;
use App\Models\WorkFlow\AccountingPeriod;
use App\Models\WorkFlow\ChartAccount;
use App\Models\WorkFlow\Invoice;
use App\Models\WorkFlow\JobRequest;
use App\Models\WorkFlow\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use DataTables;

class AccountantController extends Controller
{
    public $page_name = 'Accountant';
    private const ACCOUNTANT_STATUSES = ['draft', 'pending_approval', 'approved', 'rejected'];
    private const DEFAULT_ENTRY_LINE_COUNT = 2;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Accountant::class, 'accountant');
    }

    public function index(Request $request)
    {
        $selectedJobRequestId = (int)$request->get('job_request_id');
        $selectedEntryStatus = trim((string)$request->get('entry_status', ''));
        $accountantQuery = Accountant::query();
        if ($selectedJobRequestId > 0) {
            $accountantQuery->where('job_request_id', $selectedJobRequestId);
        }
        if (in_array($selectedEntryStatus, self::ACCOUNTANT_STATUSES, true)) {
            $accountantQuery->where('status', $selectedEntryStatus);
        }

        $chartAccountsRoots = ChartAccount::query()
            ->with('childrenRecursive')
            ->whereNull('parent_id')
            ->orderBy('code')
            ->get();

        $reportDateFrom = $this->normalizeDateInput($request->get('report_date_from'));
        $reportDateTo = $this->normalizeDateInput($request->get('report_date_to'));
        $ledgerAccountId = $request->filled('ledger_account_id') ? (int)$request->get('ledger_account_id') : null;
        $reportCurrency = strtoupper(trim((string)$request->get('report_currency', '')));
        if ($reportCurrency === '') {
            $reportCurrency = null;
        }
        $reportData = $this->buildAccountingReports($reportDateFrom, $reportDateTo, $ledgerAccountId, $reportCurrency);
        $activeTab = $request->get('tab', 'entries');

        return view('layouts.work-flow.accountant.index', [
            'page_name' => $this->page_name('All', $this->page_name),
            'accountants' => $accountantQuery->count(),
            'jcf_create_options' => $this->getJobRequestOptions(),
            'selected_job_request_id' => $selectedJobRequestId,
            'selected_entry_status' => $selectedEntryStatus,
            'pending_approval_count' => Accountant::query()->where('status', 'pending_approval')->count(),
            'chart_accounts_roots' => $chartAccountsRoots,
            'chart_account_options' => $this->getChartAccountOptions(),
            'chart_account_type_options' => ChartAccount::typeOptions(),
            'can_manage_chart_accounts' => $this->canManageChartAccounts(),
            'active_tab' => $activeTab,
            'report_date_from' => $reportDateFrom,
            'report_date_to' => $reportDateTo,
            'ledger_account_id' => $ledgerAccountId,
            'report_currency' => $reportCurrency,
            'report_currency_options' => $this->getPostedCurrencyOptions(),
            'trial_balance_rows' => $reportData['trial_balance_rows'],
            'trial_balance_totals' => $reportData['trial_balance_totals'],
            'currency_totals' => $reportData['currency_totals'],
            'is_mixed_currency' => $reportData['is_mixed_currency'],
            'income_statement_rows' => $reportData['income_statement_rows'],
            'income_statement_totals' => $reportData['income_statement_totals'],
            'balance_sheet' => $reportData['balance_sheet'],
            'ledger_rows' => $reportData['ledger_rows'],
            'ledger_totals' => $reportData['ledger_totals'],
            'accounting_periods' => $this->recentAccountingPeriods(),
        ]);
    }

    public function getDataForDataTable(Request $request)
    {
        $selectedJobRequestId = (int)$request->get('job_request_id');
        $selectedEntryStatus = trim((string)$request->get('entry_status', ''));

        $data = Accountant::query()
            ->leftJoin('users', 'accountants.user_id', '=', 'users.id')
            ->leftJoin('employees', 'employees.id', '=', 'users.employee_id')
            ->leftJoin('job_requests', 'job_requests.id', '=', 'accountants.job_request_id')
            ->leftJoin('clients', 'job_requests.client_id', '=', 'clients.id')
            ->leftJoin('suppliers', 'job_requests.supplier_id', '=', 'suppliers.id')
            ->leftJoin('invoices', 'invoices.id', '=', 'accountants.invoice_id')
            ->leftJoin('payments', 'payments.id', '=', 'accountants.payment_id')
            ->leftJoin('chart_accounts as debit_accounts', 'debit_accounts.id', '=', 'accountants.debit_account_id')
            ->leftJoin('chart_accounts as credit_accounts', 'credit_accounts.id', '=', 'accountants.credit_account_id')
            ->select([
                'accountants.id as accountant_id',
                'accountants.code as code',
                'accountants.posting_date as posting_date',
                'accountants.entry_type as entry_type',
                'accountants.amount as amount',
                'accountants.currency as currency',
                'accountants.status as status',
                'employees.name as employee',
                'job_requests.id as job_request_id',
                'job_requests.code as job_request_code',
                'job_requests.client_id as client_id',
                'job_requests.supplier_id as supplier_id',
                'clients.name as cli',
                'suppliers.name as sup',
                'invoices.id as invoice_id',
                'invoices.code as invoice_code',
                'payments.id as payment_id',
                'payments.code as payment_code',
                'debit_accounts.code as debit_account_code',
                'debit_accounts.name as debit_account_name',
                'debit_accounts.name_en as debit_account_name_en',
                'debit_accounts.name_ar as debit_account_name_ar',
                'credit_accounts.code as credit_account_code',
                'credit_accounts.name as credit_account_name',
                'credit_accounts.name_en as credit_account_name_en',
                'credit_accounts.name_ar as credit_account_name_ar',
            ]);

        if ($selectedJobRequestId > 0) {
            $data->where('accountants.job_request_id', $selectedJobRequestId);
        }
        if (in_array($selectedEntryStatus, self::ACCOUNTANT_STATUSES, true)) {
            $data->where('accountants.status', $selectedEntryStatus);
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
            ->addColumn('payment_code', function ($row) {
                if (!$row->payment_id || !$row->payment_code) {
                    return '-';
                }
                $payment = Payment::find($row->payment_id);
                if (!$payment || !Auth::user()->can('view', $payment)) {
                    return $row->payment_code;
                }
                return '<a href="'.route('payment.show', $row->payment_id).'">'.$row->payment_code.'</a>';
            })
            ->addColumn('debit_account', function ($row) {
                if (!$row->debit_account_name && !$row->debit_account_name_en && !$row->debit_account_name_ar) {
                    return '-';
                }

                return $this->formatChartAccountLabelFromColumns(
                    $row->debit_account_code,
                    $row->debit_account_name_en ?: $row->debit_account_name,
                    $row->debit_account_name_ar
                );
            })
            ->addColumn('credit_account', function ($row) {
                if (!$row->credit_account_name && !$row->credit_account_name_en && !$row->credit_account_name_ar) {
                    return '-';
                }

                return $this->formatChartAccountLabelFromColumns(
                    $row->credit_account_code,
                    $row->credit_account_name_en ?: $row->credit_account_name,
                    $row->credit_account_name_ar
                );
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                $accountant = Accountant::find($row->accountant_id);
                if (!$accountant) {
                    return '';
                }

                if (Auth::user()->can('update', $accountant)) {
                    $btn .= '<a href="'.route('accountant.edit', $row->accountant_id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                }

                if (Auth::user()->can('delete', $accountant)) {
                    $btn .= '<button type="button" data-id="'.$row->accountant_id.'" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>';
                }

                if ($btn === '') {
                    return '';
                }

                return '<div class="wf-inline-actions">'.$btn.'</div>';
            })
            ->editColumn('code', function ($row) {
                $accountant = Accountant::find($row->accountant_id);
                if (!$accountant || !Auth::user()->can('view', $accountant)) {
                    return $row->code;
                }
                return '<a href="'.route('accountant.show', $row->accountant_id).'">'.$row->code.'</a>';
            })
            ->editColumn('amount', function ($row) {
                return number_format((float)$row->amount, 2, '.', '');
            })
            ->editColumn('posting_date', function ($row) {
                return $this->formatDateOutput($row->posting_date);
            })
            ->orderColumn('code', 'accountants.code $1')
            ->orderColumn('job_request_code', 'job_requests.code $1')
            ->orderColumn('client', 'COALESCE(clients.name, suppliers.name) $1')
            ->orderColumn('posting_date', 'accountants.posting_date $1')
            ->orderColumn('entry_type', 'accountants.entry_type $1')
            ->orderColumn('amount', 'accountants.amount $1')
            ->orderColumn('currency', 'accountants.currency $1')
            ->orderColumn('status', 'accountants.status $1')
            ->orderColumn('invoice_code', 'invoices.code $1')
            ->orderColumn('payment_code', 'payments.code $1')
            ->orderColumn('debit_account', 'debit_accounts.code $1')
            ->orderColumn('credit_account', 'credit_accounts.code $1')
            ->orderColumn('employee', 'employees.name $1')
            ->filterColumn('code', function ($query, $keyword) {
                $query->whereRaw('accountants.code like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('job_request_code', function ($query, $keyword) {
                $query->whereRaw('job_requests.code like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('client', function ($query, $keyword) {
                $query->whereRaw('COALESCE(clients.name, suppliers.name) like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('posting_date', function ($query, $keyword) {
                $query->whereRaw('accountants.posting_date like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('entry_type', function ($query, $keyword) {
                $query->whereRaw('accountants.entry_type like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('amount', function ($query, $keyword) {
                $query->whereRaw('accountants.amount like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('currency', function ($query, $keyword) {
                $query->whereRaw('accountants.currency like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->whereRaw('accountants.status like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('invoice_code', function ($query, $keyword) {
                $query->whereRaw('invoices.code like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('payment_code', function ($query, $keyword) {
                $query->whereRaw('payments.code like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('debit_account', function ($query, $keyword) {
                $query->whereRaw(
                    'CONCAT(COALESCE(debit_accounts.code, \'\'), \' \', COALESCE(debit_accounts.name, \'\'), \' \', COALESCE(debit_accounts.name_en, \'\'), \' \', COALESCE(debit_accounts.name_ar, \'\')) like ?',
                    ["%{$keyword}%"]
                );
            })
            ->filterColumn('credit_account', function ($query, $keyword) {
                $query->whereRaw(
                    'CONCAT(COALESCE(credit_accounts.code, \'\'), \' \', COALESCE(credit_accounts.name, \'\'), \' \', COALESCE(credit_accounts.name_en, \'\'), \' \', COALESCE(credit_accounts.name_ar, \'\')) like ?',
                    ["%{$keyword}%"]
                );
            })
            ->filterColumn('employee', function ($query, $keyword) {
                $query->whereRaw('employees.name like ?', ["%{$keyword}%"]);
            })
            ->rawColumns(['code', 'job_request_code', 'client', 'invoice_code', 'payment_code', 'action'])
            ->make(true);
    }

    public function create()
    {
        $prefill = $this->resolvePrefillPayload(request(), null);

        return view('layouts.work-flow.accountant.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'job_request' => null,
            'job_request_options' => $this->getJobRequestOptions(),
            'invoice_options' => [],
            'payment_options' => [],
            'chart_account_options' => $this->getChartAccountOptions(),
            'posting_date' => $this->formatDateOutput(now(), 'Y-m-d'),
            'prefill_job_request_id' => $prefill['job_request_id'],
            'prefill_invoice_id' => $prefill['invoice_id'],
            'prefill_payment_id' => $prefill['payment_id'],
            'prefill_entry_type' => $prefill['entry_type'],
            'prefill_amount' => $prefill['amount'],
            'prefill_currency' => $prefill['currency'],
            'prefill_reference_no' => $prefill['reference_no'],
            'prefill_note' => $prefill['note'],
            'initial_entry_lines' => $this->buildEntryLinesForCreate($prefill),
            'posting_period_status' => $this->periodStatusForDate(now()->format('Y-m-d')),
        ]);
    }

    public function accountantWithJobRequest(Request $request, JobRequest $jobRequest)
    {
        $this->authorize('create', Accountant::class);
        $prefill = $this->resolvePrefillPayload($request, $jobRequest);

        return view('layouts.work-flow.accountant.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'job_request' => $jobRequest,
            'job_request_options' => $this->getJobRequestOptions(),
            'invoice_options' => $this->getInvoiceOptions($jobRequest->id),
            'payment_options' => $this->getPaymentOptions($jobRequest->id),
            'chart_account_options' => $this->getChartAccountOptions(),
            'posting_date' => $this->formatDateOutput(now(), 'Y-m-d'),
            'prefill_job_request_id' => $prefill['job_request_id'],
            'prefill_invoice_id' => $prefill['invoice_id'],
            'prefill_payment_id' => $prefill['payment_id'],
            'prefill_entry_type' => $prefill['entry_type'],
            'prefill_amount' => $prefill['amount'],
            'prefill_currency' => $prefill['currency'],
            'prefill_reference_no' => $prefill['reference_no'],
            'prefill_note' => $prefill['note'],
            'initial_entry_lines' => $this->buildEntryLinesForCreate($prefill),
            'posting_period_status' => $this->periodStatusForDate(now()->format('Y-m-d')),
        ]);
    }

    public function store(Request $request)
    {
        $payload = $this->validatePayload($request);
        $this->assertAccountingPeriodOpen($payload['posting_date'], 'create');
        [$resolvedStatus, $resolvedIsPosted, $approvedBy, $approvedAt] = $this->resolveStatusAndPosting(
            null,
            $payload['status'],
            $payload['is_posted']
        );

        $accountant = DB::transaction(function () use ($payload, $resolvedStatus, $resolvedIsPosted, $approvedBy, $approvedAt) {
            $accountant = Accountant::create([
                'code' => $this->nextCode($payload),
                'job_request_id' => $payload['job_request_id'],
                'invoice_id' => $payload['invoice_id'],
                'payment_id' => $payload['payment_id'],
                'posting_date' => $payload['posting_date'],
                'entry_type' => $payload['entry_type'],
                'debit_account_id' => $payload['debit_account_id'],
                'credit_account_id' => $payload['credit_account_id'],
                'amount' => $payload['amount'],
                'currency' => $payload['currency'],
                'reference_no' => $payload['reference_no'],
                'status' => $resolvedStatus,
                'is_posted' => $resolvedIsPosted,
                'note' => $payload['note'],
                'user_id' => Auth::id(),
                'approved_by' => $approvedBy,
                'approved_at' => $approvedAt,
                'sync' => 0,
                'updated' => null,
            ]);
            $this->syncEntryLines($accountant, $payload['entry_lines'], $payload['currency']);

            return $accountant;
        });

        $this->logAccountantAudit($accountant, 'created', null, $resolvedStatus, [
            'is_posted' => $resolvedIsPosted,
            'amount' => $payload['amount'],
            'job_request_id' => $payload['job_request_id'],
            'invoice_id' => $payload['invoice_id'],
            'payment_id' => $payload['payment_id'],
            'line_count' => count($payload['entry_lines']),
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => $this->action_message(0, $this->page_name)]);
        }

        return redirect()->route('accountant.show', $accountant->id);
    }

    public function show(Accountant $accountant)
    {
        return view('layouts.work-flow.accountant.show', [
            'page_name' => $this->page_name,
            'accountant' => $accountant->load([
                'jobRequest.client',
                'jobRequest.supplier',
                'invoice',
                'payment',
                'debitAccount',
                'creditAccount',
                'user.employee',
                'approvedBy.employee',
                'audits.changedBy.employee',
                'lines.chartAccount',
            ]),
        ]);
    }

    public function edit(Accountant $accountant)
    {
        return view('layouts.work-flow.accountant.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'accountant' => $accountant,
            'job_request_options' => $this->getJobRequestOptions(),
            'invoice_options' => $this->getInvoiceOptions((int)$accountant->job_request_id),
            'payment_options' => $this->getPaymentOptions((int)$accountant->job_request_id),
            'chart_account_options' => $this->getChartAccountOptions(),
            'posting_date' => $this->formatDateOutput($accountant->posting_date, 'Y-m-d'),
            'initial_entry_lines' => $this->buildEntryLinesForEdit($accountant),
            'posting_period_status' => $this->periodStatusForDate($accountant->posting_date),
        ]);
    }

    public function update(Request $request, Accountant $accountant)
    {
        if ((int)$accountant->is_posted === 1) {
            throw ValidationException::withMessages([
                'is_posted' => 'Posted entry is locked. Use Unpost first before editing.',
            ]);
        }

        $payload = $this->validatePayload($request, $accountant);
        $this->assertAccountingPeriodOpen((string)$accountant->posting_date, 'update');
        $this->assertAccountingPeriodOpen($payload['posting_date'], 'update');
        [$resolvedStatus, $resolvedIsPosted, $approvedBy, $approvedAt] = $this->resolveStatusAndPosting(
            $accountant,
            $payload['status'],
            $payload['is_posted']
        );
        $fromStatus = (string)$accountant->status;

        $updated = DB::transaction(function () use ($accountant, $payload, $resolvedStatus, $resolvedIsPosted, $approvedBy, $approvedAt) {
            $updated = Accountant::where('id', $accountant->id)->update([
                'job_request_id' => $payload['job_request_id'],
                'invoice_id' => $payload['invoice_id'],
                'payment_id' => $payload['payment_id'],
                'posting_date' => $payload['posting_date'],
                'entry_type' => $payload['entry_type'],
                'debit_account_id' => $payload['debit_account_id'],
                'credit_account_id' => $payload['credit_account_id'],
                'amount' => $payload['amount'],
                'currency' => $payload['currency'],
                'reference_no' => $payload['reference_no'],
                'status' => $resolvedStatus,
                'is_posted' => $resolvedIsPosted,
                'note' => $payload['note'],
                'approved_by' => $approvedBy,
                'approved_at' => $approvedAt,
                'updated' => 1,
            ]);

            $accountant->refresh();
            $this->syncEntryLines($accountant, $payload['entry_lines'], $payload['currency']);

            return $updated;
        });
        if ($updated) {
            $accountant->refresh();
            $this->logAccountantAudit($accountant, 'updated', $fromStatus, $resolvedStatus, [
                'is_posted' => $resolvedIsPosted,
                'amount' => $payload['amount'],
                'job_request_id' => $payload['job_request_id'],
                'invoice_id' => $payload['invoice_id'],
                'payment_id' => $payload['payment_id'],
                'line_count' => count($payload['entry_lines']),
            ]);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => $this->action_message(1, $this->page_name)]);
        }

        if ($updated) {
            return redirect()->route('accountant.show', $accountant->id);
        }

        return back();
    }

    public function destroy(Accountant $accountant)
    {
        $this->assertAccountingPeriodOpen((string)$accountant->posting_date, 'delete');

        if ((int)$accountant->is_posted === 1) {
            return response()->json(['success' => 'Posted entry cannot be deleted. Unpost it first.'], 422);
        }

        $this->logAccountantAudit($accountant, 'deleted', (string)$accountant->status, null);
        $removed = $accountant->forceDelete();

        if ($removed) {
            return response()->json(['success' => $this->action_message(2, $this->page_name)]);
        }

        return response()->json(['success' => 'Unable to delete this Accountant record.'], 422);
    }

    public function submitForApproval(Accountant $accountant)
    {
        $this->authorize('update', $accountant);
        $this->assertAccountingPeriodOpen((string)$accountant->posting_date, 'submit for approval');

        if ((int)$accountant->is_posted === 1) {
            throw ValidationException::withMessages([
                'status' => 'Posted entry is already approved.',
            ]);
        }

        $fromStatus = (string)$accountant->status;
        $this->ensureStatusTransitionAllowed($fromStatus, 'pending_approval');
        $accountant->update([
            'status' => 'pending_approval',
            'approved_by' => null,
            'approved_at' => null,
            'updated' => 1,
        ]);
        $this->logAccountantAudit($accountant, 'submit_for_approval', $fromStatus, 'pending_approval');

        return redirect()->route('accountant.show', $accountant->id)->with('success', 'Entry submitted for approval.');
    }

    public function approve(Accountant $accountant)
    {
        $this->authorize('update', $accountant);
        $this->ensureCanApprove();
        $this->assertAccountingPeriodOpen((string)$accountant->posting_date, 'approve');

        if ((string)$accountant->status !== 'pending_approval') {
            throw ValidationException::withMessages([
                'status' => 'Only pending approval entries can be approved.',
            ]);
        }

        $fromStatus = (string)$accountant->status;
        $accountant->update([
            'status' => 'approved',
            'is_posted' => 1,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'updated' => 1,
        ]);
        $this->logAccountantAudit($accountant, 'approved', $fromStatus, 'approved');

        return redirect()->route('accountant.show', $accountant->id)->with('success', 'Entry approved and posted.');
    }

    public function reject(Accountant $accountant)
    {
        $this->authorize('update', $accountant);
        $this->ensureCanApprove();
        $this->assertAccountingPeriodOpen((string)$accountant->posting_date, 'reject');

        if ((string)$accountant->status !== 'pending_approval') {
            throw ValidationException::withMessages([
                'status' => 'Only pending approval entries can be rejected.',
            ]);
        }

        $fromStatus = (string)$accountant->status;
        $accountant->update([
            'status' => 'rejected',
            'is_posted' => 0,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'updated' => 1,
        ]);
        $this->logAccountantAudit($accountant, 'rejected', $fromStatus, 'rejected');

        return redirect()->route('accountant.show', $accountant->id)->with('success', 'Entry rejected.');
    }

    public function unpost(Accountant $accountant)
    {
        $this->authorize('update', $accountant);
        $this->ensureCanApprove();
        $this->assertAccountingPeriodOpen((string)$accountant->posting_date, 'unpost');

        if ((int)$accountant->is_posted !== 1) {
            throw ValidationException::withMessages([
                'is_posted' => 'Entry is not posted.',
            ]);
        }

        $fromStatus = (string)$accountant->status;
        $accountant->update([
            'status' => 'draft',
            'is_posted' => 0,
            'approved_by' => null,
            'approved_at' => null,
            'updated' => 1,
        ]);
        $this->logAccountantAudit($accountant, 'unposted', $fromStatus, 'draft');

        return redirect()->route('accountant.show', $accountant->id)->with('success', 'Entry was unposted and moved to draft.');
    }

    public function closePeriod(Request $request)
    {
        $this->authorize('viewAny', Accountant::class);
        $this->ensureCanApprove();

        if (!Schema::hasTable('accounting_periods')) {
            return redirect()->route('accountant.index', ['tab' => 'advanced-reports'])
                ->with('error', 'Accounting periods table is not available. Run the latest migrations first.');
        }

        $payload = $request->validate([
            'period_year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'period_month' => ['required', 'integer', 'min:1', 'max:12'],
            'period_note' => ['nullable', 'string', 'max:191'],
        ]);

        $year = (int)$payload['period_year'];
        $month = (int)$payload['period_month'];
        $periodStart = Carbon::create($year, $month, 1)->startOfMonth()->format('Y-m-d');
        $periodEnd = Carbon::create($year, $month, 1)->endOfMonth()->format('Y-m-d');

        $openEntriesCount = Accountant::query()
            ->whereYear('posting_date', $year)
            ->whereMonth('posting_date', $month)
            ->whereIn('status', ['draft', 'pending_approval'])
            ->count();

        if ($openEntriesCount > 0) {
            throw ValidationException::withMessages([
                'period_month' => 'Cannot close this period while draft/pending entries exist for the same month.',
            ]);
        }

        AccountingPeriod::query()->updateOrCreate(
            ['year' => $year, 'month' => $month],
            [
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'is_closed' => 1,
                'closed_by' => Auth::id(),
                'closed_at' => now(),
                'note' => $payload['period_note'] ?? null,
            ]
        );

        return redirect()->route('accountant.index', ['tab' => 'advanced-reports'])
            ->with('success', sprintf('Accounting period %s was closed.', Carbon::create($year, $month, 1)->format('F Y')));
    }

    public function reopenPeriod(Request $request)
    {
        $this->authorize('viewAny', Accountant::class);
        $this->ensureCanApprove();

        if (!Schema::hasTable('accounting_periods')) {
            return redirect()->route('accountant.index', ['tab' => 'advanced-reports'])
                ->with('error', 'Accounting periods table is not available. Run the latest migrations first.');
        }

        $payload = $request->validate([
            'period_year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'period_month' => ['required', 'integer', 'min:1', 'max:12'],
        ]);

        $year = (int)$payload['period_year'];
        $month = (int)$payload['period_month'];

        $period = AccountingPeriod::query()
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        if (!$period) {
            throw ValidationException::withMessages([
                'period_month' => 'Selected period was not found.',
            ]);
        }

        $period->update([
            'is_closed' => 0,
            'closed_by' => null,
            'closed_at' => null,
        ]);

        return redirect()->route('accountant.index', ['tab' => 'advanced-reports'])
            ->with('success', sprintf('Accounting period %s was reopened.', Carbon::create($year, $month, 1)->format('F Y')));
    }

    public function storeChartAccount(Request $request)
    {
        $this->authorizeChartAccountCreation();

        $payload = $request->validate([
            'code' => ['required', 'string', 'max:191', 'unique:chart_accounts,code'],
            'name_en' => ['required', 'string', 'max:191'],
            'name_ar' => ['required', 'string', 'max:191'],
            'type' => ['required', 'in:asset,liability,equity,revenue,expense'],
            'parent_id' => ['nullable', 'integer', 'exists:chart_accounts,id'],
            'is_active' => ['nullable'],
            'note' => ['nullable', 'string'],
        ]);

        $parent = null;
        if (!empty($payload['parent_id'])) {
            $parent = ChartAccount::find($payload['parent_id']);
        }

        ChartAccount::create([
            'code' => $payload['code'],
            'name' => $payload['name_en'],
            'name_en' => $payload['name_en'],
            'name_ar' => $payload['name_ar'],
            'type' => $payload['type'],
            'parent_id' => $payload['parent_id'] ?? null,
            'level' => $parent ? ((int)$parent->level + 1) : 0,
            'is_active' => $request->boolean('is_active', true),
            'note' => $payload['note'] ?? null,
        ]);

        return redirect()->route('accountant.index', ['tab' => 'chart-accounts'])->with('success', 'Chart account created successfully.');
    }

    public function updateChartAccount(Request $request, ChartAccount $chartAccount)
    {
        $this->authorizeChartAccountEditing();

        $payload = $request->validate([
            'code' => ['required', 'string', 'max:191', Rule::unique('chart_accounts', 'code')->ignore($chartAccount->id)],
            'name_en' => ['required', 'string', 'max:191'],
            'name_ar' => ['required', 'string', 'max:191'],
            'type' => ['required', 'in:asset,liability,equity,revenue,expense'],
            'parent_id' => ['nullable', 'integer', 'exists:chart_accounts,id'],
            'is_active' => ['nullable'],
            'note' => ['nullable', 'string'],
        ]);

        if (!empty($payload['parent_id']) && (int)$payload['parent_id'] === (int)$chartAccount->id) {
            return redirect()->route('accountant.index', ['tab' => 'chart-accounts'])->with('error', 'Parent account cannot be the same account.');
        }
        if ($this->wouldCreateChartAccountCycle($chartAccount, !empty($payload['parent_id']) ? (int)$payload['parent_id'] : null)) {
            return redirect()->route('accountant.index', ['tab' => 'chart-accounts'])->with('error', 'Parent account cannot be a child of the same account.');
        }

        $parent = null;
        if (!empty($payload['parent_id'])) {
            $parent = ChartAccount::find($payload['parent_id']);
        }

        $chartAccount->update([
            'code' => $payload['code'],
            'name' => $payload['name_en'],
            'name_en' => $payload['name_en'],
            'name_ar' => $payload['name_ar'],
            'type' => $payload['type'],
            'parent_id' => $payload['parent_id'] ?? null,
            'level' => $parent ? ((int)$parent->level + 1) : 0,
            'is_active' => $request->boolean('is_active', true),
            'note' => $payload['note'] ?? null,
        ]);
        $this->refreshChartAccountChildrenLevels($chartAccount);

        return redirect()->route('accountant.index', ['tab' => 'chart-accounts'])->with('success', 'Chart account updated successfully.');
    }

    public function exportChartAccounts()
    {
        $this->authorize('viewAny', Accountant::class);

        $accounts = ChartAccount::query()
            ->with('parent:id,code')
            ->orderBy('code')
            ->get(['id', 'code', 'name', 'name_en', 'name_ar', 'type', 'parent_id', 'is_active', 'note']);

        $headers = $this->chartAccountImportHeaders();

        return response()->streamDownload(function () use ($accounts, $headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);

            foreach ($accounts as $account) {
                fputcsv($out, [
                    $account->code,
                    $account->name_en ?: $account->name,
                    $account->name_ar,
                    $account->type,
                    optional($account->parent)->code,
                    (int) $account->is_active,
                    $account->note,
                ]);
            }

            fclose($out);
        }, 'chart-of-accounts-'.now()->format('Ymd_His').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function downloadChartAccountsTemplate()
    {
        $this->authorize('viewAny', Accountant::class);

        $headers = $this->chartAccountImportHeaders();
        $rows = $this->chartAccountImportTemplateRows();

        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);

            foreach ($rows as $row) {
                fputcsv($out, $row);
            }

            fclose($out);
        }, 'chart-of-accounts-import-template.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function importChartAccounts(Request $request)
    {
        $this->authorizeChartAccountCreation();

        $payload = $request->validate([
            'chart_accounts_file' => ['required', 'file'],
        ]);

        $handle = fopen($payload['chart_accounts_file']->getRealPath(), 'r');
        if ($handle === false) {
            return redirect()->route('accountant.index', ['tab' => 'chart-accounts'])->with('error', 'Unable to open the selected file.');
        }

        $headerRow = fgetcsv($handle);
        if ($headerRow === false) {
            fclose($handle);
            return redirect()->route('accountant.index', ['tab' => 'chart-accounts'])->with('error', 'The import file is empty.');
        }

        $normalizedHeaders = array_map([$this, 'normalizeChartAccountCsvHeader'], $headerRow);
        foreach (['code', 'name_en', 'name_ar', 'type'] as $requiredHeader) {
            if (!in_array($requiredHeader, $normalizedHeaders, true)) {
                fclose($handle);
                return redirect()->route('accountant.index', ['tab' => 'chart-accounts'])->with('error', 'Missing required import column: '.$requiredHeader);
            }
        }

        $rows = [];
        $lineNumber = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $lineNumber++;
            if (count(array_filter($row, fn ($value) => trim((string) $value) !== '')) === 0) {
                continue;
            }

            $item = [];
            foreach ($normalizedHeaders as $index => $header) {
                $item[$header] = isset($row[$index]) ? trim((string) $row[$index]) : '';
            }
            $item['_line'] = $lineNumber;
            $rows[] = $item;
        }
        fclose($handle);

        if (count($rows) === 0) {
            return redirect()->route('accountant.index', ['tab' => 'chart-accounts'])->with('error', 'The import file does not contain any data rows.');
        }

        $createdCount = 0;
        $updatedCount = 0;
        $importCodes = [];
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($rows as $row) {
                $code = trim((string) ($row['code'] ?? ''));
                $nameEn = trim((string) ($row['name_en'] ?? ''));
                $nameAr = trim((string) ($row['name_ar'] ?? ''));
                $type = strtolower(trim((string) ($row['type'] ?? '')));

                if ($code === '' || $nameEn === '' || $nameAr === '' || $type === '') {
                    $errors[] = 'Line '.$row['_line'].' has missing required values.';
                    continue;
                }

                if (!array_key_exists($type, ChartAccount::typeOptions())) {
                    $errors[] = 'Line '.$row['_line'].' has invalid type: '.$type;
                    continue;
                }

                $account = ChartAccount::query()->where('code', $code)->first();
                $attributes = [
                    'code' => $code,
                    'name' => $nameEn,
                    'name_en' => $nameEn,
                    'name_ar' => $nameAr,
                    'type' => $type,
                    'is_active' => $this->parseChartAccountBoolean($row['is_active'] ?? '1', true) ? 1 : 0,
                    'note' => trim((string) ($row['note'] ?? '')) !== '' ? trim((string) $row['note']) : null,
                ];

                if ($account) {
                    $account->fill($attributes + ['parent_id' => null, 'level' => 0])->save();
                    $updatedCount++;
                } else {
                    ChartAccount::query()->create($attributes + ['parent_id' => null, 'level' => 0]);
                    $createdCount++;
                }

                $importCodes[] = $code;
            }

            if (!empty($errors)) {
                throw new \RuntimeException(implode(' ', $errors));
            }

            $parentCodes = array_values(array_filter(array_map(function ($row) {
                return trim((string) ($row['parent_code'] ?? ''));
            }, $rows)));

            $accountsByCode = ChartAccount::query()
                ->whereIn('code', array_values(array_unique(array_merge($importCodes, $parentCodes))))
                ->get()
                ->keyBy('code');

            foreach ($rows as $row) {
                $code = trim((string) ($row['code'] ?? ''));
                $parentCode = trim((string) ($row['parent_code'] ?? ''));
                $account = $accountsByCode->get($code);

                if (!$account) {
                    $errors[] = 'Imported account not found after save for code '.$code;
                    continue;
                }

                $parent = null;
                if ($parentCode !== '') {
                    $parent = $accountsByCode->get($parentCode);

                    if (!$parent) {
                        $errors[] = 'Line '.$row['_line'].' references missing parent code '.$parentCode;
                        continue;
                    }

                    if ((int) $parent->id === (int) $account->id) {
                        $errors[] = 'Line '.$row['_line'].' cannot use the same code as parent.';
                        continue;
                    }
                }

                $account->update([
                    'parent_id' => $parent?->id,
                    'level' => $parent ? ((int) $parent->level + 1) : 0,
                ]);
            }

            if (!empty($errors)) {
                throw new \RuntimeException(implode(' ', $errors));
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->route('accountant.index', ['tab' => 'chart-accounts'])
                ->with('error', 'Chart of accounts import failed. '.$e->getMessage());
        }

        return redirect()
            ->route('accountant.index', ['tab' => 'chart-accounts'])
            ->with('success', 'Chart of accounts import completed. Created: '.$createdCount.', Updated: '.$updatedCount.'.');
    }

    public function advancedReportsExportExcel(Request $request)
    {
        $this->authorize('viewAny', Accountant::class);

        $dateFrom = $this->normalizeDateInput($request->get('report_date_from'));
        $dateTo = $this->normalizeDateInput($request->get('report_date_to'));
        $ledgerAccountId = $request->filled('ledger_account_id') ? (int)$request->get('ledger_account_id') : null;
        $reportCurrency = strtoupper(trim((string)$request->get('report_currency', '')));
        if ($reportCurrency === '') {
            $reportCurrency = null;
        }

        $reportData = $this->buildAccountingReports($dateFrom, $dateTo, $ledgerAccountId, $reportCurrency);
        $ledgerAccountLabel = null;
        if ($ledgerAccountId) {
            $ledgerAccount = ChartAccount::find($ledgerAccountId);
            if ($ledgerAccount) {
                $ledgerAccountLabel = $this->formatChartAccountLabel($ledgerAccount);
            }
        }

        $fileName = 'accounting-advanced-reports-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($reportData, $dateFrom, $dateTo, $ledgerAccountLabel, $reportCurrency) {
            $out = fopen('php://output', 'w');
            $writeRow = function (array $row) use ($out) {
                fputcsv($out, $row);
            };

            $writeRow(['Advanced Accounting Reports']);
            $writeRow(['Date From', $dateFrom ?: 'All', 'Date To', $dateTo ?: 'All']);
            $writeRow(['Currency Filter', $reportCurrency ?: 'All']);
            if ($ledgerAccountLabel !== null) {
                $writeRow(['Ledger Account', $ledgerAccountLabel]);
            }
            $writeRow([]);

            $writeRow(['Currency Totals']);
            $writeRow(['Currency', 'Debit', 'Credit', 'Difference']);
            foreach ($reportData['currency_totals'] as $currencyTotalRow) {
                $writeRow([
                    $currencyTotalRow['currency'],
                    number_format((float)$currencyTotalRow['debit'], 2, '.', ''),
                    number_format((float)$currencyTotalRow['credit'], 2, '.', ''),
                    number_format((float)$currencyTotalRow['difference'], 2, '.', ''),
                ]);
            }
            $writeRow([]);

            $writeRow(['Trial Balance']);
            $writeRow(['Code', 'Account', 'Type', 'Currency', 'Debit', 'Credit', 'Net']);
            foreach ($reportData['trial_balance_rows'] as $row) {
                $writeRow([
                    $row['code'],
                    $row['name'],
                    ucfirst((string)$row['type']),
                    $row['currency'] ?? '',
                    number_format((float)$row['debit'], 2, '.', ''),
                    number_format((float)$row['credit'], 2, '.', ''),
                    number_format((float)$row['net'], 2, '.', ''),
                ]);
            }
            $writeRow([
                'Totals',
                '',
                '',
                number_format((float)$reportData['trial_balance_totals']['debit'], 2, '.', ''),
                number_format((float)$reportData['trial_balance_totals']['credit'], 2, '.', ''),
                number_format((float)$reportData['trial_balance_totals']['difference'], 2, '.', ''),
            ]);
            $writeRow([]);

            $writeRow(['Income Statement']);
            $writeRow(['Code', 'Account', 'Type', 'Currency', 'Amount']);
            foreach ($reportData['income_statement_rows'] as $row) {
                $writeRow([
                    $row['code'],
                    $row['name'],
                    ucfirst((string)$row['type']),
                    $row['currency'] ?? '',
                    number_format((float)$row['normal_amount'], 2, '.', ''),
                ]);
            }
            $writeRow(['Total Revenue', '', '', number_format((float)$reportData['income_statement_totals']['revenue'], 2, '.', '')]);
            $writeRow(['Total Expense', '', '', number_format((float)$reportData['income_statement_totals']['expense'], 2, '.', '')]);
            $writeRow(['Net Income', '', '', number_format((float)$reportData['income_statement_totals']['net_income'], 2, '.', '')]);
            $writeRow([]);

            $writeRow(['Balance Sheet']);
            $writeRow(['Code', 'Account', 'Type', 'Currency', 'Balance']);
            foreach ($reportData['balance_sheet']['rows'] as $row) {
                $writeRow([
                    $row['code'],
                    $row['name'],
                    ucfirst((string)$row['type']),
                    $row['currency'] ?? '',
                    number_format((float)$row['balance'], 2, '.', ''),
                ]);
            }
            $writeRow(['Assets Total', '', '', number_format((float)$reportData['balance_sheet']['assets_total'], 2, '.', '')]);
            $writeRow(['Liabilities + Equity', '', '', number_format((float)$reportData['balance_sheet']['liabilities_and_equity_total'], 2, '.', '')]);
            $writeRow(['Difference', '', '', number_format((float)$reportData['balance_sheet']['difference'], 2, '.', '')]);
            $writeRow([]);

            $writeRow(['General Ledger']);
            $writeRow(['Date', 'Entry', 'Reference', 'Memo', 'Side', 'Currency', 'Debit', 'Credit', 'Running Balance']);
            foreach ($reportData['ledger_rows'] as $row) {
                $writeRow([
                    $row['posting_date'],
                    $row['entry_code'],
                    $row['reference_no'],
                    $row['memo'],
                    $row['side'],
                    $row['currency'] ?? '',
                    number_format((float)$row['debit'], 2, '.', ''),
                    number_format((float)$row['credit'], 2, '.', ''),
                    number_format((float)$row['running_balance'], 2, '.', ''),
                ]);
            }
            $writeRow([
                'Totals',
                '',
                '',
                '',
                '',
                '',
                number_format((float)$reportData['ledger_totals']['debit'], 2, '.', ''),
                number_format((float)$reportData['ledger_totals']['credit'], 2, '.', ''),
                number_format((float)$reportData['ledger_totals']['balance'], 2, '.', ''),
            ]);

            fclose($out);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function advancedReportsExportPdf(Request $request)
    {
        $this->authorize('viewAny', Accountant::class);

        $dateFrom = $this->normalizeDateInput($request->get('report_date_from'));
        $dateTo = $this->normalizeDateInput($request->get('report_date_to'));
        $ledgerAccountId = $request->filled('ledger_account_id') ? (int)$request->get('ledger_account_id') : null;
        $reportCurrency = strtoupper(trim((string)$request->get('report_currency', '')));
        if ($reportCurrency === '') {
            $reportCurrency = null;
        }

        $reportData = $this->buildAccountingReports($dateFrom, $dateTo, $ledgerAccountId, $reportCurrency);
        $ledgerAccountLabel = null;
        if ($ledgerAccountId) {
            $ledgerAccount = ChartAccount::find($ledgerAccountId);
            if ($ledgerAccount) {
                $ledgerAccountLabel = $this->formatChartAccountLabel($ledgerAccount);
            }
        }

        $pdf = PDF::loadView('layouts.work-flow.accountant.reports.advanced-reports-pdf', [
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'report_currency' => $reportCurrency,
            'ledger_account_label' => $ledgerAccountLabel,
            'trial_balance_rows' => $reportData['trial_balance_rows'],
            'trial_balance_totals' => $reportData['trial_balance_totals'],
            'currency_totals' => $reportData['currency_totals'],
            'is_mixed_currency' => $reportData['is_mixed_currency'],
            'income_statement_rows' => $reportData['income_statement_rows'],
            'income_statement_totals' => $reportData['income_statement_totals'],
            'balance_sheet' => $reportData['balance_sheet'],
            'ledger_rows' => $reportData['ledger_rows'],
            'ledger_totals' => $reportData['ledger_totals'],
        ])->setPaper('a4', 'landscape');

        return $pdf->download('accounting-advanced-reports-'.now()->format('Ymd_His').'.pdf');
    }

    private function validatePayload(Request $request, ?Accountant $accountant = null): array
    {
        $payload = $request->validate([
            'job_request_id' => ['nullable', 'integer', 'exists:job_requests,id'],
            'invoice_id' => ['nullable', 'integer', 'exists:invoices,id'],
            'payment_id' => ['nullable', 'integer', 'exists:payments,id'],
            'posting_date' => ['nullable', 'string'],
            'entry_type' => ['required', 'in:accrual,cash,adjustment,invoice,payment,expense,bank'],
            'debit_account_id' => ['nullable', 'integer', 'exists:chart_accounts,id'],
            'credit_account_id' => ['nullable', 'integer', 'exists:chart_accounts,id'],
            'amount' => ['nullable'],
            'currency' => ['nullable', 'string', 'max:20'],
            'reference_no' => ['nullable', 'string', 'max:191'],
            'status' => ['nullable', 'in:draft,pending_approval,approved,rejected'],
            'is_posted' => ['nullable'],
            'note' => ['nullable', 'string'],
        ]);

        $payload['job_request_id'] = $payload['job_request_id'] ?? null;
        $payload['invoice_id'] = $payload['invoice_id'] ?? null;
        $payload['payment_id'] = $payload['payment_id'] ?? null;

        if (!empty($payload['invoice_id'])) {
            $invoice = Invoice::find($payload['invoice_id']);
            if (!$invoice) {
                throw ValidationException::withMessages(['invoice_id' => 'Selected invoice was not found.']);
            }

            if (!empty($payload['job_request_id']) && (int)$invoice->job_request_id !== (int)$payload['job_request_id']) {
                throw ValidationException::withMessages(['invoice_id' => 'Selected invoice does not belong to selected JCF.']);
            }

            if (empty($payload['job_request_id']) && !empty($invoice->job_request_id)) {
                $payload['job_request_id'] = (int)$invoice->job_request_id;
            }
        }

        if (!empty($payload['payment_id'])) {
            $payment = Payment::find($payload['payment_id']);
            if (!$payment) {
                throw ValidationException::withMessages(['payment_id' => 'Selected payment was not found.']);
            }

            if (!empty($payload['job_request_id']) && (int)$payment->job_request_id !== (int)$payload['job_request_id']) {
                throw ValidationException::withMessages(['payment_id' => 'Selected payment does not belong to selected JCF.']);
            }

            if (empty($payload['job_request_id']) && !empty($payment->job_request_id)) {
                $payload['job_request_id'] = (int)$payment->job_request_id;
            }
        }
        if (!empty($payload['invoice_id']) && !empty($payload['payment_id'])) {
            $invoiceForCheck = Invoice::find($payload['invoice_id']);
            $paymentForCheck = Payment::find($payload['payment_id']);
            if ($invoiceForCheck && $paymentForCheck && (int)$invoiceForCheck->job_request_id !== (int)$paymentForCheck->job_request_id) {
                throw ValidationException::withMessages([
                    'payment_id' => 'Selected payment does not match the selected invoice JCF.',
                ]);
            }
            if ($invoiceForCheck && $paymentForCheck && !empty($paymentForCheck->invoice_id) && (int)$paymentForCheck->invoice_id !== (int)$invoiceForCheck->id) {
                throw ValidationException::withMessages([
                    'payment_id' => 'Selected payment is linked to another invoice.',
                ]);
            }
        }

        $payload['posting_date'] = $this->normalizeDateInput($payload['posting_date'] ?? null) ?: Carbon::today()->format('Y-m-d');
        $payload['currency'] = strtoupper(trim((string)($payload['currency'] ?: 'USD')));
        if ($payload['currency'] === '') {
            $payload['currency'] = 'USD';
        }
        $payload['reference_no'] = $payload['reference_no'] ?? null;
        $payload['status'] = $payload['status'] ?: 'draft';
        $payload['is_posted'] = (int) $request->boolean('is_posted', false);

        $normalizedLines = $this->normalizeEntryLinesInput((array)$request->input('entry_lines', []), $payload['currency']);
        if (count($normalizedLines) > 0) {
            $this->assertEntryLinesAreBalanced($normalizedLines);
            $this->assertEntryLineAccountsAreActive($normalizedLines);

            $payload['entry_lines'] = $normalizedLines;
            [$payload['debit_account_id'], $payload['credit_account_id'], $payload['amount']] = $this->deriveLegacySummaryFromEntryLines($normalizedLines);
        } else {
            $payload['debit_account_id'] = $payload['debit_account_id'] ?? null;
            $payload['credit_account_id'] = $payload['credit_account_id'] ?? null;
            $payload['amount'] = $this->normalizeAmount($payload['amount'] ?? null);

            if ((int)$payload['debit_account_id'] <= 0) {
                throw ValidationException::withMessages([
                    'debit_account_id' => 'Debit account is required.',
                ]);
            }

            if ((int)$payload['credit_account_id'] <= 0) {
                throw ValidationException::withMessages([
                    'credit_account_id' => 'Credit account is required.',
                ]);
            }

            if ((int)$payload['debit_account_id'] === (int)$payload['credit_account_id']) {
                throw ValidationException::withMessages([
                    'credit_account_id' => 'Debit and credit accounts must be different.',
                ]);
            }

            if ((float)$payload['amount'] <= 0) {
                throw ValidationException::withMessages([
                    'amount' => 'Amount must be greater than zero.',
                ]);
            }

            $debitAccount = ChartAccount::where('id', $payload['debit_account_id'])->where('is_active', 1)->first();
            $creditAccount = ChartAccount::where('id', $payload['credit_account_id'])->where('is_active', 1)->first();
            if (!$debitAccount || !$creditAccount) {
                throw ValidationException::withMessages([
                    'debit_account_id' => 'Selected debit/credit account is not active.',
                ]);
            }

            $amount = round((float)$payload['amount'], 2);
            $payload['entry_lines'] = [
                [
                    'chart_account_id' => (int)$payload['debit_account_id'],
                    'line_type' => 'debit',
                    'amount' => $amount,
                    'currency' => $payload['currency'],
                    'line_order' => 1,
                    'note' => null,
                ],
                [
                    'chart_account_id' => (int)$payload['credit_account_id'],
                    'line_type' => 'credit',
                    'amount' => $amount,
                    'currency' => $payload['currency'],
                    'line_order' => 2,
                    'note' => null,
                ],
            ];
        }

        if ($accountant) {
            $requestedStatus = $payload['status'] ?? (string)$accountant->status;
            $this->ensureStatusTransitionAllowed((string)$accountant->status, (string)$requestedStatus);
        }

        return $payload;
    }

    private function resolveStatusAndPosting(?Accountant $accountant, string $requestedStatus, int $requestedIsPosted): array
    {
        $status = $requestedStatus !== '' ? $requestedStatus : 'draft';
        $isPosted = (int)$requestedIsPosted;
        $approvedBy = null;
        $approvedAt = null;

        if ($isPosted === 1 && $status !== 'approved') {
            $status = 'pending_approval';
            $isPosted = 0;
        }

        if ($status === 'approved') {
            $this->ensureCanApprove();
            $isPosted = 1;
            $approvedBy = Auth::id();
            $approvedAt = now();
        } elseif ($status === 'rejected') {
            $this->ensureCanApprove();
            $isPosted = 0;
            $approvedBy = Auth::id();
            $approvedAt = now();
        } elseif ($status === 'pending_approval') {
            $isPosted = 0;
        } elseif ($status === 'draft') {
            $isPosted = 0;
        }

        if ($accountant && (int)$accountant->is_posted === 1 && $isPosted !== 1) {
            throw ValidationException::withMessages([
                'is_posted' => 'Posted entry must be unposted using the Unpost action.',
            ]);
        }

        return [$status, $isPosted, $approvedBy, $approvedAt];
    }

    private function ensureStatusTransitionAllowed(string $fromStatus, string $toStatus): void
    {
        $allowedTransitions = [
            'draft' => ['draft', 'pending_approval', 'rejected'],
            'pending_approval' => ['pending_approval', 'approved', 'rejected'],
            'approved' => ['approved'],
            'rejected' => ['rejected', 'draft', 'pending_approval'],
        ];

        $fromStatus = in_array($fromStatus, self::ACCOUNTANT_STATUSES, true) ? $fromStatus : 'draft';
        $toStatus = in_array($toStatus, self::ACCOUNTANT_STATUSES, true) ? $toStatus : 'draft';

        if (!in_array($toStatus, $allowedTransitions[$fromStatus] ?? [], true)) {
            throw ValidationException::withMessages([
                'status' => 'Status transition is not allowed: '.$fromStatus.' -> '.$toStatus,
            ]);
        }
    }

    private function ensureCanApprove(): void
    {
        $user = Auth::user();
        $canApprove = $user && (
            $user->isSuperAdmin() ||
            $user->hasPermission('accountant', 'approve') ||
            $user->hasPermission('accountant', 'all')
        );
        if (!$canApprove) {
            throw ValidationException::withMessages([
                'status' => 'Only accountant approvers can complete this action.',
            ]);
        }
    }

    private function normalizeEntryLinesInput(array $lines, string $currency): array
    {
        $normalized = [];
        $lineOrder = 1;

        foreach ($lines as $line) {
            if (!is_array($line)) {
                continue;
            }

            $accountId = (int)($line['chart_account_id'] ?? 0);
            $lineType = strtolower(trim((string)($line['line_type'] ?? '')));
            $amount = $this->normalizeAmount($line['amount'] ?? null);
            $note = trim((string)($line['note'] ?? ''));

            $isEmptyLine = $accountId <= 0 && $lineType === '' && $amount <= 0 && $note === '';
            if ($isEmptyLine) {
                continue;
            }

            if (!in_array($lineType, ['debit', 'credit'], true)) {
                throw ValidationException::withMessages([
                    'entry_lines' => 'Each line must have a valid type: debit or credit.',
                ]);
            }

            if ($accountId <= 0) {
                throw ValidationException::withMessages([
                    'entry_lines' => 'Each line must include an account.',
                ]);
            }

            if ((float)$amount <= 0) {
                throw ValidationException::withMessages([
                    'entry_lines' => 'Each line amount must be greater than zero.',
                ]);
            }

            $normalized[] = [
                'chart_account_id' => $accountId,
                'line_type' => $lineType,
                'amount' => round((float)$amount, 2),
                'currency' => $currency,
                'line_order' => $lineOrder++,
                'note' => $note !== '' ? $note : null,
            ];
        }

        return $normalized;
    }

    private function assertEntryLinesAreBalanced(array $lines): void
    {
        $debit = 0.0;
        $credit = 0.0;
        foreach ($lines as $line) {
            if (($line['line_type'] ?? '') === 'debit') {
                $debit += (float)($line['amount'] ?? 0);
            } else {
                $credit += (float)($line['amount'] ?? 0);
            }
        }

        $debit = round($debit, 2);
        $credit = round($credit, 2);

        if ($debit <= 0 || $credit <= 0) {
            throw ValidationException::withMessages([
                'entry_lines' => 'Journal entry must contain both debit and credit amounts.',
            ]);
        }

        if (abs($debit - $credit) > 0.0001) {
            throw ValidationException::withMessages([
                'entry_lines' => sprintf('Journal entry is not balanced. Debit %.2f must equal credit %.2f.', $debit, $credit),
            ]);
        }
    }

    private function assertEntryLineAccountsAreActive(array $lines): void
    {
        $accountIds = array_values(array_unique(array_map(function ($line) {
            return (int)($line['chart_account_id'] ?? 0);
        }, $lines)));
        $accountIds = array_values(array_filter($accountIds, fn ($value) => $value > 0));

        if (count($accountIds) === 0) {
            throw ValidationException::withMessages([
                'entry_lines' => 'At least one account line is required.',
            ]);
        }

        $activeCount = ChartAccount::query()
            ->whereIn('id', $accountIds)
            ->where('is_active', 1)
            ->count();

        if ($activeCount !== count($accountIds)) {
            throw ValidationException::withMessages([
                'entry_lines' => 'One or more selected accounts are inactive.',
            ]);
        }
    }

    private function deriveLegacySummaryFromEntryLines(array $lines): array
    {
        $firstDebit = collect($lines)->firstWhere('line_type', 'debit');
        $firstCredit = collect($lines)->firstWhere('line_type', 'credit');
        $debitTotal = (float)collect($lines)->where('line_type', 'debit')->sum('amount');

        return [
            (int)($firstDebit['chart_account_id'] ?? 0),
            (int)($firstCredit['chart_account_id'] ?? 0),
            round($debitTotal, 2),
        ];
    }

    private function syncEntryLines(Accountant $accountant, array $lines, string $currency): void
    {
        if (!Schema::hasTable('accountant_entry_lines')) {
            return;
        }

        AccountantEntryLine::query()->where('accountant_id', $accountant->id)->delete();

        $now = now();
        $payload = [];
        foreach ($lines as $index => $line) {
            $payload[] = [
                'accountant_id' => $accountant->id,
                'chart_account_id' => (int)$line['chart_account_id'],
                'line_type' => (string)$line['line_type'],
                'amount' => round((float)$line['amount'], 2),
                'currency' => strtoupper(trim((string)($line['currency'] ?? $currency))),
                'line_order' => (int)($line['line_order'] ?? ($index + 1)),
                'note' => $line['note'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($payload)) {
            AccountantEntryLine::query()->insert($payload);
        }
    }

    private function buildEntryLinesForCreate(array $prefill): array
    {
        $prefillAmount = round((float)$this->normalizeAmount($prefill['amount'] ?? null), 2);
        if ($prefillAmount <= 0) {
            $prefillAmount = 0.0;
        }

        $oldLines = old('entry_lines');
        if (is_array($oldLines) && count($oldLines) > 0) {
            return $oldLines;
        }

        return [
            [
                'chart_account_id' => old('debit_account_id'),
                'line_type' => 'debit',
                'amount' => $prefillAmount > 0 ? number_format($prefillAmount, 2, '.', '') : '',
                'note' => '',
            ],
            [
                'chart_account_id' => old('credit_account_id'),
                'line_type' => 'credit',
                'amount' => $prefillAmount > 0 ? number_format($prefillAmount, 2, '.', '') : '',
                'note' => '',
            ],
        ];
    }

    private function buildEntryLinesForEdit(Accountant $accountant): array
    {
        $oldLines = old('entry_lines');
        if (is_array($oldLines) && count($oldLines) > 0) {
            return $oldLines;
        }

        $lines = [];
        if (Schema::hasTable('accountant_entry_lines')) {
            $lines = $accountant->lines()
                ->orderBy('line_order')
                ->orderBy('id')
                ->get()
                ->map(function (AccountantEntryLine $line) {
                    return [
                        'chart_account_id' => (int)$line->chart_account_id,
                        'line_type' => (string)$line->line_type,
                        'amount' => number_format((float)$line->amount, 2, '.', ''),
                        'note' => (string)($line->note ?? ''),
                    ];
                })
                ->toArray();
        }

        if (count($lines) > 0) {
            return $lines;
        }

        return [
            [
                'chart_account_id' => (int)$accountant->debit_account_id,
                'line_type' => 'debit',
                'amount' => number_format((float)$accountant->amount, 2, '.', ''),
                'note' => '',
            ],
            [
                'chart_account_id' => (int)$accountant->credit_account_id,
                'line_type' => 'credit',
                'amount' => number_format((float)$accountant->amount, 2, '.', ''),
                'note' => '',
            ],
        ];
    }

    private function assertAccountingPeriodOpen(?string $postingDate, string $action): void
    {
        if (!Schema::hasTable('accounting_periods')) {
            return;
        }

        if ($postingDate === null || trim((string)$postingDate) === '') {
            return;
        }

        try {
            $parsedDate = Carbon::parse($postingDate);
        } catch (\Throwable $e) {
            return;
        }

        $period = AccountingPeriod::query()
            ->where('year', (int)$parsedDate->format('Y'))
            ->where('month', (int)$parsedDate->format('m'))
            ->first();

        if ($period && (bool)$period->is_closed) {
            throw ValidationException::withMessages([
                'posting_date' => sprintf(
                    'Accounting period %s is closed. Cannot %s entries in this period.',
                    $parsedDate->format('F Y'),
                    $action
                ),
            ]);
        }
    }

    private function periodStatusForDate(?string $postingDate): string
    {
        if (!Schema::hasTable('accounting_periods') || $postingDate === null || trim((string)$postingDate) === '') {
            return 'open';
        }

        try {
            $parsedDate = Carbon::parse($postingDate);
        } catch (\Throwable $e) {
            return 'open';
        }

        $period = AccountingPeriod::query()
            ->where('year', (int)$parsedDate->format('Y'))
            ->where('month', (int)$parsedDate->format('m'))
            ->first();

        if ($period && (bool)$period->is_closed) {
            return 'closed';
        }

        return 'open';
    }

    private function recentAccountingPeriods()
    {
        if (!Schema::hasTable('accounting_periods')) {
            return collect();
        }

        return AccountingPeriod::query()
            ->with('closedBy.employee:id,name')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->limit(12)
            ->get();
    }

    private function getPostedCurrencyOptions(): array
    {
        $currencies = Accountant::query()
            ->where('is_posted', 1)
            ->whereNotNull('currency')
            ->select('currency')
            ->groupBy('currency')
            ->orderBy('currency')
            ->pluck('currency')
            ->map(function ($currency) {
                return strtoupper(trim((string)$currency));
            })
            ->filter(fn ($currency) => $currency !== '')
            ->values()
            ->all();

        if (empty($currencies)) {
            return ['USD'];
        }

        return $currencies;
    }

    private function logAccountantAudit(Accountant $accountant, string $action, ?string $fromStatus = null, ?string $toStatus = null, array $payload = []): void
    {
        AccountantAudit::create([
            'accountant_id' => $accountant->id,
            'action' => $action,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'changed_by' => Auth::id(),
            'payload' => empty($payload) ? null : json_encode($payload),
        ]);
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

    private function formatDateOutput($value, $format = 'd-m-Y'): string
    {
        if ($value === null || trim((string)$value) === '') {
            return '-';
        }

        try {
            return Carbon::parse($value)->format($format);
        } catch (\Throwable $e) {
            return '-';
        }
    }

    private function nextCode(array $payload = []): string
    {
        return Accountant::nextTreeSequentialCode(
            (int)($payload['debit_account_id'] ?? 0),
            (int)($payload['credit_account_id'] ?? 0),
            (string)($payload['posting_date'] ?? ''),
            (array)($payload['entry_lines'] ?? [])
        );
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

    private function getPaymentOptions(?int $jobRequestId): array
    {
        if (!$jobRequestId) {
            return [];
        }

        return Payment::query()
            ->where('job_request_id', $jobRequestId)
            ->orderBy('code', 'desc')
            ->get(['id', 'code'])
            ->mapWithKeys(function (Payment $payment) {
                return [$payment->id => $payment->code];
            })
            ->toArray();
    }

    private function resolvePrefillPayload(Request $request, ?JobRequest $jobRequest): array
    {
        $jobRequestId = $jobRequest ? (int)$jobRequest->id : (int)$request->query('job_request_id');
        $invoiceId = (int)$request->query('invoice_id');
        $paymentId = (int)$request->query('payment_id');

        $invoice = null;
        if ($jobRequestId > 0 && $invoiceId > 0) {
            $invoice = Invoice::where('id', $invoiceId)
                ->where('job_request_id', $jobRequestId)
                ->first();
        }

        $payment = null;
        if ($jobRequestId > 0 && $paymentId > 0) {
            $payment = Payment::where('id', $paymentId)
                ->where('job_request_id', $jobRequestId)
                ->first();
        }

        $entryType = trim((string)$request->query('entry_type', ''));
        if (!in_array($entryType, ['accrual', 'cash', 'adjustment', 'invoice', 'payment'], true)) {
            $entryType = null;
        }

        return [
            'job_request_id' => $jobRequestId > 0 ? $jobRequestId : null,
            'invoice_id' => $invoice?->id,
            'payment_id' => $payment?->id,
            'entry_type' => $entryType,
            'amount' => $request->query('amount'),
            'currency' => $this->normalizePrefillString($request->query('currency')) ?: ($invoice->type ?? null),
            'reference_no' => $this->normalizePrefillString($request->query('reference_no')),
            'note' => $this->normalizePrefillString($request->query('note')),
        ];
    }

    private function normalizePrefillString($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string)$value);
        return $value === '' ? null : $value;
    }

    private function getChartAccountOptions(): array
    {
        $accounts = ChartAccount::query()
            ->where('is_active', 1)
            ->orderBy('code')
            ->get(['id', 'code', 'name', 'name_en', 'name_ar', 'parent_id']);

        $children = [];
        foreach ($accounts as $account) {
            $parentKey = (string)($account->parent_id ?? '');
            if (!isset($children[$parentKey])) {
                $children[$parentKey] = [];
            }
            $children[$parentKey][] = $account;
        }

        $options = [];

        $walk = function ($parentKey, $level) use (&$walk, &$options, $children) {
            foreach (($children[$parentKey] ?? []) as $account) {
                $prefix = str_repeat('   ', (int)$level);
                $options[$account->id] = trim($prefix.$this->formatChartAccountLabel($account));
                $walk((string)$account->id, $level + 1);
            }
        };

        $walk('', 0);

        return $options;
    }

    private function buildAccountingReports(?string $dateFrom, ?string $dateTo, ?int $ledgerAccountId, ?string $reportCurrency = null): array
    {
        $baseQuery = Accountant::query()
            ->with([
                'debitAccount:id,code,name,name_en,name_ar,type',
                'creditAccount:id,code,name,name_en,name_ar,type',
                'lines.chartAccount:id,code,name,name_en,name_ar,type',
            ])
            ->where('is_posted', 1);

        if ($dateFrom) {
            $baseQuery->whereDate('posting_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $baseQuery->whereDate('posting_date', '<=', $dateTo);
        }

        if ($reportCurrency !== null) {
            $baseQuery->where('currency', $reportCurrency);
        }

        $entries = $baseQuery->orderBy('posting_date')->orderBy('id')->get();

        $trial = [];
        $currencyTotals = [];
        $ledgerRows = [];
        $ledgerRunning = 0.0;
        $ledgerDebitTotal = 0.0;
        $ledgerCreditTotal = 0.0;

        foreach ($entries as $entry) {
            $entryCurrency = strtoupper(trim((string)($entry->currency ?: 'USD')));
            if ($entryCurrency === '') {
                $entryCurrency = 'USD';
            }

            $entryLines = [];
            if (Schema::hasTable('accountant_entry_lines') && $entry->relationLoaded('lines') && $entry->lines->count() > 0) {
                foreach ($entry->lines as $line) {
                    if (!$line->chartAccount) {
                        continue;
                    }
                    $lineAmount = round((float)$line->amount, 2);
                    if ($lineAmount <= 0) {
                        continue;
                    }
                    $lineCurrency = strtoupper(trim((string)($line->currency ?: $entryCurrency)));
                    if ($lineCurrency === '') {
                        $lineCurrency = $entryCurrency;
                    }
                    $entryLines[] = [
                        'account' => $line->chartAccount,
                        'line_type' => $line->line_type,
                        'amount' => $lineAmount,
                        'currency' => $lineCurrency,
                    ];
                }
            } else {
                $legacyAmount = round((float)$entry->amount, 2);
                if ($legacyAmount > 0 && $entry->debitAccount && $entry->creditAccount) {
                    $entryLines[] = [
                        'account' => $entry->debitAccount,
                        'line_type' => 'debit',
                        'amount' => $legacyAmount,
                        'currency' => $entryCurrency,
                    ];
                    $entryLines[] = [
                        'account' => $entry->creditAccount,
                        'line_type' => 'credit',
                        'amount' => $legacyAmount,
                        'currency' => $entryCurrency,
                    ];
                }
            }

            foreach ($entryLines as $line) {
                $lineCurrency = $line['currency'];
                if ($reportCurrency !== null && $lineCurrency !== $reportCurrency) {
                    continue;
                }

                $account = $line['account'];
                $amount = (float)$line['amount'];
                $debit = $line['line_type'] === 'debit' ? $amount : 0.0;
                $credit = $line['line_type'] === 'credit' ? $amount : 0.0;

                if (!isset($currencyTotals[$lineCurrency])) {
                    $currencyTotals[$lineCurrency] = [
                        'currency' => $lineCurrency,
                        'debit' => 0.0,
                        'credit' => 0.0,
                    ];
                }
                $currencyTotals[$lineCurrency]['debit'] += $debit;
                $currencyTotals[$lineCurrency]['credit'] += $credit;

                $trialKey = ((int)$account->id).'|'.$lineCurrency;
                if (!isset($trial[$trialKey])) {
                    $trial[$trialKey] = [
                        'account_id' => (int)$account->id,
                        'code' => (string)$account->code,
                        'name' => (string)$account->display_name,
                        'type' => (string)$account->type,
                        'currency' => $lineCurrency,
                        'debit' => 0.0,
                        'credit' => 0.0,
                    ];
                }

                $trial[$trialKey]['debit'] += $debit;
                $trial[$trialKey]['credit'] += $credit;

                if ($ledgerAccountId !== null && (int)$ledgerAccountId === (int)$account->id) {
                    $ledgerRunning += ($debit - $credit);
                    $ledgerDebitTotal += $debit;
                    $ledgerCreditTotal += $credit;

                    $ledgerRows[] = [
                        'posting_date' => $this->formatDateOutput($entry->posting_date),
                        'entry_code' => $entry->code,
                        'reference_no' => $entry->reference_no ?: '-',
                        'memo' => $entry->note ?: '-',
                        'side' => $line['line_type'] === 'debit' ? 'Debit' : 'Credit',
                        'currency' => $lineCurrency,
                        'debit' => $debit,
                        'credit' => $credit,
                        'running_balance' => $ledgerRunning,
                    ];
                }
            }
        }

        $currencyTotalsRows = collect(array_values($currencyTotals))
            ->map(function ($row) {
                $row['difference'] = (float)$row['debit'] - (float)$row['credit'];
                return $row;
            })
            ->sortBy('currency')
            ->values();

        $trialRows = collect(array_values($trial))
            ->sort(function ($left, $right) {
                $code = strcmp((string)$left['code'], (string)$right['code']);
                if ($code !== 0) {
                    return $code;
                }

                $name = strcmp((string)$left['name'], (string)$right['name']);
                if ($name !== 0) {
                    return $name;
                }

                return strcmp((string)$left['currency'], (string)$right['currency']);
            })
            ->values()
            ->map(function ($row) {
                $row['net'] = (float)$row['debit'] - (float)$row['credit'];
                return $row;
            });

        $trialTotals = [
            'debit' => (float)$trialRows->sum('debit'),
            'credit' => (float)$trialRows->sum('credit'),
            'difference' => (float)$trialRows->sum('debit') - (float)$trialRows->sum('credit'),
        ];

        $incomeRows = $trialRows
            ->filter(function ($row) {
                return in_array($row['type'], ['revenue', 'expense'], true);
            })
            ->map(function ($row) {
                $normalAmount = $row['type'] === 'revenue'
                    ? ((float)$row['credit'] - (float)$row['debit'])
                    : ((float)$row['debit'] - (float)$row['credit']);
                $row['normal_amount'] = $normalAmount;
                return $row;
            })
            ->values();

        $incomeTotals = [
            'revenue' => (float)$incomeRows->where('type', 'revenue')->sum('normal_amount'),
            'expense' => (float)$incomeRows->where('type', 'expense')->sum('normal_amount'),
        ];
        $incomeTotals['net_income'] = $incomeTotals['revenue'] - $incomeTotals['expense'];

        $balanceRows = $trialRows
            ->filter(function ($row) {
                return in_array($row['type'], ['asset', 'liability', 'equity'], true);
            })
            ->map(function ($row) {
                $balance = $row['type'] === 'asset'
                    ? ((float)$row['debit'] - (float)$row['credit'])
                    : ((float)$row['credit'] - (float)$row['debit']);
                $row['balance'] = $balance;
                return $row;
            })
            ->values();

        $balanceSheet = [
            'assets_total' => (float)$balanceRows->where('type', 'asset')->sum('balance'),
            'liabilities_total' => (float)$balanceRows->where('type', 'liability')->sum('balance'),
            'equity_total' => (float)$balanceRows->where('type', 'equity')->sum('balance'),
            'rows' => $balanceRows,
        ];
        $balanceSheet['liabilities_and_equity_total'] = $balanceSheet['liabilities_total'] + $balanceSheet['equity_total'];
        $balanceSheet['difference'] = $balanceSheet['assets_total'] - $balanceSheet['liabilities_and_equity_total'];

        return [
            'trial_balance_rows' => $trialRows,
            'trial_balance_totals' => $trialTotals,
            'currency_totals' => $currencyTotalsRows,
            'is_mixed_currency' => $currencyTotalsRows->count() > 1,
            'income_statement_rows' => $incomeRows,
            'income_statement_totals' => $incomeTotals,
            'balance_sheet' => $balanceSheet,
            'ledger_rows' => collect($ledgerRows),
            'ledger_totals' => [
                'debit' => $ledgerDebitTotal,
                'credit' => $ledgerCreditTotal,
                'balance' => $ledgerRunning,
            ],
        ];
    }

    private function formatChartAccountLabel(ChartAccount $account): string
    {
        return trim($account->code.' - '.$account->display_name);
    }

    private function authorizeChartAccountCreation(): void
    {
        abort_unless($this->canCreateChartAccounts(), 403);
    }

    private function authorizeChartAccountEditing(): void
    {
        abort_unless($this->canEditChartAccounts(), 403);
    }

    private function canCreateChartAccounts(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        return (bool) $user->isSuperAdmin()
            || (bool) $user->hasPermission('accountant', 'all')
            || (bool) $user->hasPermission('accountant', 'create');
    }

    private function canEditChartAccounts(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        return (bool) $user->isSuperAdmin()
            || (bool) $user->hasPermission('accountant', 'all')
            || (bool) $user->hasPermission('accountant', 'edit');
    }

    private function canManageChartAccounts(): bool
    {
        return $this->canCreateChartAccounts() || $this->canEditChartAccounts();
    }

    private function wouldCreateChartAccountCycle(ChartAccount $account, ?int $parentId): bool
    {
        if (!$parentId) {
            return false;
        }

        $visited = [];
        $cursorId = $parentId;
        while ($cursorId) {
            if (in_array($cursorId, $visited, true)) {
                break;
            }
            $visited[] = $cursorId;

            if ((int) $cursorId === (int) $account->id) {
                return true;
            }

            $cursorId = (int) ChartAccount::query()->where('id', $cursorId)->value('parent_id');
        }

        return false;
    }

    private function refreshChartAccountChildrenLevels(ChartAccount $account): void
    {
        $children = ChartAccount::query()
            ->where('parent_id', $account->id)
            ->get(['id', 'level']);

        foreach ($children as $child) {
            $newLevel = (int) $account->level + 1;
            if ((int) $child->level !== $newLevel) {
                $child->update(['level' => $newLevel]);
            }
            $this->refreshChartAccountChildrenLevels($child);
        }
    }

    private function formatChartAccountLabelFromColumns(?string $code, ?string $nameEn = null, ?string $nameAr = null): string
    {
        $englishName = trim((string) ($nameEn ?? ''));
        $arabicName = trim((string) ($nameAr ?? ''));

        if ($englishName !== '' && $arabicName !== '' && $englishName !== $arabicName) {
            $name = $englishName.' / '.$arabicName;
        } else {
            $name = $englishName !== '' ? $englishName : $arabicName;
        }

        return trim(((string) $code !== '' ? $code.' - ' : '').$name);
    }

    private function chartAccountImportHeaders(): array
    {
        return ['code', 'name_en', 'name_ar', 'type', 'parent_code', 'is_active', 'note'];
    }

    private function chartAccountImportTemplateRows(): array
    {
        return [
            ['1000', 'Assets', 'الأصول', 'asset', '', '1', 'Root account'],
            ['1100', 'Cash and Bank', 'النقدية والبنوك', 'asset', '1000', '1', 'Current assets'],
            ['1200', 'Accounts Receivable', 'الذمم المدينة', 'asset', '1000', '1', 'Customer balances'],
            ['2000', 'Liabilities', 'الالتزامات', 'liability', '', '1', 'Root account'],
            ['2100', 'Accounts Payable', 'الذمم الدائنة', 'liability', '2000', '1', 'Supplier balances'],
            ['4000', 'Revenue', 'الإيرادات', 'revenue', '', '1', 'Root account'],
            ['4100', 'Service Revenue', 'إيراد الخدمات', 'revenue', '4000', '1', 'Inspection revenue'],
            ['5000', 'Expenses', 'المصروفات', 'expense', '', '1', 'Root account'],
        ];
    }

    private function normalizeChartAccountCsvHeader(string $value): string
    {
        $value = preg_replace('/^\xEF\xBB\xBF/', '', (string) $value);
        return strtolower(trim((string) $value));
    }

    private function parseChartAccountBoolean($value, bool $default = true): bool
    {
        $value = strtolower(trim((string) $value));
        if ($value === '') {
            return $default;
        }

        return in_array($value, ['1', 'true', 'yes', 'y', 'active'], true);
    }
}

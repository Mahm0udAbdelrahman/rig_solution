<?php

namespace App\Http\Controllers\Dashboard\WorkFlow;

use App\Http\Controllers\Controller;
use App\Models\WorkFlow\Accountant;
use App\Models\WorkFlow\Bank;
use App\Models\WorkFlow\BankTransaction;
use App\Models\WorkFlow\ChartAccount;
use App\Models\WorkFlow\Expense;
use App\Models\WorkFlow\Invoice;
use App\Models\WorkFlow\Payment;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BankController extends Controller
{
    public $page_name = 'Bank';

    private const BANK_STATUSES = ['draft', 'pending_approval', 'approved', 'rejected'];

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Bank::class, 'bank');
    }

    public function dashboard(Request $request)
    {
        $this->authorize('viewAny', Bank::class);
        $user = Auth::user();
        $hasFinancialAccess = $user->hasPermission('financial', 'show') || $user->hasPermission('financial', 'all');
        abort_if(!$hasFinancialAccess, 403);

        $rawFilters = $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
            'bank_account_id' => ['nullable', 'integer', 'exists:bank_accounts,id'],
            'currency' => ['nullable', 'string', 'max:20'],
            'status' => ['nullable', 'in:all,draft,pending_approval,approved,rejected,posted'],
            'direction' => ['nullable', 'in:all,in,out'],
            'source_type' => ['nullable', 'in:all,manual,payment_auto,expense_auto,expense_payment_auto'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $filters = [
            'from_date' => $rawFilters['from_date'] ?? now()->startOfMonth()->format('Y-m-d'),
            'to_date' => $rawFilters['to_date'] ?? now()->format('Y-m-d'),
            'bank_account_id' => !empty($rawFilters['bank_account_id']) ? (int)$rawFilters['bank_account_id'] : null,
            'currency' => strtoupper(trim((string)($rawFilters['currency'] ?? ''))),
            'status' => $rawFilters['status'] ?? 'all',
            'direction' => $rawFilters['direction'] ?? 'all',
            'source_type' => $rawFilters['source_type'] ?? 'all',
            'q' => trim((string)($rawFilters['q'] ?? '')),
        ];
        if ($filters['currency'] === 'ALL') {
            $filters['currency'] = '';
        }

        $bankFilterOptions = Bank::query()
            ->orderBy('code')
            ->get(['id', 'code', 'bank_name', 'account_name', 'currency', 'is_active']);

        $currencyOptions = Bank::query()
            ->select('currency')
            ->whereNotNull('currency')
            ->whereRaw('TRIM(currency) <> ""')
            ->distinct()
            ->orderBy('currency')
            ->pluck('currency')
            ->map(function ($currency) {
                return strtoupper(trim((string)$currency));
            })
            ->filter()
            ->unique()
            ->values();

        $transactionsQuery = BankTransaction::query()
            ->with(['bank:id,code,bank_name,account_name,currency'])
            ->with(['accountant:id,code', 'payment:id,code']);
        $this->applyDashboardTransactionFilters($transactionsQuery, $filters);

        $filteredTransactionsCount = (clone $transactionsQuery)->count();
        $pendingTransactions = (clone $transactionsQuery)->where('status', 'pending_approval')->count();
        $draftTransactions = (clone $transactionsQuery)->where('status', 'draft')->count();
        $approvedTransactions = (clone $transactionsQuery)->where(function ($query) {
            $query->where('status', 'approved')->orWhere('is_posted', 1);
        })->count();
        $postedTransactions = (clone $transactionsQuery)->where('is_posted', 1)->count();

        $totalInflow = (clone $transactionsQuery)
            ->where('is_posted', 1)
            ->where('direction', 'in')
            ->sum('amount');

        $totalOutflow = (clone $transactionsQuery)
            ->where('is_posted', 1)
            ->where('direction', 'out')
            ->sum('amount');

        $postedTransactionsCount = max(1, (int)$postedTransactions);
        $averagePostedAmount = (float)((((float)$totalInflow + (float)$totalOutflow) / $postedTransactionsCount));

        $largestTransaction = (clone $transactionsQuery)
            ->orderByDesc('amount')
            ->orderByDesc('id')
            ->first();

        $latestPostedDate = (clone $transactionsQuery)
            ->where('is_posted', 1)
            ->max('transaction_date');

        $dailyFlow = (clone $transactionsQuery)
            ->selectRaw('transaction_date as flow_date')
            ->selectRaw("SUM(CASE WHEN direction = 'in' AND is_posted = 1 THEN amount ELSE 0 END) as in_total")
            ->selectRaw("SUM(CASE WHEN direction = 'out' AND is_posted = 1 THEN amount ELSE 0 END) as out_total")
            ->groupBy('transaction_date')
            ->orderBy('transaction_date')
            ->get()
            ->map(function ($row) {
                $in = (float)$row->in_total;
                $out = (float)$row->out_total;
                return [
                    'date' => $row->flow_date,
                    'in_total' => $in,
                    'out_total' => $out,
                    'net_total' => $in - $out,
                ];
            })
            ->values();

        $recentTransactions = (clone $transactionsQuery)
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        $banksScope = Bank::query();
        if (!empty($filters['bank_account_id'])) {
            $banksScope->where('id', (int)$filters['bank_account_id']);
        }
        if (!empty($filters['currency'])) {
            $banksScope->whereRaw('UPPER(currency) = ?', [$filters['currency']]);
        }

        $balancesByCurrency = (clone $banksScope)
            ->selectRaw('UPPER(currency) as currency')
            ->selectRaw('SUM(current_balance) as total_balance')
            ->groupBy('currency')
            ->orderBy('currency')
            ->get();

        $bankAccountsSnapshot = (clone $banksScope)
            ->withCount([
                'transactions as transaction_count',
                'transactions as pending_transaction_count' => function ($query) {
                    $query->where('status', 'pending_approval');
                },
                'transactions as posted_transaction_count' => function ($query) {
                    $query->where('is_posted', 1);
                },
            ])
            ->orderBy('code')
            ->limit(15)
            ->get(['id', 'code', 'bank_name', 'account_name', 'currency', 'current_balance', 'is_active']);

        $canViewInvoices = $user->can('viewAny', Invoice::class);
        $dueInvoicesCount = 0;
        $dueInvoicesAmount = 0.0;
        $dueInvoices = collect();
        if ($canViewInvoices) {
            $paidSummarySubQuery = DB::table('payments')
                ->select('invoice_id', DB::raw("SUM(CASE WHEN status IN ('received','approved') THEN amount ELSE 0 END) as paid_amount"))
                ->groupBy('invoice_id');

            $dueInvoicesBase = Invoice::query()
                ->leftJoinSub($paidSummarySubQuery, 'invoice_payments', function ($join) {
                    $join->on('invoice_payments.invoice_id', '=', 'invoices.id');
                })
                ->leftJoin('job_requests', 'job_requests.id', '=', 'invoices.job_request_id')
                ->leftJoin('clients', 'clients.id', '=', 'job_requests.client_id')
                ->leftJoin('suppliers', 'suppliers.id', '=', 'job_requests.supplier_id')
                ->select([
                    'invoices.id as invoice_id',
                    'invoices.code as invoice_code',
                    'invoices.total as invoice_total',
                    'invoices.type as invoice_currency',
                    'invoices.created_at as invoice_created_at',
                    'job_requests.code as job_request_code',
                    'clients.name as client_name',
                    'suppliers.name as supplier_name',
                    DB::raw('COALESCE(invoice_payments.paid_amount, 0) as paid_amount'),
                    DB::raw('(invoices.total - COALESCE(invoice_payments.paid_amount, 0)) as due_amount'),
                ])
                ->whereRaw('(invoices.total - COALESCE(invoice_payments.paid_amount, 0)) > 0.009');

            if (!empty($filters['currency'])) {
                $dueInvoicesBase->whereRaw('UPPER(invoices.type) = ?', [$filters['currency']]);
            }
            if (!empty($filters['from_date'])) {
                $dueInvoicesBase->whereDate('invoices.created_at', '>=', $filters['from_date']);
            }
            if (!empty($filters['to_date'])) {
                $dueInvoicesBase->whereDate('invoices.created_at', '<=', $filters['to_date']);
            }
            if ($filters['q'] !== '') {
                $keyword = '%'.$filters['q'].'%';
                $dueInvoicesBase->where(function ($query) use ($keyword) {
                    $query->where('invoices.code', 'like', $keyword)
                        ->orWhere('job_requests.code', 'like', $keyword)
                        ->orWhere('clients.name', 'like', $keyword)
                        ->orWhere('suppliers.name', 'like', $keyword);
                });
            }

            $dueInvoicesCount = (clone $dueInvoicesBase)->count();
            $dueInvoicesAmount = (clone $dueInvoicesBase)->sum(DB::raw('(invoices.total - COALESCE(invoice_payments.paid_amount, 0))'));
            $dueInvoices = (clone $dueInvoicesBase)
                ->orderBy('invoices.created_at')
                ->limit(12)
                ->get();
        }

        $canViewPayments = $user->can('viewAny', Payment::class);
        $canViewExpenses = $user->can('viewAny', Expense::class);
        $canViewAccountant = $user->can('viewAny', Accountant::class);

        $pendingQueues = [
            'bank' => $user->can('viewAny', Bank::class)
                ? BankTransaction::query()->where('status', 'pending_approval')->count()
                : null,
            'payment' => $canViewPayments
                ? Payment::query()->whereIn('status', ['pending', 'pending_approval'])->count()
                : null,
            'expense' => $canViewExpenses
                ? Expense::query()->where('status', 'pending_approval')->count()
                : null,
            'accountant' => $canViewAccountant
                ? Accountant::query()->where('status', 'pending_approval')->count()
                : null,
        ];

        return view('layouts.work-flow.bank.dashboard', [
            'page_name' => 'Financial Dashboard',
            'filters' => $filters,
            'bank_options' => $bankFilterOptions,
            'currency_options' => $currencyOptions,
            'total_banks' => (clone $banksScope)->count(),
            'active_banks' => (clone $banksScope)->where('is_active', 1)->count(),
            'filtered_transactions_count' => (int)$filteredTransactionsCount,
            'pending_transactions' => (int)$pendingTransactions,
            'draft_transactions' => (int)$draftTransactions,
            'approved_transactions' => (int)$approvedTransactions,
            'posted_transactions' => (int)$postedTransactions,
            'total_inflow' => (float)$totalInflow,
            'total_outflow' => (float)$totalOutflow,
            'net_flow' => (float)$totalInflow - (float)$totalOutflow,
            'average_posted_amount' => (float)$averagePostedAmount,
            'largest_transaction' => $largestTransaction,
            'latest_posted_date' => $latestPostedDate,
            'daily_flow' => $dailyFlow,
            'balances_by_currency' => $balancesByCurrency,
            'recent_transactions' => $recentTransactions,
            'due_invoices_count' => (int)$dueInvoicesCount,
            'due_invoices_amount' => (float)$dueInvoicesAmount,
            'due_invoices' => $dueInvoices,
            'can_view_invoices' => $canViewInvoices,
            'can_view_payments' => $canViewPayments,
            'can_view_expenses' => $canViewExpenses,
            'can_view_accountant' => $canViewAccountant,
            'can_create_bank' => $user->can('create', Bank::class),
            'can_create_payment' => $user->can('create', Payment::class),
            'can_create_expense' => $user->can('create', Expense::class),
            'can_create_accountant' => $user->can('create', Accountant::class),
            'pending_queues' => $pendingQueues,
            'bank_accounts_snapshot' => $bankAccountsSnapshot,
        ]);
    }

    private function applyDashboardTransactionFilters($query, array $filters): void
    {
        if (!empty($filters['from_date'])) {
            $query->whereDate('transaction_date', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate('transaction_date', '<=', $filters['to_date']);
        }
        if (!empty($filters['bank_account_id'])) {
            $query->where('bank_account_id', (int)$filters['bank_account_id']);
        }
        if (!empty($filters['currency'])) {
            $query->whereHas('bank', function ($bankQuery) use ($filters) {
                $bankQuery->whereRaw('UPPER(currency) = ?', [$filters['currency']]);
            });
        }
        if (($filters['direction'] ?? 'all') !== 'all') {
            $query->where('direction', $filters['direction']);
        }
        if (($filters['status'] ?? 'all') !== 'all') {
            if ($filters['status'] === 'posted') {
                $query->where('is_posted', 1);
            } else {
                $query->where('status', $filters['status']);
            }
        }
        if (($filters['source_type'] ?? 'all') !== 'all') {
            $query->where('source_type', $filters['source_type']);
        }
        if (!empty($filters['q'])) {
            $keyword = '%'.$filters['q'].'%';
            $query->where(function ($searchQuery) use ($keyword) {
                $searchQuery->where('code', 'like', $keyword)
                    ->orWhere('reference_no', 'like', $keyword)
                    ->orWhere('note', 'like', $keyword)
                    ->orWhere('category', 'like', $keyword)
                    ->orWhereHas('bank', function ($bankQuery) use ($keyword) {
                        $bankQuery->where('code', 'like', $keyword)
                            ->orWhere('bank_name', 'like', $keyword)
                            ->orWhere('account_name', 'like', $keyword);
                    });
            });
        }
    }

    public function index()
    {
        return view('layouts.work-flow.bank.index', [
            'page_name' => $this->page_name('All', $this->page_name),
            'banks' => Bank::query()->count(),
        ]);
    }

    public function getDataForDataTable()
    {
        $data = Bank::query()
            ->leftJoin('users', 'bank_accounts.user_id', '=', 'users.id')
            ->leftJoin('employees', 'employees.id', '=', 'users.employee_id')
            ->select([
                'bank_accounts.id as bank_id',
                'bank_accounts.code as code',
                'bank_accounts.bank_name as bank_name',
                'bank_accounts.account_name as account_name',
                'bank_accounts.account_number as account_number',
                'bank_accounts.currency as currency',
                'bank_accounts.current_balance as current_balance',
                'bank_accounts.is_active as is_active',
                'employees.name as employee',
            ]);

        return Datatables::of($data)
            ->addColumn('status', function ($row) {
                return (int)$row->is_active === 1 ? 'Active' : 'Inactive';
            })
            ->addColumn('action', function ($row) {
                $bank = Bank::find($row->bank_id);
                if (!$bank) {
                    return '';
                }

                $btn = '';
                if (Auth::user()->can('update', $bank)) {
                    $btn .= '<a href="'.route('bank.edit', $row->bank_id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                }
                if (Auth::user()->can('delete', $bank)) {
                    $btn .= '<button type="button" data-id="'.$row->bank_id.'" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>';
                }

                return $btn === '' ? '' : '<div class="wf-inline-actions">'.$btn.'</div>';
            })
            ->editColumn('code', function ($row) {
                $bank = Bank::find($row->bank_id);
                if (!$bank || !Auth::user()->can('view', $bank)) {
                    return $row->code;
                }
                return '<a href="'.route('bank.show', $row->bank_id).'">'.$row->code.'</a>';
            })
            ->editColumn('current_balance', function ($row) {
                return number_format((float)$row->current_balance, 2, '.', '');
            })
            ->orderColumn('code', 'bank_accounts.code $1')
            ->orderColumn('bank_name', 'bank_accounts.bank_name $1')
            ->orderColumn('account_name', 'bank_accounts.account_name $1')
            ->orderColumn('account_number', 'bank_accounts.account_number $1')
            ->orderColumn('currency', 'bank_accounts.currency $1')
            ->orderColumn('current_balance', 'bank_accounts.current_balance $1')
            ->orderColumn('status', 'bank_accounts.is_active $1')
            ->orderColumn('employee', 'employees.name $1')
            ->filterColumn('code', function ($query, $keyword) {
                $query->whereRaw('bank_accounts.code like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('bank_name', function ($query, $keyword) {
                $query->whereRaw('bank_accounts.bank_name like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('account_name', function ($query, $keyword) {
                $query->whereRaw('bank_accounts.account_name like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('account_number', function ($query, $keyword) {
                $query->whereRaw('bank_accounts.account_number like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('currency', function ($query, $keyword) {
                $query->whereRaw('bank_accounts.currency like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->whereRaw('CASE WHEN bank_accounts.is_active = 1 THEN "Active" ELSE "Inactive" END like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('employee', function ($query, $keyword) {
                $query->whereRaw('employees.name like ?', ["%{$keyword}%"]);
            })
            ->rawColumns(['code', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('layouts.work-flow.bank.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'opening_date' => now()->format('Y-m-d'),
            'chart_account_options' => $this->getChartAccountOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $payload = $this->validateBankPayload($request);

        $bank = Bank::create([
            'code' => $this->nextBankCode(),
            'bank_name' => $payload['bank_name'],
            'account_name' => $payload['account_name'],
            'account_number' => $payload['account_number'],
            'iban' => $payload['iban'],
            'currency' => $payload['currency'],
            'chart_account_id' => $payload['chart_account_id'],
            'opening_balance' => $payload['opening_balance'],
            'opening_date' => $payload['opening_date'],
            'current_balance' => $payload['opening_balance'],
            'is_active' => $payload['is_active'],
            'note' => $payload['note'],
            'user_id' => Auth::id(),
            'approved_by' => null,
            'sync' => 0,
            'updated' => null,
        ]);

        return redirect()->route('bank.show', $bank->id)->with('success', $this->action_message(0, $this->page_name));
    }

    public function show(Bank $bank)
    {
        $this->recalculateBalance($bank);
        $bank->refresh();

        $transactions = $bank->transactions()
            ->with([
                'jobRequest:id,code',
                'payment:id,code',
                'accountant:id,code,status,is_posted',
                'counterAccount:id,code,name,name_en,name_ar',
                'approvedBy.employee',
                'user.employee',
            ])
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->limit(100)
            ->get();

        return view('layouts.work-flow.bank.show', [
            'page_name' => $this->page_name,
            'bank' => $bank->load(['chartAccount']),
            'transactions' => $transactions,
            'posted_in_total' => $bank->transactions()->where('is_posted', 1)->where('direction', 'in')->sum('amount'),
            'posted_out_total' => $bank->transactions()->where('is_posted', 1)->where('direction', 'out')->sum('amount'),
            'pending_count' => $bank->transactions()->where('status', 'pending_approval')->count(),
            'chart_account_options' => $this->getChartAccountOptions(),
        ]);
    }

    public function edit(Bank $bank)
    {
        return view('layouts.work-flow.bank.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'bank' => $bank,
            'opening_date' => $bank->opening_date ? Carbon::parse($bank->opening_date)->format('Y-m-d') : null,
            'chart_account_options' => $this->getChartAccountOptions(),
        ]);
    }

    public function update(Request $request, Bank $bank)
    {
        $payload = $this->validateBankPayload($request, $bank);

        $bank->update([
            'bank_name' => $payload['bank_name'],
            'account_name' => $payload['account_name'],
            'account_number' => $payload['account_number'],
            'iban' => $payload['iban'],
            'currency' => $payload['currency'],
            'chart_account_id' => $payload['chart_account_id'],
            'opening_balance' => $payload['opening_balance'],
            'opening_date' => $payload['opening_date'],
            'is_active' => $payload['is_active'],
            'note' => $payload['note'],
            'updated' => 1,
        ]);

        $this->recalculateBalance($bank);

        return redirect()->route('bank.show', $bank->id)->with('success', $this->action_message(1, $this->page_name));
    }

    public function destroy(Bank $bank)
    {
        if ($bank->transactions()->count() > 0) {
            return response()->json(['success' => 'Cannot delete bank account with transactions.'], 422);
        }

        $removed = $bank->forceDelete();
        if ($removed) {
            return response()->json(['success' => $this->action_message(2, $this->page_name)]);
        }

        return response()->json(['success' => 'Unable to delete this Bank record.'], 422);
    }

    public function storeTransaction(Request $request, Bank $bank)
    {
        $this->authorize('update', $bank);
        $payload = $this->validateTransactionPayload($request);

        DB::transaction(function () use ($bank, $payload) {
            [$resolvedStatus, $resolvedIsPosted, $approvedBy, $approvedAt] = $this->resolveStatusAndPosting(
                null,
                $payload['status'],
                (int)($payload['is_posted'] ?? 0)
            );

            $transaction = BankTransaction::create([
                'code' => $this->nextTransactionCode(),
                'bank_account_id' => $bank->id,
                'job_request_id' => $payload['job_request_id'],
                'payment_id' => $payload['payment_id'],
                'accountant_id' => null,
                'counter_account_id' => $payload['counter_account_id'],
                'direction' => $payload['direction'],
                'category' => $payload['category'],
                'source_type' => 'manual',
                'transaction_date' => $payload['transaction_date'],
                'amount' => $payload['amount'],
                'reference_no' => $payload['reference_no'],
                'status' => $resolvedStatus,
                'note' => $payload['note'],
                'approved_by' => $approvedBy,
                'approved_at' => $approvedAt,
                'is_posted' => $resolvedIsPosted,
                'user_id' => Auth::id(),
                'sync' => 0,
                'updated' => null,
            ]);

            if ((int)$resolvedIsPosted === 1) {
                $this->syncAccountantEntryForApprovedTransaction($transaction);
            }

            $this->recalculateBalance($bank);
        });

        return redirect()->route('bank.show', $bank->id)->with('success', 'Bank transaction added successfully.');
    }

    public function submitForApproval(Bank $bank, BankTransaction $transaction)
    {
        $this->authorize('update', $bank);
        $this->ensureTransactionBelongsToBank($bank, $transaction);

        if ((int)$transaction->is_posted === 1) {
            throw ValidationException::withMessages([
                'status' => 'Posted transaction is already approved.',
            ]);
        }

        $fromStatus = (string)$transaction->status;
        $this->ensureStatusTransitionAllowed($fromStatus, 'pending_approval');

        $transaction->update([
            'status' => 'pending_approval',
            'approved_by' => null,
            'approved_at' => null,
            'updated' => 1,
        ]);

        return redirect()->route('bank.show', $bank->id)->with('success', 'Transaction submitted for approval.');
    }

    public function approveTransaction(Bank $bank, BankTransaction $transaction)
    {
        $this->authorize('update', $bank);
        $this->ensureCanApprove();
        $this->ensureTransactionBelongsToBank($bank, $transaction);

        if ((string)$transaction->status !== 'pending_approval') {
            throw ValidationException::withMessages([
                'status' => 'Only pending approval transactions can be approved.',
            ]);
        }

        DB::transaction(function () use ($transaction, $bank) {
            $transaction->update([
                'status' => 'approved',
                'is_posted' => 1,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'updated' => 1,
            ]);

            $this->syncAccountantEntryForApprovedTransaction($transaction);
            $this->recalculateBalance($bank);
        });

        return redirect()->route('bank.show', $bank->id)->with('success', 'Transaction approved and posted.');
    }

    public function rejectTransaction(Bank $bank, BankTransaction $transaction)
    {
        $this->authorize('update', $bank);
        $this->ensureCanApprove();
        $this->ensureTransactionBelongsToBank($bank, $transaction);

        if ((string)$transaction->status !== 'pending_approval') {
            throw ValidationException::withMessages([
                'status' => 'Only pending approval transactions can be rejected.',
            ]);
        }

        $transaction->update([
            'status' => 'rejected',
            'is_posted' => 0,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'updated' => 1,
        ]);

        return redirect()->route('bank.show', $bank->id)->with('success', 'Transaction rejected.');
    }

    public function unpostTransaction(Bank $bank, BankTransaction $transaction)
    {
        $this->authorize('update', $bank);
        $this->ensureCanApprove();
        $this->ensureTransactionBelongsToBank($bank, $transaction);

        if ((int)$transaction->is_posted !== 1) {
            throw ValidationException::withMessages([
                'is_posted' => 'Transaction is not posted.',
            ]);
        }

        DB::transaction(function () use ($transaction, $bank) {
            $transaction->update([
                'status' => 'draft',
                'is_posted' => 0,
                'approved_by' => null,
                'approved_at' => null,
                'updated' => 1,
            ]);

            $this->unpostLinkedAccountantEntry($transaction);
            $this->recalculateBalance($bank);
        });

        return redirect()->route('bank.show', $bank->id)->with('success', 'Transaction was unposted and moved to draft.');
    }

    public function destroyTransaction(Bank $bank, BankTransaction $transaction)
    {
        $this->authorize('update', $bank);
        $this->ensureTransactionBelongsToBank($bank, $transaction);

        if ((int)$transaction->is_posted === 1) {
            throw ValidationException::withMessages([
                'transaction' => 'Posted transaction cannot be deleted.',
            ]);
        }

        $transaction->forceDelete();
        $this->recalculateBalance($bank);

        return redirect()->route('bank.show', $bank->id)->with('success', 'Bank transaction deleted successfully.');
    }

    private function validateBankPayload(Request $request, ?Bank $bank = null): array
    {
        $payload = $request->validate([
            'bank_name' => ['required', 'string', 'max:191'],
            'account_name' => ['required', 'string', 'max:191'],
            'account_number' => [
                'required',
                'string',
                'max:191',
                Rule::unique('bank_accounts', 'account_number')->ignore($bank?->id),
            ],
            'iban' => ['nullable', 'string', 'max:191'],
            'currency' => ['required', 'string', 'max:20'],
            'chart_account_id' => ['nullable', 'integer', 'exists:chart_accounts,id'],
            'opening_balance' => ['required', 'numeric'],
            'opening_date' => ['nullable', 'date'],
            'is_active' => ['nullable'],
            'note' => ['nullable', 'string'],
        ]);

        if (!empty($payload['chart_account_id'])) {
            $chartAccount = ChartAccount::query()->where('id', $payload['chart_account_id'])->where('is_active', 1)->first();
            if (!$chartAccount) {
                throw ValidationException::withMessages([
                    'chart_account_id' => 'Selected chart account is not active.',
                ]);
            }
        }

        $payload['opening_balance'] = round((float)$payload['opening_balance'], 2);
        $payload['opening_date'] = $payload['opening_date'] ? Carbon::parse($payload['opening_date'])->format('Y-m-d') : null;
        $payload['is_active'] = (int)$request->boolean('is_active', true);
        $payload['chart_account_id'] = $payload['chart_account_id'] ?? null;

        return $payload;
    }

    private function validateTransactionPayload(Request $request): array
    {
        $payload = $request->validate([
            'job_request_id' => ['nullable', 'integer', 'exists:job_requests,id'],
            'payment_id' => ['nullable', 'integer', 'exists:payments,id'],
            'counter_account_id' => ['nullable', 'integer', 'exists:chart_accounts,id'],
            'direction' => ['required', 'in:in,out'],
            'category' => ['required', 'in:deposit,withdraw,transfer,adjustment,fee,other'],
            'transaction_date' => ['nullable', 'date'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'reference_no' => ['nullable', 'string', 'max:191'],
            'status' => ['nullable', 'in:draft,pending_approval,approved,rejected'],
            'is_posted' => ['nullable'],
            'note' => ['nullable', 'string'],
        ]);

        $payload = array_merge([
            'job_request_id' => null,
            'payment_id' => null,
            'counter_account_id' => null,
            'transaction_date' => null,
            'reference_no' => null,
            'status' => 'draft',
            'is_posted' => null,
            'note' => null,
        ], $payload);

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

        if (empty($payload['counter_account_id'])) {
            $payload['counter_account_id'] = $this->defaultCounterAccountIdByDirection((string)$payload['direction']);
        }

        if (!empty($payload['counter_account_id'])) {
            $counterAccount = ChartAccount::query()->where('id', $payload['counter_account_id'])->where('is_active', 1)->first();
            if (!$counterAccount) {
                throw ValidationException::withMessages(['counter_account_id' => 'Selected counter account is not active.']);
            }
        }

        $payload['status'] = $payload['status'] ?? 'draft';
        $payload['is_posted'] = (int)$request->boolean('is_posted', false);
        $payload['transaction_date'] = $payload['transaction_date']
            ? Carbon::parse($payload['transaction_date'])->format('Y-m-d')
            : Carbon::today()->format('Y-m-d');
        $payload['amount'] = round((float)$payload['amount'], 2);
        $payload['reference_no'] = $payload['reference_no'] ?? null;

        return $payload;
    }

    private function ensureTransactionBelongsToBank(Bank $bank, BankTransaction $transaction): void
    {
        if ((int)$transaction->bank_account_id !== (int)$bank->id) {
            throw ValidationException::withMessages([
                'transaction' => 'Transaction does not belong to this bank account.',
            ]);
        }
    }

    private function resolveStatusAndPosting(?BankTransaction $transaction, ?string $requestedStatus, int $requestedIsPosted): array
    {
        $status = ($requestedStatus !== null && $requestedStatus !== '') ? $requestedStatus : 'draft';
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
        } elseif (in_array($status, ['pending_approval', 'draft'], true)) {
            $isPosted = 0;
        }

        if ($transaction && (int)$transaction->is_posted === 1 && $isPosted !== 1) {
            throw ValidationException::withMessages([
                'is_posted' => 'Posted transaction must be unposted using the Unpost action.',
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

        $fromStatus = in_array($fromStatus, self::BANK_STATUSES, true) ? $fromStatus : 'draft';
        $toStatus = in_array($toStatus, self::BANK_STATUSES, true) ? $toStatus : 'draft';

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
            $user->hasPermission('bank', 'approve') ||
            $user->hasPermission('bank', 'all') ||
            $user->hasPermission('accountant', 'approve') ||
            $user->hasPermission('accountant', 'all')
        );

        if (!$canApprove) {
            throw ValidationException::withMessages([
                'status' => 'Only bank approvers can complete this action.',
            ]);
        }
    }

    private function syncAccountantEntryForApprovedTransaction(BankTransaction $transaction): void
    {
        $transaction->loadMissing(['bank', 'counterAccount']);
        $bank = $transaction->bank;

        if (!$bank) {
            throw ValidationException::withMessages([
                'transaction' => 'Bank account was not found for this transaction.',
            ]);
        }

        if (empty($bank->chart_account_id)) {
            throw ValidationException::withMessages([
                'transaction' => 'Bank account is missing chart account mapping. Update bank account first.',
            ]);
        }

        $bankAccount = ChartAccount::query()->where('id', $bank->chart_account_id)->where('is_active', 1)->first();
        if (!$bankAccount) {
            throw ValidationException::withMessages([
                'transaction' => 'Mapped bank chart account is not active.',
            ]);
        }

        $counterAccountId = (int)($transaction->counter_account_id ?? 0);
        if ($counterAccountId <= 0) {
            $counterAccountId = (int)($this->defaultCounterAccountIdByDirection((string)$transaction->direction) ?? 0);
        }
        if ($counterAccountId <= 0) {
            throw ValidationException::withMessages([
                'transaction' => 'Counter account is required for accountant automation.',
            ]);
        }

        $counterAccount = ChartAccount::query()->where('id', $counterAccountId)->where('is_active', 1)->first();
        if (!$counterAccount) {
            throw ValidationException::withMessages([
                'transaction' => 'Counter account is not active.',
            ]);
        }

        $debitAccountId = $transaction->direction === 'out' ? $counterAccountId : (int)$bank->chart_account_id;
        $creditAccountId = $transaction->direction === 'out' ? (int)$bank->chart_account_id : $counterAccountId;

        $accountant = null;
        if (!empty($transaction->accountant_id)) {
            $accountant = Accountant::find($transaction->accountant_id);
        }

        $payload = [
            'job_request_id' => $transaction->job_request_id,
            'invoice_id' => null,
            'payment_id' => $transaction->payment_id,
            'posting_date' => $transaction->transaction_date ?: Carbon::today()->format('Y-m-d'),
            'entry_type' => 'bank',
            'debit_account_id' => $debitAccountId,
            'credit_account_id' => $creditAccountId,
            'amount' => round((float)$transaction->amount, 2),
            'currency' => $bank->currency ?: 'USD',
            'reference_no' => $transaction->reference_no ?: $transaction->code,
            'status' => 'approved',
            'is_posted' => 1,
            'note' => 'Auto-generated from Bank Transaction '.$transaction->code,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'updated' => 1,
        ];

        if ($accountant) {
            $accountant->update($payload);
        } else {
            $accountant = Accountant::create(array_merge($payload, [
                'code' => Accountant::nextTreeSequentialCode(
                    $debitAccountId,
                    $creditAccountId,
                    (string)($payload['posting_date'] ?? '')
                ),
                'user_id' => Auth::id(),
                'sync' => 0,
                'updated' => null,
            ]));
        }

        if ((int)$transaction->accountant_id !== (int)$accountant->id) {
            $transaction->update([
                'accountant_id' => $accountant->id,
                'counter_account_id' => $counterAccountId,
                'updated' => 1,
            ]);
        }
    }

    private function unpostLinkedAccountantEntry(BankTransaction $transaction): void
    {
        if (empty($transaction->accountant_id)) {
            return;
        }

        $accountant = Accountant::find($transaction->accountant_id);
        if (!$accountant) {
            return;
        }

        $accountant->update([
            'status' => 'draft',
            'is_posted' => 0,
            'approved_by' => null,
            'approved_at' => null,
            'updated' => 1,
        ]);
    }

    private function defaultCounterAccountIdByDirection(string $direction): ?int
    {
        $code = $direction === 'out' ? '2100' : '1200';
        $account = ChartAccount::query()
            ->where('code', $code)
            ->where('is_active', 1)
            ->first(['id']);

        if ($account) {
            return (int)$account->id;
        }

        $fallbackType = $direction === 'out' ? 'liability' : 'asset';
        $fallback = ChartAccount::query()
            ->where('type', $fallbackType)
            ->where('is_active', 1)
            ->orderBy('level')
            ->orderBy('id')
            ->first(['id']);

        return $fallback ? (int)$fallback->id : null;
    }

    private function getChartAccountOptions(): array
    {
        return ChartAccount::query()
            ->where('is_active', 1)
            ->orderBy('code')
            ->get(['id', 'code', 'name', 'name_en', 'name_ar'])
            ->mapWithKeys(function (ChartAccount $account) {
                $englishName = trim((string)($account->name_en ?: $account->name ?: ''));
                $arabicName = trim((string)($account->name_ar ?: ''));
                $name = $englishName;

                if ($englishName !== '' && $arabicName !== '' && $englishName !== $arabicName) {
                    $name = $englishName.' / '.$arabicName;
                } elseif ($name === '' && $arabicName !== '') {
                    $name = $arabicName;
                }

                return [$account->id => trim($account->code.' - '.$name)];
            })
            ->toArray();
    }

    private function recalculateBalance(Bank $bank): void
    {
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

    private function nextBankCode(): string
    {
        $prefix = 'BNK-'.date('y').'-';
        $lastCode = Bank::where('code', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('code');

        $next = 1;
        if ($lastCode && preg_match('/(\d+)$/', $lastCode, $matches)) {
            $next = ((int)$matches[1]) + 1;
        }

        return $prefix.str_pad((string)$next, 3, '0', STR_PAD_LEFT);
    }

    private function nextTransactionCode(): string
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

}

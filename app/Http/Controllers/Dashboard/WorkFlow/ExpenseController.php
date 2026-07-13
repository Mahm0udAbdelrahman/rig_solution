<?php

namespace App\Http\Controllers\Dashboard\WorkFlow;

use App\Http\Controllers\Controller;
use App\Models\Organization\Employee;
use App\Models\WorkFlow\Accountant;
use App\Models\WorkFlow\Bank;
use App\Models\WorkFlow\BankTransaction;
use App\Models\WorkFlow\ChartAccount;
use App\Models\WorkFlow\Expense;
use App\Models\WorkFlow\JobRequest;
use App\Models\WorkFlow\Payment;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ExpenseController extends Controller
{
    public $page_name = 'Expense';
    private const EXPENSE_STATUSES = ['draft', 'pending_approval', 'approved', 'rejected'];

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Expense::class, 'expense');
    }

    public function index(Request $request)
    {
        $selectedEmployeeId = (int)$request->get('employee_id');
        $selectedStatus = trim((string)$request->get('entry_status', ''));
        $selectedPaymentState = trim((string)$request->get('payment_state', 'all'));
        if (!in_array($selectedPaymentState, ['all', 'paid', 'unpaid', 'partial', 'pending'], true)) {
            $selectedPaymentState = 'all';
        }

        $expenseQuery = Expense::query()
            ->leftJoin('payments as linked_payments', 'linked_payments.id', '=', 'expenses.payment_id')
            ->select('expenses.*');
        if ($selectedEmployeeId > 0) {
            $expenseQuery->where('employee_id', $selectedEmployeeId);
        }
        if (in_array($selectedStatus, self::EXPENSE_STATUSES, true)) {
            $expenseQuery->where('status', $selectedStatus);
        }
        $this->applyPaymentStateFilter($expenseQuery, $selectedPaymentState);

        return view('layouts.work-flow.expense.index', [
            'page_name' => $this->page_name('All', $this->page_name),
            'expenses' => $expenseQuery->count(),
            'employee_options' => $this->getEmployeeOptions(),
            'selected_employee_id' => $selectedEmployeeId,
            'selected_entry_status' => $selectedStatus,
            'selected_payment_state' => $selectedPaymentState,
            'pending_approval_count' => Expense::query()->where('status', 'pending_approval')->count(),
        ]);
    }

    public function getDataForDataTable(Request $request)
    {
        $selectedEmployeeId = (int)$request->get('employee_id');
        $selectedStatus = trim((string)$request->get('entry_status', ''));
        $selectedPaymentState = trim((string)$request->get('payment_state', 'all'));
        if (!in_array($selectedPaymentState, ['all', 'paid', 'unpaid', 'partial', 'pending'], true)) {
            $selectedPaymentState = 'all';
        }

        $data = Expense::query()
            ->leftJoin('employees as expense_employees', 'expense_employees.id', '=', 'expenses.employee_id')
            ->leftJoin('users', 'expenses.user_id', '=', 'users.id')
            ->leftJoin('employees as creator_employees', 'creator_employees.id', '=', 'users.employee_id')
            ->leftJoin('bank_accounts', 'bank_accounts.id', '=', 'expenses.bank_account_id')
            ->leftJoin('accountants', 'accountants.id', '=', 'expenses.accountant_id')
            ->leftJoin('payments as linked_payments', 'linked_payments.id', '=', 'expenses.payment_id')
            ->select([
                'expenses.id as expense_id',
                'expenses.code as code',
                'expenses.expense_date as expense_date',
                'expenses.category as category',
                'expenses.amount as amount',
                'expenses.currency as currency',
                'expenses.status as status',
                'expenses.payment_id as payment_id',
                'expense_employees.name as expense_employee_name',
                'bank_accounts.code as bank_code',
                'bank_accounts.bank_name as bank_name',
                'bank_accounts.currency as bank_currency',
                'accountants.code as accountant_code',
                'creator_employees.name as creator_name',
                'linked_payments.code as payment_code',
                'linked_payments.status as payment_status',
                'linked_payments.amount as payment_amount',
            ]);

        if ($selectedEmployeeId > 0) {
            $data->where('expenses.employee_id', $selectedEmployeeId);
        }
        if (in_array($selectedStatus, self::EXPENSE_STATUSES, true)) {
            $data->where('expenses.status', $selectedStatus);
        }
        $this->applyPaymentStateFilter($data, $selectedPaymentState);

        return Datatables::of($data)
            ->addColumn('bank_account', function ($row) {
                if (!$row->bank_code && !$row->bank_name) {
                    return '-';
                }
                return trim(($row->bank_code ?: '').' - '.($row->bank_name ?: ''), ' -');
            })
            ->addColumn('payment_state', function ($row) {
                $expenseAmount = round((float)$row->amount, 2);
                $paymentAmount = round((float)($row->payment_amount ?? 0), 2);
                $status = strtolower(trim((string)($row->payment_status ?? '')));
                $paymentCode = trim((string)($row->payment_code ?? ''));

                if ((int)($row->payment_id ?? 0) <= 0) {
                    return '<span class="badge badge-secondary">Unpaid</span>';
                }

                if ($paymentAmount > 0 && $paymentAmount < ($expenseAmount - 0.009)) {
                    return '<span class="badge badge-info">Partial</span>'.($paymentCode !== '' ? ' <small class="text-muted">'.$paymentCode.'</small>' : '');
                }

                if (in_array($status, ['approved', 'received'], true) && $paymentAmount >= ($expenseAmount - 0.009)) {
                    return '<span class="badge badge-success">Paid</span>'.($paymentCode !== '' ? ' <small class="text-muted">'.$paymentCode.'</small>' : '');
                }

                if (in_array($status, ['pending', 'pending_approval', 'draft'], true)) {
                    return '<span class="badge badge-warning">Pending Payment</span>'.($paymentCode !== '' ? ' <small class="text-muted">'.$paymentCode.'</small>' : '');
                }

                return '<span class="badge badge-secondary">Unpaid</span>'.($paymentCode !== '' ? ' <small class="text-muted">'.$paymentCode.'</small>' : '');
            })
            ->addColumn('action', function ($row) {
                $expense = Expense::find($row->expense_id);
                if (!$expense) {
                    return '';
                }

                $btn = '';
                if (Auth::user()->can('update', $expense)) {
                    $btn .= '<a href="'.route('expense.edit', $row->expense_id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                }
                if (Auth::user()->can('delete', $expense)) {
                    $btn .= '<button type="button" data-id="'.$row->expense_id.'" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>';
                }

                if ($btn === '') {
                    return '';
                }

                return '<div class="wf-inline-actions">'.$btn.'</div>';
            })
            ->editColumn('code', function ($row) {
                $expense = Expense::find($row->expense_id);
                if (!$expense || !Auth::user()->can('view', $expense)) {
                    return $row->code;
                }
                return '<a href="'.route('expense.show', $row->expense_id).'">'.$row->code.'</a>';
            })
            ->editColumn('expense_date', function ($row) {
                return $this->formatDateOutput($row->expense_date);
            })
            ->editColumn('amount', function ($row) {
                return number_format((float)$row->amount, 2, '.', '');
            })
            ->editColumn('currency', function ($row) {
                return $row->bank_currency ?: ($row->currency ?: 'USD');
            })
            ->editColumn('expense_employee_name', function ($row) {
                return $row->expense_employee_name ?: '-';
            })
            ->editColumn('accountant_code', function ($row) {
                return $row->accountant_code ?: '-';
            })
            ->editColumn('creator_name', function ($row) {
                return $row->creator_name ?: 'System';
            })
            ->orderColumn('code', 'expenses.code $1')
            ->orderColumn('expense_date', 'expenses.expense_date $1')
            ->orderColumn('expense_employee_name', 'expense_employees.name $1')
            ->orderColumn('category', 'expenses.category $1')
            ->orderColumn('amount', 'expenses.amount $1')
            ->orderColumn('currency', 'COALESCE(bank_accounts.currency, expenses.currency) $1')
            ->orderColumn('bank_account', 'bank_accounts.code $1')
            ->orderColumn('status', 'expenses.status $1')
            ->orderColumn('payment_state', 'linked_payments.status $1')
            ->orderColumn('accountant_code', 'accountants.code $1')
            ->orderColumn('creator_name', 'creator_employees.name $1')
            ->filterColumn('code', function ($query, $keyword) {
                $query->whereRaw('expenses.code like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('expense_date', function ($query, $keyword) {
                $query->whereRaw('expenses.expense_date like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('expense_employee_name', function ($query, $keyword) {
                $query->whereRaw('expense_employees.name like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('category', function ($query, $keyword) {
                $query->whereRaw('expenses.category like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('amount', function ($query, $keyword) {
                $query->whereRaw('expenses.amount like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('currency', function ($query, $keyword) {
                $query->whereRaw('COALESCE(bank_accounts.currency, expenses.currency) like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('bank_account', function ($query, $keyword) {
                $query->whereRaw('CONCAT(COALESCE(bank_accounts.code, \'\'), \' \', COALESCE(bank_accounts.bank_name, \'\')) like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->whereRaw('expenses.status like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('payment_state', function ($query, $keyword) {
                $keyword = strtolower(trim((string)$keyword));
                if ($keyword === '') {
                    return;
                }

                if (str_contains('paid', $keyword)) {
                    $this->applyPaymentStateFilter($query, 'paid');
                    return;
                }
                if (str_contains('partial', $keyword)) {
                    $this->applyPaymentStateFilter($query, 'partial');
                    return;
                }
                if (str_contains('pending', $keyword)) {
                    $this->applyPaymentStateFilter($query, 'pending');
                    return;
                }
                if (str_contains('unpaid', $keyword) || str_contains('not', $keyword)) {
                    $this->applyPaymentStateFilter($query, 'unpaid');
                }
            })
            ->filterColumn('accountant_code', function ($query, $keyword) {
                $query->whereRaw('accountants.code like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('creator_name', function ($query, $keyword) {
                $query->whereRaw('creator_employees.name like ?', ["%{$keyword}%"]);
            })
            ->rawColumns(['code', 'payment_state', 'action'])
            ->make(true);
    }

    private function applyPaymentStateFilter($query, string $paymentState): void
    {
        if ($paymentState === 'all') {
            return;
        }

        if ($paymentState === 'paid') {
            $query->whereNotNull('expenses.payment_id')
                ->whereIn('linked_payments.status', ['approved', 'received'])
                ->whereRaw('COALESCE(linked_payments.amount, 0) >= (expenses.amount - 0.009)');
            return;
        }

        if ($paymentState === 'partial') {
            $query->whereNotNull('expenses.payment_id')
                ->whereRaw('COALESCE(linked_payments.amount, 0) > 0')
                ->whereRaw('COALESCE(linked_payments.amount, 0) < (expenses.amount - 0.009)');
            return;
        }

        if ($paymentState === 'pending') {
            $query->whereNotNull('expenses.payment_id')
                ->whereIn('linked_payments.status', ['pending', 'pending_approval', 'draft']);
            return;
        }

        if ($paymentState === 'unpaid') {
            $query->where(function ($subQuery) {
                $subQuery->whereNull('expenses.payment_id')
                    ->orWhereIn('linked_payments.status', ['rejected', 'draft', 'pending', 'pending_approval'])
                    ->orWhereRaw('COALESCE(linked_payments.amount, 0) < (expenses.amount - 0.009)');
            });
        }
    }

    public function create()
    {
        return view('layouts.work-flow.expense.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'employee_options' => $this->getEmployeeOptions(),
            'job_request_options' => $this->getJobRequestOptions(),
            'bank_options' => $this->getBankOptions(),
            'bank_currency_map' => $this->getBankCurrencyMap(),
            'expense_account_options' => $this->getExpenseAccountOptions(),
            'expense_date' => now()->format('Y-m-d'),
            'existing_payment' => null,
        ]);
    }

    public function store(Request $request)
    {
        $payload = $this->validatePayload($request);

        $expense = DB::transaction(function () use ($payload, $request) {
            [$resolvedStatus, $resolvedIsPosted, $approvedBy, $approvedAt] = $this->resolveStatusAndPosting(
                null,
                $payload['status'],
                $payload['is_posted']
            );

            $expense = Expense::create([
                'code' => $this->nextExpenseCode(),
                'employee_id' => $payload['employee_id'],
                'job_request_id' => $payload['job_request_id'],
                'bank_account_id' => $payload['bank_account_id'],
                'bank_transaction_id' => null,
                'accountant_id' => null,
                'expense_account_id' => $payload['expense_account_id'],
                'expense_date' => $payload['expense_date'],
                'category' => $payload['category'],
                'amount' => $payload['amount'],
                'currency' => $payload['currency'],
                'reference_no' => $payload['reference_no'],
                'status' => $resolvedStatus,
                'is_posted' => $resolvedIsPosted,
                'note' => $payload['note'],
                'approved_by' => $approvedBy,
                'approved_at' => $approvedAt,
                'user_id' => Auth::id(),
                'sync' => 0,
                'updated' => null,
            ]);

            $this->syncInlineExpensePayment($request, $expense);

            if ((int)$resolvedIsPosted === 1) {
                $this->syncFinancialLinks($expense);
            }

            return $expense;
        });

        return redirect()->route('expense.show', $expense->id)->with('success', $this->action_message(0, $this->page_name));
    }

    public function show(Expense $expense)
    {
        return view('layouts.work-flow.expense.show', [
            'page_name' => $this->page_name,
            'expense' => $expense->load([
                'employee',
                'jobRequest',
                'bank',
                'expenseAccount',
                'accountant',
                'bankTransaction',
                'payment.bank',
                'user.employee',
                'approvedBy.employee',
            ]),
            'bank_options' => $this->getBankOptions(),
        ]);
    }

    public function storeQuickPayment(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);
        abort_unless(Auth::user()->can('create', Payment::class), 403);

        $hadLinkedPayment = (int)($expense->payment_id ?? 0) > 0;

        DB::transaction(function () use ($request, $expense) {
            $existingPaymentId = (int)$request->input('existing_payment_id');
            if ($existingPaymentId <= 0 && (int)($expense->payment_id ?? 0) > 0) {
                $existingPaymentId = (int)$expense->payment_id;
            }

            $request->merge([
                'already_paid' => 1,
                'existing_payment_id' => $existingPaymentId > 0 ? $existingPaymentId : null,
            ]);

            $this->syncInlineExpensePayment($request, $expense);

            if ((int)$expense->is_posted === 1) {
                $this->syncFinancialLinks($expense);
            }
        });

        $expense->refresh();
        $message = $hadLinkedPayment
            ? 'Linked payment was updated successfully.'
            : 'Linked payment was added successfully.';

        return redirect()->route('expense.show', $expense->id)->with('success', $message);
    }

    public function edit(Expense $expense)
    {
        return view('layouts.work-flow.expense.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'expense' => $expense,
            'employee_options' => $this->getEmployeeOptions(),
            'job_request_options' => $this->getJobRequestOptions(),
            'bank_options' => $this->getBankOptions(),
            'bank_currency_map' => $this->getBankCurrencyMap(),
            'expense_account_options' => $this->getExpenseAccountOptions(),
            'expense_date' => $expense->expense_date ? Carbon::parse($expense->expense_date)->format('Y-m-d') : now()->format('Y-m-d'),
            'existing_payment' => $expense->payment,
        ]);
    }

    public function update(Request $request, Expense $expense)
    {
        if ((int)$expense->is_posted === 1) {
            throw ValidationException::withMessages([
                'is_posted' => 'Posted expense is locked. Use Unpost first before editing.',
            ]);
        }

        $payload = $this->validatePayload($request, $expense);

        DB::transaction(function () use ($payload, $expense, $request) {
            [$resolvedStatus, $resolvedIsPosted, $approvedBy, $approvedAt] = $this->resolveStatusAndPosting(
                $expense,
                $payload['status'],
                $payload['is_posted']
            );

            $expense->update([
                'employee_id' => $payload['employee_id'],
                'job_request_id' => $payload['job_request_id'],
                'bank_account_id' => $payload['bank_account_id'],
                'expense_account_id' => $payload['expense_account_id'],
                'expense_date' => $payload['expense_date'],
                'category' => $payload['category'],
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

            $this->syncInlineExpensePayment($request, $expense);

            if ((int)$resolvedIsPosted === 1) {
                $this->syncFinancialLinks($expense);
            } else {
                $this->clearUnpostedLinkedBankTransaction($expense);
            }
        });

        return redirect()->route('expense.show', $expense->id)->with('success', $this->action_message(1, $this->page_name));
    }

    public function destroy(Expense $expense)
    {
        if ((int)$expense->is_posted === 1) {
            return response()->json(['success' => 'Posted expense cannot be deleted.'], 422);
        }

        DB::transaction(function () use ($expense) {
            $this->clearUnpostedLinkedBankTransaction($expense);
            $this->cleanupLinkedPaymentBeforeDelete($expense);

            if (!empty($expense->accountant_id)) {
                $accountant = Accountant::find($expense->accountant_id);
                if ($accountant && (int)$accountant->is_posted === 1) {
                    throw ValidationException::withMessages([
                        'expense' => 'Cannot delete expense linked to posted accountant entry.',
                    ]);
                }
                if ($accountant) {
                    $accountant->delete();
                }
            }

            $expense->delete();
        });

        return response()->json(['success' => $this->action_message(2, $this->page_name)]);
    }

    public function submitForApproval(Expense $expense)
    {
        $this->authorize('update', $expense);

        if ((int)$expense->is_posted === 1) {
            throw ValidationException::withMessages([
                'status' => 'Posted expense is already approved.',
            ]);
        }

        $fromStatus = (string)$expense->status;
        $this->ensureStatusTransitionAllowed($fromStatus, 'pending_approval');

        $expense->update([
            'status' => 'pending_approval',
            'approved_by' => null,
            'approved_at' => null,
            'updated' => 1,
        ]);

        return redirect()->route('expense.show', $expense->id)->with('success', 'Expense submitted for approval.');
    }

    public function approve(Expense $expense)
    {
        $this->authorize('update', $expense);
        $this->ensureCanApprove();

        if ((string)$expense->status !== 'pending_approval') {
            throw ValidationException::withMessages([
                'status' => 'Only pending approval expenses can be approved.',
            ]);
        }

        DB::transaction(function () use ($expense) {
            $expense->update([
                'status' => 'approved',
                'is_posted' => 1,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'updated' => 1,
            ]);

            $this->syncFinancialLinks($expense);
        });

        return redirect()->route('expense.show', $expense->id)->with('success', 'Expense approved and posted.');
    }

    public function reject(Expense $expense)
    {
        $this->authorize('update', $expense);
        $this->ensureCanApprove();

        if ((string)$expense->status !== 'pending_approval') {
            throw ValidationException::withMessages([
                'status' => 'Only pending approval expenses can be rejected.',
            ]);
        }

        $expense->update([
            'status' => 'rejected',
            'is_posted' => 0,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'updated' => 1,
        ]);

        return redirect()->route('expense.show', $expense->id)->with('success', 'Expense rejected.');
    }

    public function unpost(Expense $expense)
    {
        $this->authorize('update', $expense);
        $this->ensureCanApprove();

        if ((int)$expense->is_posted !== 1) {
            throw ValidationException::withMessages([
                'is_posted' => 'Expense is not posted.',
            ]);
        }

        DB::transaction(function () use ($expense) {
            $expense->update([
                'status' => 'draft',
                'is_posted' => 0,
                'approved_by' => null,
                'approved_at' => null,
                'updated' => 1,
            ]);

            $this->unpostLinkedFinancialLinks($expense);
        });

        return redirect()->route('expense.show', $expense->id)->with('success', 'Expense was unposted and moved to draft.');
    }

    private function validatePayload(Request $request, ?Expense $expense = null): array
    {
        $payload = $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'job_request_id' => ['nullable', 'integer', 'exists:job_requests,id'],
            'bank_account_id' => ['nullable', 'integer', 'exists:bank_accounts,id'],
            'expense_account_id' => ['nullable', 'integer', 'exists:chart_accounts,id'],
            'expense_date' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'amount' => ['nullable'],
            'currency' => ['nullable', 'string', 'max:20'],
            'reference_no' => ['nullable', 'string', 'max:191'],
            'status' => ['nullable', 'in:draft,pending_approval,approved,rejected'],
            'is_posted' => ['nullable'],
            'note' => ['nullable', 'string'],
        ]);

        $bank = null;
        if (!empty($payload['bank_account_id'])) {
            $bank = Bank::find($payload['bank_account_id']);
            if (!$bank || (int)$bank->is_active !== 1) {
                throw ValidationException::withMessages([
                    'bank_account_id' => 'Selected bank account is not active.',
                ]);
            }
        }

        if (empty($payload['expense_account_id'])) {
            $payload['expense_account_id'] = $this->defaultExpenseAccountId();
        }
        if (empty($payload['expense_account_id'])) {
            throw ValidationException::withMessages([
                'expense_account_id' => 'No active default expense account found. Please select one.',
            ]);
        }

        $expenseAccount = ChartAccount::query()
            ->where('id', $payload['expense_account_id'])
            ->where('is_active', 1)
            ->first();

        if (!$expenseAccount) {
            throw ValidationException::withMessages([
                'expense_account_id' => 'Selected expense account is not active.',
            ]);
        }

        $payload['expense_date'] = $this->normalizeDateInput($payload['expense_date'] ?? null) ?: Carbon::today()->format('Y-m-d');
        $payload['amount'] = $this->normalizeAmount($payload['amount'] ?? null);
        if ((float)$payload['amount'] <= 0) {
            throw ValidationException::withMessages([
                'amount' => 'Amount must be greater than zero.',
            ]);
        }

        if ($bank && trim((string)$bank->currency) !== '') {
            $payload['currency'] = trim((string)$bank->currency);
        } else {
            $payload['currency'] = $payload['currency'] ?: 'USD';
        }
        $payload['currency'] = strtoupper(trim((string)$payload['currency']));
        if ($payload['currency'] === '') {
            $payload['currency'] = 'USD';
        }
        $payload['status'] = $payload['status'] ?: 'draft';
        $payload['is_posted'] = (int)$request->boolean('is_posted', false);
        $payload['category'] = $payload['category'] ?? 'general';
        $payload['reference_no'] = $payload['reference_no'] ?? null;
        $payload['job_request_id'] = $payload['job_request_id'] ?? null;
        $payload['bank_account_id'] = $payload['bank_account_id'] ?? null;

        if ($expense) {
            $requestedStatus = $payload['status'] ?? (string)$expense->status;
            $this->ensureStatusTransitionAllowed((string)$expense->status, (string)$requestedStatus);
        }

        return $payload;
    }

    private function resolveStatusAndPosting(?Expense $expense, string $requestedStatus, int $requestedIsPosted): array
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
        } elseif (in_array($status, ['pending_approval', 'draft'], true)) {
            $isPosted = 0;
        }

        if ($expense && (int)$expense->is_posted === 1 && $isPosted !== 1) {
            throw ValidationException::withMessages([
                'is_posted' => 'Posted expense must be unposted using the Unpost action.',
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

        $fromStatus = in_array($fromStatus, self::EXPENSE_STATUSES, true) ? $fromStatus : 'draft';
        $toStatus = in_array($toStatus, self::EXPENSE_STATUSES, true) ? $toStatus : 'draft';

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
            $user->hasPermission('expense', 'approve') ||
            $user->hasPermission('expense', 'all') ||
            $user->hasPermission('accountant', 'approve') ||
            $user->hasPermission('accountant', 'all') ||
            $user->hasPermission('bank', 'approve') ||
            $user->hasPermission('bank', 'all')
        );
        if (!$canApprove) {
            throw ValidationException::withMessages([
                'status' => 'Only expense approvers can complete this action.',
            ]);
        }
    }

    private function syncInlineExpensePayment(Request $request, Expense $expense): void
    {
        if (!$request->boolean('already_paid')) {
            if ((int)($expense->payment_id ?? 0) > 0) {
                $this->cleanupLinkedPaymentBeforeDelete($expense);
            }
            return;
        }

        $payment = null;
        $existingPaymentId = (int)$request->input('existing_payment_id');
        if ($existingPaymentId > 0) {
            $payment = Payment::query()->where('id', $existingPaymentId)->first();
        }
        if (!$payment && (int)($expense->payment_id ?? 0) > 0) {
            $payment = Payment::query()->where('id', (int)$expense->payment_id)->first();
        }

        $bankAccountId = (int)$request->input('payment_bank_account_id');
        if ($bankAccountId <= 0 && (int)($expense->bank_account_id ?? 0) > 0) {
            $bankAccountId = (int)$expense->bank_account_id;
        }

        $bank = null;
        if ($bankAccountId > 0) {
            $bank = Bank::find($bankAccountId);
            if (!$bank || (int)$bank->is_active !== 1) {
                throw ValidationException::withMessages([
                    'payment_bank_account_id' => 'Selected payment bank account is not active.',
                ]);
            }
        } else {
            $bankAccountId = null;
        }

        if ((int)($expense->bank_account_id ?? 0) > 0 && $bankAccountId && (int)$expense->bank_account_id !== (int)$bankAccountId) {
            throw ValidationException::withMessages([
                'payment_bank_account_id' => 'Payment bank account must match selected expense bank account.',
            ]);
        }

        if ((int)($expense->bank_account_id ?? 0) <= 0 && $bankAccountId) {
            $expense->update([
                'bank_account_id' => $bankAccountId,
                'currency' => strtoupper(trim((string)($bank?->currency ?: $expense->currency ?: 'USD'))),
                'updated' => 1,
            ]);
            $expense->refresh();
        }

        $paymentDate = $this->normalizeDateInput($request->input('payment_date'))
            ?: ($expense->expense_date ? Carbon::parse($expense->expense_date)->format('Y-m-d') : Carbon::today()->format('Y-m-d'));

        $paymentAmountInput = $request->input('payment_amount');
        $paymentAmount = $paymentAmountInput === null || trim((string)$paymentAmountInput) === ''
            ? round((float)$expense->amount, 2)
            : $this->normalizeAmount($paymentAmountInput);

        if ((float)$paymentAmount <= 0) {
            throw ValidationException::withMessages([
                'payment_amount' => 'Payment amount must be greater than zero.',
            ]);
        }

        $requestedStatus = strtolower(trim((string)$request->input('payment_status')));
        if (!in_array($requestedStatus, ['pending', 'received', 'approved', 'rejected'], true)) {
            $requestedStatus = (int)$expense->is_posted === 1 ? 'approved' : 'pending';
        }
        if ((int)$expense->is_posted === 1 && !in_array($requestedStatus, ['approved', 'received'], true)) {
            $requestedStatus = 'approved';
        }

        $payload = [
            'job_request_id' => $expense->job_request_id,
            'invoice_id' => null,
            'bank_account_id' => $bankAccountId,
            'payment_date' => $paymentDate,
            'amount' => round((float)$paymentAmount, 2),
            'method' => $this->nullableString($request->input('payment_method')),
            'reference_no' => $this->nullableString($request->input('payment_reference_no')) ?: ($expense->reference_no ?: $expense->code),
            'status' => $requestedStatus,
            'note' => $this->nullableString($request->input('payment_note')),
            'updated' => 1,
        ];

        if ($payment) {
            $payment->fill($payload);
            $payment->save();
        } else {
            $payment = Payment::create($payload + [
                'code' => $this->nextPaymentCode(),
                'user_id' => Auth::id(),
                'approved_by' => null,
                'sync' => 0,
                'updated' => null,
            ]);
        }

        if ((int)$expense->payment_id !== (int)$payment->id) {
            $expense->update([
                'payment_id' => $payment->id,
                'updated' => 1,
            ]);
            $expense->refresh();
        }

        $this->syncExpenseLinkedPaymentBankTransaction($expense, $payment);
    }

    private function syncExpenseLinkedPaymentBankTransaction(Expense $expense, Payment $payment): void
    {
        if (empty($expense->bank_transaction_id)) {
            return;
        }

        $transaction = BankTransaction::find($expense->bank_transaction_id);
        if (!$transaction) {
            return;
        }

        $updates = [];
        if ((int)$transaction->payment_id !== (int)$payment->id) {
            $updates['payment_id'] = $payment->id;
        }
        if ((int)$payment->bank_account_id !== (int)$transaction->bank_account_id && (int)$transaction->bank_account_id > 0) {
            $payment->update([
                'bank_account_id' => $transaction->bank_account_id,
                'updated' => 1,
            ]);
        }

        if (!empty($updates)) {
            $updates['updated'] = 1;
            $transaction->update($updates);
        }
    }

    private function cleanupLinkedPaymentBeforeDelete(Expense $expense): void
    {
        if ((int)($expense->payment_id ?? 0) <= 0) {
            return;
        }

        $payment = Payment::find((int)$expense->payment_id);
        if (!$payment) {
            $expense->update(['payment_id' => null, 'updated' => 1]);
            return;
        }

        $paymentTransactions = BankTransaction::query()
            ->where('payment_id', $payment->id)
            ->where('source_type', 'expense_payment_auto')
            ->get();

        foreach ($paymentTransactions as $paymentTransaction) {
            if ((int)$paymentTransaction->is_posted === 1) {
                throw ValidationException::withMessages([
                    'expense' => 'Cannot delete expense linked to posted payment transaction.',
                ]);
            }
        }

        $affectedBankIds = $paymentTransactions->pluck('bank_account_id')->filter()->unique()->values()->all();
        if ($paymentTransactions->count() > 0) {
            BankTransaction::query()
                ->where('payment_id', $payment->id)
                ->where('source_type', 'expense_payment_auto')
                ->delete();
        }

        foreach ($affectedBankIds as $affectedBankId) {
            $this->recalculateBankBalanceById((int)$affectedBankId);
        }

        if ((int)($expense->bank_transaction_id ?? 0) > 0) {
            $expenseTransaction = BankTransaction::find((int)$expense->bank_transaction_id);
            if (
                $expenseTransaction &&
                (int)$expenseTransaction->payment_id === (int)$payment->id
            ) {
                if ((int)$expenseTransaction->is_posted === 1) {
                    throw ValidationException::withMessages([
                        'expense' => 'Cannot detach payment from posted expense bank transaction. Unpost first.',
                    ]);
                }

                $expenseTransaction->update([
                    'payment_id' => null,
                    'updated' => 1,
                ]);
            }
        }

        $otherExpenseLinksCount = Expense::query()
            ->where('payment_id', $payment->id)
            ->where('id', '!=', $expense->id)
            ->count();

        if ($otherExpenseLinksCount === 0 && empty($payment->invoice_id)) {
            $payment->delete();
        }

        $expense->update(['payment_id' => null, 'updated' => 1]);
    }

    private function nullableString($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string)$value);
        return $value === '' ? null : $value;
    }

    private function syncFinancialLinks(Expense $expense): void
    {
        $this->syncCurrencyWithSelectedBank($expense);
        $accountant = $this->syncAccountantEntryForExpense($expense);
        $this->syncBankTransactionForExpense($expense, $accountant);
    }

    private function syncCurrencyWithSelectedBank(Expense $expense): void
    {
        $expense->loadMissing(['bank']);
        $bankCurrency = strtoupper(trim((string)optional($expense->bank)->currency));
        if ($bankCurrency === '') {
            return;
        }

        $currentCurrency = strtoupper(trim((string)$expense->currency));
        if ($currentCurrency === $bankCurrency) {
            return;
        }

        $expense->update([
            'currency' => $bankCurrency,
            'updated' => 1,
        ]);
        $expense->refresh();
    }

    private function syncAccountantEntryForExpense(Expense $expense): Accountant
    {
        $expense->loadMissing(['bank']);

        $expenseAccountId = (int)($expense->expense_account_id ?? 0);
        if ($expenseAccountId <= 0) {
            $expenseAccountId = (int)($this->defaultExpenseAccountId() ?? 0);
        }
        if ($expenseAccountId <= 0) {
            throw ValidationException::withMessages([
                'expense_account_id' => 'Expense account mapping is missing.',
            ]);
        }

        $expenseAccount = ChartAccount::query()->where('id', $expenseAccountId)->where('is_active', 1)->first();
        if (!$expenseAccount) {
            throw ValidationException::withMessages([
                'expense_account_id' => 'Expense account is not active.',
            ]);
        }

        $creditAccountId = null;
        if ($expense->bank_account_id) {
            $bank = $expense->bank;
            if (!$bank || empty($bank->chart_account_id)) {
                throw ValidationException::withMessages([
                    'bank_account_id' => 'Bank account must be mapped to chart account before approving expense.',
                ]);
            }
            $creditAccountId = (int)$bank->chart_account_id;
        } else {
            $creditAccountId = (int)($this->defaultPayableAccountId() ?? 0);
        }

        if ($creditAccountId <= 0) {
            throw ValidationException::withMessages([
                'expense' => 'Could not resolve credit account for expense posting.',
            ]);
        }

        $creditAccount = ChartAccount::query()->where('id', $creditAccountId)->where('is_active', 1)->first();
        if (!$creditAccount) {
            throw ValidationException::withMessages([
                'expense' => 'Resolved credit account is not active.',
            ]);
        }

        $accountant = null;
        if (!empty($expense->accountant_id)) {
            $accountant = Accountant::find($expense->accountant_id);
        }

        $payload = [
            'job_request_id' => $expense->job_request_id,
            'invoice_id' => null,
            'payment_id' => $expense->payment_id,
            'posting_date' => $expense->expense_date ?: Carbon::today()->format('Y-m-d'),
            'entry_type' => 'expense',
            'debit_account_id' => $expenseAccountId,
            'credit_account_id' => $creditAccountId,
            'amount' => round((float)$expense->amount, 2),
            'currency' => $expense->currency ?: 'USD',
            'reference_no' => $expense->reference_no ?: $expense->code,
            'status' => 'approved',
            'is_posted' => 1,
            'note' => 'Auto-generated from Expense '.$expense->code,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'updated' => 1,
        ];

        if ($accountant) {
            $accountant->update($payload);
        } else {
            $accountant = Accountant::create(array_merge($payload, [
                'code' => Accountant::nextTreeSequentialCode(
                    $expenseAccountId,
                    $creditAccountId,
                    (string)($payload['posting_date'] ?? '')
                ),
                'user_id' => Auth::id(),
                'sync' => 0,
                'updated' => null,
            ]));
        }

        if ((int)$expense->accountant_id !== (int)$accountant->id) {
            $expense->update([
                'accountant_id' => $accountant->id,
                'updated' => 1,
            ]);
        }

        return $accountant;
    }

    private function syncBankTransactionForExpense(Expense $expense, Accountant $accountant): void
    {
        $existingTransaction = null;
        if (!empty($expense->bank_transaction_id)) {
            $existingTransaction = BankTransaction::find($expense->bank_transaction_id);
        }

        if (!$expense->bank_account_id) {
            if ($existingTransaction) {
                if ((int)$existingTransaction->is_posted === 1) {
                    throw ValidationException::withMessages([
                        'bank_account_id' => 'Cannot remove bank link from a posted expense transaction.',
                    ]);
                }
                $bankId = (int)$existingTransaction->bank_account_id;
                $existingTransaction->delete();
                $expense->update(['bank_transaction_id' => null, 'updated' => 1]);
                $this->recalculateBankBalanceById($bankId);
            }
            return;
        }

        $bank = Bank::find($expense->bank_account_id);
        if (!$bank || (int)$bank->is_active !== 1) {
            throw ValidationException::withMessages([
                'bank_account_id' => 'Selected bank account is not active.',
            ]);
        }

        $oldBankId = $existingTransaction ? (int)$existingTransaction->bank_account_id : 0;

        $payload = [
            'bank_account_id' => $bank->id,
            'job_request_id' => $expense->job_request_id,
            'payment_id' => $expense->payment_id,
            'accountant_id' => $accountant->id,
            'counter_account_id' => $expense->expense_account_id,
            'direction' => 'out',
            'category' => 'withdraw',
            'source_type' => 'expense_auto',
            'transaction_date' => $expense->expense_date ?: Carbon::today()->format('Y-m-d'),
            'amount' => round((float)$expense->amount, 2),
            'reference_no' => $expense->reference_no ?: $expense->code,
            'status' => 'approved',
            'note' => 'Auto-generated from Expense '.$expense->code,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'is_posted' => 1,
            'updated' => 1,
        ];

        if ($existingTransaction) {
            $existingTransaction->update($payload);
            $transaction = $existingTransaction;
        } else {
            $transaction = BankTransaction::create(array_merge($payload, [
                'code' => $this->nextBankTransactionCode(),
                'user_id' => Auth::id(),
                'sync' => 0,
                'updated' => null,
            ]));
        }

        if ((int)$expense->bank_transaction_id !== (int)$transaction->id) {
            $expense->update([
                'bank_transaction_id' => $transaction->id,
                'updated' => 1,
            ]);
        }

        $this->recalculateBankBalanceById((int)$bank->id);
        if ($oldBankId > 0 && $oldBankId !== (int)$bank->id) {
            $this->recalculateBankBalanceById($oldBankId);
        }
    }

    private function unpostLinkedFinancialLinks(Expense $expense): void
    {
        if (!empty($expense->payment_id)) {
            $payment = Payment::find($expense->payment_id);
            if ($payment && in_array((string)$payment->status, ['approved', 'received'], true)) {
                $payment->update([
                    'status' => 'pending',
                    'updated' => 1,
                ]);
            }
        }

        if (!empty($expense->accountant_id)) {
            $accountant = Accountant::find($expense->accountant_id);
            if ($accountant) {
                $accountant->update([
                    'status' => 'draft',
                    'is_posted' => 0,
                    'approved_by' => null,
                    'approved_at' => null,
                    'updated' => 1,
                ]);
            }
        }

        if (!empty($expense->bank_transaction_id)) {
            $transaction = BankTransaction::find($expense->bank_transaction_id);
            if ($transaction) {
                $transaction->update([
                    'status' => 'draft',
                    'is_posted' => 0,
                    'approved_by' => null,
                    'approved_at' => null,
                    'updated' => 1,
                ]);
                $this->recalculateBankBalanceById((int)$transaction->bank_account_id);
            }
        }
    }

    private function clearUnpostedLinkedBankTransaction(Expense $expense): void
    {
        if (empty($expense->bank_transaction_id)) {
            return;
        }

        $transaction = BankTransaction::find($expense->bank_transaction_id);
        if (!$transaction) {
            $expense->update(['bank_transaction_id' => null, 'updated' => 1]);
            return;
        }

        if ((int)$transaction->is_posted === 1) {
            throw ValidationException::withMessages([
                'expense' => 'Cannot clear posted linked bank transaction. Unpost first.',
            ]);
        }

        $bankId = (int)$transaction->bank_account_id;
        $transaction->delete();
        $expense->update(['bank_transaction_id' => null, 'updated' => 1]);
        $this->recalculateBankBalanceById($bankId);
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

    private function defaultExpenseAccountId(): ?int
    {
        $account = ChartAccount::query()
            ->where('code', '5200')
            ->where('type', 'expense')
            ->where('is_active', 1)
            ->first(['id']);

        if ($account) {
            return (int)$account->id;
        }

        $fallback = ChartAccount::query()
            ->where('type', 'expense')
            ->where('is_active', 1)
            ->orderBy('level')
            ->orderBy('id')
            ->first(['id']);

        return $fallback ? (int)$fallback->id : null;
    }

    private function defaultPayableAccountId(): ?int
    {
        $account = ChartAccount::query()
            ->where('code', '2100')
            ->where('type', 'liability')
            ->where('is_active', 1)
            ->first(['id']);

        if ($account) {
            return (int)$account->id;
        }

        $fallback = ChartAccount::query()
            ->where('type', 'liability')
            ->where('is_active', 1)
            ->orderBy('level')
            ->orderBy('id')
            ->first(['id']);

        return $fallback ? (int)$fallback->id : null;
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

    private function nextExpenseCode(): string
    {
        $prefix = 'EXP-'.date('y').'-';
        $lastCode = Expense::where('code', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('code');

        $next = 1;
        if ($lastCode && preg_match('/(\d+)$/', $lastCode, $matches)) {
            $next = ((int)$matches[1]) + 1;
        }

        return $prefix.str_pad((string)$next, 4, '0', STR_PAD_LEFT);
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

    private function getEmployeeOptions(): array
    {
        return Employee::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->mapWithKeys(function (Employee $employee) {
                return [$employee->id => $employee->name];
            })
            ->toArray();
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

    private function getBankCurrencyMap(): array
    {
        return Bank::query()
            ->where('is_active', 1)
            ->orderBy('code', 'desc')
            ->get(['id', 'currency'])
            ->mapWithKeys(function (Bank $bank) {
                $currency = strtoupper(trim((string)$bank->currency));
                if ($currency === '') {
                    $currency = 'USD';
                }

                return [$bank->id => $currency];
            })
            ->toArray();
    }

    private function getExpenseAccountOptions(): array
    {
        return ChartAccount::query()
            ->where('is_active', 1)
            ->where('type', 'expense')
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
}

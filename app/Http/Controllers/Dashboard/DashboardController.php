<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Inspection\InspectionReport;
use App\Models\User;
use App\Models\WorkFlow\Accountant;
use App\Models\WorkFlow\Bank;
use App\Models\WorkFlow\BankTransaction;
use App\Models\WorkFlow\Expense;
use App\Models\WorkFlow\FileManager;
use App\Models\WorkFlow\Invoice;
use App\Models\WorkFlow\Inventory;
use App\Models\WorkFlow\JcfStatus;
use App\Models\WorkFlow\JobRequest;
use App\Models\WorkFlow\PackingSlip;
use App\Models\WorkFlow\Payment;
use App\Models\WorkFlow\Qutation;
use App\Models\WorkFlow\ServiceTicket;
use App\Support\ActionCenterData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function inspectionApprovedPdf(InspectionReport $inspectionReport)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        $typeDefinitions = $this->inspectionReportTypeDefinitions();
        $typeDefinition = collect($typeDefinitions)
            ->first(function (array $definition) use ($inspectionReport) {
                return (string) ($definition['reportable_type'] ?? '') === (string) $inspectionReport->reportable_type;
            });

        if (!$typeDefinition) {
            abort(404, 'Inspection report type is not mapped for PDF access.');
        }

        $moduleKey = (string) ($typeDefinition['module_key'] ?? '');
        if (!$this->canAccessInspectionType($user, $moduleKey)) {
            abort(403);
        }

        $inspectionReport->loadMissing(['job_request', 'reportable']);
        if (!$inspectionReport->reportable || !$user->can('view', $inspectionReport->reportable)) {
            abort(403);
        }

        $jcfCode = (string) optional($inspectionReport->job_request)->code;
        $reportCode = (string) ($inspectionReport->code ?? '');
        if ($jcfCode === '' || $reportCode === '') {
            abort(404, 'Inspection report PDF path cannot be resolved.');
        }

        $relativePath = 'pdf/inspection/'.trim((string) $typeDefinition['pdf_dir'], '/').'/'.$jcfCode.'/'.$reportCode.'.pdf';
        if (!Storage::disk('public')->exists($relativePath)) {
            abort(404, 'Inspection report PDF not found.');
        }

        $absolutePath = Storage::disk('public')->path($relativePath);
        $downloadName = $jcfCode.'-'.$reportCode.'.pdf';

        return response()->file($absolutePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$downloadName.'"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function index()
    {
        $user = Auth::user();

        $hasModulePermission = function (string $module, array $roles = ['all', 'show']) use ($user): bool {
            if ((bool) $user->isSuperAdmin()) {
                return true;
            }

            foreach ($roles as $role) {
                if ((bool) $user->hasPermission($module, $role)) {
                    return true;
                }
            }

            return false;
        };

        $canAccessFinancialTab = $hasModulePermission('financial', ['all', 'show']);
        $canAccessInspection = $hasModulePermission('inspection', ['all', 'show', 'create', 'edit', 'delete', 'approve']);

        $canViewJobRequest = $user->can('viewAny', JobRequest::class);
        $canCreateJobRequest = $user->can('create', JobRequest::class);

        $canViewQutation = $user->can('viewAny', Qutation::class);
        $canCreateQutation = $user->can('create', Qutation::class);
        $canApproveQutation = $hasModulePermission('qutation', ['approve', 'all']);

        $canViewPackingSlip = $user->can('viewAny', PackingSlip::class);
        $canCreatePackingSlip = $user->can('create', PackingSlip::class);

        $canViewServiceTicket = $user->can('viewAny', ServiceTicket::class);
        $canCreateServiceTicket = $user->can('create', ServiceTicket::class);

        $canViewInvoice = $user->can('viewAny', Invoice::class);
        $canCreateInvoice = $user->can('create', Invoice::class);

        $canViewPayment = $canAccessFinancialTab && $user->can('viewAny', Payment::class);
        $canCreatePayment = $canAccessFinancialTab && $user->can('create', Payment::class);

        $canViewInventory = $canAccessFinancialTab && $user->can('viewAny', Inventory::class);
        $canCreateInventory = $canAccessFinancialTab && $user->can('create', Inventory::class);

        $canViewAccountant = $canAccessFinancialTab && $user->can('viewAny', Accountant::class);
        $canCreateAccountant = $canAccessFinancialTab && $user->can('create', Accountant::class);
        $canApproveAccountant = $canAccessFinancialTab && $hasModulePermission('accountant', ['approve', 'all']);

        $canViewBank = $canAccessFinancialTab && $user->can('viewAny', Bank::class);
        $canCreateBank = $canAccessFinancialTab && $user->can('create', Bank::class);
        $canApproveBank = $canAccessFinancialTab && $hasModulePermission('bank', ['approve', 'all']);

        $canViewExpense = $canAccessFinancialTab && $user->can('viewAny', Expense::class);
        $canCreateExpense = $canAccessFinancialTab && $user->can('create', Expense::class);
        $canApproveExpense = $canAccessFinancialTab && $hasModulePermission('expense', ['approve', 'all']);

        $canViewFileManager = $user->can('viewAny', FileManager::class);
        $canCreateFileManager = $user->can('create', FileManager::class);

        $workflowVisibleCount = collect([
            $canViewJobRequest,
            $canViewQutation,
            $canViewPackingSlip,
            $canViewServiceTicket,
            $canViewInvoice,
            $canViewFileManager,
            $canAccessInspection,
        ])->filter()->count();

        $financialVisibleCount = collect([
            $canViewBank,
            $canViewPayment,
            $canViewExpense,
            $canViewInventory,
            $canViewAccountant,
        ])->filter()->count();

        if ((bool) $user->isSuperAdmin()) {
            $dashboardModeLabel = 'Super Admin Workspace';
        } elseif ($financialVisibleCount > 0 && $workflowVisibleCount === 0) {
            $dashboardModeLabel = 'Financial Workspace';
        } elseif ($workflowVisibleCount > 0 && $financialVisibleCount === 0) {
            $dashboardModeLabel = 'Workflow Workspace';
        } elseif ($workflowVisibleCount > 0 && $financialVisibleCount > 0) {
            $dashboardModeLabel = 'Hybrid Workspace';
        } else {
            $dashboardModeLabel = 'Limited Workspace';
        }

        $modulePermissions = [
            [
                'key' => 'job_request',
                'label' => 'JCF',
                'scope' => 'workflow',
                'view' => $canViewJobRequest,
                'create' => $canCreateJobRequest,
                'approve' => false,
                'url' => route('jobRequest.index'),
            ],
            [
                'key' => 'qutation',
                'label' => 'Quotations',
                'scope' => 'workflow',
                'view' => $canViewQutation,
                'create' => $canCreateQutation,
                'approve' => $canApproveQutation,
                'url' => route('qutation.index'),
            ],
            [
                'key' => 'packing_slip',
                'label' => 'Packing Slips',
                'scope' => 'workflow',
                'view' => $canViewPackingSlip,
                'create' => $canCreatePackingSlip,
                'approve' => false,
                'url' => route('packingSlip.index'),
            ],
            [
                'key' => 'service_ticket',
                'label' => 'Service Tickets',
                'scope' => 'workflow',
                'view' => $canViewServiceTicket,
                'create' => $canCreateServiceTicket,
                'approve' => false,
                'url' => route('serviceTicket.index'),
            ],
            [
                'key' => 'invoice',
                'label' => 'Invoices',
                'scope' => 'workflow',
                'view' => $canViewInvoice,
                'create' => $canCreateInvoice,
                'approve' => false,
                'url' => route('invoice.index', ['type' => Invoice::$INVOICE_RSE_TYPE]),
            ],
            [
                'key' => 'file_manager',
                'label' => 'File Manager',
                'scope' => 'workflow',
                'view' => $canViewFileManager,
                'create' => $canCreateFileManager,
                'approve' => false,
                'url' => route('fileManager.index'),
            ],
            [
                'key' => 'inspection',
                'label' => 'Inspections',
                'scope' => 'workflow',
                'view' => $canAccessInspection,
                'create' => $hasModulePermission('inspection', ['create', 'all']),
                'approve' => $hasModulePermission('inspection', ['approve', 'all']),
                'url' => route('inspection.all'),
            ],
            [
                'key' => 'bank',
                'label' => 'Banks',
                'scope' => 'financial',
                'view' => $canViewBank,
                'create' => $canCreateBank,
                'approve' => $canApproveBank,
                'url' => route('bank.index'),
            ],
            [
                'key' => 'payment',
                'label' => 'Payments',
                'scope' => 'financial',
                'view' => $canViewPayment,
                'create' => $canCreatePayment,
                'approve' => false,
                'url' => route('payment.index'),
            ],
            [
                'key' => 'expense',
                'label' => 'Expenses',
                'scope' => 'financial',
                'view' => $canViewExpense,
                'create' => $canCreateExpense,
                'approve' => $canApproveExpense,
                'url' => route('expense.index'),
            ],
            [
                'key' => 'inventory',
                'label' => 'Inventory',
                'scope' => 'financial',
                'view' => $canViewInventory,
                'create' => $canCreateInventory,
                'approve' => false,
                'url' => route('inventory.index'),
            ],
            [
                'key' => 'accountant',
                'label' => 'Accountant',
                'scope' => 'financial',
                'view' => $canViewAccountant,
                'create' => $canCreateAccountant,
                'approve' => $canApproveAccountant,
                'url' => route('accountant.index'),
            ],
        ];

        $modulePermissions = array_map(function (array $module): array {
            $module['visible'] = (bool) ($module['view'] || $module['create'] || $module['approve']);
            return $module;
        }, $modulePermissions);

        $accessBadges = collect($modulePermissions)
            ->filter(function (array $module): bool {
                return $module['visible'];
            })
            ->map(function (array $module): string {
                return $module['label'];
            })
            ->values()
            ->all();

        $approvalCapabilityCount = collect($modulePermissions)
            ->filter(function (array $module): bool {
                return (bool) $module['approve'];
            })
            ->count();

        $scopeSummary = [
            'workflow_modules' => collect($modulePermissions)->where('scope', 'workflow')->where('visible', true)->count(),
            'financial_modules' => collect($modulePermissions)->where('scope', 'financial')->where('visible', true)->count(),
            'approval_modules' => $approvalCapabilityCount,
            'module_total' => collect($modulePermissions)->where('visible', true)->count(),
        ];

        $dashboardModeHint = [
            'Super Admin Workspace' => 'Complete platform visibility with global controls and approvals.',
            'Financial Workspace' => 'Financial controls first: banking, expenses, payments, accountant and approvals.',
            'Workflow Workspace' => 'Operational workflow focus across JCF, quotations, tickets, invoices and files.',
            'Hybrid Workspace' => 'Mixed operations across workflow and financial modules.',
            'Limited Workspace' => 'Focused dashboard showing only modules available for this role.',
        ][$dashboardModeLabel] ?? 'Focused dashboard showing only permitted modules.';

        $workflowCounts = [
            'job_requests' => $canViewJobRequest ? JobRequest::count() : 0,
            'qutations' => $canViewQutation ? Qutation::count() : 0,
            'packing_slips' => $canViewPackingSlip ? PackingSlip::count() : 0,
            'service_tickets' => $canViewServiceTicket ? ServiceTicket::count() : 0,
            'invoices_total' => $canViewInvoice ? Invoice::count() : 0,
            'invoices_rse' => $canViewInvoice ? Invoice::where('invoice_company_type', Invoice::$INVOICE_RSE_TYPE)->count() : 0,
            'invoices_ltd' => $canViewInvoice ? Invoice::where('invoice_company_type', Invoice::$INVOICE_LTD_TYPE)->count() : 0,
            'payments' => $canViewPayment ? Payment::count() : 0,
            'inventories' => $canViewInventory ? Inventory::count() : 0,
            'accountants' => $canViewAccountant ? Accountant::count() : 0,
            'banks' => $canViewBank ? Bank::count() : 0,
            'expenses' => $canViewExpense ? Expense::count() : 0,
            'file_manager' => $canViewFileManager ? FileManager::where('is_available', 1)->count() : 0,
            'inspection_reports' => $canAccessInspection ? InspectionReport::count() : 0,
        ];

        $jcfStates = [
            'open' => $canViewJobRequest ? JcfStatus::where(function ($query) {
                $query->whereNull('comment')->orWhere('comment', '');
            })->count() : 0,
            'ready' => $canViewJobRequest ? JcfStatus::where('comment', 'in')->count() : 0,
            'completed' => $canViewJobRequest ? JcfStatus::where('comment', 'cm')->count() : 0,
            'canceled' => $canViewJobRequest ? JcfStatus::where('comment', 'cc')->count() : 0,
        ];

        $dueInvoicesCount = null;
        $dueInvoicesAmount = null;
        if ($canAccessFinancialTab && $canViewInvoice) {
            $paidSummarySubQuery = Payment::query()
                ->selectRaw("invoice_id, SUM(CASE WHEN status IN ('received','approved') THEN amount ELSE 0 END) as paid_amount")
                ->whereNotNull('invoice_id')
                ->groupBy('invoice_id');

            $dueInvoicesRow = Invoice::query()
                ->leftJoinSub($paidSummarySubQuery, 'invoice_payments', function ($join) {
                    $join->on('invoice_payments.invoice_id', '=', 'invoices.id');
                })
                ->selectRaw('COUNT(*) as due_count')
                ->selectRaw('COALESCE(SUM(invoices.total - COALESCE(invoice_payments.paid_amount, 0)), 0) as due_amount')
                ->whereRaw('(invoices.total - COALESCE(invoice_payments.paid_amount, 0)) > 0.009')
                ->first();

            $dueInvoicesCount = (int) ($dueInvoicesRow->due_count ?? 0);
            $dueInvoicesAmount = (float) ($dueInvoicesRow->due_amount ?? 0);
        }

        $expensePaidCount = null;
        $expenseUnpaidCount = null;
        if ($canAccessFinancialTab && $canViewExpense) {
            $expensePaidCount = Expense::query()
                ->whereHas('payment', function ($query) {
                    $query->whereIn('status', ['approved', 'received']);
                })
                ->count();

            $expenseUnpaidCount = Expense::query()
                ->where(function ($query) {
                    $query->whereNull('payment_id')
                        ->orWhereHas('payment', function ($paymentQuery) {
                            $paymentQuery->whereIn('status', ['rejected', 'draft', 'pending', 'pending_approval']);
                        });
                })
                ->count();
        }

        $financialSnapshot = [
            'active_banks' => $canViewBank ? Bank::where('is_active', 1)->count() : null,
            'bank_balance' => $canViewBank ? (float) Bank::where('is_active', 1)->sum('current_balance') : null,
            'pending_bank_transactions' => $canViewBank ? BankTransaction::where('status', 'pending_approval')->count() : null,
            'pending_expenses' => $canViewExpense ? Expense::where('status', 'pending_approval')->count() : null,
            'pending_accountant_entries' => $canViewAccountant ? Accountant::where('status', 'pending_approval')->count() : null,
            'pending_payments' => $canViewPayment ? Payment::whereIn('status', ['pending', 'pending_approval', 'draft'])->count() : null,
            'due_invoices_count' => $dueInvoicesCount,
            'due_invoices_amount' => $dueInvoicesAmount,
            'paid_expenses_count' => $expensePaidCount,
            'unpaid_expenses_count' => $expenseUnpaidCount,
        ];

        $kpiCards = [];
        if ($canViewAccountant) {
            $kpiCards[] = [
                'label' => 'Accountant Entries',
                'value' => $workflowCounts['accountants'],
                'sub' => 'Accounting operations',
                'url' => route('accountant.index'),
                'scope' => 'financial',
                'tone' => 'financial',
            ];
        }
        if ($canViewExpense) {
            $kpiCards[] = [
                'label' => 'Expenses',
                'value' => $workflowCounts['expenses'],
                'sub' => 'Expense records',
                'url' => route('expense.index'),
                'scope' => 'financial',
                'tone' => 'financial',
            ];
        }
        if ($canViewBank) {
            $kpiCards[] = [
                'label' => 'Bank Accounts',
                'value' => $workflowCounts['banks'],
                'sub' => 'Managed bank accounts',
                'url' => route('bank.index'),
                'scope' => 'financial',
                'tone' => 'financial',
            ];
        }
        if ($canViewPayment) {
            $kpiCards[] = [
                'label' => 'Payments',
                'value' => $workflowCounts['payments'],
                'sub' => 'Collected / pending payments',
                'url' => route('payment.index'),
                'scope' => 'financial',
                'tone' => 'financial',
            ];
        }
        if ($canViewInventory) {
            $kpiCards[] = [
                'label' => 'Inventory',
                'value' => $workflowCounts['inventories'],
                'sub' => 'Inventory records',
                'url' => route('inventory.index'),
                'scope' => 'financial',
                'tone' => 'financial',
            ];
        }
        if ($canViewInvoice) {
            $kpiCards[] = [
                'label' => 'Invoices Total',
                'value' => $workflowCounts['invoices_total'],
                'sub' => 'All invoice documents',
                'url' => route('invoice.index'),
                'scope' => 'workflow',
                'tone' => 'workflow',
            ];
        }
        if ($canViewJobRequest) {
            $kpiCards[] = [
                'label' => 'Job Control Forms',
                'value' => $workflowCounts['job_requests'],
                'sub' => 'All JCF records',
                'url' => route('jobRequest.index'),
                'scope' => 'workflow',
                'tone' => 'workflow',
            ];
        }
        if ($canViewQutation) {
            $kpiCards[] = [
                'label' => 'Qutations',
                'value' => $workflowCounts['qutations'],
                'sub' => 'Quotation / Contract',
                'url' => route('qutation.index'),
                'scope' => 'workflow',
                'tone' => 'workflow',
            ];
        }
        if ($canViewPackingSlip) {
            $kpiCards[] = [
                'label' => 'Packing Slips',
                'value' => $workflowCounts['packing_slips'],
                'sub' => 'Issued packing slips',
                'url' => route('packingSlip.index'),
                'scope' => 'workflow',
                'tone' => 'workflow',
            ];
        }
        if ($canViewServiceTicket) {
            $kpiCards[] = [
                'label' => 'Service Tickets',
                'value' => $workflowCounts['service_tickets'],
                'sub' => 'Planned service tickets',
                'url' => route('serviceTicket.index'),
                'scope' => 'workflow',
                'tone' => 'workflow',
            ];
        }
        if ($canViewFileManager) {
            $kpiCards[] = [
                'label' => 'Managed Files',
                'value' => $workflowCounts['file_manager'],
                'sub' => 'Indexed generated files',
                'url' => route('fileManager.index'),
                'scope' => 'workflow',
                'tone' => 'workflow',
            ];
        }
        if ($canAccessInspection) {
            $kpiCards[] = [
                'label' => 'Inspection Reports',
                'value' => $workflowCounts['inspection_reports'],
                'sub' => 'All generated reports',
                'url' => route('inspection.all'),
                'scope' => 'workflow',
                'tone' => 'workflow',
            ];
        }

        $approvalQueues = [];
        if ($canViewQutation && $canApproveQutation) {
            $count = (int) Qutation::whereNull('user_id_approved')->count();
            $approvalQueues[] = [
                'label' => 'Quotation Approvals',
                'count' => $count,
                'url' => route('qutation.index'),
                'level' => $count > 0 ? 'warning' : 'success',
            ];
        }
        if ($canViewAccountant && $canApproveAccountant) {
            $count = (int) Accountant::where('status', 'pending_approval')->count();
            $approvalQueues[] = [
                'label' => 'Accountant Approvals',
                'count' => $count,
                'url' => route('accountant.index', ['entry_status' => 'pending_approval']),
                'level' => $count > 0 ? 'danger' : 'success',
            ];
        }
        if ($canViewExpense && $canApproveExpense) {
            $count = (int) Expense::where('status', 'pending_approval')->count();
            $approvalQueues[] = [
                'label' => 'Expense Approvals',
                'count' => $count,
                'url' => route('expense.index', ['entry_status' => 'pending_approval']),
                'level' => $count > 0 ? 'warning' : 'success',
            ];
        }
        if ($canViewBank && $canApproveBank) {
            $count = (int) BankTransaction::where('status', 'pending_approval')->count();
            $approvalQueues[] = [
                'label' => 'Bank Transaction Approvals',
                'count' => $count,
                'url' => route('bank.dashboard', ['status' => 'pending_approval']),
                'level' => $count > 0 ? 'primary' : 'success',
            ];
        }

        $financialActionModules = ['bank', 'payment', 'expense', 'accountant', 'inventory'];
        $actionItems = ActionCenterData::dashboardItemsForUser($user)
            ->filter(function (array $item) use ($canAccessFinancialTab, $financialActionModules): bool {
                if ($canAccessFinancialTab) {
                    return true;
                }

                return !in_array((string) ($item['module'] ?? ''), $financialActionModules, true);
            })
            ->values()
            ->all();

        $shortcuts = array_values(array_filter([
            [
                'label' => 'My Profile',
                'icon' => 'la la-user',
                'url' => route('profile.edit'),
                'can' => true,
            ],
            [
                'label' => 'Financial Dashboard',
                'icon' => 'la la-area-chart',
                'url' => route('bank.dashboard'),
                'can' => $canViewBank,
            ],
            [
                'label' => 'Banks',
                'icon' => 'la la-university',
                'url' => route('bank.index'),
                'can' => $canViewBank,
            ],
            [
                'label' => 'Expenses',
                'icon' => 'la la-money',
                'url' => route('expense.index'),
                'can' => $canViewExpense,
            ],
            [
                'label' => 'New Expense',
                'icon' => 'la la-plus',
                'url' => route('expense.create'),
                'can' => $canCreateExpense,
            ],
            [
                'label' => 'Payments',
                'icon' => 'la la-credit-card',
                'url' => route('payment.index'),
                'can' => $canViewPayment,
            ],
            [
                'label' => 'New Payment',
                'icon' => 'la la-plus-circle',
                'url' => route('payment.create'),
                'can' => $canCreatePayment,
            ],
            [
                'label' => 'Accountant',
                'icon' => 'la la-calculator',
                'url' => route('accountant.index'),
                'can' => $canViewAccountant,
            ],
            [
                'label' => 'New Accountant Entry',
                'icon' => 'la la-plus-square',
                'url' => route('accountant.create'),
                'can' => $canCreateAccountant,
            ],
            [
                'label' => 'New JCF',
                'icon' => 'la la-plus-circle',
                'url' => route('jobRequest.create'),
                'can' => $canCreateJobRequest,
            ],
            [
                'label' => 'All JCF',
                'icon' => 'la la-list',
                'url' => route('jobRequest.index'),
                'can' => $canViewJobRequest,
            ],
            [
                'label' => 'Quotations',
                'icon' => 'la la-file-text',
                'url' => route('qutation.index'),
                'can' => $canViewQutation,
            ],
            [
                'label' => 'Packing Slips',
                'icon' => 'la la-archive',
                'url' => route('packingSlip.index'),
                'can' => $canViewPackingSlip,
            ],
            [
                'label' => 'Service Tickets',
                'icon' => 'la la-ticket',
                'url' => route('serviceTicket.index'),
                'can' => $canViewServiceTicket,
            ],
            [
                'label' => 'Invoices',
                'icon' => 'la la-money',
                'url' => route('invoice.index'),
                'can' => $canViewInvoice,
            ],
            [
                'label' => 'Inventory',
                'icon' => 'la la-archive',
                'url' => route('inventory.index'),
                'can' => $canViewInventory,
            ],
            [
                'label' => 'File Manager',
                'icon' => 'la la-folder-open',
                'url' => route('fileManager.index'),
                'can' => $canViewFileManager,
            ],
            [
                'label' => 'Inspections',
                'icon' => 'la la-certificate',
                'url' => route('inspection.all'),
                'can' => $canAccessInspection,
            ],
        ], function ($shortcut) {
            return (bool) $shortcut['can'];
        }));

        $recentItems = collect();

        if ($canViewJobRequest) {
            $recentItems = $recentItems->concat(
                JobRequest::latest()->take(8)->get(['id', 'code', 'subject', 'created_at'])->filter(function ($item) use ($user) {
                    return $user->can('view', $item) || $user->can('viewAny', JobRequest::class);
                })->map(function ($item) {
                    return [
                        'module' => 'JCF',
                        'code' => $item->code,
                        'title' => $item->subject ?: 'Job request',
                        'time' => $item->created_at,
                        'url' => route('jobRequest.show', $item->id),
                    ];
                })
            );
        }

        if ($canViewQutation) {
            $recentItems = $recentItems->concat(
                Qutation::latest()->take(8)->get(['id', 'code', 'subject', 'created_at'])->filter(function ($item) use ($user) {
                    return $user->can('view', $item) || $user->can('viewAny', Qutation::class);
                })->map(function ($item) {
                    return [
                        'module' => 'Qutation',
                        'code' => $item->code,
                        'title' => $item->subject ?: 'Quotation',
                        'time' => $item->created_at,
                        'url' => route('qutation.show', $item->id),
                    ];
                })
            );
        }

        if ($canViewPackingSlip) {
            $recentItems = $recentItems->concat(
                PackingSlip::latest()->take(8)->get(['id', 'code', 'po', 'created_at'])->filter(function ($item) use ($user) {
                    return $user->can('view', $item) || $user->can('viewAny', PackingSlip::class);
                })->map(function ($item) {
                    return [
                        'module' => 'Packing Slip',
                        'code' => $item->code,
                        'title' => $item->po ? ('PO: '.$item->po) : 'Packing Slip',
                        'time' => $item->created_at,
                        'url' => route('packingSlip.show', $item->id),
                    ];
                })
            );
        }

        if ($canViewServiceTicket) {
            $recentItems = $recentItems->concat(
                ServiceTicket::latest()->take(8)->get(['id', 'code', 'location', 'created_at'])->filter(function ($item) use ($user) {
                    return $user->can('view', $item) || $user->can('viewAny', ServiceTicket::class);
                })->map(function ($item) {
                    return [
                        'module' => 'Service Ticket',
                        'code' => $item->code,
                        'title' => $item->location ?: 'Service Ticket',
                        'time' => $item->created_at,
                        'url' => route('serviceTicket.show', $item->id),
                    ];
                })
            );
        }

        if ($canViewInvoice) {
            $recentItems = $recentItems->concat(
                Invoice::latest()->take(8)->get(['id', 'code', 'invoice_company_type', 'created_at'])->filter(function ($item) use ($user) {
                    return $user->can('view', $item) || $user->can('viewAny', Invoice::class);
                })->map(function ($item) {
                    return [
                        'module' => 'Invoice '.strtoupper((string)$item->invoice_company_type),
                        'code' => $item->code,
                        'title' => 'Invoice document',
                        'time' => $item->created_at,
                        'url' => route('invoice.show', $item->id),
                    ];
                })
            );
        }

        if ($canViewPayment) {
            $recentItems = $recentItems->concat(
                Payment::latest()->take(8)->get(['id', 'code', 'amount', 'created_at'])->filter(function ($item) use ($user) {
                    return $user->can('view', $item) || $user->can('viewAny', Payment::class);
                })->map(function ($item) {
                    return [
                        'module' => 'Payment',
                        'code' => $item->code,
                        'title' => 'Amount: '.number_format((float)$item->amount, 2, '.', ''),
                        'time' => $item->created_at,
                        'url' => route('payment.show', $item->id),
                    ];
                })
            );
        }

        if ($canViewExpense) {
            $recentItems = $recentItems->concat(
                Expense::latest()->take(8)->get(['id', 'code', 'category', 'created_at'])->filter(function ($item) use ($user) {
                    return $user->can('view', $item) || $user->can('viewAny', Expense::class);
                })->map(function ($item) {
                    return [
                        'module' => 'Expense',
                        'code' => $item->code,
                        'title' => $item->category ?: 'Expense record',
                        'time' => $item->created_at,
                        'url' => route('expense.show', $item->id),
                    ];
                })
            );
        }

        if ($canViewAccountant) {
            $recentItems = $recentItems->concat(
                Accountant::latest()->take(8)->get(['id', 'code', 'entry_type', 'created_at'])->filter(function ($item) use ($user) {
                    return $user->can('view', $item) || $user->can('viewAny', Accountant::class);
                })->map(function ($item) {
                    return [
                        'module' => 'Accountant',
                        'code' => $item->code,
                        'title' => $item->entry_type ?: 'Accountant entry',
                        'time' => $item->created_at,
                        'url' => route('accountant.show', $item->id),
                    ];
                })
            );
        }

        if ($canViewBank) {
            $recentItems = $recentItems->concat(
                BankTransaction::latest()->take(8)->get(['id', 'code', 'status', 'created_at'])->filter(function ($item) use ($user, $hasModulePermission) {
                    return $user->can('view', $item)
                        || $user->can('viewAny', BankTransaction::class)
                        || $hasModulePermission('bank', ['all', 'show']);
                })->map(function ($item) {
                    return [
                        'module' => 'Bank Transaction',
                        'code' => $item->code,
                        'title' => 'Status: '.($item->status ?: 'draft'),
                        'time' => $item->created_at,
                        'url' => route('bank.dashboard'),
                    ];
                })
            );
        }

        if ($canViewInventory) {
            $recentItems = $recentItems->concat(
                Inventory::latest()->take(8)->get(['id', 'code', 'item_name', 'created_at'])->filter(function ($item) use ($user) {
                    return $user->can('view', $item) || $user->can('viewAny', Inventory::class);
                })->map(function ($item) {
                    return [
                        'module' => 'Inventory',
                        'code' => $item->code,
                        'title' => $item->item_name ?: 'Inventory record',
                        'time' => $item->created_at,
                        'url' => route('inventory.show', $item->id),
                    ];
                })
            );
        }

        if ($canViewFileManager) {
            $recentItems = $recentItems->concat(
                FileManager::latest()->take(8)->get(['id', 'filename', 'path', 'created_at'])->filter(function ($item) use ($user) {
                    return $user->can('view', $item) || $user->can('viewAny', FileManager::class);
                })->map(function ($item) {
                    return [
                        'module' => 'File Manager',
                        'code' => $item->filename,
                        'title' => $item->path ?: 'Managed file',
                        'time' => $item->created_at,
                        'url' => route('fileManager.index'),
                    ];
                })
            );
        }

        $recentItems = $recentItems
            ->sortByDesc('time')
            ->take(12)
            ->values();

        $defaultScope = 'all';
        if ($workflowVisibleCount === 0 && $financialVisibleCount > 0) {
            $defaultScope = 'financial';
        }
        if ($financialVisibleCount === 0 && $workflowVisibleCount > 0) {
            $defaultScope = 'workflow';
        }

        $hasFinancialSnapshot = collect($financialSnapshot)->contains(function ($value) {
            return $value !== null;
        });

        $inspectionApprovalWindowOptions = [
            '1h' => ['label' => '1 Hour', 'seconds' => 3600],
            '3h' => ['label' => '3 Hours', 'seconds' => 10800],
            '1d' => ['label' => '1 Day', 'seconds' => 86400],
            '1w' => ['label' => '1 Week', 'seconds' => 604800],
        ];
        $selectedInspectionApprovalWindow = (string) request('inspection_approval_window', '1h');
        if (!array_key_exists($selectedInspectionApprovalWindow, $inspectionApprovalWindowOptions)) {
            $selectedInspectionApprovalWindow = '1h';
        }
        $selectedInspectionApprovalUserId = max(0, (int) request('inspection_approval_user_id', 0));
        $inspectionTypeDefinitions = $this->inspectionReportTypeDefinitions();
        $selectedInspectionApprovalType = strtolower(trim((string) request('inspection_approval_type', 'all')));
        if ($selectedInspectionApprovalType !== 'all' && !array_key_exists($selectedInspectionApprovalType, $inspectionTypeDefinitions)) {
            $selectedInspectionApprovalType = 'all';
        }

        $inspectionApprovalWidget = null;
        if ($canAccessInspection) {
            $windowConfig = $inspectionApprovalWindowOptions[$selectedInspectionApprovalWindow];
            $windowStartAt = now()->subSeconds((int) $windowConfig['seconds']);

            $allowedTypeDefinitions = collect($inspectionTypeDefinitions)
                ->filter(function (array $definition) use ($user) {
                    return $this->canAccessInspectionType($user, (string) ($definition['module_key'] ?? ''));
                })
                ->values();

            $allowedReportableTypes = $allowedTypeDefinitions
                ->pluck('reportable_type')
                ->filter()
                ->values()
                ->all();

            $inspectionDefinitionsByClass = $allowedTypeDefinitions
                ->mapWithKeys(function (array $definition) {
                    return [($definition['reportable_type'] ?? '') => $definition];
                })
                ->all();

            $approvedReportsBaseQuery = InspectionReport::query()
                ->whereNotNull('user_id_approved')
                ->where('updated_at', '>=', $windowStartAt);

            if ($selectedInspectionApprovalUserId > 0) {
                $approvedReportsBaseQuery->where('user_id', $selectedInspectionApprovalUserId);
            }

            if (count($allowedReportableTypes) > 0) {
                $approvedReportsBaseQuery->whereIn('reportable_type', $allowedReportableTypes);
            } else {
                $approvedReportsBaseQuery->whereRaw('1 = 0');
            }

            $approvedReportsByTypeRows = (clone $approvedReportsBaseQuery)
                ->selectRaw('reportable_type, COUNT(*) as total')
                ->groupBy('reportable_type')
                ->orderByDesc('total')
                ->limit(24)
                ->get()
                ->map(function ($row) use ($inspectionDefinitionsByClass, $selectedInspectionApprovalWindow, $selectedInspectionApprovalUserId, $selectedInspectionApprovalType) {
                    $definition = $inspectionDefinitionsByClass[$row->reportable_type] ?? null;
                    $typeKey = strtolower((string) class_basename((string) $row->reportable_type));
                    if ($definition) {
                        $typeKey = (string) ($definition['type_key'] ?? $typeKey);
                    }
                    $moduleLabel = (string) ($definition['label'] ?? trim((string) preg_replace('/(?<!^)[A-Z]/', ' $0', class_basename((string) $row->reportable_type))));

                    return [
                        'type_key' => $typeKey,
                        'module' => $moduleLabel !== '' ? $moduleLabel : class_basename((string) $row->reportable_type),
                        'count' => (int) ($row->total ?? 0),
                        'is_selected' => $selectedInspectionApprovalType === $typeKey,
                        'url' => route('dashboard.home', [
                            'inspection_approval_window' => $selectedInspectionApprovalWindow,
                            'inspection_approval_user_id' => $selectedInspectionApprovalUserId,
                            'inspection_approval_type' => $typeKey,
                        ]),
                    ];
                })
                ->values()
                ->all();

            $selectedDefinition = $selectedInspectionApprovalType === 'all'
                ? null
                : ($inspectionTypeDefinitions[$selectedInspectionApprovalType] ?? null);

            $filteredApprovedReportsQuery = clone $approvedReportsBaseQuery;
            if ($selectedDefinition) {
                $filteredApprovedReportsQuery->where('reportable_type', (string) $selectedDefinition['reportable_type']);
            }

            $approvedReportsTotal = (int) (clone $filteredApprovedReportsQuery)->count();
            $approvedReportsOverallCount = (int) (clone $approvedReportsBaseQuery)->count();

            $recentApprovedReports = (clone $filteredApprovedReportsQuery)
                ->with(['job_request:id,code', 'user.employee:id,name', 'reportable'])
                ->latest('updated_at')
                ->limit(10)
                ->get(['id', 'job_request_id', 'code', 'reportable_type', 'reportable_id', 'user_id', 'updated_at'])
                ->map(function (InspectionReport $report) use ($inspectionDefinitionsByClass, $user) {
                    $definition = $inspectionDefinitionsByClass[$report->reportable_type] ?? null;
                    $moduleName = class_basename((string) $report->reportable_type);
                    $moduleLabel = (string) ($definition['label'] ?? trim((string) preg_replace('/(?<!^)[A-Z]/', ' $0', $moduleName)));
                    $creatorName = optional(optional($report->user)->employee)->name ?: ('User #'.$report->user_id);
                    $jcfCode = (string) optional($report->job_request)->code;
                    $displayCode = $jcfCode !== '' ? ($jcfCode.'/'.$report->code) : (string) $report->code;
                    $openUrl = route('inspection.all');
                    $canOpen = false;

                    if ($report->reportable && $user->can('view', $report->reportable) && $definition && !empty($definition['show_route'])) {
                        try {
                            $openUrl = route((string) $definition['show_route'], $report->reportable_id);
                            $canOpen = true;
                        } catch (\Throwable $e) {
                            $canOpen = false;
                        }
                    }

                    $downloadUrl = null;
                    if ($canOpen && $definition && !empty($definition['pdf_dir']) && $jcfCode !== '' && (string) $report->code !== '') {
                        $relativePath = 'pdf/inspection/'.trim((string) $definition['pdf_dir'], '/').'/'.$jcfCode.'/'.$report->code.'.pdf';
                        if (Storage::disk('public')->exists($relativePath)) {
                            if (Route::has('inspection.approval.pdf')) {
                                $downloadUrl = route('inspection.approval.pdf', $report->id);
                            } else {
                                // Backward-compatible fallback for environments where the new route was not deployed yet.
                                $downloadUrl = Storage::url($relativePath);
                            }
                        }
                    }

                    return [
                        'id' => (int) $report->id,
                        'type_key' => (string) ($definition['type_key'] ?? strtolower($moduleName)),
                        'module' => $moduleLabel !== '' ? $moduleLabel : $moduleName,
                        'code' => $displayCode,
                        'creator' => $creatorName,
                        'approved_at' => $report->updated_at,
                        'open_url' => $openUrl,
                        'can_open' => $canOpen,
                        'download_url' => $downloadUrl,
                    ];
                })
                ->values()
                ->all();

            $inspectionCreatorIds = InspectionReport::query()
                ->whereNotNull('user_id')
                ->distinct()
                ->pluck('user_id');

            $inspectionCreators = User::query()
                ->whereIn('id', $inspectionCreatorIds)
                ->where('is_active', 1)
                ->with('employee:id,name')
                ->orderBy('employee_id')
                ->get(['id', 'employee_id'])
                ->map(function (User $candidate) {
                    return [
                        'id' => (int) $candidate->id,
                        'name' => optional($candidate->employee)->name ?: ('User #'.$candidate->id),
                    ];
                })
                ->values()
                ->all();

            $inspectionApprovalWidget = [
                'window_options' => $inspectionApprovalWindowOptions,
                'selected_window' => $selectedInspectionApprovalWindow,
                'selected_user_id' => $selectedInspectionApprovalUserId,
                'selected_type' => $selectedInspectionApprovalType,
                'window_started_at' => $windowStartAt,
                'window_ends_at' => now(),
                'approved_total' => $approvedReportsTotal,
                'approved_total_overall' => $approvedReportsOverallCount,
                'by_type' => $approvedReportsByTypeRows,
                'recent' => $recentApprovedReports,
                'creators' => $inspectionCreators,
                'all_type_url' => route('dashboard.home', [
                    'inspection_approval_window' => $selectedInspectionApprovalWindow,
                    'inspection_approval_user_id' => $selectedInspectionApprovalUserId,
                    'inspection_approval_type' => 'all',
                ]),
            ];
        }

        return view('layouts.dashboard', [
            'page_name' => 'Dashboard',
            'dashboard_mode_label' => $dashboardModeLabel,
            'dashboard_mode_hint' => $dashboardModeHint,
            'scope_summary' => $scopeSummary,
            'default_scope' => $defaultScope,
            'module_permissions' => $modulePermissions,
            'access_badges' => $accessBadges,
            'can_access_financial_tab' => $canAccessFinancialTab,
            'workflow_counts' => $workflowCounts,
            'kpi_cards' => $kpiCards,
            'jcf_states' => $jcfStates,
            'show_jcf_states' => $canViewJobRequest,
            'financial_snapshot' => $financialSnapshot,
            'has_financial_snapshot' => $hasFinancialSnapshot,
            'approval_queues' => $approvalQueues,
            'action_items' => $actionItems,
            'shortcuts' => $shortcuts,
            'recent_items' => $recentItems,
            'inspection_approval_widget' => $inspectionApprovalWidget,
        ]);
    }

    private function canAccessInspectionType(User $user, string $moduleKey): bool
    {
        if ((bool) $user->isSuperAdmin()) {
            return true;
        }

        if ($moduleKey === '') {
            return false;
        }

        return (bool) (
            $user->hasPermission($moduleKey, 'all') ||
            $user->hasPermission($moduleKey, 'show') ||
            $user->hasPermission($moduleKey, 'approve') ||
            $user->hasPermission('inspection', 'all') ||
            $user->hasPermission('inspection', 'show')
        );
    }

    private function inspectionReportTypeDefinitions(): array
    {
        return [
            'crane' => [
                'type_key' => 'crane',
                'label' => 'Crane',
                'module_key' => 'crane',
                'reportable_type' => 'App\\Models\\Inspection\\Lifting\\Crane',
                'show_route' => 'crane.show',
                'pdf_dir' => 'lifting/crane',
            ],
            'overheadcrane' => [
                'type_key' => 'overheadcrane',
                'label' => 'Overhead Crane',
                'module_key' => 'overheadcrane',
                'reportable_type' => 'App\\Models\\Inspection\\Lifting\\OverheadCrane',
                'show_route' => 'overheadCrane.show',
                'pdf_dir' => 'lifting/overheadcrane',
            ],
            'forklift' => [
                'type_key' => 'forklift',
                'label' => 'Forklift',
                'module_key' => 'forklift',
                'reportable_type' => 'App\\Models\\Inspection\\Lifting\\Forklift',
                'show_route' => 'forklift.show',
                'pdf_dir' => 'lifting/forklift',
            ],
            'throughexamination' => [
                'type_key' => 'throughexamination',
                'label' => 'Through Examination',
                'module_key' => 'throughexamination',
                'reportable_type' => 'App\\Models\\Inspection\\Lifting\\ThroughExamination',
                'show_route' => 'throughExamination.show',
                'pdf_dir' => 'lifting/throughexamination',
            ],
            'defect' => [
                'type_key' => 'defect',
                'label' => 'Defect',
                'module_key' => 'defect',
                'reportable_type' => 'App\\Models\\Inspection\\Lifting\\Defect',
                'show_route' => 'defect.show',
                'pdf_dir' => 'lifting/defect',
            ],
            'lregister' => [
                'type_key' => 'lregister',
                'label' => 'Lregister',
                'module_key' => 'lregister',
                'reportable_type' => 'App\\Models\\Inspection\\Lifting\\Lregister',
                'show_route' => 'lregister.show',
                'pdf_dir' => 'lifting/lregister',
            ],
            'dropobject' => [
                'type_key' => 'dropobject',
                'label' => 'Drop Object',
                'module_key' => 'dropobject',
                'reportable_type' => 'App\\Models\\Inspection\\DropObject\\DropObject',
                'show_route' => 'dropObject.show',
                'pdf_dir' => 'dropobject/dropobject',
            ],
            'mpipt' => [
                'type_key' => 'mpipt',
                'label' => 'NDT MPI/PT',
                'module_key' => 'mpipt',
                'reportable_type' => 'App\\Models\\Inspection\\Ndt\\Mpipt',
                'show_route' => 'mpipt.show',
                'pdf_dir' => 'ndt/mpipt',
            ],
            'visual' => [
                'type_key' => 'visual',
                'label' => 'NDT Visual',
                'module_key' => 'visual',
                'reportable_type' => 'App\\Models\\Inspection\\Ndt\\Visual',
                'show_route' => 'visual.show',
                'pdf_dir' => 'ndt/visual',
            ],
            'ultrasonic' => [
                'type_key' => 'ultrasonic',
                'label' => 'NDT Ultrasonic',
                'module_key' => 'ultrasonic',
                'reportable_type' => 'App\\Models\\Inspection\\Ndt\\Ultrasonic',
                'show_route' => 'ultrasonic.show',
                'pdf_dir' => 'ndt/ultrasonic',
            ],
            'summary' => [
                'type_key' => 'summary',
                'label' => 'NDT Summary',
                'module_key' => 'summary',
                'reportable_type' => 'App\\Models\\Inspection\\Ndt\\Summary',
                'show_route' => 'summary.show',
                'pdf_dir' => 'ndt/summary',
            ],
            'attached' => [
                'type_key' => 'attached',
                'label' => 'NDT Attach',
                'module_key' => 'attached',
                'reportable_type' => 'App\\Models\\Inspection\\Ndt\\Attached',
                'show_route' => 'attached.show',
                'pdf_dir' => 'ndt/attached',
            ],
            'highpressure' => [
                'type_key' => 'highpressure',
                'label' => 'NDT High Pressure UT',
                'module_key' => 'highpressure',
                'reportable_type' => 'App\\Models\\Inspection\\Ndt\\HighPressure',
                'show_route' => 'highPressure.show',
                'pdf_dir' => 'ndt/highpressure',
            ],
            'high2pressure' => [
                'type_key' => 'high2pressure',
                'label' => 'NDT UTWT',
                'module_key' => 'high2pressure',
                'reportable_type' => 'App\\Models\\Inspection\\Ndt\\High2Pressure',
                'show_route' => 'high2Pressure.show',
                'pdf_dir' => 'ndt/high2pressure',
            ],
            'high3pressure' => [
                'type_key' => 'high3pressure',
                'label' => 'NDT General UTWT',
                'module_key' => 'high3pressure',
                'reportable_type' => 'App\\Models\\Inspection\\Ndt\\High3Pressure',
                'show_route' => 'high3Pressure.show',
                'pdf_dir' => 'ndt/high3pressure',
            ],
            'witnesshydro' => [
                'type_key' => 'witnesshydro',
                'label' => 'NDT Witness Hydro',
                'module_key' => 'witnesshydro',
                'reportable_type' => 'App\\Models\\Inspection\\Ndt\\WitnessHydro',
                'show_route' => 'witnessHydro.show',
                'pdf_dir' => 'ndt/witnesshydro',
            ],
            'treatingiron' => [
                'type_key' => 'treatingiron',
                'label' => 'NDT Treating Iron',
                'module_key' => 'treatingiron',
                'reportable_type' => 'App\\Models\\Inspection\\Ndt\\TreatingIron',
                'show_route' => 'treatingIron.show',
                'pdf_dir' => 'ndt/treatingiron',
            ],
            'drawinginspection' => [
                'type_key' => 'drawinginspection',
                'label' => 'NDT Drawing',
                'module_key' => 'drawinginspection',
                'reportable_type' => 'App\\Models\\Inspection\\Ndt\\DrawingInspection',
                'show_route' => 'drawingInspection.show',
                'pdf_dir' => 'ndt/drawinginspection',
            ],
            'nregister' => [
                'type_key' => 'nregister',
                'label' => 'NDT Register',
                'module_key' => 'nregister',
                'reportable_type' => 'App\\Models\\Inspection\\Ndt\\Nregister',
                'show_route' => 'nregister.show',
                'pdf_dir' => 'ndt/nregister',
            ],
            'pipessummaryreport' => [
                'type_key' => 'pipessummaryreport',
                'label' => 'Summary of Pipes',
                'module_key' => 'pipessummaryreport',
                'reportable_type' => 'App\\Models\\Inspection\\Tubular\\PipesSummaryReport',
                'show_route' => 'pipesSummaryReports.show',
                'pdf_dir' => 'tubular/summary',
            ],
            'drillpipe' => [
                'type_key' => 'drillpipe',
                'label' => 'Drill Pipe',
                'module_key' => 'drillpipe',
                'reportable_type' => 'App\\Models\\Inspection\\Tubular\\DrillPipe',
                'show_route' => 'drillPipe.show',
                'pdf_dir' => 'tubular/drillpipe',
            ],
            'heavyweightpipe' => [
                'type_key' => 'heavyweightpipe',
                'label' => 'Heavy Weight Pipe',
                'module_key' => 'heavyweightpipe',
                'reportable_type' => 'App\\Models\\Inspection\\Tubular\\HeavyWeightPipe',
                'show_route' => 'heavyWeightPipe.show',
                'pdf_dir' => 'tubular/heavyweightpipe',
            ],
            'drillcollar' => [
                'type_key' => 'drillcollar',
                'label' => 'Drill Collar',
                'module_key' => 'drillcollar',
                'reportable_type' => 'App\\Models\\Inspection\\Tubular\\DrillCollar',
                'show_route' => 'drillCollar.show',
                'pdf_dir' => 'tubular/drillcollar',
            ],
            'subsdimensional' => [
                'type_key' => 'subsdimensional',
                'label' => 'Subs Dimensional',
                'module_key' => 'subsdimensional',
                'reportable_type' => 'App\\Models\\Inspection\\Tubular\\SubsDimensional',
                'show_route' => 'subsDimensional.show',
                'pdf_dir' => 'tubular/subsdimensional',
            ],
            'tubingstring' => [
                'type_key' => 'tubingstring',
                'label' => 'Tubing String',
                'module_key' => 'tubingstring',
                'reportable_type' => 'App\\Models\\Inspection\\Tubular\\TubingString',
                'show_route' => 'tubingString.show',
                'pdf_dir' => 'tubular/tubingstring',
            ],
            'stabilizerinspection' => [
                'type_key' => 'stabilizerinspection',
                'label' => 'Stabilizer',
                'module_key' => 'stabilizerinspection',
                'reportable_type' => 'App\\Models\\Inspection\\Tubular\\StabilizerInspection',
                'show_route' => 'stabilizerInspection.show',
                'pdf_dir' => 'tubular/stabilizerinspection',
            ],
            'reamerinspection' => [
                'type_key' => 'reamerinspection',
                'label' => 'Reamer',
                'module_key' => 'reamerinspection',
                'reportable_type' => 'App\\Models\\Inspection\\Tubular\\ReamerInspection',
                'show_route' => 'reamerInspection.show',
                'pdf_dir' => 'tubular/reamerinspection',
            ],
            'linkinspection' => [
                'type_key' => 'linkinspection',
                'label' => 'Link Inspection',
                'module_key' => 'linkinspection',
                'reportable_type' => 'App\\Models\\Inspection\\Tubular\\LinkInspection',
                'show_route' => 'linkInspection.show',
                'pdf_dir' => 'tubular/linkinspection',
            ],
            'pbl' => [
                'type_key' => 'pbl',
                'label' => 'PBL',
                'module_key' => 'pbl',
                'reportable_type' => 'App\\Models\\Inspection\\Tubular\\Pbl',
                'show_route' => 'pbl.show',
                'pdf_dir' => 'tubular/pbl',
            ],
            'tubingcasing' => [
                'type_key' => 'tubingcasing',
                'label' => 'Tubing/Casing',
                'module_key' => 'tubingcasing',
                'reportable_type' => 'App\\Models\\Inspection\\Tubular\\TubingCasing',
                'show_route' => 'tubingCasing.show',
                'pdf_dir' => 'tubular/tubingcasing',
            ],
            'calibrationpressuregauge' => [
                'type_key' => 'calibrationpressuregauge',
                'label' => 'Calibration Pressure Gauge',
                'module_key' => 'calibrationpressuregauge',
                'reportable_type' => 'App\\Models\\Inspection\\Calibration\\CalibrationPressureGauge',
                'show_route' => 'calibrationPressureGauge.show',
                'pdf_dir' => 'calibration/calibrationPressureGauge',
            ],
            'calibrationtorque' => [
                'type_key' => 'calibrationtorque',
                'label' => 'Calibration Torque',
                'module_key' => 'calibrationtorque',
                'reportable_type' => 'App\\Models\\Inspection\\Calibration\\CalibrationTorque',
                'show_route' => 'calibrationTorque.show',
                'pdf_dir' => 'calibration/calibrationTorque',
            ],
            'calibrationpressuretest' => [
                'type_key' => 'calibrationpressuretest',
                'label' => 'Calibration Pressure Test',
                'module_key' => 'calibrationpressuretest',
                'reportable_type' => 'App\\Models\\Inspection\\Calibration\\CalibrationPressureTest',
                'show_route' => 'calibrationPressureTest.show',
                'pdf_dir' => 'calibration/calibrationPressureTest',
            ],
            'calibrationyoke' => [
                'type_key' => 'calibrationyoke',
                'label' => 'Calibration Yoke',
                'module_key' => 'calibrationyoke',
                'reportable_type' => 'App\\Models\\Inspection\\Calibration\\CalibrationYoke',
                'show_route' => 'calibrationYoke.show',
                'pdf_dir' => 'calibration/calibrationYoke',
            ],
        ];
    }
}

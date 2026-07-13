<?php

namespace App\Support;

use App\Models\WorkFlow\Accountant;
use App\Models\WorkFlow\FileManager;
use App\Models\WorkFlow\Invoice;
use App\Models\WorkFlow\Inventory;
use App\Models\WorkFlow\JobRequest;
use App\Models\WorkFlow\PackingSlip;
use App\Models\WorkFlow\Payment;
use App\Models\WorkFlow\Qutation;
use App\Models\WorkFlow\ServiceTicket;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class ActionCenterData
{
    public static function dashboardItemsForUser(User $user): Collection
    {
        $items = [];

        $canViewQutation = $user->can('viewAny', Qutation::class);
        $canCreateQutation = $user->can('create', Qutation::class);
        $canApproveQutation = (bool) $user->hasPermission('qutation', 'approve') || (bool) $user->isSuperAdmin();

        $canViewPackingSlip = $user->can('viewAny', PackingSlip::class);
        $canCreatePackingSlip = $user->can('create', PackingSlip::class);

        $canViewServiceTicket = $user->can('viewAny', ServiceTicket::class);
        $canCreateServiceTicket = $user->can('create', ServiceTicket::class);

        $canViewInvoice = $user->can('viewAny', Invoice::class);
        $canCreateInvoice = $user->can('create', Invoice::class);

        $canViewPayment = $user->can('viewAny', Payment::class);
        $canCreatePayment = $user->can('create', Payment::class);

        $canViewInventory = $user->can('viewAny', Inventory::class);
        $canCreateInventory = $user->can('create', Inventory::class);

        $canViewAccountant = $user->can('viewAny', Accountant::class);
        $canCreateAccountant = $user->can('create', Accountant::class);

        $canViewFileManager = $user->can('viewAny', FileManager::class);
        $canCreateFileManager = $user->can('create', FileManager::class);

        if ($canViewQutation && $canCreateQutation) {
            $missingQuotationCount = JobRequest::whereNotNull('client_id')
                ->whereDoesntHave('qutation')
                ->count();

            if ($missingQuotationCount > 0) {
                $items[] = [
                    'module' => 'qutation',
                    'icon' => 'description',
                    'level' => 'warning',
                    'title' => 'JCF without quotation',
                    'count' => $missingQuotationCount,
                    'description' => 'Client JCFs that still need a quotation.',
                    'url' => route('qutation.index'),
                    'cta' => 'Open Quotations',
                ];
            }
        }

        if ($canViewQutation && $canApproveQutation) {
            $pendingQuotationApprovalsCount = Qutation::whereNull('user_id_approved')->count();

            if ($pendingQuotationApprovalsCount > 0) {
                $items[] = [
                    'module' => 'qutation',
                    'icon' => 'assignment',
                    'level' => 'danger',
                    'title' => 'Quotation pending approval',
                    'count' => $pendingQuotationApprovalsCount,
                    'description' => 'Quotations waiting for approval.',
                    'url' => route('qutation.index'),
                    'cta' => 'Review Quotations',
                ];
            }
        }

        if ($canViewPackingSlip && $canCreatePackingSlip) {
            $missingPackingSlipCount = JobRequest::whereNotNull('client_id')
                ->whereHas('qutation')
                ->whereDoesntHave('packingSlip')
                ->count();

            if ($missingPackingSlipCount > 0) {
                $items[] = [
                    'module' => 'packing_slip',
                    'icon' => 'local_shipping',
                    'level' => 'info',
                    'title' => 'Missing packing slip',
                    'count' => $missingPackingSlipCount,
                    'description' => 'JCFs with quotation but without packing slip.',
                    'url' => route('packingSlip.index'),
                    'cta' => 'Open Packing Slips',
                ];
            }
        }

        if ($canViewServiceTicket && $canCreateServiceTicket) {
            $missingServiceTicketCount = JobRequest::whereNotNull('client_id')
                ->whereHas('qutation')
                ->whereDoesntHave('serviceTicket')
                ->count();

            if ($missingServiceTicketCount > 0) {
                $items[] = [
                    'module' => 'service_ticket',
                    'icon' => 'build',
                    'level' => 'primary',
                    'title' => 'Missing service ticket',
                    'count' => $missingServiceTicketCount,
                    'description' => 'JCFs with quotation but without service ticket.',
                    'url' => route('serviceTicket.index'),
                    'cta' => 'Open Service Tickets',
                ];
            }
        }

        if ($canViewInvoice && $canCreateInvoice) {
            $missingInvoiceCount = JobRequest::whereNotNull('client_id')
                ->whereHas('qutation')
                ->whereDoesntHave('invoice')
                ->count();

            if ($missingInvoiceCount > 0) {
                $items[] = [
                    'module' => 'invoice',
                    'icon' => 'receipt',
                    'level' => 'success',
                    'title' => 'Missing invoice',
                    'count' => $missingInvoiceCount,
                    'description' => 'JCFs with quotation but without invoice.',
                    'url' => route('invoice.index', ['type' => Invoice::$INVOICE_RSE_TYPE]),
                    'cta' => 'Open Invoices',
                ];
            }
        }

        if ($canViewPayment && $canCreatePayment) {
            $missingPaymentCount = JobRequest::whereNotNull('client_id')
                ->whereHas('invoice')
                ->whereDoesntHave('payments')
                ->count();

            if ($missingPaymentCount > 0) {
                $items[] = [
                    'module' => 'payment',
                    'icon' => 'attach_money',
                    'level' => 'warning',
                    'title' => 'Missing payment',
                    'count' => $missingPaymentCount,
                    'description' => 'JCFs with invoice but without payment.',
                    'url' => route('payment.index'),
                    'cta' => 'Open Payments',
                ];
            }
        }

        if ($canViewInventory && $canCreateInventory) {
            $lowStockCount = Inventory::whereNotNull('min_quantity')
                ->where('min_quantity', '>', 0)
                ->whereColumn('quantity', '<=', 'min_quantity')
                ->count();

            if ($lowStockCount > 0) {
                $items[] = [
                    'module' => 'inventory',
                    'icon' => 'archive',
                    'level' => 'danger',
                    'title' => 'Low inventory stock',
                    'count' => $lowStockCount,
                    'description' => 'Inventory records below minimum quantity.',
                    'url' => route('inventory.index'),
                    'cta' => 'Review Inventory',
                ];
            }
        }

        if ($canViewAccountant && $canCreateAccountant) {
            $pendingAccountantCount = Accountant::where('status', 'pending_approval')->count();

            if ($pendingAccountantCount > 0) {
                $items[] = [
                    'module' => 'accountant',
                    'icon' => 'account_balance',
                    'level' => 'primary',
                    'title' => 'Accountant pending approval',
                    'count' => $pendingAccountantCount,
                    'description' => 'Accounting entries waiting for approval.',
                    'url' => route('accountant.index'),
                    'cta' => 'Open Accountant',
                ];
            }
        }

        if ($canViewFileManager && $canCreateFileManager) {
            $missingFilesCount = FileManager::where('is_available', 0)->count();

            if ($missingFilesCount > 0) {
                $items[] = [
                    'module' => 'file_manager',
                    'icon' => 'folder',
                    'level' => 'danger',
                    'title' => 'Missing indexed files',
                    'count' => $missingFilesCount,
                    'description' => 'Files indexed in manager but currently not found on storage.',
                    'url' => route('fileManager.index', ['available_only' => 0]),
                    'cta' => 'Open File Manager',
                ];
            }
        }

        return collect($items)->values();
    }

    public static function countForUser(User $user): int
    {
        return self::dashboardItemsForUser($user)->count();
    }

    public static function notificationItemsForUser(User $user, int $limit = 6): Collection
    {
        $baseTimestamp = now()->timestamp;

        return self::dashboardItemsForUser($user)
            ->take($limit)
            ->values()
            ->map(function (array $item, int $index) use ($baseTimestamp) {
                return [
                    'type' => 'action_center',
                    'id' => 'action-center-'.Str::slug((string) ($item['title'] ?? 'item')).'-'.$index,
                    'module' => $item['module'] ?? null,
                    'icon' => $item['icon'] ?? 'offline_bolt',
                    'title' => $item['title'] ?? 'Action Center',
                    'message' => $item['description'] ?? '',
                    'subject' => $item['cta'] ?? null,
                    'status' => $item['level'] ?? 'primary',
                    'url' => $item['url'] ?? (Route::has('dashboard') ? route('dashboard') : '#'),
                    'cta' => $item['cta'] ?? 'Open',
                    'count' => (int) ($item['count'] ?? 0),
                    'is_unread' => true,
                    'created_at_human' => 'Live',
                    'sort_at' => $baseTimestamp - $index,
                ];
            })
            ->values();
    }
}

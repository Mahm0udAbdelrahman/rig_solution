<?php

use App\Http\Controllers\Dashboard\Inspection\Calibration\CalibrationController;
use App\Http\Controllers\Dashboard\Inspection\Calibration\CalibrationPressureGaugeController;
use App\Http\Controllers\Dashboard\Inspection\Calibration\CalibrationPressureTestController;
use App\Http\Controllers\Dashboard\Inspection\Calibration\CalibrationTorqueController;
use App\Http\Controllers\Dashboard\Inspection\Calibration\CalibrationYokeController;
use App\Http\Controllers\Dashboard\Inspection\InspectionCatalogController;
use App\Http\Controllers\Dashboard\Inspection\Ndt\NregisterController;
use App\Http\Controllers\Dashboard\Inspection\Tubular\PblController;
use App\Http\Controllers\Dashboard\Inspection\Tubular\TubingCasingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\SystemMaintenanceController;
use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Artisan;
/*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web" middleware group. Now create something great!
    |
*/

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Route::get(
//     '/start',
//     function () {
//         \Illuminate\Support\Facades\Artisan::call('websockets:serve --port=7001');
//     }
// )->name('socket.start');

// Route::get(
//     '/queue',
//     function () {
//         \Illuminate\Support\Facades\Artisan::call('queue:listen');
//     }
// )->name('queue.start');

Route::get('/', function(){
    return redirect('login');
});

Route::group(
    [
        'prefix'     => 'dashboard',
        'middleware' => ['auth', 'prevent.duplicate.form.submit'],
    ],
    function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.home');
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('system/maintenance', [SystemMaintenanceController::class, 'index'])->name('system.maintenance.index');
        Route::post('system/maintenance/run', [SystemMaintenanceController::class, 'run'])->name('system.maintenance.run');
        Route::get('system/maintenance/download-database', [SystemMaintenanceController::class, 'downloadDatabase'])->name('system.maintenance.downloadDatabase');
        // *********** CustomController ************  //
        // Route::get('{table_name}/prepend_data_to_local', [App\Http\Controllers\CustomController::class, 'prepend_data_to_local'])->name('system.prepend_data_to_local');
        // Route::resource('toolapi', App\Http\Controllers\Api\Dashboard\Organization\DepartmentController::class);
        Route::get('connectServer', [App\Http\Controllers\CustomController::class, 'connect_server'])->name('system.connect_server');
        Route::post('connectServer', [App\Http\Controllers\CustomController::class, 'get_api_access_token'])->name('system.get_api_access_token');
        Route::get('syncData', [App\Http\Controllers\CustomController::class, 'sync_view'])->name('system.sync_view');
        Route::post('sync', [App\Http\Controllers\CustomController::class, 'sync'])->name('system.sync');
        Route::get('toLocal', [App\Http\Controllers\CustomController::class, 'sync_from_server_to_local'])->name('system.sync_from_server_to_local');
        Route::post('makeImageForPdf', [App\Http\Controllers\CustomController::class, 'makeImageForPdf'])->name('report.makeImageForPdf');
        Route::post('generatePdf', [App\Http\Controllers\CustomController::class, 'generatePdf'])->name('report.generatePdf');
        Route::post('userApprove', [App\Http\Controllers\CustomController::class, 'userApprove'])->name('userApprove');
        Route::get('{table}/forjcf', [App\Http\Controllers\CustomController::class, 'forJcf'])->name('data.forJcf');
        Route::get('jobRequest/{jobRequest}/showFor', [App\Http\Controllers\CustomController::class, 'getReportData'])->name('jobRequest.showForAdd');
        Route::get('{inspectionReport}/duplicate', [App\Http\Controllers\CustomController::class, 'duplicate'])->name('report.duplicate');
        /**/
        Route::group(
            ['prefix' => 'work-flow'],
            function () {
                    // ************ Job Requests ************  //
                    Route::get('jobRequest/getDataForDataTable', [App\Http\Controllers\Dashboard\WorkFlow\JobRequestController::class, 'getDataForDataTable'])->name('getDataForDataTable.jobRequest');
                    Route::get('jobRequest/{jobRequest}/overview', [App\Http\Controllers\Dashboard\WorkFlow\JobRequestController::class, 'overview'])->name('jobRequest.overview');
                    Route::resource('jobRequest', App\Http\Controllers\Dashboard\WorkFlow\JobRequestController::class);
                    Route::post('jcfStatus/{jcfStatus}', [App\Http\Controllers\Dashboard\WorkFlow\JcfStatusController::class, 'update'])->name('jcfStatus.update');
                    Route::get('jobRequest/{jobRequest}/qutation', [App\Http\Controllers\Dashboard\WorkFlow\QutationController::class, 'qutationWithJobRequest'])->name('qutation.qutationWithJobRequest');
                    Route::get('jobRequest/{jobRequest}/qutation/edit', [App\Http\Controllers\Dashboard\WorkFlow\QutationController::class, 'qutationWithJobRequestEdit'])->name('qutation.qutationWithJobRequestEdit');
                    Route::get('jobRequest/{jobRequest}/packingSlip', [App\Http\Controllers\Dashboard\WorkFlow\PackingSlipController::class, 'packingSlipWithJobRequest'])->name('packingSlip.packingSlipWithJobRequest');
                    Route::get('jobRequest/{jobRequest}/packingSlip/edit', [App\Http\Controllers\Dashboard\WorkFlow\PackingSlipController::class, 'packingSlipWithJobRequestEdit'])->name('packingSlip.packingSlipWithJobRequestEdit');
                    Route::get('jobRequest/{jobRequest}/serviceTicket', [App\Http\Controllers\Dashboard\WorkFlow\ServiceTicketController::class, 'serviceTicketWithJobRequest'])->name('serviceTicket.serviceTicketWithJobRequest');
                    Route::get('jobRequest/{jobRequest}/serviceTicket/edit', [App\Http\Controllers\Dashboard\WorkFlow\ServiceTicketController::class, 'serviceTicketWithJobRequestEdit'])->name('serviceTicket.serviceTicketWithJobRequestEdit');
                    Route::get('jobRequest/{jobRequest}/invoice', [App\Http\Controllers\Dashboard\WorkFlow\InvoiceController::class, 'invoiceWithJobRequestNew'])->name('invoice.invoiceWithJobRequestNew');
                    Route::get('jobRequest/{jobRequest}/invoice/edit', [App\Http\Controllers\Dashboard\WorkFlow\InvoiceController::class, 'invoiceWithJobRequestNewEdit'])->name('invoice.invoiceWithJobRequestNewEdit');
                    Route::get('jobRequest/{jobRequest}/payment', [App\Http\Controllers\Dashboard\WorkFlow\PaymentController::class, 'paymentWithJobRequest'])->name('payment.paymentWithJobRequest');
                    Route::get('jobRequest/{jobRequest}/inventory', [App\Http\Controllers\Dashboard\WorkFlow\InventoryController::class, 'inventoryWithJobRequest'])->name('inventory.inventoryWithJobRequest');
                    Route::get('jobRequest/{jobRequest}/accountant', [App\Http\Controllers\Dashboard\WorkFlow\AccountantController::class, 'accountantWithJobRequest'])->name('accountant.accountantWithJobRequest');
                    /**/
                    // ************ Quotations *************  //
                    Route::get('qutation/getDataForDataTable', [App\Http\Controllers\Dashboard\WorkFlow\QutationController::class, 'getDataForDataTable'])->name('getDataForDataTable.qutation');
                    Route::resource('qutation', App\Http\Controllers\Dashboard\WorkFlow\QutationController::class);
                    /**/
                    // ************ PackingSlips ***********  //
                    Route::get('packingSlip/getDataForDataTable', [App\Http\Controllers\Dashboard\WorkFlow\PackingSlipController::class, 'getDataForDataTable'])->name('getDataForDataTable.packingSlip');
                    Route::resource('packingSlip', App\Http\Controllers\Dashboard\WorkFlow\PackingSlipController::class);
                    /**/
                    // ********** ServiceTickets *********  //
                    Route::get('serviceTicket/getDataForDataTable', [App\Http\Controllers\Dashboard\WorkFlow\ServiceTicketController::class, 'getDataForDataTable'])->name('getDataForDataTable.serviceTicket');
                    Route::resource('serviceTicket', App\Http\Controllers\Dashboard\WorkFlow\ServiceTicketController::class);
                    /**/
                    // *********** Invoices *************  //
                    Route::get('invoice/getDataForDataTable', [App\Http\Controllers\Dashboard\WorkFlow\InvoiceController::class, 'getDataForDataTable'])->name('getDataForDataTable.invoice');
                    Route::get('invoice/{invoice}/export-excel', [App\Http\Controllers\Dashboard\WorkFlow\InvoiceController::class, 'exportExcel'])->name('invoice.exportExcel');
                    Route::resource('invoice', App\Http\Controllers\Dashboard\WorkFlow\InvoiceController::class);
                    // *********** Payments *************  //
                    Route::get('payment/getDataForDataTable', [App\Http\Controllers\Dashboard\WorkFlow\PaymentController::class, 'getDataForDataTable'])->name('getDataForDataTable.payment');
                    Route::resource('payment', App\Http\Controllers\Dashboard\WorkFlow\PaymentController::class);
                    // *********** Inventory ************  //
                    Route::get('inventory/getDataForDataTable', [App\Http\Controllers\Dashboard\WorkFlow\InventoryController::class, 'getDataForDataTable'])->name('getDataForDataTable.inventory');
                    Route::resource('inventory', App\Http\Controllers\Dashboard\WorkFlow\InventoryController::class);
                    // *********** Banks ***********  //
                    Route::get('bank/dashboard', [App\Http\Controllers\Dashboard\WorkFlow\BankController::class, 'dashboard'])->name('bank.dashboard');
                    Route::get('bank/getDataForDataTable', [App\Http\Controllers\Dashboard\WorkFlow\BankController::class, 'getDataForDataTable'])->name('getDataForDataTable.bank');
                    Route::post('bank/{bank}/transactions', [App\Http\Controllers\Dashboard\WorkFlow\BankController::class, 'storeTransaction'])->name('bank.transactions.store');
                    Route::post('bank/{bank}/transactions/{transaction}/submit-for-approval', [App\Http\Controllers\Dashboard\WorkFlow\BankController::class, 'submitForApproval'])->name('bank.transactions.submitForApproval');
                    Route::post('bank/{bank}/transactions/{transaction}/approve', [App\Http\Controllers\Dashboard\WorkFlow\BankController::class, 'approveTransaction'])->name('bank.transactions.approve');
                    Route::post('bank/{bank}/transactions/{transaction}/reject', [App\Http\Controllers\Dashboard\WorkFlow\BankController::class, 'rejectTransaction'])->name('bank.transactions.reject');
                    Route::post('bank/{bank}/transactions/{transaction}/unpost', [App\Http\Controllers\Dashboard\WorkFlow\BankController::class, 'unpostTransaction'])->name('bank.transactions.unpost');
                    Route::delete('bank/{bank}/transactions/{transaction}', [App\Http\Controllers\Dashboard\WorkFlow\BankController::class, 'destroyTransaction'])->name('bank.transactions.destroy');
                    Route::resource('bank', App\Http\Controllers\Dashboard\WorkFlow\BankController::class);
                    // *********** Expenses ***********  //
                    Route::get('expense/getDataForDataTable', [App\Http\Controllers\Dashboard\WorkFlow\ExpenseController::class, 'getDataForDataTable'])->name('getDataForDataTable.expense');
                    Route::post('expense/{expense}/submit-for-approval', [App\Http\Controllers\Dashboard\WorkFlow\ExpenseController::class, 'submitForApproval'])->name('expense.submitForApproval');
                    Route::post('expense/{expense}/approve', [App\Http\Controllers\Dashboard\WorkFlow\ExpenseController::class, 'approve'])->name('expense.approve');
                    Route::post('expense/{expense}/reject', [App\Http\Controllers\Dashboard\WorkFlow\ExpenseController::class, 'reject'])->name('expense.reject');
                    Route::post('expense/{expense}/unpost', [App\Http\Controllers\Dashboard\WorkFlow\ExpenseController::class, 'unpost'])->name('expense.unpost');
                    Route::post('expense/{expense}/quick-payment', [App\Http\Controllers\Dashboard\WorkFlow\ExpenseController::class, 'storeQuickPayment'])->name('expense.quickPayment.store');
                    Route::resource('expense', App\Http\Controllers\Dashboard\WorkFlow\ExpenseController::class);
                    // *********** Accountant ***********  //
                    Route::get('accountant/getDataForDataTable', [App\Http\Controllers\Dashboard\WorkFlow\AccountantController::class, 'getDataForDataTable'])->name('getDataForDataTable.accountant');
                    Route::post('accountant/chart-account', [App\Http\Controllers\Dashboard\WorkFlow\AccountantController::class, 'storeChartAccount'])->name('accountant.chartAccount.store');
                    Route::patch('accountant/chart-account/{chartAccount}', [App\Http\Controllers\Dashboard\WorkFlow\AccountantController::class, 'updateChartAccount'])->name('accountant.chartAccount.update');
                    Route::get('accountant/chart-account/export', [App\Http\Controllers\Dashboard\WorkFlow\AccountantController::class, 'exportChartAccounts'])->name('accountant.chartAccount.export');
                    Route::get('accountant/chart-account/template', [App\Http\Controllers\Dashboard\WorkFlow\AccountantController::class, 'downloadChartAccountsTemplate'])->name('accountant.chartAccount.template');
                    Route::post('accountant/chart-account/import', [App\Http\Controllers\Dashboard\WorkFlow\AccountantController::class, 'importChartAccounts'])->name('accountant.chartAccount.import');
                    Route::get('accountant/advanced-reports/export/excel', [App\Http\Controllers\Dashboard\WorkFlow\AccountantController::class, 'advancedReportsExportExcel'])->name('accountant.advancedReports.exportExcel');
                    Route::get('accountant/advanced-reports/export/pdf', [App\Http\Controllers\Dashboard\WorkFlow\AccountantController::class, 'advancedReportsExportPdf'])->name('accountant.advancedReports.exportPdf');
                    Route::post('accountant/period/close', [App\Http\Controllers\Dashboard\WorkFlow\AccountantController::class, 'closePeriod'])->name('accountant.period.close');
                    Route::post('accountant/period/reopen', [App\Http\Controllers\Dashboard\WorkFlow\AccountantController::class, 'reopenPeriod'])->name('accountant.period.reopen');
                    Route::post('accountant/{accountant}/submit-for-approval', [App\Http\Controllers\Dashboard\WorkFlow\AccountantController::class, 'submitForApproval'])->name('accountant.submitForApproval');
                    Route::post('accountant/{accountant}/approve', [App\Http\Controllers\Dashboard\WorkFlow\AccountantController::class, 'approve'])->name('accountant.approve');
                    Route::post('accountant/{accountant}/reject', [App\Http\Controllers\Dashboard\WorkFlow\AccountantController::class, 'reject'])->name('accountant.reject');
                    Route::post('accountant/{accountant}/unpost', [App\Http\Controllers\Dashboard\WorkFlow\AccountantController::class, 'unpost'])->name('accountant.unpost');
                    Route::resource('accountant', App\Http\Controllers\Dashboard\WorkFlow\AccountantController::class);
                    // *********** File Manager ***********  //
                    Route::get('fileManager/getDataForDataTable', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'getDataForDataTable'])->name('getDataForDataTable.fileManager');
                    Route::post('fileManager/sync', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'sync'])->name('fileManager.sync');
                    Route::post('fileManager/upload', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'upload'])->name('fileManager.upload');
                    Route::post('fileManager/bulk-move', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'bulkMove'])->name('fileManager.bulkMove');
                    Route::post('fileManager/bulk-download', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'bulkDownload'])->name('fileManager.bulkDownload');
                    Route::post('fileManager/bulk-export-inspection-workbooks', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'bulkExportInspectionWorkbooks'])->name('fileManager.bulkExportInspectionWorkbooks');
                    Route::post('fileManager/bulk-destroy', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'bulkDestroy'])->name('fileManager.bulkDestroy');
                    Route::post('fileManager/bulk-destroy-physical', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'bulkDestroyPhysical'])->name('fileManager.bulkDestroyPhysical');
                    Route::get('fileManager/export/excel', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'exportExcel'])->name('fileManager.exportExcel');
                    Route::get('fileManager/export/pdf', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'exportPdf'])->name('fileManager.exportPdf');
                    Route::get('fileManager/export/inspections/excel', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'exportInspectionsExcel'])->name('fileManager.exportInspectionsExcel');
                    Route::get('fileManager/export/inspections/pdf', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'exportInspectionsPdf'])->name('fileManager.exportInspectionsPdf');
                    Route::get('fileManager/{fileManager}/inspection-workbook', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'exportInspectionWorkbook'])->name('fileManager.exportInspectionWorkbook');
                    Route::get('fileManager/{fileManager}/download', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'download'])->name('fileManager.download');
                    Route::delete('fileManager/{fileManager}/physical', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'destroyPhysical'])->name('fileManager.destroyPhysical');
                    Route::delete('fileManager/{fileManager}', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'destroy'])->name('fileManager.destroy');
                    Route::get('fileManager', [App\Http\Controllers\Dashboard\WorkFlow\FileManagerController::class, 'index'])->name('fileManager.index');
                    // *********** Rig MailCenter ***********  //
                    Route::post('mailCenter/settings', [App\Http\Controllers\Dashboard\WorkFlow\MailCenterController::class, 'updateSettings'])->name('mailCenter.settings.update');
                    Route::post('mailCenter/mailbox', [App\Http\Controllers\Dashboard\WorkFlow\MailCenterController::class, 'storeMailbox'])->name('mailCenter.mailbox.store');
                    Route::put('mailCenter/mailbox/{mailbox}', [App\Http\Controllers\Dashboard\WorkFlow\MailCenterController::class, 'updateMailbox'])->name('mailCenter.mailbox.update');
                    Route::delete('mailCenter/mailbox/{mailbox}', [App\Http\Controllers\Dashboard\WorkFlow\MailCenterController::class, 'destroyMailbox'])->name('mailCenter.mailbox.destroy');
                    Route::post('mailCenter/template', [App\Http\Controllers\Dashboard\WorkFlow\MailCenterController::class, 'storeTemplate'])->name('mailCenter.template.store');
                    Route::put('mailCenter/template/{template}', [App\Http\Controllers\Dashboard\WorkFlow\MailCenterController::class, 'updateTemplate'])->name('mailCenter.template.update');
                    Route::delete('mailCenter/template/{template}', [App\Http\Controllers\Dashboard\WorkFlow\MailCenterController::class, 'destroyTemplate'])->name('mailCenter.template.destroy');
                    Route::get('mailCenter/compose/{relatedType}/{relatedId}', [App\Http\Controllers\Dashboard\WorkFlow\MailCenterController::class, 'composeRelated'])->name('mailCenter.compose.related');
                    Route::get('mailCenter/navbar-snapshot', [App\Http\Controllers\Dashboard\WorkFlow\MailCenterController::class, 'navbarSnapshot'])->name('mailCenter.navbar.snapshot');
                    Route::post('mailCenter/notifications/read-all', [App\Http\Controllers\Dashboard\WorkFlow\MailCenterController::class, 'markAllNotificationsRead'])->name('mailCenter.notifications.readAll');
                    Route::get('mailCenter/notifications/{notificationId}/open', [App\Http\Controllers\Dashboard\WorkFlow\MailCenterController::class, 'openNotification'])->name('mailCenter.notifications.open');
                    Route::post('mailCenter/preferences', [App\Http\Controllers\Dashboard\WorkFlow\MailCenterController::class, 'updateNotificationPreferences'])->name('mailCenter.preferences.update');
                    Route::post('mailCenter/{mailCenter}/approve', [App\Http\Controllers\Dashboard\WorkFlow\MailCenterController::class, 'approve'])->name('mailCenter.approve');
                    Route::post('mailCenter/{mailCenter}/send', [App\Http\Controllers\Dashboard\WorkFlow\MailCenterController::class, 'send'])->name('mailCenter.send');
                    Route::resource('mailCenter', App\Http\Controllers\Dashboard\WorkFlow\MailCenterController::class);
                    /**/
                    // ********** Inquiries *************  //
                    Route::resource('inquiry', App\Http\Controllers\Dashboard\WorkFlow\InquiryController::class);
                    /**/
            }
        );
        /**/
        Route::group(
            ['prefix' => 'organization'],
            function () {
                Route::get('staff-performance', [App\Http\Controllers\Dashboard\Organization\StaffPerformanceController::class, 'index'])->name('organization.staffPerformance');
                Route::get('department/getDataForDataTable', [App\Http\Controllers\Dashboard\Organization\DepartmentController::class, 'getDataForDataTable'])->name('getDataForDataTable.department');
                Route::resource('department', App\Http\Controllers\Dashboard\Organization\DepartmentController::class);
                Route::post('department/managers/id', [App\Http\Controllers\Dashboard\Organization\DepartmentController::class, 'showManagers'])->name('department.showManagers');
                Route::get('employee/getDataForDataTable', [App\Http\Controllers\Dashboard\Organization\EmployeeController::class, 'getDataForDataTable'])->name('getDataForDataTable.employee');
                Route::resource('employee', App\Http\Controllers\Dashboard\Organization\EmployeeController::class);
                Route::get('user/getDataForDataTable', [App\Http\Controllers\Dashboard\Organization\UserController::class, 'getDataForDataTable'])->name('getDataForDataTable.user');
                Route::post('user/bulk-toggle-status', [App\Http\Controllers\Dashboard\Organization\UserController::class, 'bulkToggleStatus'])->name('user.bulkToggleStatus');
                Route::post('user/{user}/toggle-status', [App\Http\Controllers\Dashboard\Organization\UserController::class, 'toggleStatus'])->name('user.toggleStatus');
                Route::resource('user', App\Http\Controllers\Dashboard\Organization\UserController::class);
                Route::get('role/getDataForDataTable', [App\Http\Controllers\Dashboard\Organization\RoleController::class, 'getDataForDataTable'])->name('getDataForDataTable.role');
                Route::resource('role', App\Http\Controllers\Dashboard\Organization\RoleController::class);
            }
        );
        /**/
        Route::group(
            ['prefix' => 'persons'],
            function () {
                Route::get('supplier/getDataForDataTable', [App\Http\Controllers\Dashboard\Persons\SupplierController::class, 'getDataForDataTable'])->name('getDataForDataTable.supplier');
                Route::resource('supplier', App\Http\Controllers\Dashboard\Persons\SupplierController::class);
                Route::get('supplier/{supplier}/contactperson', [App\Http\Controllers\Dashboard\Persons\SupplierController::class, 'contactPersonShow'])->name('supplier.contactPersonShow');
                Route::post('supplier/{supplier}', [App\Http\Controllers\Dashboard\Persons\SupplierController::class, 'contactPersonStore'])->name('supplier.contactPersonStore');
                Route::get('client/getDataForDataTable', [App\Http\Controllers\Dashboard\Persons\ClientController::class, 'getDataForDataTable'])->name('getDataForDataTable.client');
                Route::resource('client', App\Http\Controllers\Dashboard\Persons\ClientController::class);
                Route::get('client/{client}/contactperson', [App\Http\Controllers\Dashboard\Persons\ClientController::class, 'contactPersonShow'])->name('client.contactPersonShow');
                Route::post('client/{client}', [App\Http\Controllers\Dashboard\Persons\ClientController::class, 'contactPersonStore'])->name('client.contactPersonStore');
                Route::resource('contactPerson', App\Http\Controllers\Dashboard\Persons\ContactPersonController::class);
                Route::get('client/{client}/department', [App\Http\Controllers\Dashboard\Persons\ClientController::class, 'departmentShow'])->name('client.departmentShow');
                Route::post('client/{client}/department', [App\Http\Controllers\Dashboard\Persons\ClientController::class, 'departmentStore'])->name('client.departmentStore');
                Route::patch('clientDepartment/{clientDepartment}/password', [App\Http\Controllers\Dashboard\Persons\ClientDepartmentController::class, 'updatePassword'])->name('clientDepartment.password.update');
                Route::resource('clientDepartment', App\Http\Controllers\Dashboard\Persons\ClientDepartmentController::class);
            }
        );
        /**/
        Route::group(
            ['prefix' => 'general-info'],
            function () {
                // Route::resource('item', App\Http\Controllers\Dashboard\GeneralInfo\ItemController::class);
                // Route::get('item/m/multi', [App\Http\Controllers\Dashboard\GeneralInfo\ItemController::class, 'showMulti'])->name('item.showMulti');
                Route::get('tool/getDataForDataTable', [App\Http\Controllers\Dashboard\GeneralInfo\ToolController::class, 'getDataForDataTable'])->name('getDataForDataTable.tool');
                Route::resource('tool', App\Http\Controllers\Dashboard\GeneralInfo\ToolController::class);
                Route::get('specification/getDataForDataTable', [App\Http\Controllers\Dashboard\GeneralInfo\SpecificationController::class, 'getDataForDataTable'])->name('getDataForDataTable.specification');
                Route::resource('specification', App\Http\Controllers\Dashboard\GeneralInfo\SpecificationController::class);
                Route::resource('footer-values', App\Http\Controllers\Dashboard\GeneralInfo\FooterValueController::class);
                Route::resource('inspectionLogo', App\Http\Controllers\Dashboard\GeneralInfo\InspectionLogoController::class);
                Route::get('footer-address', [App\Http\Controllers\Dashboard\GeneralInfo\FooterAddressController::class, 'edit'])->name('footer-address.edit');
                Route::post('footer-address', [App\Http\Controllers\Dashboard\GeneralInfo\FooterAddressController::class, 'update'])->name('footer-address.update');
            }
        );

        Route::group(
            ['prefix' => 'inspection'],
            function () {
                Route::get(
                    '/',
                    [InspectionCatalogController::class, 'index']
                )->name('inspection.all');
                Route::get('lifting', [InspectionCatalogController::class, 'lifting'])->name('inspection.catalog.lifting');
                Route::get('ndt', [InspectionCatalogController::class, 'ndt'])->name('inspection.catalog.ndt');
                Route::get('tubular', [InspectionCatalogController::class, 'tubular'])->name('inspection.catalog.tubular');
                Route::get('drop-object', [InspectionCatalogController::class, 'dropObject'])->name('inspection.catalog.dropObject');
                Route::get('calibration', [InspectionCatalogController::class, 'calibration'])->name('inspection.catalog.calibration');
                Route::get('overview', [InspectionCatalogController::class, 'overview'])->name('inspection.catalog.overview');
                Route::get('approval-report/{inspectionReport}/pdf', [DashboardController::class, 'inspectionApprovedPdf'])->name('inspection.approval.pdf');

                Route::group(
                    ['prefix' => 'lifting'],
                    function () {
                        // ************* Cranes ***************  //
                        Route::get('crane/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Lifting\CraneController::class, 'getDataForDataTable'])->name('getDataForDataTable.crane');
                        Route::resource('crane', App\Http\Controllers\Dashboard\Inspection\Lifting\CraneController::class);
                        Route::get('crane/{crane}/publish', [App\Http\Controllers\Dashboard\Inspection\Lifting\CraneController::class, 'publish'])->name('publish.crane');
                        /**/
                        // *********** Cranes 2 *************  //
                        Route::resource('crane2', App\Http\Controllers\Dashboard\Inspection\Lifting\Crane2Controller::class);
                        Route::get('crane2/{crane}/create', [App\Http\Controllers\Dashboard\Inspection\Lifting\Crane2Controller::class, 'create'])->name('crane2_create.crane');
                        Route::post('crane2/{crane}/store', [App\Http\Controllers\Dashboard\Inspection\Lifting\Crane2Controller::class, 'store'])->name('crane2_create.store');
                        Route::get('crane2/{crane}/edit', [App\Http\Controllers\Dashboard\Inspection\Lifting\Crane2Controller::class, 'edit'])->name('crane2_edit.crane');
                        Route::put('crane2/{crane}/update', [App\Http\Controllers\Dashboard\Inspection\Lifting\Crane2Controller::class, 'update'])->name('crane2_edit.update');
                        /**/
                        // ********* Overhead Cranes ********  //
                        Route::get('overheadCrane/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Lifting\OverheadCraneController::class, 'getDataForDataTable'])->name('getDataForDataTable.overheadCrane');
                        Route::resource('overheadCrane', App\Http\Controllers\Dashboard\Inspection\Lifting\OverheadCraneController::class);
                        Route::get('overheadCrane/{overheadCrane}/publish', [App\Http\Controllers\Dashboard\Inspection\Lifting\OverheadCraneController::class, 'publish'])->name('publish.overheadCrane');
                        /**/
                        // ******** Overhead Cranes 2 *******  //
                        Route::resource('overheadCrane2', App\Http\Controllers\Dashboard\Inspection\Lifting\OverheadCrane2Controller::class);
                        Route::get('overheadCrane2/{overheadCrane}/create', [App\Http\Controllers\Dashboard\Inspection\Lifting\OverheadCrane2Controller::class, 'create'])->name('overhead_crane2_create.overhead_crane');
                        Route::post('overheadCrane2/{overheadCrane}/store', [App\Http\Controllers\Dashboard\Inspection\Lifting\OverheadCrane2Controller::class, 'store'])->name('overhead_crane2_create.store');
                        Route::get('overheadCrane2/{overheadCrane}/edit', [App\Http\Controllers\Dashboard\Inspection\Lifting\OverheadCrane2Controller::class, 'edit'])->name('overhead_crane2_edit.overhead_crane');
                        Route::put('overheadCrane2/{overheadCrane}/update', [App\Http\Controllers\Dashboard\Inspection\Lifting\OverheadCrane2Controller::class, 'update'])->name('overhead_crane2_edit.update');
                        /**/
                        // *********** Forklift *************  //
                        Route::get('forklift/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Lifting\ForkliftController::class, 'getDataForDataTable'])->name('getDataForDataTable.forklift');
                        Route::resource('forklift', App\Http\Controllers\Dashboard\Inspection\Lifting\ForkliftController::class);
                        Route::get('forklift/{forklift}/publish', [App\Http\Controllers\Dashboard\Inspection\Lifting\ForkliftController::class, 'publish'])->name('publish.forklift');
                        /**/
                        // *********** Forklift 2 *************  //
                        Route::resource('forklift2', App\Http\Controllers\Dashboard\Inspection\Lifting\Forklift2Controller::class);
                        Route::get('forklift2/{forklift}/create', [App\Http\Controllers\Dashboard\Inspection\Lifting\Forklift2Controller::class, 'create'])->name('forklift2_create.forklift');
                        Route::post('forklift2/{forklift}/store', [App\Http\Controllers\Dashboard\Inspection\Lifting\Forklift2Controller::class, 'store'])->name('forklift2_create.store');
                        Route::get('forklift2/{forklift}/edit', [App\Http\Controllers\Dashboard\Inspection\Lifting\Forklift2Controller::class, 'edit'])->name('forklift2_edit.forklift');
                        Route::put('forklift2/{forklift}/update', [App\Http\Controllers\Dashboard\Inspection\Lifting\Forklift2Controller::class, 'update'])->name('forklift2_edit.update');
                        /**/
                        // *********** Through Examination *************  //
                        Route::get('/throughExamination/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Lifting\ThroughExaminationController::class, 'getDataForDataTable'])->name('getDataForDataTable.throughExamination');
                                Route::resource('throughExamination', App\Http\Controllers\Dashboard\Inspection\Lifting\ThroughExaminationController::class);
                                Route::get('throughExamination/{throughExamination}/publish', [App\Http\Controllers\Dashboard\Inspection\Lifting\ThroughExaminationController::class, 'publish'])->name('publish.throughExamination');
                        /**/
                        // *********** Defect *************  //
                        Route::get('defect/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Lifting\DefectController::class, 'getDataForDataTable'])->name('getDataForDataTable.defect');
                        Route::resource('defect', App\Http\Controllers\Dashboard\Inspection\Lifting\DefectController::class);
                        Route::get('defect/{defect}/publish', [App\Http\Controllers\Dashboard\Inspection\Lifting\DefectController::class, 'publish'])->name('publish.defect');
                        /**/
                        Route::get('lregister/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Lifting\LregisterController::class, 'getDataForDataTable'])->name('getDataForDataTable.lregister');
                        Route::get('lregister/{lregister}/getForShow', [App\Http\Controllers\Dashboard\Inspection\Lifting\LregisterController::class, 'getForShow'])->name('getDataForDataTable.getForShow');
                        Route::get('lregister/{lregister}/export-excel', [App\Http\Controllers\Dashboard\Inspection\Lifting\LregisterController::class, 'exportExcel'])->name('lregister.exportExcel');
                        Route::resource('lregister', App\Http\Controllers\Dashboard\Inspection\Lifting\LregisterController::class);
                    }
                );

                Route::group(
                    ['prefix' => 'ndt'],
                    function () {
                        /**/
                        // *************** 01 MPIPT ************** //
                        Route::get('mpipt/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Ndt\MpiptController::class, 'getDataForDataTable'])->name('getDataForDataTable.mpipt');
                        Route::resource('mpipt', App\Http\Controllers\Dashboard\Inspection\Ndt\MpiptController::class);
                        Route::get('mpipt/{mpipt}/publish', [App\Http\Controllers\Dashboard\Inspection\Ndt\MpiptController::class, 'publish'])->name('publish.mpipt');
                        /**/
                        // 02 Visual
                        Route::get('visual/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Ndt\VisualController::class, 'getDataForDataTable'])->name('getDataForDataTable.visual');
                        Route::resource('visual', App\Http\Controllers\Dashboard\Inspection\Ndt\VisualController::class);
                        Route::get('visual/{visual}/publish', [App\Http\Controllers\Dashboard\Inspection\Ndt\VisualController::class, 'publish'])->name('publish.visual');
                        /**/
                        // 03 Ultrasonic
                              Route::get('ultrasonic/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Ndt\UltrasonicController::class, 'getDataForDataTable'])->name('getDataForDataTable.ultrasonic');
                              Route::resource('ultrasonic', App\Http\Controllers\Dashboard\Inspection\Ndt\UltrasonicController::class);
                              Route::get('ultrasonic/{ultrasonic}/publish', [App\Http\Controllers\Dashboard\Inspection\Ndt\UltrasonicController::class, 'publish'])->name('publish.ultrasonic');
                        /**/
                        // 04 Summary
                        Route::get('summary/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Ndt\SummaryController::class, 'getDataForDataTable'])->name('getDataForDataTable.summary');
                        Route::resource('summary', App\Http\Controllers\Dashboard\Inspection\Ndt\SummaryController::class);
                        Route::get('summary/{summary}/publish', [App\Http\Controllers\Dashboard\Inspection\Ndt\SummaryController::class, 'publish'])->name('publish.summary');
                        /**/
                        // 05 Attached
                        Route::get('attached/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Ndt\AttachedController::class, 'getDataForDataTable'])->name('getDataForDataTable.attached');
                        Route::resource('attached', App\Http\Controllers\Dashboard\Inspection\Ndt\AttachedController::class);
                        Route::get('attached/{attached}/publish', [App\Http\Controllers\Dashboard\Inspection\Ndt\AttachedController::class, 'publish'])->name('publish.attached');
                        /**/
                        // 08 Witness Hydro
                        Route::get('witnessHydro/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Ndt\WitnessHydroController::class, 'getDataForDataTable'])->name('getDataForDataTable.witnessHydro');
                        Route::resource('witnessHydro', App\Http\Controllers\Dashboard\Inspection\Ndt\WitnessHydroController::class);
                        Route::get('witnessHydro/{witnessHydro}/publish', [App\Http\Controllers\Dashboard\Inspection\Ndt\WitnessHydroController::class, 'publish'])->name('publish.witnessHydro');
                        /**/
                        Route::get('highPressure/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Ndt\HighPressureController::class, 'getDataForDataTable'])->name('getDataForDataTable.highPressure');
                        Route::resource('highPressure', App\Http\Controllers\Dashboard\Inspection\Ndt\HighPressureController::class);
                        Route::get('highPressure/{highPressure}/publish', [App\Http\Controllers\Dashboard\Inspection\Ndt\HighPressureController::class, 'publish'])->name('publish.highPressure');
                        /**/
                        Route::get('high2Pressure/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Ndt\High2PressureController::class, 'getDataForDataTable'])->name('getDataForDataTable.high2Pressure');
                        Route::resource('high2Pressure', App\Http\Controllers\Dashboard\Inspection\Ndt\High2PressureController::class);
                        Route::get('high2Pressure/{high2Pressure}/publish', [App\Http\Controllers\Dashboard\Inspection\Ndt\High2PressureController::class, 'publish'])->name('publish.high2Pressure');
                        /**/
                        Route::get('high3Pressure/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Ndt\High3PressureController::class, 'getDataForDataTable'])->name('getDataForDataTable.high3Pressure');
                        Route::resource('high3Pressure', App\Http\Controllers\Dashboard\Inspection\Ndt\High3PressureController::class);
                        Route::get('high3Pressure/{high3Pressure}/publish', [App\Http\Controllers\Dashboard\Inspection\Ndt\High3PressureController::class, 'publish'])->name('publish.high3Pressure');
                        /**/
                              // 09 Treating Iron
                        Route::get('treatingIron/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Ndt\TreatingIronController::class, 'getDataForDataTable'])->name('getDataForDataTable.treatingIron');
                        Route::resource('treatingIron', App\Http\Controllers\Dashboard\Inspection\Ndt\TreatingIronController::class);
                        Route::get('treatingIron/{treatingIron}/publish', [App\Http\Controllers\Dashboard\Inspection\Ndt\TreatingIronController::class, 'publish'])->name('publish.treatingIron');
                        /**/
                            // new 11 Drawing Report
                        Route::get('drawingInspection/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Ndt\DrawingInspectionController::class, 'getDataForDataTable'])->name('getDataForDataTable.drawingInspection');
                        Route::resource('drawingInspection', \App\Http\Controllers\Dashboard\Inspection\Ndt\DrawingInspectionController::class);
                        Route::get('drawingInspection/{drawingInspection}/publish', [App\Http\Controllers\Dashboard\Inspection\Ndt\DrawingInspectionController::class, 'publish'])->name('publish.drawingInspection');
                        Route::patch('drawingInspection/{drawingInspection}/publish', [App\Http\Controllers\Dashboard\Inspection\Ndt\DrawingInspectionController::class, 'publishSubmit'])->name('drawingInspection.publishSubmit');

						/**/
						// new 12 NDT Register
						Route::get('nregister/getDataForDataTable', [NregisterController::class, 'getDataForDataTable'])->name('getDataForDataTable.nregister');
						Route::get('nregister/{nregister}/export-excel', [NregisterController::class, 'exportExcel'])->name('nregister.exportExcel');
						Route::resource('nregister', NregisterController::class);
						Route::get('nregister/{nregister}/publish', [NregisterController::class, 'publish'])->name('publish.nregister');
						Route::patch('nregister/{nregister}/publish', [NregisterController::class, 'publishSubmit'])->name('nregister.publishSubmit');

                    }
                );

                Route::group(['prefix' => 'tubular'],function (){
                    Route::get('pipesSummaryReports/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Tubular\PipesSummaryReportController::class, 'getDataForDataTable'])->name('getDataForDataTable.pipesSummaryReports');
                    Route::resource('pipesSummaryReports', \App\Http\Controllers\Dashboard\Inspection\Tubular\PipesSummaryReportController::class);
                    Route::get('pipesSummaryReports/{pipesSummaryReport}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\PipesSummaryReportController::class, 'publish'])->name('publish.pipesSummaryReports');
                    Route::patch('pipesSummaryReports/{pipesSummaryReport}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\PipesSummaryReportController::class, 'publishSubmit'])->name('pipesSummaryReports.publishSubmit');

                    Route::get('drillPipe/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Tubular\DrillPipeController::class, 'getDataForDataTable'])->name('getDataForDataTable.drillPipe');
                    Route::resource('drillPipe', \App\Http\Controllers\Dashboard\Inspection\Tubular\DrillPipeController::class);
                    Route::get('drillPipe/{drillPipe}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\DrillPipeController::class, 'publish'])->name('publish.drillPipe');
                    Route::patch('drillPipe/{drillPipe}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\DrillPipeController::class, 'publishSubmit'])->name('drillPipe.publishSubmit');

                    Route::get('heavyWeightPipe/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Tubular\HeavyWeightPipeController::class, 'getDataForDataTable'])->name('getDataForDataTable.heavyWeightPipe');
                    Route::resource('heavyWeightPipe', \App\Http\Controllers\Dashboard\Inspection\Tubular\HeavyWeightPipeController::class);
                    Route::get('heavyWeightPipe/{heavyWeightPipe}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\HeavyWeightPipeController::class, 'publish'])->name('publish.heavyWeightPipe');
                    Route::patch('heavyWeightPipe/{heavyWeightPipe}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\HeavyWeightPipeController::class, 'publishSubmit'])->name('heavyWeightPipe.publishSubmit');

                    Route::get('drillCollar/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Tubular\DrillCollarController::class, 'getDataForDataTable'])->name('getDataForDataTable.drillCollar');
                    Route::resource('drillCollar', \App\Http\Controllers\Dashboard\Inspection\Tubular\DrillCollarController::class);
                    Route::get('drillCollar/{drillCollar}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\DrillCollarController::class, 'publish'])->name('publish.drillCollar');
                    Route::patch('drillCollar/{drillCollar}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\DrillCollarController::class, 'publishSubmit'])->name('drillCollar.publishSubmit');

                    Route::get('subsDimensional/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Tubular\SubsDimensionalController::class, 'getDataForDataTable'])->name('getDataForDataTable.subsDimensional');
                    Route::resource('subsDimensional', \App\Http\Controllers\Dashboard\Inspection\Tubular\SubsDimensionalController::class);
                    Route::get('subsDimensional/{subsDimensional}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\SubsDimensionalController::class, 'publish'])->name('publish.subsDimensional');
                    Route::patch('subsDimensional/{subsDimensional}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\SubsDimensionalController::class, 'publishSubmit'])->name('subsDimensional.publishSubmit');

                    Route::get('tubingString/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Tubular\TubingStringController::class, 'getDataForDataTable'])->name('getDataForDataTable.tubingString');
                    Route::resource('tubingString', \App\Http\Controllers\Dashboard\Inspection\Tubular\TubingStringController::class);
                    Route::get('tubingString/{tubingString}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\TubingStringController::class, 'publish'])->name('publish.tubingString');
                    Route::patch('tubingString/{tubingString}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\TubingStringController::class, 'publishSubmit'])->name('tubingString.publishSubmit');

                    Route::get('stabilizerInspection/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Tubular\StabilizerInspectionController::class, 'getDataForDataTable'])->name('getDataForDataTable.stabilizerInspection');
                    Route::resource('stabilizerInspection', \App\Http\Controllers\Dashboard\Inspection\Tubular\StabilizerInspectionController::class);
                    Route::get('stabilizerInspection/{stabilizerInspection}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\StabilizerInspectionController::class, 'publish'])->name('publish.stabilizerInspection');
                    Route::patch('stabilizerInspection/{stabilizerInspection}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\StabilizerInspectionController::class, 'publishSubmit'])->name('stabilizerInspection.publishSubmit');

                    Route::get('reamerInspection/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Tubular\ReamerInspectionController::class, 'getDataForDataTable'])->name('getDataForDataTable.reamerInspection');
                    Route::resource('reamerInspection', \App\Http\Controllers\Dashboard\Inspection\Tubular\ReamerInspectionController::class);
                    Route::get('reamerInspection/{reamerInspection}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\ReamerInspectionController::class, 'publish'])->name('publish.reamerInspection');
                    Route::patch('reamerInspection/{reamerInspection}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\ReamerInspectionController::class, 'publishSubmit'])->name('reamerInspection.publishSubmit');

                    Route::get('linkInspection/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\Tubular\LinkInspectionController::class, 'getDataForDataTable'])->name('getDataForDataTable.linkInspection');
                    Route::resource('linkInspection', \App\Http\Controllers\Dashboard\Inspection\Tubular\LinkInspectionController::class);
                    Route::get('linkInspection/{linkInspection}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\LinkInspectionController::class, 'publish'])->name('publish.linkInspection');
                    Route::patch('linkInspection/{linkInspection}/publish', [App\Http\Controllers\Dashboard\Inspection\Tubular\LinkInspectionController::class, 'publishSubmit'])->name('linkInspection.publishSubmit');

                    Route::get('pbl/getDataForDataTable', [PblController::class, 'getDataForDataTable'])->name('getDataForDataTable.pbl');
                    Route::resource('pbl', PblController::class);
                    Route::get('pbl/{pbl}/publish', [PblController::class, 'publish'])->name('publish.pbl');
                    Route::patch('pbl/{pbl}/publish', [PblController::class, 'publishSubmit'])->name('pbl.publishSubmit');

                    Route::get('tubingCasing/getDataForDataTable', [TubingCasingController::class, 'getDataForDataTable'])->name('getDataForDataTable.tubingCasing');
                    Route::resource('tubingCasing', TubingCasingController::class);
                    Route::get('tubingCasing/{tubingCasing}/publish', [TubingCasingController::class, 'publish'])->name('publish.tubingCasing');
                    Route::patch('tubingCasing/{tubingCasing}/publish', [TubingCasingController::class, 'publishSubmit'])->name('tubingCasing.publishSubmit');

                });

				Route::group([],function (){

                    Route::get('dropObject/getDataForDataTable', [App\Http\Controllers\Dashboard\Inspection\DropObject\DropObjectController::class, 'getDataForDataTable'])->name('getDataForDataTable.dropObject');
                    Route::resource('dropObject', \App\Http\Controllers\Dashboard\Inspection\DropObject\DropObjectController::class);
                    Route::get('dropObject/{dropObject}/publish', [App\Http\Controllers\Dashboard\Inspection\DropObject\DropObjectController::class, 'publish'])->name('publish.dropObject');
                    Route::patch('dropObject/{dropObject}/publish', [App\Http\Controllers\Dashboard\Inspection\DropObject\DropObjectController::class, 'publishSubmit'])->name('dropObject.publishSubmit');

				});

				Route::group(['prefix' => 'calibration'],function (){

                    Route::get('calibrationPressureGauge/getDataForDataTable', [CalibrationPressureGaugeController::class, 'getDataForDataTable'])->name('getDataForDataTable.calibrationPressureGauge');
                    Route::resource('calibrationPressureGauge', CalibrationPressureGaugeController::class);
                    Route::get('calibrationPressureGauge/{calibrationPressureGauge}/publish', [CalibrationPressureGaugeController::class, 'publish'])->name('publish.calibrationPressureGauge');
                    Route::patch('calibrationPressureGauge/{calibrationPressureGauge}/publish', [CalibrationPressureGaugeController::class, 'publishSubmit'])->name('calibrationPressureGauge.publishSubmit');
				
                    Route::get('calibrationPressureTest/getDataForDataTable', [CalibrationPressureTestController::class, 'getDataForDataTable'])->name('getDataForDataTable.calibrationPressureTest');
                    Route::resource('calibrationPressureTest', CalibrationPressureTestController::class);
                    Route::get('calibrationPressureTest/{calibrationPressureTest}/publish', [CalibrationPressureTestController::class, 'publish'])->name('publish.calibrationPressureTest');
                    Route::patch('calibrationPressureTest/{calibrationPressureTest}/publish', [CalibrationPressureTestController::class, 'publishSubmit'])->name('calibrationPressureTest.publishSubmit');
							
                    Route::get('calibrationYoke/getDataForDataTable', [CalibrationYokeController::class, 'getDataForDataTable'])->name('getDataForDataTable.calibrationYoke');
                    Route::resource('calibrationYoke', CalibrationYokeController::class);
                    Route::get('calibrationYoke/{calibrationYoke}/publish', [CalibrationYokeController::class, 'publish'])->name('publish.calibrationYoke');
                    Route::patch('calibrationYoke/{calibrationYoke}/publish', [CalibrationYokeController::class, 'publishSubmit'])->name('calibrationYoke.publishSubmit');
								
                    Route::get('calibrationTorque/getDataForDataTable', [CalibrationTorqueController::class, 'getDataForDataTable'])->name('getDataForDataTable.calibrationTorque');
                    Route::resource('calibrationTorque', CalibrationTorqueController::class);
                    Route::get('calibrationTorque/{calibrationTorque}/publish', [CalibrationTorqueController::class, 'publish'])->name('publish.calibrationTorque');
                    Route::patch('calibrationTorque/{calibrationTorque}/publish', [CalibrationTorqueController::class, 'publishSubmit'])->name('calibrationTorque.publishSubmit');
				
				});
            }
        );
    }
);

// ************* Client Area *************  //
Route::get('customer/login', [App\Http\Controllers\Auth\Customer\LoginController::class, 'showCustomerLogin'])->name('customer.login');
Route::post('customer/login', [App\Http\Controllers\Auth\Customer\LoginController::class, 'attemptLogin'])->name('customer.auth');
Route::post('customer/logout', [App\Http\Controllers\Auth\Customer\LoginController::class, 'logout'])->name('customer.logout');
Route::group(
    [
        'prefix'     => 'customer',
        'middleware' => ['auth:customer'],
    ],
    function () {
        Route::get('/', [App\Http\Controllers\Customer\PortalController::class, 'customerOverview'])->name('customer.overview');
        Route::get('/reports', [App\Http\Controllers\Customer\PortalController::class, 'customerReports'])->name('customer.reports');
        Route::get('/download-files', [App\Http\Controllers\FileController::class, 'downloadFiles'])->name('client.downloadFiles');

    }
);
Route::group(
    [
        'prefix' => 'department',
        'middleware' => ['auth:clientDepartments'],
    ],
    function () {
        Route::get('/', [App\Http\Controllers\Customer\PortalController::class, 'departmentOverview'])->name('department.overview');
        Route::get('/reports', [App\Http\Controllers\Customer\PortalController::class, 'departmentReports'])->name('department.reports');
        Route::get('/download-files', [App\Http\Controllers\FileController::class, 'downloadFiles'])->name('department.downloadFiles');

    }
);
// ************************************  //

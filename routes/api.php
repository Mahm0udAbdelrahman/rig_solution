<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::group(
    [
        'prefix'     => 'v1/dashboard',
        'middleware' => 'auth:api',
    ],
    function () {
        // Route::group(
        // ['prefix' => 'work-flow'],
        // function () {
        // Route::apiResource('jobrequestapi', App\Http\Controllers\Api\Dashboard\WorkFlow\JobRequestController::class);
        // Route::apiResource('qutationapi', App\Http\Controllers\Api\Dashboard\WorkFlow\QutationController::class);
        // Route::apiResource('packingslipapi', App\Http\Controllers\Api\Dashboard\WorkFlow\PackingSlipController::class);
        // Route::apiResource('serviceticketapi', App\Http\Controllers\Api\Dashboard\WorkFlow\ServiceTicketController::class);
        // Route::apiResource('invoiceapi', App\Http\Controllers\Api\Dashboard\WorkFlow\InvoiceController::class);
        // }
        // );
        // Route::group(
        // ['prefix' => 'inspection'],
        // function () {
        // Route::post('inspectionapi/{report}/{table}', [App\Http\Controllers\Api\Dashboard\Inspection\InspectionReportController::class, 'store'])->name('inspectionapireport.store');
        // }
        // );
        // Route::group(
        // ['prefix' => 'organization'],
        // function () {
        // Route::apiResource('departmentapi', App\Http\Controllers\Api\Dashboard\Organization\DepartmentController::class);
        // }
        // );
        Route::group(
            ['prefix' => 'persons'],
            function () {
                Route::apiResource('clientapi', App\Http\Controllers\Api\Dashboard\Persons\ClientController::class);
                Route::apiResource('supplierapi', App\Http\Controllers\Api\Dashboard\Persons\SupplierController::class);
            }
        );
        Route::group(
            ['prefix' => 'general-info'],
            function () {
                Route::apiResource('specificationapi', App\Http\Controllers\Api\Dashboard\GeneralInfo\SpecificationController::class);
                Route::apiResource('toolapi', App\Http\Controllers\Api\Dashboard\GeneralInfo\ToolController::class);
            }
        );
    }
);


// Route::group(['prefix' => 'to-local'], function(){
// Route::get('{table_name}', [App\Http\Controllers\CustomController::class, 'prepend_data_to_local'])->name('table.prepend_data_to_local');
// });

<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Inspection\Calibration\CalibrationPressureGauge;
use App\Models\Inspection\Calibration\CalibrationPressureTest;
use App\Models\Inspection\Calibration\CalibrationTorque;
use App\Models\Inspection\Calibration\CalibrationYoke;
use App\Models\Inspection\DropObject\DropObject;
use App\Models\Inspection\Tubular\Pbl;
use App\Models\Inspection\Tubular\TubingCasing;
use App\Models\WorkFlow\Accountant;
use App\Models\WorkFlow\Bank;
use App\Models\WorkFlow\Expense;
use App\Models\WorkFlow\FileManager;
use App\Models\WorkFlow\Inventory;
use App\Models\WorkFlow\MailCenter;
use App\Models\WorkFlow\Payment;
use App\Policies\Inspection\Calibration\CalibrationPressureGaugePolicy;
use App\Policies\Inspection\Calibration\CalibrationPressureTestPolicy;
use App\Policies\Inspection\Calibration\CalibrationTorquePolicy;
use App\Policies\Inspection\Calibration\CalibrationYokePolicy;
use App\Policies\WorkFlow\AccountantPolicy;
use App\Policies\WorkFlow\BankPolicy;
use App\Policies\WorkFlow\ExpensePolicy;
use App\Policies\WorkFlow\FileManagerPolicy;
use App\Policies\WorkFlow\InventoryPolicy;
use App\Policies\WorkFlow\MailCenterPolicy;
use App\Policies\WorkFlow\PaymentPolicy;
use App\Policies\Inspection\Tubular\PblPolicy;
use App\Policies\Inspection\Tubular\TubingCasingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\GeneralInfo\Item' => 'App\Policies\GeneralInfo\ItemPolicy',
        'App\Models\GeneralInfo\Specification' => 'App\Policies\GeneralInfo\SpecificationPolicy',
        'App\Models\GeneralInfo\Tool' => 'App\Policies\GeneralInfo\ToolPolicy',

        'App\Models\Organization\Employee' => 'App\Policies\Organization\EmployeePolicy',
        'App\Models\Organization\Department' => 'App\Policies\Organization\DepartmentPolicy',
        'App\Models\Organization\Role' => 'App\Policies\Organization\RolePolicy',

        'App\Models\Inspection\Lifting\Crane' => 'App\Policies\Inspection\Lifting\CranePolicy',
        'App\Models\Inspection\Lifting\Defect' => 'App\Policies\Inspection\Lifting\DefectPolicy',
        'App\Models\Inspection\Lifting\Forklift' => 'App\Policies\Inspection\Lifting\ForkliftPolicy',
        'App\Models\Inspection\Lifting\Lregister' => 'App\Policies\Inspection\Lifting\LregisterPolicy',
        'App\Models\Inspection\Lifting\OverheadCrane' => 'App\Policies\Inspection\Lifting\OverheadCranePolicy',
        'App\Models\Inspection\Lifting\ThroughExamination' => 'App\Policies\Inspection\Lifting\ThroughExaminationPolicy',
        //
        'App\Models\Inspection\Ndt\Mpipt' => 'App\Policies\Inspection\Ndt\MpiptPolicy',
        'App\Models\Inspection\Ndt\Visual' => 'App\Policies\Inspection\Ndt\VisualPolicy',
        'App\Models\Inspection\Ndt\Summary' => 'App\Policies\Inspection\Ndt\SummaryPolicy',
        'App\Models\Inspection\Ndt\Attached' => 'App\Policies\Inspection\Ndt\AttachedPolicy',
        'App\Models\Inspection\Ndt\Ultrasonic' => 'App\Policies\Inspection\Ndt\UltrasonicPolicy',
        'App\Models\Inspection\Ndt\WitnessHydro' => 'App\Policies\Inspection\Ndt\WitnessHydroPolicy',
        'App\Models\Inspection\Ndt\TreatingIron' => 'App\Policies\Inspection\Ndt\TreatingIronPolicy',
        'App\Models\Inspection\Ndt\High2Pressure' => 'App\Policies\Inspection\Ndt\High2PressurePolicy',
        'App\Models\Inspection\Ndt\High3Pressure' => 'App\Policies\Inspection\Ndt\High3PressurePolicy',
        'App\Models\Inspection\Ndt\HighPressure' => 'App\Policies\Inspection\Ndt\HighPressurePolicy',
        'App\Models\Inspection\Ndt\Nregister' => 'App\Policies\Inspection\Ndt\NregisterPolicy',
        'App\Models\Inspection\Ndt\DrawingInspection' => 'App\Policies\Inspection\Ndt\DrawingInspectionPolicy',
        //
        'App\Models\Inspection\Tubular\PipesSummaryReport' => 'App\Policies\Inspection\Tubular\PipesSummaryReportPolicy',
        'App\Models\Inspection\Tubular\DrillPipe' => 'App\Policies\Inspection\Tubular\DrillPipePolicy',
        'App\Models\Inspection\Tubular\HeavyWeightPipe' => 'App\Policies\Inspection\Tubular\HeavyWeightPipePolicy',
        'App\Models\Inspection\Tubular\DrillCollar' => 'App\Policies\Inspection\Tubular\DrillCollarPolicy',
        'App\Models\Inspection\Tubular\SubsDimensional' => 'App\Policies\Inspection\Tubular\SubsDimensionalPolicy',
        'App\Models\Inspection\Tubular\TubingString' => 'App\Policies\Inspection\Tubular\TubingStringPolicy',
        'App\Models\Inspection\Tubular\StabilizerInspection' => 'App\Policies\Inspection\Tubular\StabilizerInspectionPolicy',
        'App\Models\Inspection\Tubular\ReamerInspection' => 'App\Policies\Inspection\Tubular\ReamerInspectionPolicy',
        'App\Models\Inspection\Tubular\LinkInspection' => 'App\Policies\Inspection\Tubular\LinkInspectionPolicy',
				TubingCasing::class => TubingCasingPolicy::class,
		Pbl::class => PblPolicy::class,
        //
		DropObject::class => 'App\Policies\Inspection\DropObject\DropObjectPolicy',
		//
		CalibrationPressureGauge::class => CalibrationPressureGaugePolicy::class,
		CalibrationPressureTest::class => CalibrationPressureTestPolicy::class,
		CalibrationTorque::class => CalibrationTorquePolicy::class,
		CalibrationYoke::class => CalibrationYokePolicy::class,
		//
        'App\Models\WorkFlow\JobRequest' => 'App\Policies\WorkFlow\JobRequestPolicy',
        'App\Models\WorkFlow\Qutation' => 'App\Policies\WorkFlow\QutationPolicy',
        'App\Models\WorkFlow\PackingSlip' => 'App\Policies\WorkFlow\PackingSlipPolicy',
        'App\Models\WorkFlow\ServiceTicket' => 'App\Policies\WorkFlow\ServiceTicketPolicy',
        'App\Models\WorkFlow\Invoice' => 'App\Policies\WorkFlow\InvoicePolicy',
        Payment::class => PaymentPolicy::class,
        Inventory::class => InventoryPolicy::class,
        Accountant::class => AccountantPolicy::class,
        Bank::class => BankPolicy::class,
        Expense::class => ExpensePolicy::class,
        FileManager::class => FileManagerPolicy::class,
        MailCenter::class => MailCenterPolicy::class,
        //
        'App\Models\Persons\Client' => 'App\Policies\Persons\ClientPolicy',
        'App\Models\Persons\Supplier' => 'App\Policies\Persons\SupplierPolicy',

        'App\Models\User' => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}

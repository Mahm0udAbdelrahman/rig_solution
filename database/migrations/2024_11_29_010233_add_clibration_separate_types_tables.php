<?php

use App\Models\GeneralInfo\FooterValue;
use App\Models\Inspection\Calibration\CalibrationPressureGauge;
use App\Models\Inspection\Calibration\CalibrationPressureTest;
use App\Models\Inspection\Calibration\CalibrationTorque;
use App\Models\Inspection\Calibration\CalibrationYoke;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		/* Delete old calibration table */
        Schema::dropIfExists('calibrations');
        FooterValue::query()
			->where('related_inspection', '=','App\Models\Inspection\Calibration\Calibration')
            ->delete();
		/********************************/
 

        $modelNames = [
            'calibration_pressure_gauges',
            'calibration_pressure_tests',
            'calibration_torques',
            'calibration_yokes'
        ];

        foreach ($modelNames as $modelName) {
            Schema::create($modelName, function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('job_request_id');
                $table->string('code');
                $table->integer('sync')->default(false);
                $table->integer('updated')->nullable();
                $table->unsignedBigInteger('user_id_approved')->nullable()->default(null);
                // calibration details
                $table->date('receipt_date')->nullable();
                $table->date('calibration_date')->nullable();
                $table->date('due_date')->nullable();
                $table->date('issue_date')->nullable();
                $table->string('contact_name')->nullable();
                $table->string('contact_info')->nullable();
                // environment conditions
                $table->string('temperature')->nullable();
                $table->string('relative_humidity')->nullable();
                // unit under calibration
                $table->string('equipment_description')->nullable();
                $table->string('code_number')->nullable();
                $table->string('serial_number')->nullable();
                $table->string('manufacturer')->nullable();
                $table->string('model')->nullable();
                $table->string('range')->nullable();
                $table->string('type')->nullable();
                $table->string('resolution')->nullable();
                $table->string('read_out_unit')->nullable();
                $table->string('max_permissible_error')->nullable();
                // reference equipment used
                $table->string('device_description')->nullable();
                $table->string('device_serial_number')->nullable();
                $table->string('device_manufacturer')->nullable();
                $table->string('device_model')->nullable();
                $table->string('device_range')->nullable();
                $table->string('device_resolution')->nullable();
                $table->date('device_calibration_date')->nullable();
                $table->string('certificate_number')->nullable();
                // calibration method and standard used
                $table->string('calibration_method')->nullable();
                $table->string('reported_unit')->nullable();
                // calibration test results
                $table->string('accuracy')->nullable();
                $table->string('uncertainty')->nullable();
                $table->text('calibration_details')->nullable();
                // statement of calibration
                $table->text('statement')->nullable();
                //
                $table->timestamps();
            });
        }

        // Insert footer values for each model
        DB::table('footer_values')->insert([
            [
                'related_inspection' => \App\Models\Inspection\Calibration\CalibrationPressureGauge::class,
                'name' => 'Calibration Certificate (Pressure Gauge)',
                'form_no' => 'RS-RF-F18',
                'issue_no' => '04',
                'issue_date' => '1-Mar-2024',
                'revision_no' => '00',
                'revision_date' => '1-Mar-2024',
            ],
            [
                'related_inspection' => \App\Models\Inspection\Calibration\CalibrationPressureTest::class,
                'name' => 'Calibration Certificate (Pressure Test)',
                'form_no' => 'RS-RF-F18',
                'issue_no' => '04',
                'issue_date' => '1-Mar-2024',
                'revision_no' => '00',
                'revision_date' => '1-Mar-2024',
            ],
            [
                'related_inspection' => \App\Models\Inspection\Calibration\CalibrationTorque::class,
                'name' => 'Calibration Certificate (Torque)',
                'form_no' => 'RS-RF-F18',
                'issue_no' => '04',
                'issue_date' => '1-Mar-2024',
                'revision_no' => '00',
                'revision_date' => '1-Mar-2024',
            ],
            [
                'related_inspection' => \App\Models\Inspection\Calibration\CalibrationYoke::class,
                'name' => 'Calibration Certificate (Yoke)',
                'form_no' => 'RS-RF-F18',
                'issue_no' => '04',
                'issue_date' => '1-Mar-2024',
                'revision_no' => '00',
                'revision_date' => '1-Mar-2024',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $modelNames = [
            'calibration_pressure_gauges',
            'calibration_pressure_tests',
            'calibration_torques',
            'calibration_yokes'
        ];

        foreach ($modelNames as $modelName) {
            Schema::dropIfExists($modelName);
        }

        // Delete footer values for each model
        $inspectionClasses = [
            CalibrationPressureGauge::class,
            CalibrationPressureTest::class,
            CalibrationTorque::class,
            CalibrationYoke::class,
        ];

        FooterValue::query()
            ->whereIn('related_inspection', $inspectionClasses)
            ->delete();
    }
};


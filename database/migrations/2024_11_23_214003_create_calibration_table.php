<?php

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
        Schema::create('calibrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_request_id');
            $table->string('code');
            $table->integer('sync')->default(false);
            $table->integer('updated')->nullable();
            $table->unsignedBigInteger('user_id_approved')->nullable()->default(null);
            $table->string('calibration_report_type');
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
			// unite under calibration
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


        /****Insert Drill Pipe footer values*****/
        DB::table('footer_values')->insert([
            'related_inspection' => \App\Models\Inspection\Calibration\Calibration::class,
            'name' => 'Drop Object Inspection Report',
            'form_no' => 'RS-RF-F18',
            'issue_no' => '04',
            'issue_date' => '1-Mar-2024',
            'revision_no' => '00',
            'revision_date' => '1-Mar-2024',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calibrations');

        /****Delete Drill Pipe footer values*****/
        \App\Models\GeneralInfo\FooterValue::query()
            ->where('related_inspection', '=','App\Models\Inspection\Calibration\Calibration')
            ->delete();
    }
};

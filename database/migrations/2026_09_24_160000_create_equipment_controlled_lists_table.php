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
        Schema::create('equipment_controlled_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('equipment_description')->nullable();
            $table->string('internal_code')->nullable()->index();
            $table->string('manufacturer')->nullable();
            $table->string('model_type')->nullable();
            $table->string('capacity_range')->nullable();
            $table->string('serial_number')->nullable()->index();
            $table->date('date_into_service')->nullable();
            $table->string('interval')->nullable(); // Maintenance / Calibration Interval (e.g. Pre-Use, Pre-Use/Annual)
            $table->date('calibration_date')->nullable();
            $table->date('calibration_due_date')->nullable();
            $table->string('calibrated_by')->nullable();
            $table->string('recalibration_alarm')->nullable(); // e.g. Calibrated, Re-Calibrate, etc.
            $table->string('location_department')->nullable(); // e.g. Store, Workshop, NDT
            $table->string('status')->default('Active')->nullable(); // Active, Under Maintenance, Under Calibration, Out of Service
            $table->string('date_removed_from_service')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_controlled_lists');
    }
};

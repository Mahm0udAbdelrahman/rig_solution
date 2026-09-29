<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Additional equipment used in an inspection report, picked from the Equipment Controlled List.
     * Values are copied at save time so the report keeps what was used even if the register changes later.
     */
    public function up()
    {
        Schema::create('report_equipment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('reportable');
            $table->string('section', 50); // which part of the report, e.g. ut_instrument, mpi, load_cell
            $table->unsignedSmallInteger('sort')->default(0);
            $table->unsignedBigInteger('equipment_controlled_list_id')->nullable()->index();
            $table->string('equipment_no')->nullable();
            $table->string('equipment_description')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('model_type')->nullable();
            $table->string('capacity_range')->nullable();
            $table->string('calibration_due_date')->nullable(); // dd-mm-yyyy, as shown on reports
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_equipment');
    }
};

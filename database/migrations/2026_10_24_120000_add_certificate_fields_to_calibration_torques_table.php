<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fields needed by the Torque calibration certificate (RS-CAL-P11-F02, ISO 6789-2), laid out like the
     * Pressure Gauge certificate. Raw readings and the uncertainty measurements stay in calibration_details.
     */
    private array $columns = [
        'contact_name',
        'contact_info',
        'calibration_location',
        'due_period',               // annual | semi_annual | manual
        'temperature_start',
        'temperature_end',
        'temperature_tolerance',
        'temperature_uncertainty',  // variation = |start - end| / 2
        'humidity_start',
        'humidity_end',
        'humidity_tolerance',
        'humidity_uncertainty',     // variation = |start - end| / 2
        'reference_range',
        'specification_status',     // in | out
    ];

    public function up(): void
    {
        Schema::table('calibration_torques', function (Blueprint $table) {
            foreach ($this->columns as $column) {
                if (!Schema::hasColumn('calibration_torques', $column)) {
                    $table->string($column)->nullable();
                }
            }
            if (!Schema::hasColumn('calibration_torques', 'remarks')) {
                $table->text('remarks')->nullable();
            }
            if (!Schema::hasColumn('calibration_torques', 'user_id_issued')) {
                $table->unsignedBigInteger('user_id_issued')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('calibration_torques', function (Blueprint $table) {
            foreach (array_merge($this->columns, ['remarks', 'user_id_issued']) as $column) {
                if (Schema::hasColumn('calibration_torques', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

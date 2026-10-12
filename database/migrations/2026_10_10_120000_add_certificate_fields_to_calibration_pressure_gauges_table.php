<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fields needed by the new Pressure Gauge calibration certificate layout (RS-CAL-P11-F01E).
     */
    private array $columns = [
        'due_period',               // annual | semi_annual
        'calibration_location',
        'range_from',
        'range_to',
        'digital_analog',
        'unit_segment_location',
        'temperature_tolerance',
        'temperature_uncertainty',
        'humidity_tolerance',
        'humidity_uncertainty',
        'device_accuracy',
        'device_traceability',
        'specification_status',     // in | out
        'issued_by',
    ];

    public function up(): void
    {
        Schema::table('calibration_pressure_gauges', function (Blueprint $table) {
            foreach ($this->columns as $column) {
                if (!Schema::hasColumn('calibration_pressure_gauges', $column)) {
                    $table->string($column)->nullable();
                }
            }
            if (!Schema::hasColumn('calibration_pressure_gauges', 'remarks')) {
                $table->text('remarks')->nullable();
            }
            // the standard method text (two sentences) does not fit in a varchar(255)
            $table->text('calibration_method')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('calibration_pressure_gauges', function (Blueprint $table) {
            foreach (array_merge($this->columns, ['remarks']) as $column) {
                if (Schema::hasColumn('calibration_pressure_gauges', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * - calibration_pressure_gauges.user_id_issued: the QA user who issued the certificate (role permission "issue"),
     *   replaces the free-text "Issued By (QA)" field.
     * - inspection_logos.second_logo: optional logo that replaces the Rig Solution logo on calibration certificates.
     */
    public function up(): void
    {
        Schema::table('calibration_pressure_gauges', function (Blueprint $table) {
            if (!Schema::hasColumn('calibration_pressure_gauges', 'user_id_issued')) {
                $table->unsignedBigInteger('user_id_issued')->nullable();
            }
        });

        Schema::table('inspection_logos', function (Blueprint $table) {
            if (!Schema::hasColumn('inspection_logos', 'second_logo')) {
                $table->string('second_logo')->nullable()->after('logo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('calibration_pressure_gauges', function (Blueprint $table) {
            if (Schema::hasColumn('calibration_pressure_gauges', 'user_id_issued')) {
                $table->dropColumn('user_id_issued');
            }
        });

        Schema::table('inspection_logos', function (Blueprint $table) {
            if (Schema::hasColumn('inspection_logos', 'second_logo')) {
                $table->dropColumn('second_logo');
            }
        });
    }
};

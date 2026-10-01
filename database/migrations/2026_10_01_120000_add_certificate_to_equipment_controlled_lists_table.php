<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Calibration certificate (PDF or image) uploaded for an equipment after it is created.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('equipment_controlled_lists') && !Schema::hasColumn('equipment_controlled_lists', 'certificate_path')) {
            Schema::table('equipment_controlled_lists', function (Blueprint $table) {
                $table->string('certificate_path')->nullable()->after('notes');
                $table->string('certificate_name')->nullable()->after('certificate_path');
                $table->timestamp('certificate_uploaded_at')->nullable()->after('certificate_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('equipment_controlled_lists') && Schema::hasColumn('equipment_controlled_lists', 'certificate_path')) {
            Schema::table('equipment_controlled_lists', function (Blueprint $table) {
                $table->dropColumn(['certificate_path', 'certificate_name', 'certificate_uploaded_at']);
            });
        }
    }
};

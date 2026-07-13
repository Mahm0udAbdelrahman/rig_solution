<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('employees')) {
            return;
        }

        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'avatar')) {
                $table->string('avatar')->nullable()->after('esign');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('employees') || !Schema::hasColumn('employees', 'avatar')) {
            return;
        }

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
    }
};

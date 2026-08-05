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
        if (Schema::hasTable('employees') && !Schema::hasColumn('employees', 'is_assistant')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->boolean('is_assistant')->default(false)->after('desc');
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
        if (Schema::hasTable('employees') && Schema::hasColumn('employees', 'is_assistant')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('is_assistant');
            });
        }
    }
};

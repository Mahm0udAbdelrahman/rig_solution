<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('qutations', function (Blueprint $table) {
            $table->text('creation_date')->nullable();
        });

        // Set initial values for existing rows
        DB::table('qutations')->update(['creation_date' => DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')")]);
    }

    public function down()
    {
        Schema::table('qutations', function (Blueprint $table) {
            $table->dropColumn('creation_date');
        });
    }
};

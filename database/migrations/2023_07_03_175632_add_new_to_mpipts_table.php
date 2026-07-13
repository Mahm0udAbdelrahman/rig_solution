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
        Schema::table('mpipts', function (Blueprint $table) {
            $table->string('nmpr_800')->after('nmpr_30');
            $table->string('nmpr_900')->after('nmpr_800');
            $table->string('nmpr_1000')->after('nmpr_900');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mpipts', function (Blueprint $table) {
            //
        });
    }
};

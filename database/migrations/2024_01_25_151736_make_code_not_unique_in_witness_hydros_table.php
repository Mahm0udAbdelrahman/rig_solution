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
        Schema::table('witness_hydros', function (Blueprint $table) {
            //the code unique constrain name is: witness_hydros_nwhr_3_unique
            $table->dropUnique('witness_hydros_nwhr_3_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('witness_hydros', function (Blueprint $table) {
            $table->unique('code', 'witness_hydros_nwhr_3_unique');
        });
    }
};

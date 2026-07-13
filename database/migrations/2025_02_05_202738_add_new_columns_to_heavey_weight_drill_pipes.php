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
        Schema::table('heavy_weight_pipes', function (Blueprint $table) {
            $table->string('inspection_applied')->nullable();
            $table->string('internal_service_order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('heavy_weight_pipes', function (Blueprint $table) {
						$table->dropColumn('inspection_applied');
						$table->dropColumn('internal_service_order');
        });
    }
};

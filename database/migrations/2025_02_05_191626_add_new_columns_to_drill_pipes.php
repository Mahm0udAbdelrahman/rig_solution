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
        Schema::table('drill_pipes', function (Blueprint $table) {
            $table->string('joint_description')->nullable();
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
        Schema::table('drill_pipes', function (Blueprint $table) {
            $table->dropColumn('joint_description');
						$table->dropColumn('internal_service_order');
        });
    }
};

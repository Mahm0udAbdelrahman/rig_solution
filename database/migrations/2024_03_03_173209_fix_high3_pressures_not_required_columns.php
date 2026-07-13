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
        Schema::table('high3_pressures', function (Blueprint $table) {
            $table->string('nh2pr_10')->nullable()->change();
            $table->string('nh2pr_12')->nullable()->change();
            $table->string('nh2pr_33')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('high3_pressures', function (Blueprint $table) {
            // Revert the changes if necessary
            $table->string('nh2pr_10')->nullable(false)->change();
            $table->string('nh2pr_12')->nullable(false)->change();
            $table->string('nh2pr_33')->nullable(false)->change();
        });
    }
};

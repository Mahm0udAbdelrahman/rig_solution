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
        Schema::table('high2_pressures', function (Blueprint $table) {
            $table->string('edition')->nullable();
			$table->string('nh2pr_28')->nullable()->change();
            $table->string('nh2pr_29')->nullable()->change();
            $table->string('nh2pr_30')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('high2_pressures', function (Blueprint $table) {
            $table->dropColumn('edition');
			$table->string('nh2pr_28')->nullable(false)->change();
            $table->string('nh2pr_29')->nullable(false)->change();
            $table->string('nh2pr_30')->nullable(false)->change();
        });
    }
};

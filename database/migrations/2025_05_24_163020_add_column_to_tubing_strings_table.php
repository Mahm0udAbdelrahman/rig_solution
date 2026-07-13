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
        Schema::table('tubing_strings', function (Blueprint $table) {
            $table->string('joint_description')->nullable()->after('equipment_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tubing_strings', function (Blueprint $table) {
            $table->dropColumn('joint_description');
        });
    }
};

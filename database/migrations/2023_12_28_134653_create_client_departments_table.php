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
        Schema::create('client_departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('description');
            $table->unsignedBigInteger('client_id');
            $table->timestamps();
        });

        Schema::table('job_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('client_department_id')->after('contact_people_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_departments');

        Schema::table('job_requests', function (Blueprint $table) {
            $table->dropColumn('client_department_id');
        });
    }
};

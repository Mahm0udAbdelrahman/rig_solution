<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('footer_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });

        DB::table('footer_addresses')->insert([
            'address_line_1' => 'Head Office: Block# 3053|Hamdy Ramadan street',
            'address_line_2' => '2nd Floor #2 |El-Mearag City|Maadi|Cairo|Egypt',
            'phone' => '+20 2 24477058',
            'mobile' => '+20 1032703368',
            'email' => 'rse@rigsolutionz.com',
            'website' => 'www.rigsolutionz.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('footer_addresses');
    }
};

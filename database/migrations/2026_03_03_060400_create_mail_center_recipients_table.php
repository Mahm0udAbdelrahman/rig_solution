<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('mail_center_recipients')) {
            return;
        }

        Schema::create('mail_center_recipients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mail_center_id');
            $table->unsignedBigInteger('contact_person_id')->nullable();
            $table->string('recipient_type')->default('to');
            $table->string('name')->nullable();
            $table->string('email');
            $table->string('delivery_status')->nullable();
            $table->timestamps();

            $table->foreign('mail_center_id')
                ->references('id')
                ->on('mail_center_messages')
                ->cascadeOnDelete();
            $table->foreign('contact_person_id')
                ->references('id')
                ->on('contact_people')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mail_center_recipients');
    }
};

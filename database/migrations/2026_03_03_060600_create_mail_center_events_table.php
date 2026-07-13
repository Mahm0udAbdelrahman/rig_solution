<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('mail_center_events')) {
            return;
        }

        Schema::create('mail_center_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mail_center_id');
            $table->string('event_type');
            $table->string('description')->nullable();
            $table->longText('payload_json')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('mail_center_id')
                ->references('id')
                ->on('mail_center_messages')
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mail_center_events');
    }
};

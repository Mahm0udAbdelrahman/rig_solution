<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('mail_center_attachments')) {
            return;
        }

        Schema::create('mail_center_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mail_center_id');
            $table->unsignedBigInteger('file_manager_id')->nullable();
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('filename');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->timestamps();

            $table->foreign('mail_center_id')
                ->references('id')
                ->on('mail_center_messages')
                ->cascadeOnDelete();
            $table->foreign('file_manager_id')
                ->references('id')
                ->on('file_managers')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mail_center_attachments');
    }
};

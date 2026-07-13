<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('mail_center_mailbox_user')) {
            return;
        }

        if (!Schema::hasTable('mail_center_mailboxes') || !Schema::hasTable('users')) {
            return;
        }

        Schema::create('mail_center_mailbox_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mailbox_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->unique(['mailbox_id', 'user_id']);
            $table->foreign('mailbox_id')->references('id')->on('mail_center_mailboxes')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mail_center_mailbox_user');
    }
};

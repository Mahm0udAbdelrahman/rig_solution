<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('mail_center_user_preferences')) {
            return;
        }

        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::create('mail_center_user_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->boolean('notify_on_pending_approval')->default(1);
            $table->boolean('notify_on_approved')->default(1);
            $table->boolean('notify_on_sent')->default(1);
            $table->boolean('notify_on_failed')->default(1);
            $table->boolean('show_pending_approval_queue')->default(1);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        if (!Schema::hasTable('mail_center_user_preferences')) {
            return;
        }

        Schema::dropIfExists('mail_center_user_preferences');
    }
};

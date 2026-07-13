<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('mail_center_settings')) {
            return;
        }

        if (!Schema::hasTable('mail_center_mailboxes')) {
            return;
        }

        Schema::create('mail_center_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('default_mailbox_id')->nullable();
            $table->longText('default_signature_html')->nullable();
            $table->longText('default_footer_html')->nullable();
            $table->boolean('require_approval')->default(0);
            $table->boolean('track_events')->default(1);
            $table->unsignedSmallInteger('attachment_limit_mb')->default(10);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('default_mailbox_id')
                ->references('id')
                ->on('mail_center_mailboxes')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mail_center_settings');
    }
};

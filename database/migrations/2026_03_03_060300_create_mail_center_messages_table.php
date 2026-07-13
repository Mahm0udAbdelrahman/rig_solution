<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('mail_center_messages')) {
            return;
        }

        Schema::create('mail_center_messages', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedBigInteger('mailbox_id')->nullable();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->unsignedBigInteger('job_request_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('related_type')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->string('subject');
            $table->longText('body_html')->nullable();
            $table->longText('body_text')->nullable();
            $table->text('to_emails')->nullable();
            $table->text('cc_emails')->nullable();
            $table->text('bcc_emails')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('approval_required')->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('provider_message_id')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();

            $table->foreign('mailbox_id')->references('id')->on('mail_center_mailboxes')->nullOnDelete();
            $table->foreign('template_id')->references('id')->on('mail_center_templates')->nullOnDelete();
            $table->foreign('job_request_id')->references('id')->on('job_requests')->nullOnDelete();
            $table->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mail_center_messages');
    }
};

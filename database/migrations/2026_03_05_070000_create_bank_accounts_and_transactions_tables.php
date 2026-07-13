<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bank_accounts')) {
            Schema::create('bank_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable()->index();
                $table->string('bank_name')->nullable();
                $table->string('account_name')->nullable();
                $table->string('account_number')->nullable()->index();
                $table->string('iban')->nullable();
                $table->string('currency', 20)->nullable()->default('USD');
                $table->decimal('opening_balance', 15, 2)->nullable()->default(0);
                $table->date('opening_date')->nullable();
                $table->decimal('current_balance', 15, 2)->nullable()->default(0);
                $table->tinyInteger('is_active')->nullable()->default(1);
                $table->text('note')->nullable();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('approved_by')->nullable()->index();
                $table->integer('sync')->nullable();
                $table->integer('updated')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('bank_transactions')) {
            Schema::create('bank_transactions', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable()->index();
                $table->unsignedBigInteger('bank_account_id')->index();
                $table->unsignedBigInteger('job_request_id')->nullable()->index();
                $table->unsignedBigInteger('payment_id')->nullable()->index();
                $table->unsignedBigInteger('accountant_id')->nullable()->index();
                $table->enum('direction', ['in', 'out'])->default('in');
                $table->enum('category', ['deposit', 'withdraw', 'transfer', 'adjustment', 'fee', 'other'])->default('deposit');
                $table->date('transaction_date')->nullable();
                $table->decimal('amount', 15, 2)->default(0);
                $table->string('reference_no')->nullable();
                $table->string('status')->nullable()->default('draft');
                $table->text('note')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable()->index();
                $table->timestamp('approved_at')->nullable();
                $table->tinyInteger('is_posted')->nullable()->default(0);
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->integer('sync')->nullable();
                $table->integer('updated')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('bank_transactions');
        Schema::dropIfExists('bank_accounts');
    }
};


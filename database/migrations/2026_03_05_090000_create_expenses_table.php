<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable()->index();
                $table->unsignedBigInteger('employee_id')->nullable()->index();
                $table->unsignedBigInteger('job_request_id')->nullable()->index();
                $table->unsignedBigInteger('bank_account_id')->nullable()->index();
                $table->unsignedBigInteger('bank_transaction_id')->nullable()->index();
                $table->unsignedBigInteger('accountant_id')->nullable()->index();
                $table->unsignedBigInteger('expense_account_id')->nullable()->index();
                $table->date('expense_date')->nullable();
                $table->string('category', 100)->nullable();
                $table->decimal('amount', 15, 2)->nullable()->default(0);
                $table->string('currency', 20)->nullable()->default('USD');
                $table->string('reference_no')->nullable();
                $table->string('status')->nullable()->default('draft');
                $table->tinyInteger('is_posted')->nullable()->default(0);
                $table->text('note')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable()->index();
                $table->timestamp('approved_at')->nullable();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->integer('sync')->nullable();
                $table->integer('updated')->nullable();
                $table->timestamps();
            });
        }

        $ensureColumns = function (string $tableName, array $columnDefinitions): void {
            if (!Schema::hasTable($tableName)) {
                return;
            }

            foreach ($columnDefinitions as $columnName => $definition) {
                if (Schema::hasColumn($tableName, $columnName)) {
                    continue;
                }

                Schema::table($tableName, function (Blueprint $table) use ($definition) {
                    $definition($table);
                });
            }
        };

        $ensureColumns('expenses', [
            'code' => fn (Blueprint $table) => $table->string('code')->nullable()->index(),
            'employee_id' => fn (Blueprint $table) => $table->unsignedBigInteger('employee_id')->nullable()->index(),
            'job_request_id' => fn (Blueprint $table) => $table->unsignedBigInteger('job_request_id')->nullable()->index(),
            'bank_account_id' => fn (Blueprint $table) => $table->unsignedBigInteger('bank_account_id')->nullable()->index(),
            'bank_transaction_id' => fn (Blueprint $table) => $table->unsignedBigInteger('bank_transaction_id')->nullable()->index(),
            'accountant_id' => fn (Blueprint $table) => $table->unsignedBigInteger('accountant_id')->nullable()->index(),
            'expense_account_id' => fn (Blueprint $table) => $table->unsignedBigInteger('expense_account_id')->nullable()->index(),
            'expense_date' => fn (Blueprint $table) => $table->date('expense_date')->nullable(),
            'category' => fn (Blueprint $table) => $table->string('category', 100)->nullable(),
            'amount' => fn (Blueprint $table) => $table->decimal('amount', 15, 2)->nullable()->default(0),
            'currency' => fn (Blueprint $table) => $table->string('currency', 20)->nullable()->default('USD'),
            'reference_no' => fn (Blueprint $table) => $table->string('reference_no')->nullable(),
            'status' => fn (Blueprint $table) => $table->string('status')->nullable()->default('draft'),
            'is_posted' => fn (Blueprint $table) => $table->tinyInteger('is_posted')->nullable()->default(0),
            'note' => fn (Blueprint $table) => $table->text('note')->nullable(),
            'approved_by' => fn (Blueprint $table) => $table->unsignedBigInteger('approved_by')->nullable()->index(),
            'approved_at' => fn (Blueprint $table) => $table->timestamp('approved_at')->nullable(),
            'user_id' => fn (Blueprint $table) => $table->unsignedBigInteger('user_id')->nullable()->index(),
            'sync' => fn (Blueprint $table) => $table->integer('sync')->nullable(),
            'updated' => fn (Blueprint $table) => $table->integer('updated')->nullable(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};


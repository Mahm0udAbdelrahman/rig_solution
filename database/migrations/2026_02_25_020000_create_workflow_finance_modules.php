<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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

        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable()->index();
                $table->unsignedBigInteger('job_request_id')->nullable()->index();
                $table->unsignedBigInteger('invoice_id')->nullable()->index();
                $table->date('payment_date')->nullable();
                $table->decimal('amount', 15, 2)->nullable();
                $table->string('method')->nullable();
                $table->string('reference_no')->nullable();
                $table->string('status')->nullable()->default('pending');
                $table->text('note')->nullable();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('approved_by')->nullable()->index();
                $table->integer('sync')->nullable();
                $table->integer('updated')->nullable();
                $table->timestamps();
            });
        }

        $ensureColumns('payments', [
            'code' => fn (Blueprint $table) => $table->string('code')->nullable(),
            'job_request_id' => fn (Blueprint $table) => $table->unsignedBigInteger('job_request_id')->nullable(),
            'invoice_id' => fn (Blueprint $table) => $table->unsignedBigInteger('invoice_id')->nullable(),
            'payment_date' => fn (Blueprint $table) => $table->date('payment_date')->nullable(),
            'amount' => fn (Blueprint $table) => $table->decimal('amount', 15, 2)->nullable(),
            'method' => fn (Blueprint $table) => $table->string('method')->nullable(),
            'reference_no' => fn (Blueprint $table) => $table->string('reference_no')->nullable(),
            'status' => fn (Blueprint $table) => $table->string('status')->nullable()->default('pending'),
            'note' => fn (Blueprint $table) => $table->text('note')->nullable(),
            'user_id' => fn (Blueprint $table) => $table->unsignedBigInteger('user_id')->nullable(),
            'approved_by' => fn (Blueprint $table) => $table->unsignedBigInteger('approved_by')->nullable(),
            'sync' => fn (Blueprint $table) => $table->integer('sync')->nullable(),
            'updated' => fn (Blueprint $table) => $table->integer('updated')->nullable(),
        ]);

        if (!Schema::hasTable('inventories')) {
            Schema::create('inventories', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable()->index();
                $table->unsignedBigInteger('job_request_id')->nullable()->index();
                $table->string('item_name')->nullable();
                $table->string('sku')->nullable()->index();
                $table->decimal('quantity', 15, 2)->nullable()->default(0);
                $table->string('unit')->nullable();
                $table->string('location')->nullable();
                $table->decimal('min_quantity', 15, 2)->nullable()->default(0);
                $table->string('status')->nullable()->default('available');
                $table->text('note')->nullable();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->integer('sync')->nullable();
                $table->integer('updated')->nullable();
                $table->timestamps();
            });
        }

        $ensureColumns('inventories', [
            'code' => fn (Blueprint $table) => $table->string('code')->nullable(),
            'job_request_id' => fn (Blueprint $table) => $table->unsignedBigInteger('job_request_id')->nullable(),
            'item_name' => fn (Blueprint $table) => $table->string('item_name')->nullable(),
            'sku' => fn (Blueprint $table) => $table->string('sku')->nullable(),
            'quantity' => fn (Blueprint $table) => $table->decimal('quantity', 15, 2)->nullable()->default(0),
            'unit' => fn (Blueprint $table) => $table->string('unit')->nullable(),
            'location' => fn (Blueprint $table) => $table->string('location')->nullable(),
            'min_quantity' => fn (Blueprint $table) => $table->decimal('min_quantity', 15, 2)->nullable()->default(0),
            'status' => fn (Blueprint $table) => $table->string('status')->nullable()->default('available'),
            'note' => fn (Blueprint $table) => $table->text('note')->nullable(),
            'user_id' => fn (Blueprint $table) => $table->unsignedBigInteger('user_id')->nullable(),
            'sync' => fn (Blueprint $table) => $table->integer('sync')->nullable(),
            'updated' => fn (Blueprint $table) => $table->integer('updated')->nullable(),
        ]);

        if (!Schema::hasTable('accountants')) {
            Schema::create('accountants', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable()->index();
                $table->unsignedBigInteger('job_request_id')->nullable()->index();
                $table->unsignedBigInteger('invoice_id')->nullable()->index();
                $table->unsignedBigInteger('payment_id')->nullable()->index();
                $table->string('entry_type')->nullable();
                $table->decimal('amount', 15, 2)->nullable();
                $table->string('currency')->nullable()->default('USD');
                $table->string('status')->nullable()->default('draft');
                $table->text('note')->nullable();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('approved_by')->nullable()->index();
                $table->integer('sync')->nullable();
                $table->integer('updated')->nullable();
                $table->timestamps();
            });
        }

        $ensureColumns('accountants', [
            'code' => fn (Blueprint $table) => $table->string('code')->nullable(),
            'job_request_id' => fn (Blueprint $table) => $table->unsignedBigInteger('job_request_id')->nullable(),
            'invoice_id' => fn (Blueprint $table) => $table->unsignedBigInteger('invoice_id')->nullable(),
            'payment_id' => fn (Blueprint $table) => $table->unsignedBigInteger('payment_id')->nullable(),
            'entry_type' => fn (Blueprint $table) => $table->string('entry_type')->nullable(),
            'amount' => fn (Blueprint $table) => $table->decimal('amount', 15, 2)->nullable(),
            'currency' => fn (Blueprint $table) => $table->string('currency')->nullable()->default('USD'),
            'status' => fn (Blueprint $table) => $table->string('status')->nullable()->default('draft'),
            'note' => fn (Blueprint $table) => $table->text('note')->nullable(),
            'user_id' => fn (Blueprint $table) => $table->unsignedBigInteger('user_id')->nullable(),
            'approved_by' => fn (Blueprint $table) => $table->unsignedBigInteger('approved_by')->nullable(),
            'sync' => fn (Blueprint $table) => $table->integer('sync')->nullable(),
            'updated' => fn (Blueprint $table) => $table->integer('updated')->nullable(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accountants');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('payments');
    }
};

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
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->date('receipt_date')->nullable();
            $table->enum('outstand_days', [30, 45, 60, 90])->nullable();
            $table->decimal('paid_amount', 8, 2)->nullable();
            $table->enum('paid_currency', ['EGP', 'USD'])->nullable();
            $table->decimal('exchange_rate', 4, 2)->nullable();
            $table->enum('method', ['cash', 'transfare', 'cheque', 'other'])->nullable();
            $table->string('method_number')->nullable();
            $table->decimal('method_amount', 8, 2)->nullable();
            $table->date('collect_date')->nullable();
            $table->decimal('outstand_amount', 8, 2)->nullable();
            $table->enum('status', ['past_due', 'paid_in_full', 'other'])->nullable();
            $table->decimal('vat_amount', 8, 2)->nullable();
            $table->decimal('holding_tax_amount', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};

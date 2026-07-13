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
            $table->unsignedBigInteger('invoice_id');
            $table->date('receipt_date');
            $table->enum('outstand_days', [30, 45, 60, 90]);
            $table->decimal('paid_amount', 8, 2);
            $table->enum('paid_currency', ['EGP', 'USD']);
            $table->decimal('exchange_rate', 4, 2);
            $table->enum('method', ['cash', 'transfare', 'cheque', 'other']);
            $table->string('method_number')->nullable();
            $table->decimal('method_amount', 8, 2);
            $table->date('collect_date');
            $table->decimal('outstand_amount', 8, 2);
            $table->enum('status', ['past_due', 'paid_in_full', 'other']);
            $table->decimal('vat_amount', 8, 2);
            $table->decimal('holding_tax_amount', 8, 2);
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
        Schema::dropIfExists('finances');
    }
};

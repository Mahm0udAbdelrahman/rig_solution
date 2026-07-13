<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('accounting_periods')) {
            Schema::create('accounting_periods', function (Blueprint $table) {
                $table->id();
                $table->unsignedSmallInteger('year')->index();
                $table->unsignedTinyInteger('month')->index();
                $table->date('period_start');
                $table->date('period_end');
                $table->boolean('is_closed')->default(false)->index();
                $table->unsignedBigInteger('closed_by')->nullable()->index();
                $table->timestamp('closed_at')->nullable();
                $table->string('note', 191)->nullable();
                $table->timestamps();
                $table->unique(['year', 'month']);
            });
        }

        if (!Schema::hasTable('accountant_entry_lines')) {
            Schema::create('accountant_entry_lines', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('accountant_id')->index();
                $table->unsignedBigInteger('chart_account_id')->index();
                $table->enum('line_type', ['debit', 'credit'])->index();
                $table->decimal('amount', 15, 2)->default(0);
                $table->string('currency', 20)->nullable();
                $table->unsignedInteger('line_order')->default(1);
                $table->string('note', 191)->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('expenses') && !Schema::hasColumn('expenses', 'payment_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->unsignedBigInteger('payment_id')->nullable()->index()->after('accountant_id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('expenses') && Schema::hasColumn('expenses', 'payment_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->dropColumn('payment_id');
            });
        }

        Schema::dropIfExists('accountant_entry_lines');
        Schema::dropIfExists('accounting_periods');
    }
};

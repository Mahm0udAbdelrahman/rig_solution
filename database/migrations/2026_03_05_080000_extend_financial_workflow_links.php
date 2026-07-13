<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('payments') && !Schema::hasColumn('payments', 'bank_account_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->unsignedBigInteger('bank_account_id')->nullable()->index()->after('invoice_id');
            });
        }

        if (Schema::hasTable('bank_accounts') && !Schema::hasColumn('bank_accounts', 'chart_account_id')) {
            Schema::table('bank_accounts', function (Blueprint $table) {
                $table->unsignedBigInteger('chart_account_id')->nullable()->index()->after('currency');
            });
        }

        if (Schema::hasTable('bank_transactions') && !Schema::hasColumn('bank_transactions', 'counter_account_id')) {
            Schema::table('bank_transactions', function (Blueprint $table) {
                $table->unsignedBigInteger('counter_account_id')->nullable()->index()->after('accountant_id');
            });
        }

        if (Schema::hasTable('bank_transactions') && !Schema::hasColumn('bank_transactions', 'source_type')) {
            Schema::table('bank_transactions', function (Blueprint $table) {
                $table->string('source_type', 40)->nullable()->default('manual')->index()->after('category');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bank_transactions') && Schema::hasColumn('bank_transactions', 'source_type')) {
            Schema::table('bank_transactions', function (Blueprint $table) {
                $table->dropColumn('source_type');
            });
        }

        if (Schema::hasTable('bank_transactions') && Schema::hasColumn('bank_transactions', 'counter_account_id')) {
            Schema::table('bank_transactions', function (Blueprint $table) {
                $table->dropColumn('counter_account_id');
            });
        }

        if (Schema::hasTable('bank_accounts') && Schema::hasColumn('bank_accounts', 'chart_account_id')) {
            Schema::table('bank_accounts', function (Blueprint $table) {
                $table->dropColumn('chart_account_id');
            });
        }

        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'bank_account_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('bank_account_id');
            });
        }
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $this->addNullableForeign('payments', 'job_request_id', 'job_requests', 'id', 'fk_payments_job_request_id');
        $this->addNullableForeign('payments', 'invoice_id', 'invoices', 'id', 'fk_payments_invoice_id');
        $this->addNullableForeign('payments', 'bank_account_id', 'bank_accounts', 'id', 'fk_payments_bank_account_id');
        $this->addNullableForeign('payments', 'user_id', 'users', 'id', 'fk_payments_user_id');
        $this->addNullableForeign('payments', 'approved_by', 'users', 'id', 'fk_payments_approved_by');

        $this->addNullableForeign('accountants', 'job_request_id', 'job_requests', 'id', 'fk_accountants_job_request_id');
        $this->addNullableForeign('accountants', 'invoice_id', 'invoices', 'id', 'fk_accountants_invoice_id');
        $this->addNullableForeign('accountants', 'payment_id', 'payments', 'id', 'fk_accountants_payment_id');
        $this->addNullableForeign('accountants', 'debit_account_id', 'chart_accounts', 'id', 'fk_accountants_debit_account_id');
        $this->addNullableForeign('accountants', 'credit_account_id', 'chart_accounts', 'id', 'fk_accountants_credit_account_id');
        $this->addNullableForeign('accountants', 'user_id', 'users', 'id', 'fk_accountants_user_id');
        $this->addNullableForeign('accountants', 'approved_by', 'users', 'id', 'fk_accountants_approved_by');

        $this->addNullableForeign('bank_accounts', 'chart_account_id', 'chart_accounts', 'id', 'fk_bank_accounts_chart_account_id');
        $this->addNullableForeign('bank_accounts', 'user_id', 'users', 'id', 'fk_bank_accounts_user_id');
        $this->addNullableForeign('bank_accounts', 'approved_by', 'users', 'id', 'fk_bank_accounts_approved_by');

        $this->addNullableForeign('bank_transactions', 'job_request_id', 'job_requests', 'id', 'fk_bank_transactions_job_request_id');
        $this->addNullableForeign('bank_transactions', 'payment_id', 'payments', 'id', 'fk_bank_transactions_payment_id');
        $this->addNullableForeign('bank_transactions', 'accountant_id', 'accountants', 'id', 'fk_bank_transactions_accountant_id');
        $this->addNullableForeign('bank_transactions', 'counter_account_id', 'chart_accounts', 'id', 'fk_bank_transactions_counter_account_id');
        $this->addNullableForeign('bank_transactions', 'user_id', 'users', 'id', 'fk_bank_transactions_user_id');
        $this->addNullableForeign('bank_transactions', 'approved_by', 'users', 'id', 'fk_bank_transactions_approved_by');

        $this->addNullableForeign('expenses', 'employee_id', 'employees', 'id', 'fk_expenses_employee_id');
        $this->addNullableForeign('expenses', 'job_request_id', 'job_requests', 'id', 'fk_expenses_job_request_id');
        $this->addNullableForeign('expenses', 'bank_account_id', 'bank_accounts', 'id', 'fk_expenses_bank_account_id');
        $this->addNullableForeign('expenses', 'bank_transaction_id', 'bank_transactions', 'id', 'fk_expenses_bank_transaction_id');
        $this->addNullableForeign('expenses', 'accountant_id', 'accountants', 'id', 'fk_expenses_accountant_id');
        $this->addNullableForeign('expenses', 'payment_id', 'payments', 'id', 'fk_expenses_payment_id');
        $this->addNullableForeign('expenses', 'expense_account_id', 'chart_accounts', 'id', 'fk_expenses_expense_account_id');
        $this->addNullableForeign('expenses', 'user_id', 'users', 'id', 'fk_expenses_user_id');
        $this->addNullableForeign('expenses', 'approved_by', 'users', 'id', 'fk_expenses_approved_by');

        $this->addNullableForeign('accounting_periods', 'closed_by', 'users', 'id', 'fk_accounting_periods_closed_by');

        $this->addStrictForeign('accountant_entry_lines', 'accountant_id', 'accountants', 'id', 'fk_accountant_entry_lines_accountant_id', 'cascade');
        $this->addStrictForeign('accountant_entry_lines', 'chart_account_id', 'chart_accounts', 'id', 'fk_accountant_entry_lines_chart_account_id', 'restrict');
    }

    public function down()
    {
        $this->dropForeignIfExists('accountant_entry_lines', 'fk_accountant_entry_lines_chart_account_id');
        $this->dropForeignIfExists('accountant_entry_lines', 'fk_accountant_entry_lines_accountant_id');

        $this->dropForeignIfExists('accounting_periods', 'fk_accounting_periods_closed_by');

        $this->dropForeignIfExists('expenses', 'fk_expenses_approved_by');
        $this->dropForeignIfExists('expenses', 'fk_expenses_user_id');
        $this->dropForeignIfExists('expenses', 'fk_expenses_expense_account_id');
        $this->dropForeignIfExists('expenses', 'fk_expenses_payment_id');
        $this->dropForeignIfExists('expenses', 'fk_expenses_accountant_id');
        $this->dropForeignIfExists('expenses', 'fk_expenses_bank_transaction_id');
        $this->dropForeignIfExists('expenses', 'fk_expenses_bank_account_id');
        $this->dropForeignIfExists('expenses', 'fk_expenses_job_request_id');
        $this->dropForeignIfExists('expenses', 'fk_expenses_employee_id');

        $this->dropForeignIfExists('bank_transactions', 'fk_bank_transactions_approved_by');
        $this->dropForeignIfExists('bank_transactions', 'fk_bank_transactions_user_id');
        $this->dropForeignIfExists('bank_transactions', 'fk_bank_transactions_counter_account_id');
        $this->dropForeignIfExists('bank_transactions', 'fk_bank_transactions_accountant_id');
        $this->dropForeignIfExists('bank_transactions', 'fk_bank_transactions_payment_id');
        $this->dropForeignIfExists('bank_transactions', 'fk_bank_transactions_job_request_id');

        $this->dropForeignIfExists('bank_accounts', 'fk_bank_accounts_approved_by');
        $this->dropForeignIfExists('bank_accounts', 'fk_bank_accounts_user_id');
        $this->dropForeignIfExists('bank_accounts', 'fk_bank_accounts_chart_account_id');

        $this->dropForeignIfExists('accountants', 'fk_accountants_approved_by');
        $this->dropForeignIfExists('accountants', 'fk_accountants_user_id');
        $this->dropForeignIfExists('accountants', 'fk_accountants_credit_account_id');
        $this->dropForeignIfExists('accountants', 'fk_accountants_debit_account_id');
        $this->dropForeignIfExists('accountants', 'fk_accountants_payment_id');
        $this->dropForeignIfExists('accountants', 'fk_accountants_invoice_id');
        $this->dropForeignIfExists('accountants', 'fk_accountants_job_request_id');

        $this->dropForeignIfExists('payments', 'fk_payments_approved_by');
        $this->dropForeignIfExists('payments', 'fk_payments_user_id');
        $this->dropForeignIfExists('payments', 'fk_payments_bank_account_id');
        $this->dropForeignIfExists('payments', 'fk_payments_invoice_id');
        $this->dropForeignIfExists('payments', 'fk_payments_job_request_id');
    }

    private function addNullableForeign(string $table, string $column, string $refTable, string $refColumn, string $constraintName): void
    {
        if (!Schema::hasTable($table) || !Schema::hasTable($refTable) || !Schema::hasColumn($table, $column) || $this->hasForeignConstraint($table, $constraintName)) {
            return;
        }

        DB::table($table)
            ->whereNotNull($column)
            ->whereNotIn($column, DB::table($refTable)->select($refColumn))
            ->update([$column => null]);

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($column, $refTable, $refColumn, $constraintName) {
                $blueprint->foreign($column, $constraintName)->references($refColumn)->on($refTable)->nullOnDelete();
            });
        } catch (\Throwable $e) {
            // Ignore if DB engine has already equivalent FK with different generated name.
        }
    }

    private function addStrictForeign(
        string $table,
        string $column,
        string $refTable,
        string $refColumn,
        string $constraintName,
        string $onDelete = 'restrict'
    ): void {
        if (!Schema::hasTable($table) || !Schema::hasTable($refTable) || !Schema::hasColumn($table, $column) || $this->hasForeignConstraint($table, $constraintName)) {
            return;
        }

        if ($table === 'accountant_entry_lines' && $column === 'accountant_id') {
            DB::table('accountant_entry_lines')
                ->whereNotIn('accountant_id', DB::table('accountants')->select('id'))
                ->delete();
        }

        if ($table === 'accountant_entry_lines' && $column === 'chart_account_id') {
            DB::table('accountant_entry_lines')
                ->whereNotIn('chart_account_id', DB::table('chart_accounts')->select('id'))
                ->delete();
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($column, $refTable, $refColumn, $constraintName, $onDelete) {
                $foreign = $blueprint->foreign($column, $constraintName)->references($refColumn)->on($refTable);
                if ($onDelete === 'cascade') {
                    $foreign->cascadeOnDelete();
                    return;
                }
                $foreign->restrictOnDelete();
            });
        } catch (\Throwable $e) {
            // Ignore if already exists.
        }
    }

    private function dropForeignIfExists(string $table, string $constraintName): void
    {
        if (!Schema::hasTable($table) || !$this->hasForeignConstraint($table, $constraintName)) {
            return;
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($constraintName) {
                $blueprint->dropForeign($constraintName);
            });
        } catch (\Throwable $e) {
            // Ignore if already dropped.
        }
    }

    private function hasForeignConstraint(string $table, string $constraintName): bool
    {
        $driver = DB::getDriverName();
        if (!in_array($driver, ['mysql', 'mariadb'], true)) {
            return false;
        }

        $database = DB::getDatabaseName();
        $result = DB::selectOne(
            'SELECT COUNT(*) AS aggregate FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = "FOREIGN KEY"',
            [$database, $table, $constraintName]
        );

        return (int)($result->aggregate ?? 0) > 0;
    }
};

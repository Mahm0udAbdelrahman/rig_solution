<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

        if (!Schema::hasTable('chart_accounts')) {
            Schema::create('chart_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable()->index();
                $table->string('name');
                $table->string('type', 40)->index(); // asset, liability, equity, revenue, expense
                $table->unsignedBigInteger('parent_id')->nullable()->index();
                $table->unsignedInteger('level')->default(0);
                $table->boolean('is_active')->default(true);
                $table->text('note')->nullable();
                $table->timestamps();
            });
        }

        $ensureColumns('chart_accounts', [
            'code' => fn (Blueprint $table) => $table->string('code')->nullable(),
            'name' => fn (Blueprint $table) => $table->string('name'),
            'type' => fn (Blueprint $table) => $table->string('type', 40),
            'parent_id' => fn (Blueprint $table) => $table->unsignedBigInteger('parent_id')->nullable(),
            'level' => fn (Blueprint $table) => $table->unsignedInteger('level')->default(0),
            'is_active' => fn (Blueprint $table) => $table->boolean('is_active')->default(true),
            'note' => fn (Blueprint $table) => $table->text('note')->nullable(),
        ]);

        $ensureColumns('accountants', [
            'posting_date' => fn (Blueprint $table) => $table->date('posting_date')->nullable(),
            'debit_account_id' => fn (Blueprint $table) => $table->unsignedBigInteger('debit_account_id')->nullable(),
            'credit_account_id' => fn (Blueprint $table) => $table->unsignedBigInteger('credit_account_id')->nullable(),
            'reference_no' => fn (Blueprint $table) => $table->string('reference_no')->nullable(),
            'is_posted' => fn (Blueprint $table) => $table->boolean('is_posted')->default(true),
        ]);

        if (Schema::hasTable('chart_accounts') && DB::table('chart_accounts')->count() === 0) {
            $roots = [
                ['code' => '1000', 'name' => 'Assets', 'type' => 'asset'],
                ['code' => '2000', 'name' => 'Liabilities', 'type' => 'liability'],
                ['code' => '3000', 'name' => 'Equity', 'type' => 'equity'],
                ['code' => '4000', 'name' => 'Revenue', 'type' => 'revenue'],
                ['code' => '5000', 'name' => 'Expenses', 'type' => 'expense'],
            ];

            $rootIds = [];
            foreach ($roots as $root) {
                $rootIds[$root['code']] = DB::table('chart_accounts')->insertGetId([
                    'code' => $root['code'],
                    'name' => $root['name'],
                    'type' => $root['type'],
                    'parent_id' => null,
                    'level' => 0,
                    'is_active' => 1,
                    'note' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $children = [
                ['code' => '1100', 'name' => 'Cash and Bank', 'type' => 'asset', 'parent_code' => '1000'],
                ['code' => '1200', 'name' => 'Accounts Receivable', 'type' => 'asset', 'parent_code' => '1000'],
                ['code' => '1300', 'name' => 'Inventory', 'type' => 'asset', 'parent_code' => '1000'],
                ['code' => '2100', 'name' => 'Accounts Payable', 'type' => 'liability', 'parent_code' => '2000'],
                ['code' => '2200', 'name' => 'Tax Payable', 'type' => 'liability', 'parent_code' => '2000'],
                ['code' => '3100', 'name' => 'Retained Earnings', 'type' => 'equity', 'parent_code' => '3000'],
                ['code' => '4100', 'name' => 'Service Revenue', 'type' => 'revenue', 'parent_code' => '4000'],
                ['code' => '5100', 'name' => 'Cost of Services', 'type' => 'expense', 'parent_code' => '5000'],
                ['code' => '5200', 'name' => 'Operating Expenses', 'type' => 'expense', 'parent_code' => '5000'],
            ];

            foreach ($children as $child) {
                DB::table('chart_accounts')->insert([
                    'code' => $child['code'],
                    'name' => $child['name'],
                    'type' => $child['type'],
                    'parent_id' => $rootIds[$child['parent_code']] ?? null,
                    'level' => 1,
                    'is_active' => 1,
                    'note' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('accountants')) {
            Schema::table('accountants', function (Blueprint $table) {
                $dropColumns = [];
                foreach (['posting_date', 'debit_account_id', 'credit_account_id', 'reference_no', 'is_posted'] as $columnName) {
                    if (Schema::hasColumn('accountants', $columnName)) {
                        $dropColumns[] = $columnName;
                    }
                }

                if (!empty($dropColumns)) {
                    $table->dropColumn($dropColumns);
                }
            });
        }

        Schema::dropIfExists('chart_accounts');
    }
};


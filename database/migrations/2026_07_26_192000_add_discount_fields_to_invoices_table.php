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
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'discount_type')) {
                $table->string('discount_type', 20)->default('percentage')->after('sub_total');
            }
            if (!Schema::hasColumn('invoices', 'discount_value')) {
                $table->decimal('discount_value', 15, 2)->default(0.00)->after('discount_type');
            }
            if (!Schema::hasColumn('invoices', 'discount_amount')) {
                $table->decimal('discount_amount', 15, 2)->default(0.00)->after('discount_value');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'discount_amount')) {
                $table->dropColumn('discount_amount');
            }
            if (Schema::hasColumn('invoices', 'discount_value')) {
                $table->dropColumn('discount_value');
            }
            if (Schema::hasColumn('invoices', 'discount_type')) {
                $table->dropColumn('discount_type');
            }
        });
    }
};

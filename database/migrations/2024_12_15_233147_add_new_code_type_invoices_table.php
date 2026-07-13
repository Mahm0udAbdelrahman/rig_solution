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
			$table->dropUnique('invoices_code_unique');
			$table->enum('invoice_company_type', ['OLD', 'NEW'])->default('OLD')->after('code');
			$table->unique(['code', 'invoice_company_type'], 'invoices_code_company_type_unique');
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
			$table->dropUnique('invoices_code_company_type_unique');
			$table->dropColumn('invoice_company_type');
			$table->unique('code', 'invoices_code_unique');
		});
	}
};

<?php

use App\Models\WorkFlow\Invoice;
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
		Schema::table('invoices', function (Blueprint $table) {
			$table->string('invoice_company_type')->default(Invoice::$INVOICE_RSE_TYPE)->change();
		});

		DB::table('invoices')
			->where('invoice_company_type', 'OLD')
			->update(['invoice_company_type' => Invoice::$INVOICE_RSE_TYPE]);

		DB::table('invoices')
			->where('invoice_company_type', 'NEW')
			->update(['invoice_company_type' => Invoice::$INVOICE_LTD_TYPE]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('invoices')
			->where('invoice_company_type', Invoice::$INVOICE_RSE_TYPE)
			->update(['invoice_company_type' => 'OLD']);

		DB::table('invoices')
			->where('invoice_company_type', Invoice::$INVOICE_LTD_TYPE)
			->update(['invoice_company_type' => 'NEW']);

		Schema::table('invoices', function (Blueprint $table) {
			$table->string('invoice_company_type')->default('OLD')->change();
		});

	}
};


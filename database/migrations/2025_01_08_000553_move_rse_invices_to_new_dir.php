<?php

use App\Models\WorkFlow\Invoice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$sourceDir = 'pdf/workflow/invoice';
		$destinationDir = 'pdf/workflow/invoice/' . Invoice::$INVOICE_RSE_TYPE;

		if (Storage::disk('public')->exists($sourceDir)) {
			$files = Storage::disk('public')->files($sourceDir);

			foreach ($files as $file) {
				$filename = basename($file);
				Storage::disk('public')->move($file, $destinationDir . '/' . $filename);
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
		$sourceDir = 'pdf/workflow/invoice/' . Invoice::$INVOICE_RSE_TYPE;
		$destinationDir = 'pdf/workflow/invoice';

		if (Storage::disk('public')->exists($sourceDir)) {
			$files = Storage::disk('public')->files($sourceDir);

			foreach ($files as $file) {
				$filename = basename($file);
				Storage::disk('public')->move($file, $destinationDir . '/' . $filename);
			}
		}
	}
};

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
		Schema::table('summaries', function (Blueprint $table) {
			$table->dropUnique('summaries_nsr_3_unique'); // Drop existing unique constraint
			$table->unique(['code', 'job_request_id'], 'summaries_code_job_request_unique'); // Create new unique constraint
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('summaries', function (Blueprint $table) {
			$table->dropUnique('summaries_code_job_request_unique'); // Drop new unique constraint
			$table->unique('code', 'summaries_nsr_3_unique'); // Recreate old unique constraint
		});
	}
};

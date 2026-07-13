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
			$table->dropUnique('summaries_code_job_request_unique'); // Drop existing unique constraint
		});

		Schema::table('high3_pressures', function (Blueprint $table) {
            $table->dropUnique('high3_pressures_code_job_request_unique'); // Drop existing unique constraint
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
			$table->unique(['code', 'job_request_id'], 'summaries_code_job_request_unique'); // Create new unique constraint
		});

		Schema::table('high3_pressures', function (Blueprint $table) {
            $table->unique(['code', 'job_request_id'], 'high3_pressures_code_job_request_unique'); // Create new unique constraint
        });
    }
};

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
        Schema::table('high3_pressures', function (Blueprint $table) {
            $table->dropUnique('high2_pressures_nh2pr_3_unique'); // Drop existing unique constraint
            $table->unique(['code', 'job_request_id'], 'high3_pressures_code_job_request_unique'); // Create new unique constraint
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('high3_pressures', function (Blueprint $table) {
            $table->dropUnique('high3_pressures_code_job_request_unique'); // Drop new unique constraint
            $table->unique('code', 'high2_pressures_nh2pr_3_unique'); // Recreate old unique constraint
        });
    }
};

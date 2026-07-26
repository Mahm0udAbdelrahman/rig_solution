<?php

use Illuminate\Database\Migrations\Migration;
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
        $tables = [
            'through_examinations',
            'mpipts',
            'nregisters',
            'overhead_cranes',
            'overhead_crane2s',
            'pipes_summary_reports',
            'pbls',
            'summaries',
            'treating_irons',
            'tubing_casings',
            'tubing_strings',
            'ultrasonics',
            'visuals',
            'witness_hydros',
            'stabilizer_inspections',
            'subs_dimensionals',
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            $cols = DB::select("SHOW COLUMNS FROM `$table` WHERE Field='id'");
            if (!empty($cols)) {
                $col = $cols[0];
                $hasPrimary = ($col->Key === 'PRI');
                $hasAutoIncrement = (strpos($col->Extra, 'auto_increment') !== false);

                if (!$hasPrimary || !$hasAutoIncrement) {
                    if (!$hasPrimary) {
                        try {
                            DB::statement("ALTER TABLE `$table` ADD PRIMARY KEY (`id`)");
                        } catch (\Throwable $e) {
                            // Primary key might already exist
                        }
                    }

                    if (!$hasAutoIncrement) {
                        try {
                            DB::statement("ALTER TABLE `$table` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
                        } catch (\Throwable $e) {
                            // Ignore if modify fails
                        }
                    }
                }
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
        // No down migration needed for auto_increment fix
    }
};

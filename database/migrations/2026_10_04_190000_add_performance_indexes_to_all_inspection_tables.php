<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * List of inspection tables that participate in inspection listing and need composite indexes.
     */
    protected array $inspectionTables = [
        // Tubular
        'drill_pipes',
        'heavy_weight_pipes',
        'drill_collars',
        'subs_dimensionals',
        'tubing_strings',
        'stabilizer_inspections',
        'reamer_inspections',
        'link_inspections',
        'pbls',
        'tubing_casings',
        'pipes_summary_reports',

        // Lifting
        'cranes',
        'overhead_cranes',
        'forklifts',
        'defects',
        'lregisters',

        // NDT
        'visuals',
        'ultrasonics',
        'summaries',
        'attacheds',
        'high3_pressures',
        'high_pressures',
        'high2_pressures',
        'witness_hydros',
        'treating_irons',
        'drawing_inspections',
        'nregisters',

        // Drop Object
        'drop_objects',

        // Calibration
        'calibration_pressure_gauges',
        'calibration_pressure_tests',
        'calibration_torques',
        'calibration_yokes',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $self = $this;

        foreach ($this->inspectionTables as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            $columns = Schema::getColumnListing($tableName);
            $hasJobRequest = in_array('job_request_id', $columns, true);
            $hasCode = in_array('code', $columns, true);
            $hasUserApproved = in_array('user_id_approved', $columns, true);

            Schema::table($tableName, function (Blueprint $table) use ($self, $tableName, $hasJobRequest, $hasCode, $hasUserApproved) {
                // 1. Composite index on (job_request_id, code, id) for subquery MAX(id) and group filtering
                if ($hasJobRequest && $hasCode) {
                    $indexName = $tableName . '_job_code_id_idx';
                    if (!$self->hasIndex($tableName, $indexName)) {
                        $table->index(['job_request_id', 'code', 'id'], $indexName);
                    }

                    $codeIndexName = $tableName . '_code_idx';
                    if (!$self->hasIndex($tableName, $codeIndexName)) {
                        $table->index(['code'], $codeIndexName);
                    }
                } elseif ($hasJobRequest) {
                    $jobIndexName = $tableName . '_job_req_id_idx';
                    if (!$self->hasIndex($tableName, $jobIndexName)) {
                        $table->index(['job_request_id'], $jobIndexName);
                    }
                }

                // 2. Index on user_id_approved if column exists
                if ($hasUserApproved) {
                    $approvedIndexName = $tableName . '_user_id_approved_idx';
                    if (!$self->hasIndex($tableName, $approvedIndexName)) {
                        $table->index(['user_id_approved'], $approvedIndexName);
                    }
                }
            });
        }

        // 3. Optimized indexes for inspection_reports (catalog & metrics aggregation)
        if (Schema::hasTable('inspection_reports')) {
            Schema::table('inspection_reports', function (Blueprint $table) use ($self) {
                if (!$self->hasIndex('inspection_reports', 'inspection_reports_reportable_metrics_idx')) {
                    $table->index(['reportable_type', 'publish', 'user_id_approved'], 'inspection_reports_reportable_metrics_idx');
                }
                if (!$self->hasIndex('inspection_reports', 'inspection_reports_code_idx')) {
                    $table->index(['code'], 'inspection_reports_code_idx');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $self = $this;

        foreach ($this->inspectionTables as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($self, $tableName) {
                $indexName = $tableName . '_job_code_id_idx';
                if ($self->hasIndex($tableName, $indexName)) {
                    $table->dropIndex($indexName);
                }

                $codeIndexName = $tableName . '_code_idx';
                if ($self->hasIndex($tableName, $codeIndexName)) {
                    $table->dropIndex($codeIndexName);
                }

                $jobIndexName = $tableName . '_job_req_id_idx';
                if ($self->hasIndex($tableName, $jobIndexName)) {
                    $table->dropIndex($jobIndexName);
                }

                $approvedIndexName = $tableName . '_user_id_approved_idx';
                if ($self->hasIndex($tableName, $approvedIndexName)) {
                    $table->dropIndex($approvedIndexName);
                }
            });
        }

        if (Schema::hasTable('inspection_reports')) {
            Schema::table('inspection_reports', function (Blueprint $table) use ($self) {
                if ($self->hasIndex('inspection_reports', 'inspection_reports_reportable_metrics_idx')) {
                    $table->dropIndex('inspection_reports_reportable_metrics_idx');
                }
                if ($self->hasIndex('inspection_reports', 'inspection_reports_code_idx')) {
                    $table->dropIndex('inspection_reports_code_idx');
                }
            });
        }
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        $database = DB::getDatabaseName();

        return DB::table('information_schema.statistics')
            ->where('table_schema', $database)
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->exists();
    }
};

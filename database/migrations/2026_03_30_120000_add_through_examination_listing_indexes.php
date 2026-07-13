<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $self = $this;

        if (Schema::hasTable('through_examinations')) {
            Schema::table('through_examinations', function (Blueprint $table) use ($self) {
                if (!$self->hasIndex('through_examinations', 'through_examinations_job_code_id_idx')) {
                    $table->index(['job_request_id', 'code', 'id'], 'through_examinations_job_code_id_idx');
                }

                if (!$self->hasIndex('through_examinations', 'through_examinations_code_idx')) {
                    $table->index(['code'], 'through_examinations_code_idx');
                }
            });
        }

        if (Schema::hasTable('inspection_reports')) {
            Schema::table('inspection_reports', function (Blueprint $table) use ($self) {
                if (!$self->hasIndex('inspection_reports', 'inspection_reports_reportable_type_id_idx')) {
                    $table->index(['reportable_type', 'reportable_id'], 'inspection_reports_reportable_type_id_idx');
                }

                if (!$self->hasIndex('inspection_reports', 'inspection_reports_reportable_state_idx')) {
                    $table->index(['reportable_type', 'reportable_id', 'publish', 'user_id_approved'], 'inspection_reports_reportable_state_idx');
                }
            });
        }

        if (Schema::hasTable('job_requests')) {
            Schema::table('job_requests', function (Blueprint $table) use ($self) {
                if (!$self->hasIndex('job_requests', 'job_requests_code_idx')) {
                    $table->index(['code'], 'job_requests_code_idx');
                }
            });
        }

        if (Schema::hasTable('file_managers')) {
            Schema::table('file_managers', function (Blueprint $table) use ($self) {
                if (!$self->hasIndex('file_managers', 'file_managers_inspection_lookup_idx')) {
                    $table->index(
                        ['module', 'category', 'extension', 'is_available', 'job_request_code', 'entity_code'],
                        'file_managers_inspection_lookup_idx'
                    );
                }
            });
        }
    }

    public function down(): void
    {
        $self = $this;

        if (Schema::hasTable('through_examinations')) {
            Schema::table('through_examinations', function (Blueprint $table) use ($self) {
                if ($self->hasIndex('through_examinations', 'through_examinations_job_code_id_idx')) {
                    $table->dropIndex('through_examinations_job_code_id_idx');
                }

                if ($self->hasIndex('through_examinations', 'through_examinations_code_idx')) {
                    $table->dropIndex('through_examinations_code_idx');
                }
            });
        }

        if (Schema::hasTable('inspection_reports')) {
            Schema::table('inspection_reports', function (Blueprint $table) use ($self) {
                if ($self->hasIndex('inspection_reports', 'inspection_reports_reportable_type_id_idx')) {
                    $table->dropIndex('inspection_reports_reportable_type_id_idx');
                }

                if ($self->hasIndex('inspection_reports', 'inspection_reports_reportable_state_idx')) {
                    $table->dropIndex('inspection_reports_reportable_state_idx');
                }
            });
        }

        if (Schema::hasTable('job_requests')) {
            Schema::table('job_requests', function (Blueprint $table) use ($self) {
                if ($self->hasIndex('job_requests', 'job_requests_code_idx')) {
                    $table->dropIndex('job_requests_code_idx');
                }
            });
        }

        if (Schema::hasTable('file_managers')) {
            Schema::table('file_managers', function (Blueprint $table) use ($self) {
                if ($self->hasIndex('file_managers', 'file_managers_inspection_lookup_idx')) {
                    $table->dropIndex('file_managers_inspection_lookup_idx');
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

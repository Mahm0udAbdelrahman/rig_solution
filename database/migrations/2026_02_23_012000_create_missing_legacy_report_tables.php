<?php

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
        $addLegacyColumns = function (Blueprint $table, array $columns) {
            foreach ($columns as $column) {
                if (str_ends_with($column, '_id')) {
                    $table->unsignedBigInteger($column)->nullable()->index();
                    continue;
                }

                if ($column === 'date' || str_ends_with($column, '_date')) {
                    $table->date($column)->nullable();
                    continue;
                }

                if (in_array($column, ['sync', 'updated', 'status', 'publish'], true)) {
                    $table->integer($column)->nullable();
                    continue;
                }

                if (in_array($column, ['code', 'color_code', 'edition'], true)) {
                    $table->string($column)->nullable();
                    continue;
                }

                $table->text($column)->nullable();
            }
        };

        if (!Schema::hasTable('clients')) {
            Schema::create('clients', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable();
                $table->string('name');
                $table->string('password')->nullable();
                $table->string('email')->nullable();
                $table->string('fax')->nullable();
                $table->string('tax_card')->nullable();
                $table->string('tel')->nullable();
                $table->string('location')->nullable();
                $table->string('url')->nullable();
                $table->text('desc')->nullable();
                $table->text('logo')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('tel')->nullable();
                $table->string('fax')->nullable();
                $table->string('tax_card')->nullable();
                $table->string('location')->nullable();
                $table->string('url')->nullable();
                $table->text('desc')->nullable();
                $table->text('logo')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('contact_people')) {
            Schema::create('contact_people', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('tel')->nullable();
                $table->string('postion')->nullable();
                $table->unsignedBigInteger('responseable_id')->nullable()->index();
                $table->string('responseable_type')->nullable()->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('specifications')) {
            Schema::create('specifications', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable()->index();
                $table->string('name');
                $table->text('desc')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('job_requests')) {
            Schema::create('job_requests', function (Blueprint $table) use ($addLegacyColumns) {
                $table->id();
                $addLegacyColumns($table, [
                    'code',
                    'purchase_order',
                    'jcf_ref',
                    'client_id',
                    'supplier_id',
                    'contact_people_id',
                    'client_department_id',
                    'subject',
                    'work_location',
                    'contactway',
                    'user_id',
                    'job_requierd_details',
                    'contact_date',
                    'managers',
                    'tools',
                    'scope_of_work',
                    'specification',
                    'deploc',
                    'sync',
                    'updated',
                ]);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('inspection_reports')) {
            Schema::create('inspection_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('job_request_id')->nullable()->index();
                $table->string('code')->nullable();
                $table->integer('status')->nullable();
                $table->integer('publish')->nullable();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('user_id_edit')->nullable()->index();
                $table->unsignedBigInteger('user_id_approved')->nullable()->index();
                $table->unsignedBigInteger('reportable_id')->nullable()->index();
                $table->string('reportable_type')->nullable()->index();
                $table->integer('sync')->nullable();
                $table->integer('updated')->nullable();
                $table->timestamps();
            });
        }

        $reportTables = [
            'cranes' => [
                'job_request_id', 'lcr_2', 'code', 'lcr_4', 'lcr_5', 'lcr_6', 'lcr_7', 'lcr_8', 'lcr_10',
                'lcr_11', 'lcr_12', 'lcr_13', 'lcr_14', 'lcr_15', 'lcr_16', 'lcr_17', 'lcr_18', 'lcr_19',
                'lcr_20', 'lcr_21', 'lcr_22', 'lcr_23', 'lcr_24', 'lcr_25', 'lcr_26', 'lcr_27', 'lcr_28',
                'lcr_29', 'lcr_30', 'lcr_31', 'lcr_32', 'lcr_33', 'lcr_34', 'lcr_35', 'lcr_36', 'lcr_37',
                'lcr_38', 'lcr_39', 'lcr_40', 'lcr_41', 'lcr_42', 'lcr_43', 'lcr_44', 'lcr_45', 'lcr_46',
                'sync', 'updated',
            ],
            'overhead_cranes' => [
                'job_request_id', 'locr_2', 'code', 'locr_5', 'locr_6', 'locr_7', 'locr_8', 'locr_10',
                'locr_11', 'locr_12', 'locr_13', 'locr_14', 'locr_15', 'locr_16', 'locr_17', 'locr_18',
                'locr_19', 'locr_20', 'locr_21', 'locr_22', 'locr_23', 'locr_24', 'locr_25', 'locr_26',
                'locr_27', 'locr_28', 'locr_29', 'locr_30', 'locr_31', 'locr_32', 'locr_33', 'locr_34',
                'locr_35', 'locr_36', 'locr_37', 'locr_38', 'locr_39', 'locr_40', 'locr_41', 'locr_42',
                'locr_43', 'locr_44', 'sync', 'updated',
            ],
            'forklifts' => [
                'job_request_id', 'lfr_2', 'code', 'lfr_6', 'lfr_7', 'lfr_8', 'lfr_10', 'lfr_11', 'lfr_12',
                'lfr_13', 'lfr_14', 'lfr_15', 'lfr_16', 'lfr_17', 'lfr_18', 'lfr_19', 'lfr_20', 'lfr_21',
                'lfr_22', 'lfr_23', 'lfr_24', 'lfr_25', 'lfr_26', 'lfr_27', 'lfr_28', 'lfr_29', 'lfr_30',
                'lfr_31', 'lfr_32', 'lfr_33', 'lfr_34', 'sync', 'updated',
            ],
            'through_examinations' => [
                'job_request_id', 'lter_2', 'code', 'lter_6', 'lter_7', 'lter_8', 'lter_10', 'lter_15',
                'lter_16', 'lter_17', 'lter_18', 'lter_19', 'lter_20', 'lter_21', 'lter_22', 'lter_23',
                'lter_24', 'lter_25', 'lter_26', 'lter_27', 'lter_28', 'lter_29', 'lter_30', 'lter_31',
                'lter_32', 'lter_33', 'lter_34', 'lter_35', 'lter_36', 'lter_37', 'lter_38', 'lter_39',
                'lter_40', 'lter_41', 'lter_42', 'lter_43', 'lter_44', 'lter_45', 'lter_46', 'lter_47',
                'lter_48', 'lter_49', 'lter_50', 'lter_51', 'sync', 'updated',
            ],
            'defects' => [
                'job_request_id', 'ldr_2', 'code', 'ldr_6', 'ldr_8', 'sync', 'updated',
            ],
            'lregisters' => [
                'job_request_id', 'date', 'code', 'color_code', 'sync', 'updated',
            ],
            'mpipts' => [
                'job_request_id', 'nmpr_2', 'code', 'nmpr_6', 'nmpr_7', 'nmpr_8', 'nmpr_10', 'acceptance',
                'nmpr_12', 'nmpr_13', 'desc', 'nmpr_28', 'nmpr_29', 'nmpr_30', 'nmpr_800', 'nmpr_900',
                'nmpr_1000', 'sync',
            ],
            'visuals' => [
                'job_request_id', 'nvr_2', 'code', 'nvr_4', 'desc', 'nvr_6', 'nvr_7', 'nvr_8', 'nvr_9',
                'nvr_10', 'nvr_11', 'nvr_12', 'nvr_13', 'nvr_14', 'nvr_15', 'nvr_16', 'acceptance', 'nvr_18',
                'nvr_19', 'nvr_23', 'sync', 'updated',
            ],
            'ultrasonics' => [
                'job_request_id', 'nur_2', 'code', 'nur_6', 'nur_8', 'nur_9', 'nur_10', 'acceptance',
                'nur_12', 'nur_13', 'nur_14', 'nur_15', 'nur_16', 'nur_17', 'nur_18', 'nur_19', 'nur_20',
                'nur_21', 'nur_22', 'nur_23', 'nur_24', 'desc', 'nur_26', 'nur_27', 'nur_28', 'nur_29',
                'nur_30', 'nur_31', 'nur_32', 'nur_33', 'nur_34', 'sync', 'updated',
            ],
            'summaries' => [
                'job_request_id', 'nsr_2', 'code', 'nsr_4', 'desc', 'nsr_7', 'nsr_8', 'sync', 'updated',
            ],
            'attacheds' => [
                'job_request_id', 'nar_2', 'code', 'nar_4', 'desc', 'nar_6', 'sync',
            ],
            'witness_hydros' => [
                'job_request_id', 'nwhr_2', 'code', 'nwhr_6', 'nwhr_7', 'nwhr_8', 'nwhr_9', 'nwhr_10',
                'acceptance', 'desc', 'nwhr_15', 'nwhr_16', 'nwhr_17', 'nwhr_18', 'nwhr_19', 'nwhr_20',
                'nwhr_21', 'nwhr_22', 'nwhr_23', 'nwhr_24', 'nwhr_25', 'nwhr_26', 'nwhr_27', 'nwhr_28',
                'nwhr_29', 'nwhr_30', 'nwhr_31', 'nwhr_32', 'nwhr_33', 'nwhr_34', 'nwhr_35', 'nwhr_36',
                'nwhr_37', 'nwhr_38', 'nwhr_39',
            ],
            'high_pressures' => [
                'job_request_id', 'nhpr_2', 'code', 'nhpr_6', 'nhpr_7', 'desc', 'edition', 'nhpr_10', 'nhpr_11',
                'nhpr_12', 'acceptance', 'nhpr_14', 'nhpr_15', 'nhpr_16', 'nhpr_17', 'nhpr_18', 'nhpr_19',
                'nhpr_20', 'nhpr_21', 'nhpr_22', 'nhpr_23', 'nhpr_24', 'nhpr_25', 'nhpr_26', 'nhpr_27',
                'nhpr_28', 'nhpr_29', 'nhpr_30', 'nhpr_31', 'nhpr_32',
            ],
            'high2_pressures' => [
                'job_request_id', 'nh2pr_2', 'code', 'nh2pr_6', 'nh2pr_7', 'desc', 'edition', 'nh2pr_9',
                'nh2pr_10', 'nh2pr_11', 'nh2pr_12', 'acceptance', 'nh2pr_14', 'nh2pr_15', 'nh2pr_16',
                'nh2pr_17', 'nh2pr_18', 'nh2pr_19', 'nh2pr_20', 'nh2pr_21', 'nh2pr_22', 'nh2pr_23',
                'nh2pr_24', 'nh2pr_25', 'nh2pr_26', 'nh2pr_27', 'nh2pr_28', 'nh2pr_29', 'nh2pr_30',
                'nh2pr_31', 'nh2pr_32', 'nh2pr_33', 'sync',
            ],
            'high3_pressures' => [
                'job_request_id', 'nh2pr_2', 'code', 'nh2pr_6', 'nh2pr_7', 'desc', 'edition', 'nh2pr_10',
                'nh2pr_11', 'nh2pr_12', 'acceptance', 'nh2pr_14', 'nh2pr_15', 'nh2pr_16', 'nh2pr_17',
                'nh2pr_18', 'nh2pr_19', 'nh2pr_20', 'nh2pr_21', 'nh2pr_22', 'nh2pr_23', 'nh2pr_24',
                'nh2pr_25', 'nh2pr_26', 'nh2pr_27', 'nh2pr_28', 'nh2pr_29', 'nh2pr_30', 'nh2pr_31',
                'nh2pr_32', 'nh2pr_33', 'sync',
            ],
            'treating_irons' => [
                'job_request_id', 'ntir_2', 'code', 'ntir_6', 'ntir_7', 'ntir_9', 'ntir_10', 'ntir_11', 'desc',
                'ntir_13', 'ntir_14', 'ntir_15', 'ntir_16', 'ntir_17', 'ntir_18', 'ntir_19', 'ntir_20',
                'ntir_21', 'ntir_22', 'ntir_23', 'ntir_24', 'ntir_25', 'ntir_26', 'ntir_27', 'ntir_28',
                'ntir_29', 'ntir_30', 'ntir_31', 'ntir_32', 'ntir_33', 'ntir_34', 'ntir_35', 'ntir_36',
                'ntir_37', 'ntir_38', 'ntir_39', 'ntir_40', 'ntir_41', 'ntir_42', 'ntir_43', 'ntir_44',
                'ntir_45', 'ntir_46', 'ntir_47', 'ntir_48', 'ntir_49', 'sync',
            ],
        ];

        foreach ($reportTables as $tableName => $columns) {
            if (!Schema::hasTable($tableName)) {
                Schema::create($tableName, function (Blueprint $table) use ($addLegacyColumns, $columns) {
                    $table->id();
                    $addLegacyColumns($table, $columns);
                    $table->timestamps();
                });
            }
        }

        $allowDataMutations = !app()->environment('production')
            || (bool) env('ALLOW_PROD_MIGRATION_DATA_PATCHES', false);

        if (!$allowDataMutations) {
            return;
        }

        // Seed minimal NDT specification options used by forms.
        if (Schema::hasTable('specifications')) {
            $requiredSpecifications = [
                ['code' => 'S-002', 'name' => 'API', 'desc' => 'Bootstrap specification'],
                ['code' => 'S-003', 'name' => 'ASTM', 'desc' => 'Bootstrap specification'],
                ['code' => 'S-005', 'name' => 'ASME', 'desc' => 'Bootstrap specification'],
                ['code' => 'S-008', 'name' => 'Customer Spec', 'desc' => 'Bootstrap specification'],
                ['code' => 'S-009', 'name' => 'Other', 'desc' => 'Bootstrap specification'],
            ];

            foreach ($requiredSpecifications as $spec) {
                $exists = DB::table('specifications')->where('code', $spec['code'])->exists();
                if (!$exists) {
                    DB::table('specifications')->insert([
                        'code' => $spec['code'],
                        'name' => $spec['name'],
                        'desc' => $spec['desc'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
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
        $tables = [
            'treating_irons',
            'high3_pressures',
            'high2_pressures',
            'high_pressures',
            'witness_hydros',
            'attacheds',
            'summaries',
            'ultrasonics',
            'visuals',
            'mpipts',
            'lregisters',
            'defects',
            'through_examinations',
            'forklifts',
            'overhead_cranes',
            'cranes',
            'inspection_reports',
            'job_requests',
            'specifications',
            'contact_people',
            'suppliers',
            'clients',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};

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
        $ensureColumns = function (string $tableName, array $columnDefinitions): void {
            if (!Schema::hasTable($tableName)) {
                return;
            }

            foreach ($columnDefinitions as $columnName => $definition) {
                if (Schema::hasColumn($tableName, $columnName)) {
                    continue;
                }

                Schema::table($tableName, function (Blueprint $table) use ($definition) {
                    $definition($table);
                });
            }
        };

        if (!Schema::hasTable('tools')) {
            Schema::create('tools', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable()->index();
                $table->string('name')->nullable();
                $table->text('desc')->nullable();
                $table->timestamps();
            });
        }

        $ensureColumns('tools', [
            'code' => fn (Blueprint $table) => $table->string('code')->nullable(),
            'name' => fn (Blueprint $table) => $table->string('name')->nullable(),
            'desc' => fn (Blueprint $table) => $table->text('desc')->nullable(),
        ]);

        if (!Schema::hasTable('jcf_statuses')) {
            Schema::create('jcf_statuses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('job_request_id')->nullable()->index();
                $table->integer('status')->nullable();
                $table->string('comment')->nullable();
                $table->date('start_at')->nullable();
                $table->date('end_at')->nullable();
                $table->timestamps();
            });
        }

        $ensureColumns('jcf_statuses', [
            'job_request_id' => fn (Blueprint $table) => $table->unsignedBigInteger('job_request_id')->nullable(),
            'status' => fn (Blueprint $table) => $table->integer('status')->nullable(),
            'comment' => fn (Blueprint $table) => $table->string('comment')->nullable(),
            'start_at' => fn (Blueprint $table) => $table->date('start_at')->nullable(),
            'end_at' => fn (Blueprint $table) => $table->date('end_at')->nullable(),
        ]);

        if (!Schema::hasTable('qutations')) {
            Schema::create('qutations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('job_request_id')->nullable()->index();
                $table->string('code')->nullable()->index();
                $table->date('delivery')->nullable();
                $table->text('location')->nullable();
                $table->longText('payment_method')->nullable();
                $table->longText('terms')->nullable();
                $table->longText('items')->nullable();
                $table->text('subject')->nullable();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('user_id_approved')->nullable()->index();
                $table->string('type')->nullable();
                $table->integer('sync')->nullable();
                $table->integer('updated')->nullable();
                $table->text('creation_date')->nullable();
                $table->timestamps();
            });
        }

        $ensureColumns('qutations', [
            'job_request_id' => fn (Blueprint $table) => $table->unsignedBigInteger('job_request_id')->nullable(),
            'code' => fn (Blueprint $table) => $table->string('code')->nullable(),
            'delivery' => fn (Blueprint $table) => $table->date('delivery')->nullable(),
            'location' => fn (Blueprint $table) => $table->text('location')->nullable(),
            'payment_method' => fn (Blueprint $table) => $table->longText('payment_method')->nullable(),
            'terms' => fn (Blueprint $table) => $table->longText('terms')->nullable(),
            'items' => fn (Blueprint $table) => $table->longText('items')->nullable(),
            'subject' => fn (Blueprint $table) => $table->text('subject')->nullable(),
            'user_id' => fn (Blueprint $table) => $table->unsignedBigInteger('user_id')->nullable(),
            'user_id_approved' => fn (Blueprint $table) => $table->unsignedBigInteger('user_id_approved')->nullable(),
            'type' => fn (Blueprint $table) => $table->string('type')->nullable(),
            'sync' => fn (Blueprint $table) => $table->integer('sync')->nullable(),
            'updated' => fn (Blueprint $table) => $table->integer('updated')->nullable(),
            'creation_date' => fn (Blueprint $table) => $table->text('creation_date')->nullable(),
        ]);

        if (!Schema::hasTable('packing_slips')) {
            Schema::create('packing_slips', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable()->index();
                $table->unsignedBigInteger('job_request_id')->nullable()->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->text('dlocation')->nullable();
                $table->text('po')->nullable();
                $table->text('shppingmethods')->nullable();
                $table->date('orderdate')->nullable();
                $table->longText('items')->nullable();
                $table->text('notice')->nullable();
                $table->text('transportation')->nullable();
                $table->text('received')->nullable();
                $table->text('delivered')->nullable();
                $table->integer('sync')->nullable();
                $table->integer('updated')->nullable();
                $table->timestamps();
            });
        }

        $ensureColumns('packing_slips', [
            'code' => fn (Blueprint $table) => $table->string('code')->nullable(),
            'job_request_id' => fn (Blueprint $table) => $table->unsignedBigInteger('job_request_id')->nullable(),
            'user_id' => fn (Blueprint $table) => $table->unsignedBigInteger('user_id')->nullable(),
            'dlocation' => fn (Blueprint $table) => $table->text('dlocation')->nullable(),
            'po' => fn (Blueprint $table) => $table->text('po')->nullable(),
            'shppingmethods' => fn (Blueprint $table) => $table->text('shppingmethods')->nullable(),
            'orderdate' => fn (Blueprint $table) => $table->date('orderdate')->nullable(),
            'items' => fn (Blueprint $table) => $table->longText('items')->nullable(),
            'notice' => fn (Blueprint $table) => $table->text('notice')->nullable(),
            'transportation' => fn (Blueprint $table) => $table->text('transportation')->nullable(),
            'received' => fn (Blueprint $table) => $table->text('received')->nullable(),
            'delivered' => fn (Blueprint $table) => $table->text('delivered')->nullable(),
            'sync' => fn (Blueprint $table) => $table->integer('sync')->nullable(),
            'updated' => fn (Blueprint $table) => $table->integer('updated')->nullable(),
        ]);

        if (!Schema::hasTable('service_tickets')) {
            Schema::create('service_tickets', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable()->index();
                $table->text('location')->nullable();
                $table->date('start')->nullable();
                $table->date('end')->nullable();
                $table->unsignedBigInteger('job_request_id')->nullable()->index();
                $table->longText('services')->nullable();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->text('notice')->nullable();
                $table->date('approval_date')->nullable();
                $table->integer('sync')->nullable();
                $table->integer('updated')->nullable();
                $table->timestamps();
            });
        }

        $ensureColumns('service_tickets', [
            'code' => fn (Blueprint $table) => $table->string('code')->nullable(),
            'location' => fn (Blueprint $table) => $table->text('location')->nullable(),
            'start' => fn (Blueprint $table) => $table->date('start')->nullable(),
            'end' => fn (Blueprint $table) => $table->date('end')->nullable(),
            'job_request_id' => fn (Blueprint $table) => $table->unsignedBigInteger('job_request_id')->nullable(),
            'services' => fn (Blueprint $table) => $table->longText('services')->nullable(),
            'user_id' => fn (Blueprint $table) => $table->unsignedBigInteger('user_id')->nullable(),
            'notice' => fn (Blueprint $table) => $table->text('notice')->nullable(),
            'approval_date' => fn (Blueprint $table) => $table->date('approval_date')->nullable(),
            'sync' => fn (Blueprint $table) => $table->integer('sync')->nullable(),
            'updated' => fn (Blueprint $table) => $table->integer('updated')->nullable(),
        ]);

        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable();
                $table->string('invoice_company_type')->default('RSE');
                $table->text('cpo')->nullable();
                $table->text('contract')->nullable();
                $table->unsignedBigInteger('job_request_id')->nullable()->index();
                $table->longText('items')->nullable();
                $table->longText('terms')->nullable();
                $table->decimal('sub_total', 15, 2)->nullable();
                $table->decimal('tax', 15, 2)->nullable();
                $table->decimal('withholding', 15, 2)->nullable();
                $table->decimal('total', 15, 2)->nullable();
                $table->string('type')->nullable();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('user_id_edit')->nullable()->index();
                $table->unsignedBigInteger('user_id_approved')->nullable()->index();
                $table->integer('sync')->nullable();
                $table->integer('updated')->nullable();
                $table->timestamps();
                $table->unique(['code', 'invoice_company_type'], 'invoices_code_company_type_unique');
            });
        }

        $ensureColumns('invoices', [
            'code' => fn (Blueprint $table) => $table->string('code')->nullable(),
            'invoice_company_type' => fn (Blueprint $table) => $table->string('invoice_company_type')->default('RSE'),
            'cpo' => fn (Blueprint $table) => $table->text('cpo')->nullable(),
            'contract' => fn (Blueprint $table) => $table->text('contract')->nullable(),
            'job_request_id' => fn (Blueprint $table) => $table->unsignedBigInteger('job_request_id')->nullable(),
            'items' => fn (Blueprint $table) => $table->longText('items')->nullable(),
            'terms' => fn (Blueprint $table) => $table->longText('terms')->nullable(),
            'sub_total' => fn (Blueprint $table) => $table->decimal('sub_total', 15, 2)->nullable(),
            'tax' => fn (Blueprint $table) => $table->decimal('tax', 15, 2)->nullable(),
            'withholding' => fn (Blueprint $table) => $table->decimal('withholding', 15, 2)->nullable(),
            'total' => fn (Blueprint $table) => $table->decimal('total', 15, 2)->nullable(),
            'type' => fn (Blueprint $table) => $table->string('type')->nullable(),
            'user_id' => fn (Blueprint $table) => $table->unsignedBigInteger('user_id')->nullable(),
            'user_id_edit' => fn (Blueprint $table) => $table->unsignedBigInteger('user_id_edit')->nullable(),
            'user_id_approved' => fn (Blueprint $table) => $table->unsignedBigInteger('user_id_approved')->nullable(),
            'sync' => fn (Blueprint $table) => $table->integer('sync')->nullable(),
            'updated' => fn (Blueprint $table) => $table->integer('updated')->nullable(),
        ]);

        if (!Schema::hasTable('department_job_request')) {
            Schema::create('department_job_request', function (Blueprint $table) {
                $table->unsignedBigInteger('department_id')->index();
                $table->unsignedBigInteger('job_request_id')->index();
                $table->unique(['department_id', 'job_request_id'], 'department_job_request_unique');
            });
        }

        $ensureColumns('department_job_request', [
            'department_id' => fn (Blueprint $table) => $table->unsignedBigInteger('department_id')->nullable(),
            'job_request_id' => fn (Blueprint $table) => $table->unsignedBigInteger('job_request_id')->nullable(),
        ]);

        if (!Schema::hasTable('employee_job_request')) {
            Schema::create('employee_job_request', function (Blueprint $table) {
                $table->unsignedBigInteger('employee_id')->index();
                $table->unsignedBigInteger('job_request_id')->index();
                $table->unique(['employee_id', 'job_request_id'], 'employee_job_request_unique');
            });
        }

        $ensureColumns('employee_job_request', [
            'employee_id' => fn (Blueprint $table) => $table->unsignedBigInteger('employee_id')->nullable(),
            'job_request_id' => fn (Blueprint $table) => $table->unsignedBigInteger('job_request_id')->nullable(),
        ]);

        $allowDataMutations = !app()->environment('production')
            || (bool) env('ALLOW_PROD_MIGRATION_DATA_PATCHES', false);

        if (!$allowDataMutations) {
            return;
        }

        // Bootstrap minimal references so JCF forms open on clean databases.
        if (Schema::hasTable('departments') && DB::table('departments')->count() === 0) {
            DB::table('departments')->insert([
                'code' => 'DEP-001',
                'name' => 'General Department',
                'desc' => 'Bootstrap department',
                'employee_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (Schema::hasTable('tools') && DB::table('tools')->count() === 0) {
            DB::table('tools')->insert([
                'code' => 'T-001',
                'name' => 'General Tool',
                'desc' => 'Bootstrap tool',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (Schema::hasTable('job_requests') && Schema::hasTable('jcf_statuses')) {
            $jobRequestIds = DB::table('job_requests')->pluck('id');
            foreach ($jobRequestIds as $jobRequestId) {
                $exists = DB::table('jcf_statuses')->where('job_request_id', $jobRequestId)->exists();
                if (!$exists) {
                    DB::table('jcf_statuses')->insert([
                        'job_request_id' => $jobRequestId,
                        'status' => 1,
                        'comment' => null,
                        'start_at' => now()->toDateString(),
                        'end_at' => now()->toDateString(),
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
        Schema::dropIfExists('employee_job_request');
        Schema::dropIfExists('department_job_request');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('service_tickets');
        Schema::dropIfExists('packing_slips');
        Schema::dropIfExists('qutations');
        Schema::dropIfExists('jcf_statuses');
        Schema::dropIfExists('tools');
    }
};

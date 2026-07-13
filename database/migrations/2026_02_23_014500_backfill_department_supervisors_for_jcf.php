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
        $allowDataMutations = !app()->environment('production')
            || (bool) env('ALLOW_PROD_MIGRATION_DATA_PATCHES', false);

        if (!$allowDataMutations) {
            return;
        }

        if (!Schema::hasTable('departments') || !Schema::hasTable('employees')) {
            return;
        }

        $firstEmployeeId = DB::table('employees')->orderBy('id')->value('id');
        if (!$firstEmployeeId) {
            return;
        }

        // Ensure each department has a supervisor on fresh/incomplete databases.
        DB::table('departments')
            ->whereNull('employee_id')
            ->update(['employee_id' => $firstEmployeeId]);

        if (!Schema::hasTable('department_employee')) {
            return;
        }

        $departments = DB::table('departments')->select('id', 'employee_id')->get();
        foreach ($departments as $department) {
            if (!$department->employee_id) {
                continue;
            }

            $exists = DB::table('department_employee')
                ->where('department_id', $department->id)
                ->where('employee_id', $department->employee_id)
                ->exists();

            if (!$exists) {
                DB::table('department_employee')->insert([
                    'department_id' => $department->id,
                    'employee_id' => $department->employee_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
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
        if (!Schema::hasTable('departments') || !Schema::hasTable('department_employee')) {
            return;
        }

        $departments = DB::table('departments')->select('id', 'employee_id')->get();
        foreach ($departments as $department) {
            if (!$department->employee_id) {
                continue;
            }

            DB::table('department_employee')
                ->where('department_id', $department->id)
                ->where('employee_id', $department->employee_id)
                ->delete();
        }
    }
};

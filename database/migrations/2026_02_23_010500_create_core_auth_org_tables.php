<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->longText('roles')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('tel')->nullable();
                $table->text('esign')->nullable();
                $table->text('desc')->nullable();
                $table->string('type')->nullable();
                $table->timestamps();
            });
        }
        if (Schema::hasTable('employees') && !Schema::hasColumn('employees', 'type')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->string('type')->nullable()->after('desc');
            });
        }

        if (!Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable();
                $table->string('name');
                $table->text('desc')->nullable();
                $table->unsignedBigInteger('employee_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('department_employee')) {
            Schema::create('department_employee', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('department_id');
                $table->unsignedBigInteger('employee_id');
                $table->timestamps();
                $table->unique(['department_id', 'employee_id'], 'department_employee_unique');
            });
        }

        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id')->unique();
                $table->unsignedBigInteger('role_id')->nullable();
                $table->string('password');
                $table->timestamp('email_verified_at')->nullable();
                $table->rememberToken();
                $table->boolean('is_super_admin')->default(false);
                $table->boolean('is_active')->nullable();
                $table->timestamp('last_active_at')->nullable();
                $table->timestamps();
            });
        }

        $allowDataMutations = !app()->environment('production')
            || (bool) env('ALLOW_PROD_MIGRATION_DATA_PATCHES', false);

        if (!$allowDataMutations) {
            return;
        }

        // Bootstrap a default login if this is a fresh/incomplete database.
        $roleId = DB::table('roles')->where('name', 'Super Admin')->value('id');
        if (!$roleId) {
            $roleId = DB::table('roles')->insertGetId([
                'name' => 'Super Admin',
                'roles' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $employeeId = DB::table('employees')->where('email', 'rigsolut')->value('id');
        if (!$employeeId) {
            $employeeId = DB::table('employees')->where('code', 'EMP-001')->value('id');
        }
        if (!$employeeId) {
            $employeePayload = [
                'code' => 'EMP-001',
                'name' => 'System Admin',
                'email' => 'rigsolut',
                'tel' => '',
                'esign' => '',
                'desc' => 'Bootstrap admin account',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (Schema::hasColumn('employees', 'type')) {
                $employeePayload['type'] = '';
            }

            $employeeId = DB::table('employees')->insertGetId($employeePayload);
        }

        $userId = DB::table('users')->where('employee_id', $employeeId)->value('id');
        if (!$userId) {
            DB::table('users')->insert([
                'employee_id' => $employeeId,
                'role_id' => $roleId,
                'password' => Hash::make('123456'),
                'is_super_admin' => true,
                'is_active' => true,
                'last_active_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('department_employee');
        Schema::dropIfExists('users');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('roles');
    }
};

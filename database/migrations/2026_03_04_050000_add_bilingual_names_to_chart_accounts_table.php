<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('chart_accounts')) {
            return;
        }

        Schema::table('chart_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('chart_accounts', 'name_en')) {
                $table->string('name_en')->nullable()->after('name');
            }

            if (!Schema::hasColumn('chart_accounts', 'name_ar')) {
                $table->string('name_ar')->nullable()->after('name_en');
            }
        });

        $knownArabicNames = [
            '1000' => 'الأصول',
            '1100' => 'النقدية والبنوك',
            '1200' => 'الذمم المدينة',
            '1300' => 'المخزون',
            '2000' => 'الالتزامات',
            '2100' => 'الذمم الدائنة',
            '2200' => 'الضرائب المستحقة',
            '3000' => 'حقوق الملكية',
            '3100' => 'الأرباح المحتجزة',
            '4000' => 'الإيرادات',
            '4100' => 'إيراد الخدمات',
            '5000' => 'المصروفات',
            '5100' => 'تكلفة الخدمات',
            '5200' => 'المصروفات التشغيلية',
        ];

        DB::table('chart_accounts')
            ->select('id', 'code', 'name', 'name_en', 'name_ar')
            ->orderBy('id')
            ->get()
            ->each(function ($account) use ($knownArabicNames) {
                $englishName = trim((string) ($account->name_en ?: $account->name ?: ''));
                $arabicName = trim((string) ($account->name_ar ?: ($knownArabicNames[$account->code] ?? $account->name ?: '')));

                DB::table('chart_accounts')
                    ->where('id', $account->id)
                    ->update([
                        'name_en' => $englishName !== '' ? $englishName : null,
                        'name_ar' => $arabicName !== '' ? $arabicName : null,
                        'name' => $englishName !== '' ? $englishName : ($arabicName !== '' ? $arabicName : $account->name),
                    ]);
            });
    }

    public function down()
    {
        if (!Schema::hasTable('chart_accounts')) {
            return;
        }

        Schema::table('chart_accounts', function (Blueprint $table) {
            $dropColumns = [];

            foreach (['name_en', 'name_ar'] as $column) {
                if (Schema::hasColumn('chart_accounts', $column)) {
                    $dropColumns[] = $column;
                }
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};

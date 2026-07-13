<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('accountants') && !Schema::hasColumn('accountants', 'approved_at')) {
            Schema::table('accountants', function (Blueprint $table) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            });
        }

        if (!Schema::hasTable('accountant_audits')) {
            Schema::create('accountant_audits', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('accountant_id')->index();
                $table->string('action', 100);
                $table->string('from_status', 100)->nullable();
                $table->string('to_status', 100)->nullable();
                $table->unsignedBigInteger('changed_by')->nullable()->index();
                $table->longText('payload')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('accountant_audits');

        if (Schema::hasTable('accountants') && Schema::hasColumn('accountants', 'approved_at')) {
            Schema::table('accountants', function (Blueprint $table) {
                $table->dropColumn('approved_at');
            });
        }
    }
};


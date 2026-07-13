<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('mail_center_settings')) {
            return;
        }

        Schema::table('mail_center_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('mail_center_settings', 'navbar_polling_enabled')) {
                $table->boolean('navbar_polling_enabled')->default(1)->after('show_navbar_notifications');
            }

            if (!Schema::hasColumn('mail_center_settings', 'navbar_polling_interval_seconds')) {
                $table->unsignedSmallInteger('navbar_polling_interval_seconds')->default(60)->after('navbar_polling_enabled');
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('mail_center_settings')) {
            return;
        }

        Schema::table('mail_center_settings', function (Blueprint $table) {
            $dropColumns = [];

            if (Schema::hasColumn('mail_center_settings', 'navbar_polling_enabled')) {
                $dropColumns[] = 'navbar_polling_enabled';
            }

            if (Schema::hasColumn('mail_center_settings', 'navbar_polling_interval_seconds')) {
                $dropColumns[] = 'navbar_polling_interval_seconds';
            }

            if (count($dropColumns) > 0) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};

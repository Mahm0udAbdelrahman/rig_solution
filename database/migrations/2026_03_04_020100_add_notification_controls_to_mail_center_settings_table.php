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
            if (!Schema::hasColumn('mail_center_settings', 'show_navbar_mail')) {
                $table->boolean('show_navbar_mail')->default(1)->after('track_events');
            }
            if (!Schema::hasColumn('mail_center_settings', 'show_navbar_notifications')) {
                $table->boolean('show_navbar_notifications')->default(1)->after('show_navbar_mail');
            }
            if (!Schema::hasColumn('mail_center_settings', 'notify_on_pending_approval')) {
                $table->boolean('notify_on_pending_approval')->default(1)->after('show_navbar_notifications');
            }
            if (!Schema::hasColumn('mail_center_settings', 'notify_on_approved')) {
                $table->boolean('notify_on_approved')->default(1)->after('notify_on_pending_approval');
            }
            if (!Schema::hasColumn('mail_center_settings', 'notify_on_sent')) {
                $table->boolean('notify_on_sent')->default(1)->after('notify_on_approved');
            }
            if (!Schema::hasColumn('mail_center_settings', 'notify_on_failed')) {
                $table->boolean('notify_on_failed')->default(1)->after('notify_on_sent');
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
            foreach ([
                'show_navbar_mail',
                'show_navbar_notifications',
                'notify_on_pending_approval',
                'notify_on_approved',
                'notify_on_sent',
                'notify_on_failed',
            ] as $columnName) {
                if (Schema::hasColumn('mail_center_settings', $columnName)) {
                    $dropColumns[] = $columnName;
                }
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};

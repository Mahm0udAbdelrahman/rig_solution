<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('mail_center_user_preferences')) {
            return;
        }

        Schema::table('mail_center_user_preferences', function (Blueprint $table) {
            if (!Schema::hasColumn('mail_center_user_preferences', 'show_action_center_items')) {
                $table->boolean('show_action_center_items')->default(1)->after('show_pending_approval_queue');
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('mail_center_user_preferences')) {
            return;
        }

        Schema::table('mail_center_user_preferences', function (Blueprint $table) {
            if (Schema::hasColumn('mail_center_user_preferences', 'show_action_center_items')) {
                $table->dropColumn('show_action_center_items');
            }
        });
    }
};

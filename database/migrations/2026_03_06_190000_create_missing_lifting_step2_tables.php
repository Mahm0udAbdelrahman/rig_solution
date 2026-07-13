<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('crane2s')) {
            Schema::create('crane2s', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('crane_id');

                for ($i = 1; $i <= 43; $i++) {
                    $table->text('lcr2_' . $i)->nullable();
                }

                $table->timestamps();

                $table->unique('crane_id');
                $table->index('crane_id');
            });
        }

        if (!Schema::hasTable('forklift2s')) {
            Schema::create('forklift2s', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('forklift_id');

                for ($i = 1; $i <= 42; $i++) {
                    $table->text('lfr2_' . $i)->nullable();
                }

                $table->timestamps();

                $table->unique('forklift_id');
                $table->index('forklift_id');
            });
        }

        if (!Schema::hasTable('overhead_crane2s')) {
            Schema::create('overhead_crane2s', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('overhead_crane_id');

                for ($i = 1; $i <= 43; $i++) {
                    $table->text('locr2_' . $i)->nullable();
                }

                $table->timestamps();

                $table->unique('overhead_crane_id');
                $table->index('overhead_crane_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('overhead_crane2s');
        Schema::dropIfExists('forklift2s');
        Schema::dropIfExists('crane2s');
    }
};


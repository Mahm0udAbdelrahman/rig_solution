<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        if (!Schema::hasTable('file_managers')) {
            Schema::create('file_managers', function (Blueprint $table) {
                $table->id();
                $table->string('disk', 40)->default('public')->index();
                $table->string('path')->unique();
                $table->string('filename')->index();
                $table->string('extension', 20)->nullable()->index();
                $table->string('mime_type')->nullable();
                $table->unsignedBigInteger('size_bytes')->nullable();
                $table->string('module', 100)->nullable()->index();
                $table->string('category', 120)->nullable()->index();
                $table->string('entity_type', 100)->nullable()->index();
                $table->string('entity_code', 191)->nullable()->index();
                $table->string('job_request_code', 191)->nullable()->index();
                $table->boolean('is_inspection')->default(false)->index();
                $table->boolean('is_available')->default(true)->index();
                $table->timestamp('last_seen_at')->nullable();
                $table->timestamp('generated_at')->nullable();
                $table->text('note')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_managers');
    }
};


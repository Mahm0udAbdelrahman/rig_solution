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
        Schema::create('pipes_summary_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_request_id');
            $table->string('code');
            $table->integer('sync')->default(false);
            $table->integer('updated')->nullable();
            $table->unsignedBigInteger('user_id_approved')->nullable()->default(null);
            $table->date('examination_date')->nullable();
            $table->string('edition')->nullable();
            $table->string('specification')->nullable();
            $table->string('other_specification')->nullable();
            $table->string('inspection_method')->nullable();
            $table->string('other_inspection_method')->nullable();
            $table->string('equipment_used')->nullable();
            $table->string('other_equipment')->nullable();
            $table->string('equipment_no')->nullable();
            $table->string('pipe_status')->nullable();
            $table->string('nominal_size')->nullable();
            $table->string('nom_wall')->nullable();
            $table->string('pipe_grade')->nullable();
            $table->string('tool_joint_od')->nullable();
            $table->string('tool_joint_id')->nullable();
            $table->string('weight')->nullable();
            $table->string('threads')->nullable();
            $table->string('class')->nullable();
            $table->string('hard_faced')->nullable();
            $table->string('coated')->nullable();
            $table->string('jp_ready_use')->nullable();
            $table->text('jp_ready_use_comment')->nullable();
            $table->string('jp_need_recut')->nullable();
            $table->text('jp_need_recut_comment')->nullable();
            $table->string('jp_recut_pin_box')->nullable();
            $table->text('jp_recut_pin_box_comment')->nullable();
            $table->string('jp_recut_pin')->nullable();
            $table->text('jp_recut_pin_comment')->nullable();
            $table->string('jp_recut_box')->nullable();
            $table->text('jp_recut_box_comment')->nullable();
            $table->string('joints_class_2')->nullable();
            $table->text('joints_class_2_comment')->nullable();
            $table->string('joints_class_3')->nullable();
            $table->text('joints_class_3_comment')->nullable();
            $table->string('joints_junk')->nullable();
            $table->text('joints_junk_comment')->nullable();
            $table->string('total_joints')->nullable();
            $table->text('total_joints_comment')->nullable();
            $table->string('connections_manually')->nullable();
            $table->text('connections_manually_comment')->nullable();
            $table->string('total_boxs')->nullable();
            $table->text('total_boxs_comment')->nullable();
            $table->string('total_pins')->nullable();
            $table->text('total_pins_comment')->nullable();
            $table->string('total_straightened')->nullable();
            $table->text('total_straightened_comment')->nullable();
            $table->text('comment')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pipes_summary_reports');
    }
};

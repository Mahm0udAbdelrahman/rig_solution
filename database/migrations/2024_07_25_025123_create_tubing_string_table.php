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
        Schema::create('tubing_strings', function (Blueprint $table) {
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
            $table->text('equipment_no')->nullable();
            $table->string('pipe_status')->nullable();
            $table->string('coated')->nullable();
            $table->string('pipe_od')->nullable();
            $table->string('pipe_grade')->nullable();
            $table->string('lbs_ft')->nullable();
            $table->string('weight')->nullable();
            $table->string('drift_od')->nullable();
            $table->string('connection')->nullable();
            $table->string('tool_joint_od')->nullable();
            $table->string('tool_joint_id')->nullable();
            $table->text('inspection_data')->nullable();
            $table->string('new_pipes')->nullable();
            $table->text('new_pipes_comment')->nullable();
            $table->string('premium_class')->nullable();
            $table->text('premium_class_comment')->nullable();
            $table->string('joints_class_2')->nullable();
            $table->text('joints_class_2_comment')->nullable();
            $table->string('joints_class_3')->nullable();
            $table->text('joints_class_3_comment')->nullable();
            $table->string('joints_junk')->nullable();
            $table->text('joints_junk_comment')->nullable();
            $table->timestamps();
        });

        /****Insert Drill Pipe footer values*****/
        DB::table('footer_values')->insert([
            'related_inspection' => \App\Models\Inspection\Tubular\TubingString::class,
            'name' => 'Tubing String Inspection Report',
            'form_no' => 'RS-RF-F18',
            'issue_no' => '04',
            'issue_date' => '1-Mar-2024',
            'revision_no' => '00',
            'revision_date' => '1-Mar-2024',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tubing_strings');

        /****Delete Drill Pipe footer values*****/
        \App\Models\GeneralInfo\FooterValue::query()
            ->where('related_inspection', '=','App\Models\Inspection\Tubular\TubingString')
            ->delete();
    }
};

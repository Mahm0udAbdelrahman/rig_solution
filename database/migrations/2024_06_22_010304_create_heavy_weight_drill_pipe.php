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
        Schema::create('heavy_weight_pipes', function (Blueprint $table) {
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
            $table->text('equipment_no')->nullable();
            $table->string('joint_size')->nullable();
            $table->string('connection_type')->nullable();
            $table->text('inspection_data')->nullable();
            $table->string('connection_accepted')->nullable();
            $table->string('connection_defective')->nullable();
            $table->string('connection_repaired')->nullable();
            $table->string('total_connection')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
        });


        /****Insert Drill Pipe footer values*****/
        DB::table('footer_values')->insert([
            'related_inspection' => \App\Models\Inspection\Tubular\HeavyWeightPipe::class,
            'name' => 'Tubular Heavy Weight Drill Pipe Inspection Report',
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
        Schema::dropIfExists('heavy_weight_pipes');

        /****Delete Drill Pipe footer values*****/
        \App\Models\GeneralInfo\FooterValue::query()
            ->where('related_inspection', '=','App\Models\Inspection\Tubular\HeavyWeightPipe')
            ->delete();
    }
};

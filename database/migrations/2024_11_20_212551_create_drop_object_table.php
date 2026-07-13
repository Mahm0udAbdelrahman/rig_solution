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
        Schema::create('drop_objects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_request_id');
            $table->string('code');
            $table->integer('sync')->default(false);
            $table->integer('updated')->nullable();
            $table->unsignedBigInteger('user_id_approved')->nullable()->default(null);
            $table->date('survey_date')->nullable();
            $table->string('inspection_area')->nullable();
            $table->string('other_inspection_area')->nullable();
            $table->text('inspection_data')->nullable();
            $table->timestamps();
        });


        /****Insert Drill Pipe footer values*****/
        DB::table('footer_values')->insert([
            'related_inspection' => \App\Models\Inspection\DropObject\DropObject::class,
            'name' => 'Drop Object Inspection Report',
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
        Schema::dropIfExists('drop_objects');

        /****Delete Drill Pipe footer values*****/
        \App\Models\GeneralInfo\FooterValue::query()
            ->where('related_inspection', '=','App\Models\Inspection\DropObject\DropObject')
            ->delete();
    }
};

<?php

use App\Models\GeneralInfo\FooterValue;
use App\Models\Inspection\Tubular\TubingCasing;
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
		Schema::create('tubing_casings', function (Blueprint $table) {
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
			$table->text('pipe_description')->nullable();
			$table->string('pipe_status')->nullable();
			$table->string('pipe_type')->nullable();
			$table->string('nominal_od')->nullable();
			$table->string('grade')->nullable();
			$table->string('lbs_ft')->nullable();
			$table->string('threads')->nullable();
			$table->string('range')->nullable();
			$table->string('nominal_wall_thickness_mm')->nullable();
			$table->string('nominal_wall_thickness_inch')->nullable();
			$table->string('wall_thickness_1_mm')->nullable();
			$table->string('wall_thickness_1_inch')->nullable();
			$table->string('wall_thickness_2_mm')->nullable();
			$table->string('wall_thickness_2_inch')->nullable();
			$table->string('wall_thickness_3_mm')->nullable();
			$table->string('wall_thickness_3_inch')->nullable();
			$table->text('inspection_data')->nullable();
			$table->text('comment')->nullable();
			$table->string('abbreviation_code')->nullable();
			$table->string('total_items_inspected')->nullable();
			$table->string('total_items_ready')->nullable();
			$table->timestamps();
		});

		/****Insert Drill Pipe footer values*****/
		DB::table('footer_values')->insert([
			'related_inspection' => TubingCasing::class,
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
		Schema::dropIfExists('tubing_casings');

		/****Delete Drill Pipe footer values*****/
		FooterValue::query()->where('related_inspection', '=', TubingCasing::class)->delete();
	}
};

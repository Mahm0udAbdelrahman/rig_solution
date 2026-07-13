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
        Schema::create('footer_values', function (Blueprint $table) {
            $table->id();
            $table->string('related_inspection')->unique();
            $table->string('name');
            $table->string('form_no');
            $table->string('issue_no');
            $table->string('issue_date');
            $table->string('revision_no');
            $table->string('revision_date');
            $table->timestamps();
        });


        // Insert Form Values for each inspection
        $oldInspection = [
            [\App\Models\Inspection\Lifting\Crane::class, 'Lifting Crane Report'],
            [\App\Models\Inspection\Lifting\Defect::class, 'Lifting Defect Report'],
            [\App\Models\Inspection\Lifting\Forklift::class, 'Lifting Forklift Report'],
            [\App\Models\Inspection\Lifting\Lregister::class, 'Lifting Register Report'],
            [\App\Models\Inspection\Lifting\OverheadCrane::class, 'Lifting Overhead Crane Report'],
            [\App\Models\Inspection\Lifting\ThroughExamination::class, 'Lifting Through Examination Report'],

            [\App\Models\Inspection\Ndt\Mpipt::class, 'NDT MPI-PT Report'],
            [\App\Models\Inspection\Ndt\Visual::class, 'NDT Visual Report'],
            [\App\Models\Inspection\Ndt\Ultrasonic::class, 'NDT UT Shear Wave Report'],
            [\App\Models\Inspection\Ndt\Summary::class, 'NDT Summary Report'],
            [\App\Models\Inspection\Ndt\Attached::class, 'NDT Attached Report'],
            [\App\Models\Inspection\Ndt\High3Pressure::class, 'NDT High Pressure UT Report'],
            [\App\Models\Inspection\Ndt\HighPressure::class, 'NDT UTWT Report'],
            [\App\Models\Inspection\Ndt\High2Pressure::class, 'NDT General UTWT Report'],
            [\App\Models\Inspection\Ndt\WitnessHydro::class, 'NDT Witness Hydro Test Report'],
            [\App\Models\Inspection\Ndt\TreatingIron::class, 'NDT Treating Iron Inspection Report'],
        ];

        $data = [];
        foreach ($oldInspection as $inspection) {
            $data[] = [
                'related_inspection' => $inspection[0],
                'name' => $inspection[1],
                'form_no' => 'RSE-RF-01',
                'issue_no' => '05',
                'issue_date' => '1-Jan-2022',
                'revision_no' => '00',
                'revision_date' => '1-Mar-2024',
            ];
        }
        // insert new pipes Summary data (Tubular)
        $data[] = [
            'related_inspection' => \App\Models\Inspection\Tubular\PipesSummaryReport::class,
            'name' => 'Tubular Summary of Pipes Inspections Report',
            'form_no' => 'RS-RF-F18',
            'issue_no' => '04',
            'issue_date' => '1-Mar-2024',
            'revision_no' => '00',
            'revision_date' => '1-Mar-2024',
        ];

        DB::table('footer_values')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('footer_values');
    }
};

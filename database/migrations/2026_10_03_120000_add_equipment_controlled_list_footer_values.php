<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $relatedInspection = 'App\Models\GeneralInfo\EquipmentControlledList';

    /**
     * ISO footer of the Equipment Controlled List export (form RS-IMS-P10-F01), editable from General Info > Footer Values.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('footer_values') || DB::table('footer_values')->where('related_inspection', $this->relatedInspection)->exists()) {
            return;
        }

        DB::table('footer_values')->insert([
            'related_inspection' => $this->relatedInspection,
            'name' => 'Equipment Controlled List',
            'form_no' => 'RS-IMS-P10-F01',
            'issue_no' => '01',
            'issue_date' => '01 January 2022',
            'revision_no' => '01',
            'revision_date' => '15 August 2023',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('footer_values')) {
            DB::table('footer_values')->where('related_inspection', $this->relatedInspection)->delete();
        }
    }
};

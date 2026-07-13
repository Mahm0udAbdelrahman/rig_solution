<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added this import for DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calibration_torques', function (Blueprint $table) {
            $table->renameColumn('range', 'range_value'); 
            $table->string('range_unit')->after('range')->nullable();
            $table->renameColumn('type', 'type_number'); 
            $table->string('type_letter')->after('type')->nullable();
            $table->renameColumn('resolution', 'resolution_value'); 
            $table->renameColumn('read_out_unit', 'scale_interval_value'); 
            $table->renameColumn('max_permissible_error', 'permissible_deviation'); 
            $table->string('calibration_direction')->after('max_permissible_error')->nullable();
            
            // Clear the column data first
            DB::statement('UPDATE calibration_torques SET device_resolution = NULL');
            DB::statement('UPDATE calibration_torques SET device_calibration_date = NULL');
            
            // Change types before renaming
            $table->date('device_resolution')->change();
            $table->string('device_calibration_date')->change(); 
            
            $table->renameColumn('device_description', 'reference_equipment_description'); 
            $table->renameColumn('device_serial_number', 'reference_manufacturer'); 
            $table->renameColumn('device_manufacturer', 'reference_model'); 
            $table->renameColumn('device_model', 'reference_serial_number'); 
            $table->renameColumn('device_range', 'calibration_certificate_no'); 
            $table->renameColumn('device_resolution', 'calibration_due_date');
            $table->renameColumn('device_calibration_date', 'traceability'); 
            
            $table->dropColumn('contact_name');
            $table->dropColumn('contact_info');
            $table->dropColumn('certificate_number');
            $table->dropColumn('calibration_method');
            $table->dropColumn('reported_unit');
            $table->dropColumn('accuracy');
            $table->dropColumn('uncertainty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calibration_torques', function (Blueprint $table) {
            // Add back the dropped columns
            $table->string('contact_name')->nullable();
            $table->string('contact_info')->nullable();
            $table->string('certificate_number')->nullable();
            $table->string('calibration_method')->nullable();
            $table->string('reported_unit')->nullable();
            $table->string('accuracy')->nullable();
            $table->string('uncertainty')->nullable();
            
            // Reverse the column renames and drops
            $table->renameColumn('traceability', 'device_calibration_date');
            $table->renameColumn('calibration_due_date', 'device_resolution');
            $table->renameColumn('calibration_certificate_no', 'device_range');
            $table->renameColumn('reference_serial_number', 'device_model');
            $table->renameColumn('reference_model', 'device_manufacturer');
            $table->renameColumn('reference_manufacturer', 'device_serial_number');
            $table->renameColumn('reference_equipment_description', 'device_description');
            
            $table->dropColumn('calibration_direction');
            $table->renameColumn('permissible_deviation', 'max_permissible_error');
            
            // $table->dropColumn('scale_interval_number');
            $table->renameColumn('scale_interval_value', 'read_out_unit');
            
            // $table->dropColumn('resolution_unit');
            $table->renameColumn('resolution_value', 'resolution');
            
            $table->dropColumn('type_letter');
            $table->renameColumn('type_number', 'type');
            
            $table->dropColumn('range_unit');
            $table->renameColumn('range_value', 'range');
        });
    }
};

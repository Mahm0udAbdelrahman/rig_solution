<?php

namespace App\Models\Inspection;

use App\Models\GeneralInfo\EquipmentControlledList;
use Illuminate\Database\Eloquent\Model;

class ReportEquipment extends Model
{
    protected $table = 'report_equipment';

    /**
     * Copied from the register when the report is saved
     */
    public static $FIELDS = [
        'equipment_no',
        'equipment_description',
        'manufacturer',
        'model_type',
        'capacity_range',
        'calibration_due_date',
    ];

    protected $fillable = [
        'section',
        'sort',
        'equipment_controlled_list_id',
        'equipment_no',
        'equipment_description',
        'manufacturer',
        'model_type',
        'capacity_range',
        'calibration_due_date',
    ];

    public function reportable()
    {
        return $this->morphTo();
    }

    public function equipment()
    {
        return $this->belongsTo(EquipmentControlledList::class, 'equipment_controlled_list_id');
    }
}

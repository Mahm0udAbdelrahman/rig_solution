<?php

namespace App\Models\Inspection\Tubular;

use App\Models\GeneralInfo\FooterValue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

class PipesSummaryReport extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
        'job_request_id',
        'code',
        'sync',
        'updated',
        'user_id_approved',
        'examination_date',
        'edition',
        'specification',
        'other_specification',
        'inspection_method',
        'other_inspection_method',
        'equipment_used',
        'other_equipment',
        'equipment_no',
        'pipe_status',
        'nominal_size',
        'nom_wall',
        'pipe_grade',
        'tool_joint_od',
        'tool_joint_id',
        'weight',
        'threads',
        'class',
        'hard_faced',
        'coated',
        'jp_ready_use',
        'jp_ready_use_comment',
        'jp_need_recut',
        'jp_need_recut_comment',
        'jp_recut_pin_box',
        'jp_recut_pin_box_comment',
        'jp_recut_pin',
        'jp_recut_pin_comment',
        'jp_recut_box',
        'jp_recut_box_comment',
        'joints_class_2',
        'joints_class_2_comment',
        'joints_class_3',
        'joints_class_3_comment',
        'joints_junk',
        'joints_junk_comment',
        'total_joints',
        'total_joints_comment',
        'connections_manually',
        'connections_manually_comment',
        'total_boxs',
        'total_boxs_comment',
        'total_pins',
        'total_pins_comment',
        'total_straightened',
        'total_straightened_comment',
        'comment',
    ];

    protected $dates = ['examination_date'];

    protected $hidden = ['created_at', 'updated_at'];

    public static $INSPECTION_METHOD = ['WET', 'DRY', 'EAI', 'EMI', 'UT-EAI', 'VTI', 'WT', 'TGI'];

    public static $EQUIPMENTS = ['UV light', 'AC yoke', 'DC coil', 'EMI Unit', 'UT-EA', 'WT'];

    public function job_request()
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function report()
    {
        return $this->morphOne(InspectionReport::class, 'reportable');
    }

    // Mutators to JSON-encode array fields
    public function setSpecificationAttribute($value)
    {
        $this->attributes['specification'] = json_encode($value);
    }

    public function setInspectionMethodAttribute($value)
    {
        $this->attributes['inspection_method'] = json_encode($value);
    }

    public function setEquipmentUsedAttribute($value)
    {
        $this->attributes['equipment_used'] = json_encode($value);
    }

    public function setEquipmentNoAttribute($value)
    {
        $this->attributes['equipment_no'] = json_encode($value);
    }

    // Accessors to JSON-decode array fields
    public function getSpecificationAttribute($value)
    {
        $decodedArray = json_decode($value, true);

        // Check if 'S-008' code for other specification exists in the array
        if (($key = array_search('S-008', $decodedArray)) !== false) {
            // Remove 'S-008' from its current position
            unset($decodedArray[$key]);
            // Push 'S-008' to the end of the array
            $decodedArray[] = 'S-008';
        }

        return $decodedArray;
    }

    public function getInspectionMethodAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getEquipmentUsedAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getEquipmentNoAttribute($value)
    {
        return json_decode($value, true);
    }

    // Accessor method to format examination_date attribute
    public function getExaminationDateAttribute($value)
    {
        $date = new Carbon($value);
        return $value ? $date->format('d-m-Y') : null;
    }

    // Define the virtual attribute accessor for related Footer Values
    public function getFooterValuesAttribute()
    {
        $footerValues = FooterValue::query()
            ->where('related_inspection', $this->getMorphClass())
            ->get()->first();
        return $footerValues;
    }
}

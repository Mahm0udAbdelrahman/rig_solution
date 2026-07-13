<?php

namespace App\Models\Inspection\Tubular;

use App\Models\GeneralInfo\FooterValue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

class TubingCasing extends Model
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
        'equipment_no',
        'pipe_description',
        'pipe_status',
				'pipe_type',
				'nominal_od',
				'grade',
				'lbs_ft',
				'threads',
				'range',
				'nominal_wall_thickness_mm',
				'nominal_wall_thickness_inch',
				'wall_thickness_1_mm',
				'wall_thickness_1_inch',
				'wall_thickness_2_mm',
				'wall_thickness_2_inch',
				'wall_thickness_3_mm',
				'wall_thickness_3_inch',
				'inspection_data',
				'comment',
				'abbreviation_code',
				'total_items_inspected',
				'total_items_ready',
    ];

    protected $dates = ['examination_date'];

    protected $hidden = ['created_at', 'updated_at'];

    public static $INSPECTION_METHOD = ['FLD', 'MPI', 'EMI', 'EAI', 'VTI', 'WT', 'TGI'];

    public static $EQUIPMENTS = ['UV light', 'AC yoke', 'DC coil', 'WT', 'Drift'];

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

    public function setInspectionDataAttribute($value)
    {
        $this->attributes['inspection_data'] = json_encode($value);
    }

    // Accessors to JSON-decode array fields
    public function getSpecificationAttribute($value)
    {
        $decodedArray = json_decode($value, true);

        // Check if 'S-008' code for other specification exists in the array
        if (is_array($decodedArray) && ($key = array_search('S-008', $decodedArray)) !== false) {
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

    public function getInspectionDataAttribute($value)
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

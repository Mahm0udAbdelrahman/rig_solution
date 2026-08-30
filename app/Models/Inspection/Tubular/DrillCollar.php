<?php

namespace App\Models\Inspection\Tubular;

use App\Models\GeneralInfo\FooterValue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

class DrillCollar extends Model
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
        'inspection_description',
        'dc_description',
        'size',
        'connection',
        'connection_defective',
        'connection_accepted',
        'connection_to_be_repaired',
        'total_connection_inspected',
        'joints_to_be_repaired',
        'total_joints_inspected',
        'standards',
        'inspection_data',
        'comment',
				'inspection_applied',
				'internal_service_order'
    ];

    protected $dates = ['examination_date'];

    protected $hidden = ['created_at', 'updated_at'];

    public static $INSPECTION_METHOD = ['WET', 'DRY', 'EAI', 'EMI', 'UT-EAI', 'VTI', 'WT', 'TGI'];

    public static $EQUIPMENTS = ['UV light', 'AC yoke', 'DC coil', 'EMI Unit', 'UT-EA', 'WT'];

    public static $PIPE_TYPE_OPTIONS = [
        'type_1'=> 'Size 4 ¾” (QC Max = 4 9/64”)',
        'type_2'=> 'Size 6 ½” (QC Max = 5 3/8”)',
        'type_3'=> 'Size 8 ¼” (QC Max = 6 3/32”)',
        'type_4'=> 'Size 9 1/2”  (QC Max = 7 5/32”)',
    ];

    public static $PIPE_TYPES_STANDARDS = [
        'type_1'=> [
            'input_1' => '>10',
            'input_2' => '4 9/64',
            'input_3' => '9/16',
            'input_4' => '<3 30/64',
            'input_5' => '>3 31/64',
            'input_6' => '>10',
            'input_7' => '4 1/16',
            'input_8' => '<3 31/64',
            'input_9' => '<3 33/64',
            'input_10' => '< 23/32',
            'input_11' => '>1 1/32',
        ],
        'type_2'=> [
            'input_1' => '>10',
            'input_2' => '5 3/8',
            'input_3' => '9/16',
            'input_4' => '<4 5/8',
            'input_5' => '>4 41/64',
            'input_6' => '>10',
            'input_7' => '4 13/16',
            'input_8' => '<3 31/64',
            'input_9' => '<3 33/64',
            'input_10' => '< 23/32',
            'input_11' => '>1 1/32',
        ],
        'type_3'=> [
            'input_1' => '>10',
            'input_2' => '6 3/32',
            'input_3' => '9/16',
            'input_4' => '<5 9/32',
            'input_5' => '>5 19/64',
            'input_6' => '>10',
            'input_7' => '5 1/16',
            'input_8' => '<5 25/64',
            'input_9' => '<5 27/64',
            'input_10' => '< 23/32',
            'input_11' => '>1 1/32',
        ],
        'type_4'=> [
            'input_1' => '>10',
            'input_2' => '7 5/32',
            'input_3' => '9/16',
            'input_4' => '<5 55/64',
            'input_5' => '>5 56/64',
            'input_6' => '>10',
            'input_7' => '5 5/16',
            'input_8' => '<6 21/64',
            'input_9' => '<6 23/64',
            'input_10' => '< 23/32',
            'input_11' => '>1 1/32',
        ],

    ];

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

    public function setEquipmentNoAttribute($value)
    {
        $this->attributes['equipment_no'] = json_encode($value);
    }

    public function setInspectionDataAttribute($value)
    {
        $this->attributes['inspection_data'] = json_encode($value);
    }
    public function setStandardsAttribute($value)
    {
        $this->attributes['standards'] = json_encode($value);
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

    public function getEquipmentNoAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getInspectionDataAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getStandardsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setExaminationDateAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['examination_date'] = null;
            return;
        }
        try {
            if ($value instanceof \DateTimeInterface) {
                $this->attributes['examination_date'] = $value->format('Y-m-d');
            } elseif (is_string($value)) {
                $value = trim($value);
                if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $value)) {
                    $this->attributes['examination_date'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
                } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                    $this->attributes['examination_date'] = $value;
                } else {
                    $this->attributes['examination_date'] = Carbon::parse($value)->format('Y-m-d');
                }
            } else {
                $this->attributes['examination_date'] = null;
            }
        } catch (\Throwable $e) {
            $this->attributes['examination_date'] = null;
        }
    }

    // Accessor method to format examination_date attribute
    public function getExaminationDateAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        try {
            return Carbon::parse($value)->format('d-m-Y');
        } catch (\Throwable $e) {
            return null;
        }
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

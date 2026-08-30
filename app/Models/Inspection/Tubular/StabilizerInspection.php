<?php

namespace App\Models\Inspection\Tubular;

use App\Models\GeneralInfo\FooterValue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

class StabilizerInspection extends Model
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
        'material_description',
        'tool_number',
        'condition',
        'inspection_applied',
//        'dimensions_unit',
//        'front',
//        'middle',
//        'back',
//        'input_a',
//        'input_b',
//        'input_c',
//        'input_d',
//        'input_e',
//        'input_e',
        'dimensions_data',
        'pipe_type',
        'inspection_data',
        'body_condition',
        'blades_condition',
        'hf_condition',
        'blades_diameter',
        'comment'
    ];

    protected $dates = ['examination_date'];

    protected $hidden = ['created_at', 'updated_at'];

    public static $INSPECTION_METHOD = ['WET', 'DRY', 'EAI', 'EMI', 'UT-EAI', 'VTI', 'WT', 'TGI'];

    public static $EQUIPMENTS = ['UV light', 'AC yoke', 'DC coil', 'EMI Unit', 'UT-EA', 'WT'];

    public static $PIPE_TYPE_OPTIONS = [
        'type_1'=> 'BOX - BOX',
        'type_2'=> 'BOX - PIN',
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

    public function setDimensionsDataAttribute($value)
    {
        $this->attributes['dimensions_data'] = json_encode($value);
    }

    // Accessors to JSON-decode array fields
    public function getSpecificationAttribute($value)
    {
        $decodedArray = json_decode($value ?? '[]', true);
        if (!is_array($decodedArray)) {
            $decodedArray = [];
        }

        // Check if 'S-008' code for other specification exists in the array
        if (($key = array_search('S-008', $decodedArray, true)) !== false) {
            // Remove 'S-008' from its current position
            unset($decodedArray[$key]);
            // Push 'S-008' to the end of the array
            $decodedArray[] = 'S-008';
        }

        return array_values($decodedArray);
    }

    public function getInspectionMethodAttribute($value)
    {
        $decodedArray = json_decode($value ?? '[]', true);
        return is_array($decodedArray) ? $decodedArray : [];
    }

    public function getEquipmentNoAttribute($value)
    {
        $decodedArray = json_decode($value ?? '[]', true);
        if (!is_array($decodedArray)) {
            return [];
        }

        // Legacy rows may be stored as a single associative row instead of an indexed array of rows.
        if (
            array_key_exists('equipment_no_value', $decodedArray)
            || array_key_exists('equipment_used', $decodedArray)
            || array_key_exists('other_equipment', $decodedArray)
        ) {
            $decodedArray = [$decodedArray];
        }

        return collect($decodedArray)
            ->map(function ($item) {
                if (!is_array($item)) {
                    return null;
                }

                return [
                    'equipment_no_value' => trim((string) ($item['equipment_no_value'] ?? '')),
                    'equipment_used' => trim((string) ($item['equipment_used'] ?? '')),
                    'other_equipment' => trim((string) ($item['other_equipment'] ?? '')),
                ];
            })
            ->filter(function ($item) {
                return is_array($item) && (
                    $item['equipment_no_value'] !== ''
                    || $item['equipment_used'] !== ''
                    || $item['other_equipment'] !== ''
                );
            })
            ->values()
            ->all();
    }

    public function getInspectionDataAttribute($value)
    {
        $defaults = [];
        for ($i = 1; $i <= 20; $i++) {
            $defaults['input_'.$i] = '';
        }

        $decodedArray = json_decode($value ?? '[]', true);
        if (!is_array($decodedArray)) {
            return $defaults;
        }

        return array_merge($defaults, $decodedArray);
    }

    public function getDimensionsDataAttribute($value)
    {
        $defaults = [
            'unit' => '',
            'front' => '',
            'middle' => '',
            'back' => '',
            'input_a' => '',
            'input_b' => '',
            'input_c' => '',
            'input_d' => '',
            'input_e' => '',
        ];

        $decodedArray = json_decode($value ?? '[]', true);
        if (!is_array($decodedArray)) {
            return $defaults;
        }

        return array_merge($defaults, $decodedArray);
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

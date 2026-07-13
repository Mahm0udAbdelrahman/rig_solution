<?php

namespace App\Models\Inspection\DropObject;

use App\Models\GeneralInfo\FooterValue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

class DropObject extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
        'job_request_id',
        'code',
        'sync',
        'updated',
        'user_id_approved',
        'survey_date',
        'inspection_area',
        'other_inspection_area',
        'inspection_data',
    ];

    protected $dates = ['survey_date'];

    protected $hidden = ['created_at', 'updated_at'];

    public static $INSPECTION_AREA_OPTIONS = [
		'crown_section' => 'Crown Section',
		'bottom_of_crown_to_top_of_monkey_board_section' => 'Bottom of Crown to Top of Monkey Board Section',
		'monkey_board_section' => 'Monkey Board Section',
		'monkey_board_to_rig_floor_level' => 'Monkey Board to Rig Floor Level',
		'rig_floor_level' => 'Rig Floor Level',
		'bottom_of_rig_floor' => 'Bottom of Rig Floor',
		'mud_system_level' => 'Mud System Level',
		'caravan_level' => 'Caravan Level',
		'other' => 'Other',
	];

    public function job_request()
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function report()
    {
        return $this->morphOne(InspectionReport::class, 'reportable');
    }

    // Accessor method to format survey_date attribute
    public function getSurveyDateAttribute($value)
    {
        $date = new Carbon($value);
        return $value ? $date->format('d-m-Y') : null;
    }

	public function setInspectionDataAttribute($value)
    {
        $this->attributes['inspection_data'] = json_encode($value);
    }

	public function getInspectionDataAttribute($value)
    {
        return json_decode($value, true);
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

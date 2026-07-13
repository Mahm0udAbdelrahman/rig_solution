<?php

namespace App\Models\Inspection\Ndt;

use App\Models\GeneralInfo\FooterValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

class High3Pressure extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
        'job_request_id',
        'nh2pr_2',
        'code',
        'nh2pr_6',
        'nh2pr_7',
        'desc',
        'edition',
        'nh2pr_10',
        'nh2pr_11',
        'nh2pr_12',
        'acceptance',
        'nh2pr_14',
        'nh2pr_15',
        'nh2pr_16',
        'nh2pr_17',
        'nh2pr_18',
        'nh2pr_19',
        'nh2pr_20',
        'nh2pr_21',
        'nh2pr_22',
        'nh2pr_23',
        'nh2pr_24',
        'nh2pr_25',
        'nh2pr_26',
        'nh2pr_27',
        'nh2pr_28',
        'nh2pr_29',
        'nh2pr_30',
        'nh2pr_31',
        'nh2pr_32',
        'nh2pr_33',
        'sync',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function job_request()
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function report()
    {
        return $this->morphOne(InspectionReport::class, 'reportable');
    }

    public function checkbox_yes($checkbox_id){
        if(str_contains((string) $checkbox_id, '_y'))
        {
            return "checked";
        }
    }

    public function checkbox_no($checkbox_id)
    {
        if(str_contains((string) $checkbox_id, '_n'))
        {
            return "checked";
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

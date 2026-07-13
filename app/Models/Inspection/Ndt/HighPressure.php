<?php

namespace App\Models\Inspection\Ndt;

use App\Models\GeneralInfo\FooterValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

class HighPressure extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
       'job_request_id',
       'nhpr_2',
       'code',
       'nhpr_6',
       'nhpr_7',
       'desc',
	   'edition',
       'nhpr_10',
       'nhpr_11',
       'nhpr_12',
       'acceptance',
       'nhpr_14',
       'nhpr_15',
       'nhpr_16',
       'nhpr_17',
       'nhpr_18',
       'nhpr_19',
       'nhpr_20',
       'nhpr_21',
       'nhpr_22',
       'nhpr_23',
       'nhpr_24',
       'nhpr_25',
       'nhpr_26',
       'nhpr_27',
       'nhpr_28',
       'nhpr_29',
       'nhpr_30',
       'nhpr_31',
       'nhpr_32',
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

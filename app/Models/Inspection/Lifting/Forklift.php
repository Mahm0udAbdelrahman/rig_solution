<?php

namespace App\Models\Inspection\Lifting;

use App\Models\GeneralInfo\FooterValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Lifting\Forklift2;
use App\Traits\HasInspectionLogo;

class Forklift extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
       'job_request_id',
       'lfr_2',
       'code',
       'lfr_6',
       'lfr_7',
       'lfr_8',
       'lfr_10',
       'lfr_11',
       'lfr_12',
       'lfr_13',
       'lfr_14',
       'lfr_15',
       'lfr_16',
       'lfr_17',
       'lfr_18',
       'lfr_19',
       'lfr_20',
       'lfr_21',
       'lfr_22',
       'lfr_23',
       'lfr_24',
       'lfr_25',
       'lfr_26',
       'lfr_27',
       'lfr_28',
       'lfr_29',
       'lfr_30',
       'lfr_31',
       'lfr_32',
       'lfr_33',
       'lfr_34',
       'sync',
       'updated',
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

     public function forklift2()
     {
         return $this->hasOne(Forklift2::class);
     }

     public function checkbox_yes($checkbox_id){
       if(str_contains((string) $checkbox_id, '_y')){
         return "checked";
       }
     }

     public function checkbox_no($checkbox_id){
       if(str_contains((string) $checkbox_id, '_n')){
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

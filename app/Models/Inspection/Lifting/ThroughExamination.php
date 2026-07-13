<?php

namespace App\Models\Inspection\Lifting;

use App\Models\GeneralInfo\FooterValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

class ThroughExamination extends Model
{
    use HasFactory, HasInspectionLogo;

    // protected $connection = 'mysql2';

    protected $fillable = [
       'job_request_id',
       'lter_2',
       'code',
       'lter_6',
       'lter_7',
       'lter_8',
       // 'lter_9',
       'lter_10',
       'lter_15',
       'lter_16',
       'lter_17',
       'lter_18',
       'lter_19',
       'lter_20',
       'lter_21',
       'lter_22',
       'lter_23',
       'lter_24',
       'lter_25',
       'lter_26',
       'lter_27',
       'lter_28',
       'lter_29',
       'lter_30',
       'lter_31',
       'lter_32',
       'lter_33',
       'lter_34',
       'lter_35',
       'lter_36',
       'lter_37',
       'lter_38',
       'lter_39',
       'lter_40',
       'lter_41',
       'lter_42',
       'lter_43',
       'lter_44',
       'lter_45',
       'lter_46',
       'lter_47',
       'lter_48',
       'lter_49',
       'lter_50',
       'lter_51',
       'sync',
       'updated',
     ];

     protected $casts = [
       'lter_10' => 'array'
     ];

    protected $hidden = ['created_at', 'updated_at'];

     public function report()
     {
         return $this->morphOne(InspectionReport::class, 'reportable');
     }


     public function job_request()
     {
         return $this->belongsTo(JobRequest::class);
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

<?php

namespace App\Models\Inspection\Ndt;

use App\Models\GeneralInfo\FooterValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

class Summary extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
       'job_request_id',
       'nsr_2',
       'code',
       'nsr_4',
       // 'nsr_5',
       'desc',
       'nsr_7',
       'nsr_8',
       'sync',
       'updated',
     ];

     protected $hidden = ['id', 'created_at', 'updated_at'];

     public function job_request()
     {
         return $this->belongsTo(JobRequest::class);
     }

     public function report()
     {
         return $this->morphOne(InspectionReport::class, 'reportable');
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

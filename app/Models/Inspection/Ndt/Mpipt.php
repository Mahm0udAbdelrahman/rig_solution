<?php

namespace App\Models\Inspection\Ndt;

use App\Models\GeneralInfo\FooterValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

class Mpipt extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
       'job_request_id',
       'nmpr_2',
       'code',
       'nmpr_6',
       'nmpr_7',
       'nmpr_8',
       'nmpr_10',
       'acceptance',
       'nmpr_12',
       'nmpr_13',
       'desc',
       'nmpr_28',
       'nmpr_29',
       'nmpr_30',
       'nmpr_800',
       'nmpr_900',
       'nmpr_1000',
//       'temperature',
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

     public function getMtvalue($id, $db){
       $index = array_search($id, array_column(json_decode($this[$db]), 'id'));
       $value = '';
       if($index !== FALSE){
         $value = json_decode($this[$db])[$index]->value;
       }else{
         $value = 'N/A';
       }
       return $value;
     }

     public function checkMtvalue($id, $db){
       $index = array_search($id, array_column(json_decode($this[$db]), 'id'));
       $value = '';
       if($index !== FALSE){
         $value = 'checked';
       }else{
         $value = 'disabled';
       }
       return $value;
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

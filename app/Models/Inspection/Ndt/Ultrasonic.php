<?php

namespace App\Models\Inspection\Ndt;

use App\Models\GeneralInfo\FooterValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

class Ultrasonic extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
       'job_request_id',
       'nur_2',
       'code',
       // 'nur_4',
       // 'nur_5',
       'nur_6',
       // 'nur_7',
       'nur_8',
       'nur_9',
       'nur_10',
       'acceptance',
       'nur_12',
       'nur_13',
       'nur_14',
       'nur_15',
       'nur_16',
       'nur_17',
       'nur_18',
       'nur_19',
       'nur_20',
       'nur_21',
       'nur_22',
       'nur_23',
       'nur_24',
       'desc',
       'nur_26',
       'nur_27',
       'nur_28',
       'nur_29',
       'nur_30',
       'nur_31',
       'nur_32',
       'nur_33',
       'nur_34',
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

    // Define the virtual attribute accessor for related Footer Values
    public function getFooterValuesAttribute()
    {
        $footerValues = FooterValue::query()
            ->where('related_inspection', $this->getMorphClass())
            ->get()->first();
        return $footerValues;
    }

}

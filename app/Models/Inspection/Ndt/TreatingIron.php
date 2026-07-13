<?php

namespace App\Models\Inspection\Ndt;

use App\Models\GeneralInfo\FooterValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

class TreatingIron extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
				'job_request_id',
				'ntir_2',
				'code',
				'ntir_6',
				'ntir_7',
				'ntir_9',
				'ntir_10',
				'ntir_11',
				'desc',
				'ntir_13',
				'ntir_14',
				'ntir_15',
				'ntir_16',
				'ntir_17',
				'ntir_18',
				'ntir_19',
				'ntir_20',
				'ntir_21',
				'ntir_22',
				'ntir_23',
				'ntir_24',
				'ntir_25',
				'ntir_26',
				'ntir_27',
				'ntir_28',
				'ntir_29',
				'ntir_30',
				'ntir_31',
				'ntir_32',
				'ntir_33',
				'ntir_34',
				'ntir_35',
				'ntir_36',
				'ntir_37',
				'ntir_38',
				'ntir_39',
				'ntir_40',
				'ntir_41',
				'ntir_42',
				'ntir_43',
				'ntir_44',
				'ntir_45',
        'ntir_46',
        'ntir_47',
        'ntir_48',
        'ntir_49',
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

<?php

namespace App\Models\Inspection\Ndt;

use App\Models\GeneralInfo\FooterValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

class Visual extends Model
{
    use HasFactory, HasInspectionLogo;
    protected $fillable = [
      'job_request_id',
      'nvr_2',
      'code',
      'nvr_4',
      'desc',
      'nvr_6',
      'nvr_7',
      'nvr_8',
      'nvr_9',
      'nvr_10',
      'nvr_11',
      'nvr_12',
      'nvr_13',
      'nvr_14',
      'nvr_15',
      'nvr_16',
      'acceptance',
      'nvr_18',
      'nvr_19',
      'nvr_23',
      // 'nvr_24',
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

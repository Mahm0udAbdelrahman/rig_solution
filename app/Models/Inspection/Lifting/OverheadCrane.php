<?php

namespace App\Models\Inspection\Lifting;

use App\Models\GeneralInfo\FooterValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Lifting\OverheadCrane2;
use App\Traits\HasInspectionLogo;

class OverheadCrane extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
      'job_request_id',
      'locr_2',
      'code',
      'locr_5',
      'locr_6',
      'locr_7',
      'locr_8',
      'locr_10',
      'locr_11',
      'locr_12',
      'locr_13',
      'locr_14',
      'locr_15',
      'locr_16',
      'locr_17',
      'locr_18',
      'locr_19',
      'locr_20',
      'locr_21',
      'locr_22',
      'locr_23',
      'locr_24',
      'locr_25',
      'locr_26',
      'locr_27',
      'locr_28',
      'locr_29',
      'locr_30',
      'locr_31',
      'locr_32',
      'locr_33',
      'locr_34',
      'locr_35',
      'locr_36',
      'locr_37',
      'locr_38',
      'locr_39',
      'locr_40',
      'locr_41',
      'locr_42',
      'locr_43',
      'locr_44',
      'sync',
      'updated',
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

    public function overhead_crane2()
    {
        return $this->hasOne(OverheadCrane2::class);
    }

    public function overheadcrane2()
    {
        return $this->hasOne(OverheadCrane2::class);
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

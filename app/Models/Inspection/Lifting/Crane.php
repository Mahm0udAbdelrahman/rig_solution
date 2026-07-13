<?php

namespace App\Models\Inspection\Lifting;

use App\Models\GeneralInfo\FooterValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Lifting\Crane2;
use App\Traits\HasInspectionLogo;

class Crane extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
				'job_request_id',
				'lcr_2',
				'code',
				'lcr_4',
				'lcr_5',
				'lcr_6',
				'lcr_7',
				'lcr_8',
				'lcr_10',
				'lcr_11',
				'lcr_12',
				'lcr_13',
				'lcr_14',
				'lcr_15',
				'lcr_16',
				'lcr_17',
				'lcr_18',
				'lcr_19',
				'lcr_20',
				'lcr_21',
				'lcr_22',
				'lcr_23',
				'lcr_24',
				'lcr_25',
				'lcr_26',
				'lcr_27',
				'lcr_28',
				'lcr_29',
				'lcr_30',
				'lcr_31',
				'lcr_32',
				'lcr_33',
				'lcr_34',
				'lcr_35',
				'lcr_36',
				'lcr_37',
				'lcr_38',
				'lcr_39',
				'lcr_40',
				'lcr_41',
				'lcr_42',
				'lcr_43',
				'lcr_44',
				'lcr_45',
				'lcr_46',
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

    public function crane2()
    {
        return $this->hasOne(Crane2::class);
    }

    public function checkbox_yes($checkbox_id)
		{
	      if (str_contains((string) $checkbox_id, '_y'))
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

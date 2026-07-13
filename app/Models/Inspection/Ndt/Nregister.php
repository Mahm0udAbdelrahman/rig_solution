<?php

namespace App\Models\Inspection\Ndt;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GeneralInfo\FooterValue;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

class Nregister extends Model
{
	use HasFactory, HasInspectionLogo;

	protected $fillable = [
		'job_request_id',
		'code',
		'sync',
		'updated',
		'register_date'
	];

	protected $hidden = ['created_at', 'updated_at'];

	protected $dates = ['register_date'];

	public function job_request()
	{
		return $this->belongsTo(JobRequest::class);
	}

	public function report()
	{
		return $this->morphOne(InspectionReport::class, 'reportable');
	}

	// Accessor method to format register_date attribute
    public function getRegisterDateAttribute($value)
    {
        $date = new Carbon($value);
        return $value ? $date->format('d-m-Y') : null;
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

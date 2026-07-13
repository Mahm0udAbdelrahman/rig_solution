<?php

namespace App\Models\Inspection\Calibration;

use App\Models\GeneralInfo\FooterValue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

// 'TYPE_3' => 'Calibration Certificate (Yoke)',
class CalibrationYoke extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
        'job_request_id',
        'code',
        'sync',
        'updated',
        'user_id_approved',
        'receipt_date', /*  */
        'calibration_date',/*  */
        'due_date',/*  */
        'issue_date',/*  */
        'contact_name',/*  */
        'contact_info',/*  */
        'temperature',/*  */
        'relative_humidity',/*  */
        // 'environment_conditions',
        'temperature', /*  */
        'relative_humidity', /*  */
        'equipment_description', /*  */
        'serial_number', /*  */
        'code_number', /*  */
        'manufacturer', /*  */
        'model', /*  */
        'range', /*  */
        'type', /*  */
        'resolution', /*  */
        'read_out_unit',/*  */
        'max_permissible_error', /*  */
        'device_description', /*  */
        'device_serial_number', /*  */
        'device_manufacturer', /*  */
        'device_model', /*  */
        'device_range',/*  */
        'device_resolution',/*  */
        'device_calibration_date', /*  */
        'certificate_number',
        'calibration_method',/*  */
        'reported_unit', /*  */
        'accuracy',/*  */
        'uncertainty', /*  */
        'calibration_details',/*  */
        'statement',/*  */
    ];

    protected $dates = ['receipt_date', 'calibration_date', 'due_date', 'issue_date', 'device_calibration_date'];

    protected $hidden = ['created_at', 'updated_at'];

    public function job_request()
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function report()
    {
        return $this->morphOne(InspectionReport::class, 'reportable');
    }

    // Accessor method to format receipt_date attribute
    public function getReceiptDateAttribute($value)
    {
        $date = new Carbon($value);
        return $value ? $date->format('d-m-Y') : null;
    }

	// Accessor method to format calibration_date attribute
	public function getCalibrationDateAttribute($value)
	{
		$date = new Carbon($value);
		return $value ? $date->format('d-m-Y') : null;
	}

	// Accessor method to format due_date attribute
	public function getDueDateAttribute($value)
	{
		$date = new Carbon($value);
		return $value ? $date->format('d-m-Y') : null;
	}

	// Accessor method to format issue_date attribute
	public function getIssueDateAttribute($value)
	{
		$date = new Carbon($value);
		return $value ? $date->format('d-m-Y') : null;
	}

	// Accessor method to format device_calibration_date attribute
	public function getDeviceCalibrationDateAttribute($value)
	{
		$date = new Carbon($value);
		return $value ? $date->format('d-m-Y') : null;
	}


	public function setCalibrationDetailsAttribute($value)
    {
        $this->attributes['calibration_details'] = json_encode($value);
    }

	public function getCalibrationDetailsAttribute($value)
    {
        return json_decode($value, true);
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

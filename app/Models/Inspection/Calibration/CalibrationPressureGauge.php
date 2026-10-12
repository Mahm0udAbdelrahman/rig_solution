<?php

namespace App\Models\Inspection\Calibration;

use App\Models\GeneralInfo\FooterValue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

//		'TYPE_2' => 'Calibration Certificate (Pressure Gauge)',
class CalibrationPressureGauge extends Model
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
        // certificate layout RS-CAL-P11-F01E
        'due_period',
        'calibration_location',
        'range_from',
        'range_to',
        'digital_analog',
        'unit_segment_location',
        'temperature_tolerance',
        'temperature_uncertainty',
        'humidity_tolerance',
        'humidity_uncertainty',
        'device_accuracy',
        'device_traceability',
        'specification_status',
        'issued_by',
        'remarks',
    ];

    // Drop-down lists of the "RS-CAL-P11-F01 Pressure Gauge" Excel data entry sheet
    public const UNITS = ['Bar', 'hPa', 'kPa', 'Mpa', 'psi', 'mBar', 'Pa', 'kg/cm2', 'mmHg', 'inHg', 'mmH2O', 'inH2O'];

    public const DIGITAL_ANALOG = ['Digital', 'Analog'];

    public const CALIBRATION_METHODS = [
        'Pressure' => "The designated device is Calibrated according to standard method DKD-R6-1:2014, and Rig Solution Of Calibration laboratory method RS-WP-401\n"
            . 'The reported expanded uncertainty is based on a standard uncertainty multiplied by a coverage factor k=2, providing a level of confidence of approximately 95%.',
        'Vacuum' => "The designated device is Calibrated according to standard method DKD-R6-2:2014, and Rig Solution Of Calibration laboratory method RS-WP-401\n"
            . 'The reported expanded uncertainty is based on a standard uncertainty multiplied by a coverage factor k=2, providing a level of confidence of approximately 95%.',
    ];

    /**
     * Select options for a fixed list, keeping a legacy value that is not in the list so editing never loses it.
     */
    public static function selectOptions(array $options, ?string $current = null): array
    {
        $result = array_combine($options, $options);
        if ($current !== null && $current !== '' && !in_array($current, $options, true)) {
            $result = [$current => $current] + $result;
        }
        return $result;
    }

    public const DUE_PERIOD_MONTHS = [
        'annual' => 12,
        'semi_annual' => 6,
    ];

    /**
     * Due date = calibration date + period - 1 day (e.g. 18-04-2026 annual -> 17-04-2027).
     */
    public static function computeDueDate(?string $calibrationDate, ?string $period): ?string
    {
        $months = self::DUE_PERIOD_MONTHS[$period] ?? null;
        if (!$calibrationDate || !$months) {
            return null;
        }
        try {
            $date = Carbon::createFromFormat('d-m-Y', $calibrationDate);
        } catch (\Throwable $e) {
            try {
                $date = Carbon::parse($calibrationDate);
            } catch (\Throwable $e) {
                return null;
            }
        }
        return $date->addMonthsNoOverflow($months)->subDay()->format('d-m-Y');
    }

    /**
     * Rows of the calibration results table with the max correction for the rising / falling series.
     */
    public function getCalibrationResultsAttribute(): array
    {
        $rows = collect(data_get($this->calibration_details, 'results', []))
            ->filter(fn ($row) => is_array($row) && collect($row)->filter(fn ($v) => $v !== null && $v !== '')->isNotEmpty())
            ->values();

        $maxOf = function (string $key) use ($rows) {
            $values = $rows->pluck($key)->filter(fn ($v) => is_numeric($v))->map(fn ($v) => (float) $v);
            if ($values->isEmpty()) {
                return null;
            }
            return $values->sortByDesc(fn ($v) => abs($v))->first();
        };

        return [
            'rows' => $rows->all(),
            'max_rising' => $maxOf('p_error_rising'),
            'max_falling' => $maxOf('p_error_failing'),
        ];
    }

    /**
     * Format a d-m-Y attribute as d-M-Y (18-Apr-2026) for the certificate.
     */
    public function certificateDate(string $attribute): string
    {
        $value = $this->{$attribute};
        if (!$value) {
            return '';
        }
        try {
            return Carbon::createFromFormat('d-m-Y', $value)->format('d-M-Y');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }

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


	// browsers submit line breaks as \r\n; keep \n so the saved text matches the CALIBRATION_METHODS options
	public function setCalibrationMethodAttribute($value)
	{
		$this->attributes['calibration_method'] = $value === null ? null : str_replace(["\r\n", "\r"], "\n", $value);
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

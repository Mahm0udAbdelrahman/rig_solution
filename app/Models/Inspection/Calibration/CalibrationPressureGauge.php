<?php

namespace App\Models\Inspection\Calibration;

use App\Models\GeneralInfo\FooterValue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkFlow\JobRequest;
use App\Models\User;
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
        // user_id_issued is not fillable: only the QA issue action (role permission "issue") sets it
    ];

    protected static function booted()
    {
        // a duplicated certificate has to be issued by QA again
        static::replicating(function (self $model) {
            $model->user_id_issued = null;
        });
    }

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

    /** Correction columns of a result row => the REF column they are calculated from */
    public const CORRECTIONS = ['p_error_rising' => 'pressure_rising', 'p_error_failing' => 'pressure_falling'];

    /**
     * Number of decimals as typed: "4.10" -> 2, "11.9" -> 1, "12" -> 0.
     */
    public static function typedDecimals($value): int
    {
        return preg_match('/[.,](\d+)$/', trim((string) $value), $m) ? strlen($m[1]) : 0;
    }

    public static function typedNumber($value): ?float
    {
        $value = str_replace(',', '.', trim((string) $value));
        return is_numeric($value) ? (float) $value : null;
    }

    /**
     * Correction = REF - UUC, signed, with the decimals typed in the row (2.00 / 0 -> -2.00, 12.00 / 11.9 -> -0.10).
     */
    public static function correction($uuc, $ref): ?string
    {
        $u = self::typedNumber($uuc);
        $r = self::typedNumber($ref);
        if ($u === null || $r === null) {
            return null;
        }
        $decimals = max(self::typedDecimals($uuc), self::typedDecimals($ref));
        $value = round($r - $u, $decimals);

        return number_format($value == 0 ? 0 : $value, $decimals, '.', '');
    }

    /**
     * Result row with its corrections recalculated from the typed UUC / REF values.
     */
    public static function withCorrections(array $row): array
    {
        foreach (self::CORRECTIONS as $correction => $ref) {
            $value = self::correction($row['instrument_reading'] ?? null, $row[$ref] ?? null);
            if ($value !== null) {
                $row[$correction] = $value;
            }
        }
        return $row;
    }

    /**
     * Rows of the calibration results table with the max correction for the rising / falling series
     * and the calibration range (lowest / highest measured UUC value). Values are kept as typed.
     */
    public function getCalibrationResultsAttribute(): array
    {
        $rows = collect(data_get($this->calibration_details, 'results', []))
            ->filter(fn ($row) => is_array($row) && collect($row)->filter(fn ($v) => $v !== null && $v !== '')->isNotEmpty())
            ->map(fn ($row) => self::withCorrections($row))
            ->values();

        // the typed value of the extreme row, so the sign and decimals stay as on the table
        $pick = function (string $key, callable $score) use ($rows) {
            $values = $rows->pluck($key)->filter(fn ($v) => self::typedNumber($v) !== null);
            if ($values->isEmpty()) {
                return null;
            }
            return trim((string) $values->sortByDesc(fn ($v) => $score(self::typedNumber($v)))->first());
        };

        return [
            'rows' => $rows->all(),
            'max_rising' => $pick('p_error_rising', fn ($v) => abs($v)),
            'max_falling' => $pick('p_error_failing', fn ($v) => abs($v)),
            'range_min' => $pick('instrument_reading', fn ($v) => -$v),
            'range_max' => $pick('instrument_reading', fn ($v) => $v),
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

    // QA user who issued the certificate
    public function issuer()
    {
        return $this->belongsTo(User::class, 'user_id_issued');
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
        // corrections are always REF - UUC, whatever was posted
        if (is_array($value) && is_array($value['results'] ?? null)) {
            foreach ($value['results'] as $index => $row) {
                if (is_array($row)) {
                    $value['results'][$index] = self::withCorrections($row);
                }
            }
        }
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

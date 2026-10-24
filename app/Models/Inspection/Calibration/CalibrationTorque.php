<?php

namespace App\Models\Inspection\Calibration;

use App\Models\GeneralInfo\FooterValue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Models\User;
use App\Support\CalibrationCertificate;
use App\Support\TorqueCalibration;
use App\Traits\HasInspectionLogo;

// 'TYPE_4' => 'Calibration Certificate (Torque)',
// Torque wrench certificate RS-CAL-P11-F02 (ISO 6789-1 / ISO 6789-2), laid out like the Pressure Gauge certificate.
class CalibrationTorque extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
        'job_request_id',
        'code',
        'sync',
        'updated',
        'user_id_approved',
        'receipt_date',
        'calibration_date',
        'due_date',
        'issue_date',
        'due_period',
        'contact_name',
        'contact_info',
        'calibration_location',
        // environmental conditions: start / end readings, average and variation are calculated
        'temperature_start',
        'temperature_end',
        'temperature_tolerance',
        'humidity_start',
        'humidity_end',
        'humidity_tolerance',
        // equipment under calibration
        'equipment_description',
        'code_number',
        'serial_number',
        'manufacturer',
        'model',
        'range_value',
        'range_unit',
        'type_number',
        'type_letter',
        'resolution_value',
        'scale_interval_value',
        'permissible_deviation',
        'calibration_direction',
        // reference equipment
        'reference_equipment_description',
        'reference_serial_number',
        'reference_manufacturer',
        'reference_model',
        'reference_range',
        'calibration_certificate_no',
        'calibration_due_date',
        'traceability',
        'calibration_details',
        'specification_status',
        'remarks',
        'statement',
        // user_id_issued is not fillable: only the QA issue action (role permission "issue") sets it
    ];

    public const UNITS = ['N·m', 'gf·cm', 'kgf·cm', 'kgf·m', 'mN·m', 'cN·m', 'N·cm', 'ozf·in', 'lbf·in', 'lbf·ft'];

    public const DIRECTIONS = [
        'cw' => 'Clockwise direction',
        'ccw' => 'Anticlockwise direction',
        'cad' => 'Clockwise and anticlockwise directions',
    ];

    public const TYPES = ['1' => 'Type I', '2' => 'Type II'];

    public const CLASSES = ['A', 'B', 'C', 'D', 'E', 'F'];

    public const PERMISSIBLE_DEVIATIONS = ['4' => '4 %', '6' => '6 %'];

    /** Target points of the calibration, % of the range */
    public const TARGET_PERCENTS = [20, 60, 100];

    public const CALIBRATION_METHOD = 'The calibration was carried out in accordance with ISO 6789-1:2017 & ISO 6789-2:2017 "Assembly tools for screws and nuts - Hand torque tools - Requirements for calibration and determination of measurement uncertainty".';

    public const CONFORMITY_STATEMENT = "It is made according to the manufacturing specification, taking into account the uncertainty of measurement, the two possible conditions are indicated as below:\n"
        . "- Pass (Conforming): The measurement result, including its associated uncertainty, falls within the acceptable tolerance or specification limits.\n"
        . '- Fail (Non-conforming): The measurement result, considering uncertainty, falls outside the tolerance limits.';

    public const DEFAULT_STATEMENT = [
        'The reported relative expanded uncertainty, W of the calibrated torque tool is based on a standard uncertainty, w multiplied by a coverage factor k = 2, providing a level of confidence 95%.',
        "The relative measurement uncertainty interval, W' is calculated using the following equation: W' = |a̅s| + W",
        'This certificate refers only to the particular item submitted for calibration.',
        'This certificate shall not be reproduced unless written permission has been obtained. In this case, the certificate is to be reproduced in full.',
        'This certificate is valid only with signature and stamp and or QR code.',
    ];

    protected $dates = ['receipt_date', 'calibration_date', 'due_date', 'issue_date', 'calibration_due_date'];

    protected $hidden = ['created_at', 'updated_at'];

    protected static function booted()
    {
        // a duplicated certificate has to be issued by QA again
        static::replicating(function (self $model) {
            $model->user_id_issued = null;
        });

        // environmental conditions: average and variation of the start / end readings (Data Input sheet)
        static::saving(function (self $model) {
            foreach (['temperature' => 'temperature', 'humidity' => 'relative_humidity'] as $prefix => $averageColumn) {
                $start = TorqueCalibration::number($model->{$prefix . '_start'});
                $end = TorqueCalibration::number($model->{$prefix . '_end'});
                if ($start === null && $end === null) {
                    continue;
                }
                $values = array_filter([$start, $end], fn ($v) => $v !== null);
                $model->{$averageColumn} = self::trimNumber(array_sum($values) / count($values), 1);
                $model->{$prefix . '_uncertainty'} = ($start !== null && $end !== null) ? self::trimNumber(abs($start - $end) / 2, 2) : null;
            }
        });
    }

    private static function trimNumber(float $value, int $decimals): string
    {
        $text = number_format($value, $decimals, '.', '');
        return str_contains($text, '.') ? rtrim(rtrim($text, '0'), '.') : $text;
    }

    public static function computeDueDate(?string $calibrationDate, ?string $period): ?string
    {
        return CalibrationCertificate::computeDueDate($calibrationDate, $period);
    }

    /** Directions calibrated: ['cw'], ['ccw'] or both */
    public function getDirectionsAttribute(): array
    {
        $direction = $this->calibration_direction;
        if (!isset(self::DIRECTIONS[$direction])) {
            // first-version records saved no direction: use the results they have
            $details = (array) $this->calibration_details;
            $hasCw = !empty($details['readings']['cw']) || $this->legacyResults('cw');
            $hasCcw = !empty($details['readings']['ccw']) || $this->legacyResults('ccw');
            $direction = $hasCcw ? ($hasCw ? 'cad' : 'ccw') : 'cw';
        }

        return match ($direction) {
            'ccw' => ['ccw'],
            'cad' => ['cw', 'ccw'],
            default => ['cw'],
        };
    }

    /**
     * ISO 6789-2 results and uncertainty budget of the saved readings (see App\Support\TorqueCalibration).
     */
    public function getAnalysisAttribute(): array
    {
        $details = (array) $this->calibration_details;
        // records made before target values were saved: 20 / 60 / 100 % of the range
        $range = TorqueCalibration::number($this->range_value);
        foreach (self::TARGET_PERCENTS as $point => $percent) {
            if (TorqueCalibration::number($details['targets'][$point] ?? null) === null && $range !== null) {
                $details['targets'][$point] = $range * $percent / 100;
            }
        }

        return TorqueCalibration::analyse($details, $this->range_unit, $this->resolution_value, $this->directions);
    }

    /**
     * Statement lines printed under the conformity statement (older records repeat the conformity text: left out).
     */
    public function getStatementLinesAttribute(): array
    {
        $lines = array_map('trim', preg_split('/\r\n|\r|\n/', (string) $this->statement));

        return array_values(array_filter($lines, function ($line) {
            return trim($line, " .\t") !== ''
                && !str_starts_with($line, 'It is made according to the manufacturing specification')
                && !preg_match('/Pass \(Conforming\)|Fail \(Non-conforming\)/', $line);
        }));
    }

    /**
     * Results table of a certificate made before the raw data entry (applied torque, UUT average, correction, U exp).
     */
    public function legacyResults(string $direction): array
    {
        $key = $direction === 'ccw' ? 'counter_clockwise' : 'clockwise';
        return array_values(array_filter((array) data_get($this->calibration_details, 'legacy.' . $key, []), function ($row) {
            return is_array($row) && collect($row)->filter(fn ($v) => $v !== null && $v !== '')->isNotEmpty();
        }));
    }

    public function job_request()
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function report()
    {
        return $this->morphOne(InspectionReport::class, 'reportable');
    }

    // QA user who issued the certificate
    public function issuer()
    {
        return $this->belongsTo(User::class, 'user_id_issued');
    }

    public function getReceiptDateAttribute($value)
    {
        return $value ? (new Carbon($value))->format('d-m-Y') : null;
    }

    public function getCalibrationDateAttribute($value)
    {
        return $value ? (new Carbon($value))->format('d-m-Y') : null;
    }

    public function getDueDateAttribute($value)
    {
        return $value ? (new Carbon($value))->format('d-m-Y') : null;
    }

    public function getIssueDateAttribute($value)
    {
        return $value ? (new Carbon($value))->format('d-m-Y') : null;
    }

    // due date of the reference equipment calibration
    public function getCalibrationDueDateAttribute($value)
    {
        return $value && !str_starts_with((string) $value, '0000') ? (new Carbon($value))->format('d-m-Y') : null;
    }

    public function setCalibrationDetailsAttribute($value)
    {
        $this->attributes['calibration_details'] = json_encode($value);
    }

    /**
     * Raw data in the current layout. Older records are converted when read:
     * - July 2025 entry: clockwise / counter_clockwise rows of "1st".."5th" readings
     * - first version: clockwise / counter_clockwise rows of torque, uut, correction, uexp (kept as "legacy" results)
     */
    public function getCalibrationDetailsAttribute($value)
    {
        $details = json_decode((string) $value, true) ?: [];
        if (isset($details['readings']) || isset($details['legacy'])) {
            return $details;
        }

        $converted = [];
        foreach (['clockwise' => 'cw', 'counter_clockwise' => 'ccw'] as $oldKey => $direction) {
            $rows = array_values(array_filter((array) ($details[$oldKey] ?? []), 'is_array'));
            foreach ($rows as $point => $row) {
                if (array_key_exists('1st', $row)) {
                    $converted['readings'][$direction][$point] = array_values(array_intersect_key($row, array_flip(['1st', '2nd', '3rd', '4th', '5th'])));
                } elseif (array_key_exists('torque', $row)) {
                    $converted['legacy'][$oldKey][] = $row;
                }
            }
        }

        return $converted ?: $details;
    }

    public function getFooterValuesAttribute()
    {
        return FooterValue::query()
            ->where('related_inspection', $this->getMorphClass())
            ->get()->first();
    }
}

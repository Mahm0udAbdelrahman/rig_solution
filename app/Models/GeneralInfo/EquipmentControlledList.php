<?php

namespace App\Models\GeneralInfo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class EquipmentControlledList extends Model
{
    use HasFactory;

    protected $table = 'equipment_controlled_lists';

    protected $fillable = [
        'equipment_description',
        'internal_code',
        'manufacturer',
        'model_type',
        'capacity_range',
        'serial_number',
        'date_into_service',
        'interval',
        'calibration_date',
        'calibration_due_date',
        'calibrated_by',
        'recalibration_alarm',
        'location_department',
        'status',
        'date_removed_from_service',
        'notes',
        'certificate_path',
        'certificate_name',
        'certificate_uploaded_at',
        'user_id',
    ];

    protected $casts = [
        'date_into_service' => 'date',
        'calibration_date' => 'date',
        'calibration_due_date' => 'date',
        'certificate_uploaded_at' => 'datetime',
    ];

    protected $appends = ['certificate_url'];

    /**
     * Calibration intervals offered on the register, mapped to their length in months
     */
    public static $INTERVALS = [
        '6 Months' => 6,
        'Annual' => 12,
    ];

    public static $DEFAULT_INTERVAL = 'Annual';

    /**
     * Due date = calibration date + interval, or null when the interval is not a known one
     */
    public static function computeDueDate($calibrationDate, $interval)
    {
        if (empty($calibrationDate) || !isset(self::$INTERVALS[$interval])) {
            return null;
        }

        return Carbon::parse($calibrationDate)
            ->addMonthsNoOverflow(self::$INTERVALS[$interval])
            ->format('Y-m-d');
    }

    /**
     * Public link to the uploaded calibration certificate, or null when none was uploaded
     */
    public function getCertificateUrlAttribute()
    {
        return $this->certificate_path ? asset('storage/' . $this->certificate_path) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Equipment offered in the report equipment pickers (everything still in service), loaded once per request
     */
    public static function pickerOptions()
    {
        static $options;

        return $options ??= self::query()
            ->where(function ($q) {
                $q->whereNull('status')->orWhere('status', '!=', 'Out of Service');
            })
            ->orderBy('serial_number')
            ->get();
    }

    /**
     * Values a report picker copies into its form fields
     */
    public function getPickerDataAttribute()
    {
        return [
            'equipment_no' => $this->serial_number ?: $this->internal_code,
            'serial_number' => $this->serial_number,
            'internal_code' => $this->internal_code,
            'equipment_description' => $this->equipment_description,
            'manufacturer' => $this->manufacturer,
            'model_type' => $this->model_type,
            'capacity_range' => $this->capacity_range,
            'calibrated_by' => $this->calibrated_by,
            'calibration_date' => $this->calibration_date ? $this->calibration_date->format('d-m-Y') : '',
            'calibration_due_date' => $this->calibration_due_date ? $this->calibration_due_date->format('d-m-Y') : '',
        ];
    }

    public function getPickerLabelAttribute()
    {
        $label = trim(($this->serial_number ?: $this->internal_code) . ' — ' . $this->equipment_description, ' —');
        if ($this->internal_code && $this->serial_number) {
            $label .= ' (' . $this->internal_code . ')';
        }
        if ($this->calibration_due_date) {
            $label .= ' · Due ' . $this->calibration_due_date->format('d-m-Y')
                . ($this->calibration_due_date->isPast() ? ' (Overdue)' : '');
        }

        return $label;
    }

    /**
     * Determine dynamic alarm status if not explicitly set
     */
    public function getComputedAlarmAttribute()
    {
        if (!empty($this->recalibration_alarm)) {
            return $this->recalibration_alarm;
        }

        if (!$this->calibration_due_date) {
            return 'N/A';
        }

        $dueDate = Carbon::parse($this->calibration_due_date);
        $today = Carbon::today();

        if ($dueDate->isPast()) {
            return 'Overdue / Re-Calibrate';
        } elseif ($dueDate->diffInDays($today) <= 30) {
            return 'Due Soon';
        }

        return 'Calibrated';
    }
}

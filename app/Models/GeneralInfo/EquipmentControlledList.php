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
     * Status shown instead of "Active" once the calibration due date has passed
     */
    const STATUS_RECALIBRATE = 'Re-Calibrate';

    public static $STATUSES = ['Active', 'Under Maintenance', 'Under Calibration', 'Out of Service'];

    /**
     * Calibration due date has passed (due today is still valid)
     */
    public function getIsOverdueAttribute()
    {
        return $this->calibration_due_date && $this->calibration_due_date->lt(Carbon::today());
    }

    /**
     * Status as shown on the list and the ISO export: an Active equipment whose due date has passed becomes Re-Calibrate.
     * Under Maintenance / Under Calibration / Out of Service are kept as entered.
     */
    public function getDisplayStatusAttribute()
    {
        $status = $this->status ?: 'Active';

        return ($status === 'Active' && $this->is_overdue) ? self::STATUS_RECALIBRATE : $status;
    }

    /**
     * Re-calibration Alarm column of the ISO form: from the due date when there is one, otherwise the value entered
     */
    public function getIsoAlarmAttribute()
    {
        if ($this->calibration_due_date) {
            return $this->is_overdue ? 'Re-Calibrate' : 'Calibrated';
        }

        return $this->recalibration_alarm ?: 'N/A';
    }

    /**
     * Filter by the status as displayed (see getDisplayStatusAttribute)
     */
    public function scopeWhereDisplayStatus($query, $status)
    {
        $today = Carbon::today()->format('Y-m-d');
        $isActive = function ($q) {
            $q->whereNull('status')->orWhere('status', '')->orWhere('status', 'Active');
        };

        if ($status === self::STATUS_RECALIBRATE) {
            return $query->where($isActive)->where('calibration_due_date', '<', $today);
        }

        if ($status === 'Active') {
            return $query->where($isActive)->where(function ($q) use ($today) {
                $q->whereNull('calibration_due_date')->orWhere('calibration_due_date', '>=', $today);
            });
        }

        return $query->where('status', $status);
    }

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
}

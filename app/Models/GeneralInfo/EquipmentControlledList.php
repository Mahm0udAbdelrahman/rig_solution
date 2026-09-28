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
        'user_id',
    ];

    protected $casts = [
        'date_into_service' => 'date',
        'calibration_date' => 'date',
        'calibration_due_date' => 'date',
    ];

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

    public function user()
    {
        return $this->belongsTo(User::class);
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

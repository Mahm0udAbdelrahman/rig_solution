<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Layout helpers for the calibration certificates (Pressure Gauge, Pressure Test / Vacuum, Torque, Yoke, ...).
 * The certificates follow the "Calibration Certificate" Excel sheet: 11 columns (B..L) and Excel row heights,
 * with a shared header, client block and environmental conditions block (views: calibration/partials/show/certificate).
 */
class CalibrationCertificate
{
    /** Excel column widths B..L as % of the page */
    public const COLUMN_WIDTHS = [10.11, 10.11, 9.72, 9.80, 10.39, 9.32, 10.11, 10.86, 6.18, 6.77, 6.65];

    /** Excel row height (pt) -> px on the 1150px paper */
    public const ROW_SCALE = 0.67;

    public const DEFAULT_FIRST_LOGO = 'app-assets/images/logo/egac-accredited-logo.png';

    public const RIG_LOGO = 'app-assets/images/logo/rig-solution-logo.png';

    /** Calibration interval options of the due date; "manual" = typed by hand or left blank */
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
     * Request data with the due date of the chosen period (calibration date + 6 / 12 months),
     * or as typed (can be blank) for the "manual" period.
     */
    public static function withDueDate(array $data): array
    {
        $period = $data['due_period'] ?? 'annual';
        if (isset(self::DUE_PERIOD_MONTHS[$period])) {
            if (!empty($data['calibration_date'])) {
                $data['due_date'] = self::computeDueDate($data['calibration_date'], $period);
            }
        } else {
            $data['due_period'] = 'manual';
            $data['due_date'] = trim((string) ($data['due_date'] ?? '')) ?: null;
        }
        return $data;
    }

    public static function colgroup(): string
    {
        return '<colgroup>' . collect(self::COLUMN_WIDTHS)
            ->map(fn ($width) => '<col style="width:' . $width . '%">')
            ->implode('') . '</colgroup>';
    }

    public static function rowHeight(float $points): string
    {
        return round($points * self::ROW_SCALE, 1) . 'px';
    }

    /**
     * Date attribute as shown on certificates (18-Apr-2026); models expose dates as d-m-Y.
     */
    public static function date($model, string $attribute): string
    {
        $value = data_get($model, $attribute);
        if (!$value) {
            return '';
        }
        try {
            return Carbon::createFromFormat('d-m-Y', (string) $value)->format('d-M-Y');
        } catch (\Throwable $e) {
            try {
                return Carbon::parse($value)->format('d-M-Y');
            } catch (\Throwable $e) {
                return (string) $value;
            }
        }
    }

    /**
     * First (accreditation) logo: the one set in General Info > Inspection Logos when its file exists, otherwise EGAC.
     */
    public static function firstLogoUrl($model): string
    {
        $logo = data_get($model, 'inspection_logo');

        return ($logo && Storage::disk('public')->exists($logo))
            ? asset('storage/' . $logo)
            : asset(self::DEFAULT_FIRST_LOGO);
    }

    /**
     * Second logo: the "Second Logo" of the header logo set in General Info > Inspection Logos when its file exists,
     * otherwise the Rig Solution logo.
     */
    public static function secondLogoUrl($model): string
    {
        $logo = data_get($model, 'inspection_second_logo');

        return ($logo && Storage::disk('public')->exists($logo))
            ? asset('storage/' . $logo)
            : asset(self::RIG_LOGO);
    }

    /**
     * Calibration location entered on the report, or the job request location for older reports.
     */
    public static function location($model): string
    {
        $location = data_get($model, 'calibration_location');
        if ($location) {
            return $location;
        }
        $jobRequest = $model->job_request;
        if (!$jobRequest) {
            return '';
        }

        return $jobRequest->clientDepartment
            ? $jobRequest->clientDepartment->name . ' / ' . $jobRequest->deploc
            : (string) $jobRequest->deploc;
    }
}

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

<?php

namespace App\Support;

class InspectionRevisionWorkflow
{
    private const EXCLUDED_REPORTABLE_TYPES = [
        'App\\Models\\Inspection\\Lifting\\Lregister',
        'App\\Models\\Inspection\\Ndt\\DrawingInspection',
        'App\\Models\\Inspection\\Ndt\\Nregister',
    ];

    public static function supports(?string $reportableType): bool
    {
        $reportableType = trim((string) $reportableType);
        if ($reportableType === '') {
            return false;
        }

        return !in_array($reportableType, self::EXCLUDED_REPORTABLE_TYPES, true);
    }
}

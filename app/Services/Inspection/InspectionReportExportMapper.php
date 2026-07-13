<?php

namespace App\Services\Inspection;

use App\Models\Inspection\InspectionReport;

class InspectionReportExportMapper
{
    public function sectionLabel(?string $reportableType): string
    {
        $segments = $this->reportableSegments($reportableType);

        return ucfirst(strtolower((string) ($segments[0] ?? 'Inspection')));
    }

    public function typeLabel(?string $reportableType): string
    {
        $basename = class_basename((string) $reportableType);
        if ($basename === '') {
            return 'Unknown';
        }

        return trim((string) preg_replace('/(?<!^)[A-Z]/', ' $0', $basename));
    }

    public function entityTypeFromReport(?InspectionReport $report): string
    {
        if (!$report) {
            return '';
        }

        $segments = $this->reportableSegments((string) $report->reportable_type);
        $basename = $segments[1] ?? class_basename((string) $report->reportable_type);
        if ($basename === '') {
            return '';
        }

        return strtolower(lcfirst((string) $basename));
    }

    public function fileEntityTypeFromPath(?string $category, ?string $entityType): string
    {
        $entityType = strtolower(trim((string) $entityType));
        if ($entityType !== '') {
            return $entityType;
        }

        $parts = array_values(array_filter(explode('/', strtolower(trim((string) $category))), function ($part) {
            return $part !== '';
        }));

        return (string) ($parts[count($parts) - 1] ?? '');
    }

    public function identifier($reportable): string
    {
        if (!$reportable) {
            return '';
        }

        if (isset($reportable->lter_10) && !empty($reportable->lter_10) && isset($reportable->lter_10['pop1'])) {
            return (string) $reportable->lter_10['pop1'];
        }

        if (isset($reportable->ldr_8) && !empty($reportable->ldr_8)) {
            $parts = [];
            $decoded = json_decode((string) $reportable->ldr_8);
            foreach ((array) $decoded as $item) {
                $value = data_get($item, 'lcr_10');
                if (!empty($value)) {
                    $parts[] = (string) $value;
                }
            }
            if (!empty($parts)) {
                return implode(' | ', $parts);
            }
        }

        return $this->firstNonEmptyField($reportable, [
            'identification_no',
            'equipment_no',
            'serial_number',
            'device_serial_number',
            'material_no',
            'tool_number',
            'lcr_12',
            'locr_12',
            'lfr_12',
            'nmpr_28',
            'ntir_13',
            'nvr_6',
            'nur_26',
            'nwhr_15',
            'nhpr_10',
            'nh2pr_10',
        ]);
    }

    public function equipment($reportable): string
    {
        if (!$reportable) {
            return '';
        }

        if (isset($reportable->lter_10) && !empty($reportable->lter_10) && isset($reportable->lter_10['pop20'])) {
            return (string) $reportable->lter_10['pop20'];
        }

        return $this->firstNonEmptyField($reportable, [
            'name',
            'equipment_name',
            'item_name',
            'desc',
            'description',
            'joint_description',
            'material_description',
            'dc_description',
            'lcr_10',
            'locr_10',
            'lfr_10',
            'nur_12',
        ]);
    }

    public function examinationDate($reportable): string
    {
        if (!$reportable) {
            return '';
        }

        return $this->firstNonEmptyField($reportable, [
            'examination_date',
            'ldr_6',
            'lcr_6',
            'locr_6',
            'lfr_6',
            'lter_6',
            'nmpr_6',
            'ntir_6',
            'nvr_4',
            'nur_6',
            'nsr_4',
            'nwhr_6',
            'nhpr_6',
            'nh2pr_6',
            'nar_4',
        ]);
    }

    public function approvalStatus(?InspectionReport $report): string
    {
        if (!$report) {
            return 'Unlinked File';
        }

        return !empty($report->user_id_approved) ? 'Approved' : 'Need Approve';
    }

    public function publishStatus(?InspectionReport $report): string
    {
        if (!$report) {
            return 'Unlinked File';
        }

        if (!empty($report->publish)) {
            return 'Published';
        }

        return stripos((string) $report->code, 'duplicated') !== false ? 'Need Publish' : 'Draft';
    }

    private function reportableSegments(?string $reportableType): array
    {
        $clean = str_replace('App\\Models\\Inspection\\', '', (string) $reportableType);

        return array_values(array_filter(explode('\\', $clean), function ($segment) {
            return $segment !== '';
        }));
    }

    private function firstNonEmptyField($reportable, array $fields): string
    {
        foreach ($fields as $field) {
            $value = data_get($reportable, $field);
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            if ($value !== null && trim((string) $value) !== '') {
                return trim((string) $value);
            }
        }

        return '';
    }
}

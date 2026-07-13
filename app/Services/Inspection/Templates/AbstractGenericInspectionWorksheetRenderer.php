<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\InspectionReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class AbstractGenericInspectionWorksheetRenderer extends AbstractLiftingReportWorksheetRenderer
{
    protected function renderGenericModel(
        Worksheet $sheet,
        InspectionReport $report,
        Model $model,
        int $revisionNumber,
        string $title,
        string $subtitle,
        string $pdfDirectory,
        array $primaryFields = [],
        ?string $descriptionField = null,
        array $skipFields = []
    ): void {
        $model->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment');
        $report->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment', 'user.employee');

        $approvedUser = !empty($report->user_id_approved) ? User::query()->with('employee')->find($report->user_id_approved) : null;
        $footerValues = data_get($model, 'footer_values');

        $this->bootSheet($sheet, 'REV '.str_pad((string) $revisionNumber, 2, '0', STR_PAD_LEFT));
        $this->configureColumns($sheet);
        $this->addHeaderImages($sheet, $report);

        $row = $this->renderStandardHeader($sheet, $title, $subtitle);
        $row = $this->labeledValueRow(
            $sheet,
            $row,
            'Name of employer for whom the examination was made',
            $this->clientName($report),
            'Address of premises at examination was made',
            $this->clientLocation($report)
        );
        $row = $this->tripleValueRow($sheet, $row, [
            ['Purchase Order', (string) optional($report->job_request)->purchase_order],
            ['JCF Number', (string) optional($report->job_request)->code],
            ['Report No', $this->reportNumberWithRevision($report, (string) data_get($model, 'code'), $revisionNumber)],
        ]);
        $row = $this->labeledValueRow(
            $sheet,
            $row,
            'Work location',
            $this->workLocation($report),
            'Examination Date',
            $this->detectExaminationDate($model)
        );

        if ($descriptionField !== null) {
            $description = $this->normalizeValue(data_get($model, $descriptionField));
            if ($description !== '') {
                $row = $this->singleWideRow($sheet, $row, $this->fieldLabel($descriptionField), $description, $this->rowSpanFor($description));
            }
        }

        if (!empty($primaryFields)) {
            $row = $this->sectionTitle($sheet, $row, 'Primary Details');
            $row = $this->renderMappedFieldRows($sheet, $row, $model, $primaryFields);
        }

        $capturedRows = [];
        $skip = array_flip(array_merge([
            'job_request_id',
            'code',
            'sync',
            'updated',
            'user_id_approved',
            'created_at',
            'updated_at',
        ], $skipFields, array_keys($primaryFields), $descriptionField ? [$descriptionField] : []));

        foreach ($this->fillableFields($model) as $field) {
            if (isset($skip[$field])) {
                continue;
            }

            $value = $this->normalizeValue(data_get($model, $field));
            if ($value === '') {
                continue;
            }

            $capturedRows[] = [$this->fieldLabel($field), $value];
        }

        if (!empty($capturedRows)) {
            $row = $this->sectionTitle($sheet, $row, 'Captured Data');
            foreach ($capturedRows as [$label, $value]) {
                $row = $this->singleWideRow($sheet, $row, $label, $value, $this->rowSpanFor($value));
            }
        }

        $row++;
        $this->footerBlock(
            $sheet,
            $row,
            $report,
            $approvedUser,
            url('storage/'.$pdfDirectory.'/'.optional($report->job_request)->code.'/'.data_get($model, 'code').'.pdf'),
            data_get($footerValues, 'form_no'),
            data_get($footerValues, 'issue_no'),
            data_get($footerValues, 'issue_date'),
            data_get($footerValues, 'revision_no'),
            data_get($footerValues, 'revision_date')
        );
    }

    protected function renderMappedFieldRows(Worksheet $sheet, int $row, Model $model, array $fieldMap): int
    {
        $pairs = [];
        foreach ($fieldMap as $field => $label) {
            $value = $this->normalizeValue(data_get($model, $field));
            if ($value === '') {
                continue;
            }

            $pairs[] = [(string) $label, $value];
        }

        if (empty($pairs)) {
            return $row;
        }

        while (!empty($pairs)) {
            $chunk = array_splice($pairs, 0, 3);
            if (count($chunk) === 3 && $this->chunkFitsTripleRow($chunk)) {
                $row = $this->tripleValueRow($sheet, $row, $chunk);
                continue;
            }

            foreach ($chunk as [$label, $value]) {
                $row = $this->singleWideRow($sheet, $row, $label, $value, $this->rowSpanFor($value));
            }
        }

        return $row;
    }

    protected function fillableFields(Model $model): array
    {
        return method_exists($model, 'getFillable') ? $model->getFillable() : [];
    }

    protected function detectExaminationDate(Model $model): string
    {
        foreach ([
            'examination_date',
            'calibration_date',
            'issue_date',
            'receipt_date',
            'nar_4',
            'nsr_4',
            'nvr_4',
            'nwhr_6',
            'ntir_6',
            'nhpr_6',
            'nh2pr_6',
            'nh3pr_6',
            'nur_6',
        ] as $field) {
            $value = $this->normalizeValue(data_get($model, $field));
            if ($value !== '') {
                return $value;
            }
        }

        return '';
    }

    protected function fieldLabel(string $field): string
    {
        $field = trim($field);
        if ($field === 'desc') {
            return 'Description';
        }

        if (preg_match('/^([a-z]+)_([0-9]+)$/i', $field, $matches)) {
            return strtoupper($matches[1]).' '.$matches[2];
        }

        return Str::of($field)
            ->replace('_', ' ')
            ->replaceMatches('/(?<!^)([A-Z])/', ' $1')
            ->title()
            ->value();
    }

    protected function normalizeValue(mixed $value, int $depth = 0): string
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_scalar($value)) {
            return trim((string) $value);
        }

        if ($depth >= 2) {
            return trim((string) json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        if (is_object($value)) {
            $value = method_exists($value, 'toArray') ? $value->toArray() : (array) $value;
        }

        if (!is_array($value) || $value === []) {
            return trim((string) $value);
        }

        $isAssoc = array_keys($value) !== range(0, count($value) - 1);
        if ($isAssoc) {
            $parts = [];
            foreach ($value as $key => $item) {
                $itemValue = $this->normalizeValue($item, $depth + 1);
                if ($itemValue !== '') {
                    $parts[] = $this->fieldLabel((string) $key).': '.$itemValue;
                }
            }

            return implode(' | ', $parts);
        }

        $parts = [];
        foreach ($value as $index => $item) {
            $itemValue = $this->normalizeValue($item, $depth + 1);
            if ($itemValue !== '') {
                $parts[] = is_array($item) || is_object($item) ? '#'.($index + 1).' '.$itemValue : $itemValue;
            }
        }

        return implode(' | ', $parts);
    }

    protected function rowSpanFor(string $value): int
    {
        $length = mb_strlen($value);
        $lines = substr_count($value, "\n") + 1;

        return max(1, min(5, max($lines, (int) ceil($length / 130))));
    }

    protected function chunkFitsTripleRow(array $chunk): bool
    {
        foreach ($chunk as $pair) {
            if (mb_strlen((string) ($pair[1] ?? '')) > 60 || str_contains((string) ($pair[1] ?? ''), "\n")) {
                return false;
            }
        }

        return true;
    }
}

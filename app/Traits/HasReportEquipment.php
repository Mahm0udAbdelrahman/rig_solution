<?php

namespace App\Traits;

use App\Models\GeneralInfo\EquipmentControlledList;
use App\Models\Inspection\ReportEquipment;
use Illuminate\Http\Request;

trait HasReportEquipment
{
    public static function bootHasReportEquipment()
    {
        static::deleting(function ($model) {
            $model->reportEquipment()->delete();
        });
    }

    public function reportEquipment()
    {
        return $this->morphMany(ReportEquipment::class, 'reportable')->orderBy('sort');
    }

    /**
     * Additional equipment rows of one report section
     */
    public function reportEquipmentFor($section)
    {
        return $this->reportEquipment->where('section', $section)->values();
    }

    /**
     * Copy this report's equipment rows to another report (revisions, duplicates)
     */
    public function copyReportEquipmentTo($target)
    {
        if (!$target || !method_exists($target, 'reportEquipment')) {
            return;
        }

        $target->reportEquipment()->createMany(
            $this->reportEquipment->map(fn ($row) => $row->only($row->getFillable()))->all()
        );
        $target->unsetRelation('reportEquipment');
    }

    /**
     * Save the rows posted by layouts.repeated.equipment_rows: every section rendered on the form
     * (report_equipment_sections[]) is replaced by its posted rows (report_equipment[section][i][...]).
     */
    public function syncReportEquipment(Request $request)
    {
        $sections = array_filter(array_unique((array) $request->input('report_equipment_sections', [])), function ($section) {
            return is_string($section) && preg_match('/^[a-z0-9_]{1,50}$/', $section);
        });
        $posted = (array) $request->input('report_equipment', []);

        foreach ($sections as $section) {
            $this->reportEquipment()->where('section', $section)->delete();

            $sort = 0;
            foreach ((array) ($posted[$section] ?? []) as $row) {
                $row = (array) $row;
                $values = [];
                foreach (ReportEquipment::$FIELDS as $field) {
                    $value = isset($row[$field]) && is_string($row[$field]) ? trim($row[$field]) : '';
                    $values[$field] = $value === '' ? null : mb_substr($value, 0, 255);
                }
                $equipmentId = EquipmentControlledList::whereKey((int) ($row['equipment_id'] ?? 0))->value('id');
                if (!$equipmentId && !$values['equipment_no']) {
                    continue;
                }

                $this->reportEquipment()->create($values + [
                    'section' => $section,
                    'sort' => $sort++,
                    'equipment_controlled_list_id' => $equipmentId,
                ]);
            }
        }

        $this->unsetRelation('reportEquipment');
    }
}

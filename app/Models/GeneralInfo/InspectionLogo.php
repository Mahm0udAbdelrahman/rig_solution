<?php

namespace App\Models\GeneralInfo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionLogo extends Model
{
    use HasFactory;

    protected $fillable = [
		'name',
        'logo',
        'second_logo',
		'related_inspections',
    ];

    /**
     * All inspection report models grouped by section ([section => [class => label]]).
     * Every report is listed; one already linked to another header logo is labelled with that logo's name
     * and moves to this logo when it is ticked (see takeOverRelatedInspections).
     *
     * @param InspectionLogo|null $logo
     * @return array
     */
    public static function getAllInspectionModels(?InspectionLogo $logo = null): array
    {
        $namespaces = [
            'App\Models\Inspection\Lifting',
            'App\Models\Inspection\Ndt',
            'App\Models\Inspection\Tubular',
            'App\Models\Inspection\DropObject',
            'App\Models\Inspection\Calibration',
        ];

        // class => name of the other logo it is linked to
        $linkedTo = [];
        foreach (self::query()->when($logo, fn ($q) => $q->whereKeyNot($logo->getKey()))->get() as $other) {
            foreach ($other->related_inspections ?: [] as $class) {
                $linkedTo[$class] = $other->name;
            }
        }

        return array_merge(...array_map(function ($namespace) use ($linkedTo) {
            return self::getModelsInNamespace($namespace, $linkedTo);
        }, $namespaces));
    }

    /**
     * Returns the models of a namespace as [section => [class => label]].
     *
     * @param string $namespace
     * @param array $linkedTo
     * @return array
     */
    private static function getModelsInNamespace(string $namespace, array $linkedTo): array
    {
        $section = array_slice(explode('\\', $namespace), -1)[0];
        $directoryPath = base_path(str_replace('App', 'app', str_replace('\\', '/', $namespace)));
        if (!is_dir($directoryPath)) {
            return [$section => []];
        }

        $models = [];
        foreach (scandir($directoryPath) as $file) {
            if (!is_file($directoryPath . DIRECTORY_SEPARATOR . $file) || !str_ends_with($file, '.php')) {
                continue;
            }
            $modelName = substr($file, 0, -4);
            $key = $namespace . '\\' . $modelName;
            if (!class_exists($key)) {
                continue;
            }
            $models[$key] = isset($linkedTo[$key]) ? $modelName . ' (now: ' . $linkedTo[$key] . ')' : $modelName;
        }
        asort($models);

        return [$section => $models];
    }

    /**
     * A report has one header logo: remove this logo's reports from every other logo.
     */
    public function takeOverRelatedInspections(): void
    {
        $mine = $this->related_inspections ?: [];
        if (!$mine) {
            return;
        }
        foreach (self::query()->whereKeyNot($this->getKey())->get() as $other) {
            $current = $other->related_inspections ?: [];
            $remaining = array_values(array_diff($current, $mine));
            if (count($remaining) !== count($current)) {
                $other->update(['related_inspections' => $remaining]);
            }
        }
    }

	protected function setRelatedInspectionsAttribute($value)
    {
        $this->attributes['related_inspections'] = json_encode($value);
    }

    protected function getRelatedInspectionsAttribute($value)
    {
        return json_decode((string) $value, true) ?: [];
    }
}

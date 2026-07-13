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
		'related_inspections',
    ];

    /**
     * @param InspectionLogo|null $logo
     * @return array
     */
    
    public static function getAllInspectionModels(InspectionLogo $logo = null): array
    {
        $namespaces = [
            'App\Models\Inspection\Lifting',
            'App\Models\Inspection\Ndt',
            'App\Models\Inspection\Tubular',
            'App\Models\Inspection\DropObject',
            'App\Models\Inspection\Calibration',
        ];

        return array_merge(...array_map(function($namespace) use ($logo) {
            return self::getModelsInNamespace($namespace, $logo);
        }, $namespaces));
    }

    /**
     * Returns an array of fully qualified class names of all models in a given
     * namespace.
     *
     * @param string $namespace
     * @param InspectionLogo|null $logo
     * @return array
     */
    private static function getModelsInNamespace(string $namespace, InspectionLogo $logo = null): array
    {
        $directoryPath = base_path(str_replace('App', 'app', str_replace('\\', '/', $namespace)));
        if (!is_dir($directoryPath)) {
            return [];
        }
        $files = scandir($directoryPath);
        $models = [];
        $alreadySelected = InspectionLogo::pluck('related_inspections')->flatten(1)->toArray();

        foreach ($files as $file) {
            if (is_file($directoryPath . DIRECTORY_SEPARATOR . $file)) {
                $modelName = str_replace('.php', '', $file);

                $key = $namespace . '\\' . $modelName;
                if (isset($logo) && in_array($key, $logo->related_inspections)) {
                    $models[$key] = $modelName;
                } elseif (class_exists($key) && !in_array($key, $alreadySelected)) {
                    $models[$key] = $modelName;
                }
            }
        }
        return [array_slice(explode('\\', $namespace), -1)[0] => $models];
    }

	protected function setRelatedInspectionsAttribute($value)
    {
        $this->attributes['related_inspections'] = json_encode($value);
    }

    protected function getRelatedInspectionsAttribute($value)
    {
        return json_decode($value, true);
    }
}

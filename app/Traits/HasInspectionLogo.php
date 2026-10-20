<?php

namespace App\Traits;

use App\Models\GeneralInfo\InspectionLogo;

trait HasInspectionLogo
{
    /**
     * Get the related Inspection Logo based on the current model.
     * 
     * @return string|bool
     */
    public function getInspectionLogoAttribute()
    {
        // Fetch the related Inspection Logo based on the current model's class
        $inspectionLogo = InspectionLogo::query()
            ->whereJsonContains('related_inspections', get_class($this))
            ->first();
        
        // Return the logo or false if no related logo is found
        return $inspectionLogo ? $inspectionLogo->logo : false;
    }

    /**
     * Optional second logo of the related Inspection Logo (replaces the Rig Solution logo on calibration certificates).
     *
     * @return string|bool
     */
    public function getInspectionSecondLogoAttribute()
    {
        $inspectionLogo = InspectionLogo::query()
            ->whereJsonContains('related_inspections', get_class($this))
            ->first();

        return $inspectionLogo && $inspectionLogo->second_logo ? $inspectionLogo->second_logo : false;
    }
}

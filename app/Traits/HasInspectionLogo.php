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
}

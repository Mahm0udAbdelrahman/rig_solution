<?php

namespace Tests\Fixtures\Inspection;

use App\Models\Inspection\InspectionReport;
use Illuminate\Database\Eloquent\Model;

class FakeInspectionReportable extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function report()
    {
        return $this->morphOne(InspectionReport::class, 'reportable');
    }
}

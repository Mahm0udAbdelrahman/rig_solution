<?php

namespace App\Models\Inspection\Ndt;

use App\Models\GeneralInfo\FooterValue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Traits\HasInspectionLogo;

class DrawingInspection extends Model
{
    use HasFactory, HasInspectionLogo;

    protected $fillable = [
        'job_request_id',
        'code',
        'sync',
        'updated',
        'user_id_approved',
        'examination_date',
        'description',
        'dimension',
        'pressure',
        'identification_no',
        'report_image',
        'comment'
    ];

    protected $dates = ['examination_date'];

    protected $hidden = ['created_at', 'updated_at'];

    public function job_request()
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function report()
    {
        return $this->morphOne(InspectionReport::class, 'reportable');
    }

    public function setExaminationDateAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['examination_date'] = null;
            return;
        }
        try {
            if ($value instanceof \DateTimeInterface) {
                $this->attributes['examination_date'] = $value->format('Y-m-d');
            } elseif (is_string($value)) {
                $value = trim($value);
                if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $value)) {
                    $this->attributes['examination_date'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
                } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                    $this->attributes['examination_date'] = $value;
                } else {
                    $this->attributes['examination_date'] = Carbon::parse($value)->format('Y-m-d');
                }
            } else {
                $this->attributes['examination_date'] = null;
            }
        } catch (\Throwable $e) {
            $this->attributes['examination_date'] = null;
        }
    }

    // Accessor method to format examination_date attribute
    public function getExaminationDateAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        try {
            return Carbon::parse($value)->format('d-m-Y');
        } catch (\Throwable $e) {
            return null;
        }
    }

    // Define the virtual attribute accessor for related Footer Values
    public function getFooterValuesAttribute()
    {
        $footerValues = FooterValue::query()
            ->where('related_inspection', $this->getMorphClass())
            ->get()->first();
        return $footerValues;
    }
}

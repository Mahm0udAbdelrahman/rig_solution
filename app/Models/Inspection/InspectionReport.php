<?php

namespace App\Models\Inspection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

use App\Models\WorkFlow\JobRequest;
use App\Models\User;

class InspectionReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_request_id',
        'code',
        'status',
        'publish',
        'user_id',
        'user_id_edit',
        'user_id_approved',
        'reportable_id',
        'reportable_type',
        'sync',
        'updated',
    ];

    public function reportable()
    {
        return $this->morphTo();
    }

    protected static function booted()
    {
        static::creating(function (InspectionReport $report) {
            // Publish status is controlled by PDF upload flow only.
            if ((int) ($report->publish ?? 0) === 1) {
                $report->publish = null;
            }
        });

        static::updating(function (InspectionReport $report) {
            $approvalWasCleared = $report->isDirty('user_id_approved')
                && !empty($report->getOriginal('user_id_approved'))
                && empty($report->user_id_approved);

            $reportableChanged = $report->isDirty('reportable_id') || $report->isDirty('reportable_type');
            $editorStateChanged = $report->isDirty('user_id_edit');
            $isDuplicatedClone = stripos((string) ($report->code ?? $report->getOriginal('code')), 'duplicated') !== false;
            $duplicatedCloneTransitioned = $isDuplicatedClone && ($reportableChanged || $editorStateChanged);

            // Block model-level publish=1 writes; publish is set only by PDF upload endpoint.
            if ($report->isDirty('publish') && (int) $report->publish === 1) {
                $report->publish = $report->getOriginal('publish');
            }

            // Duplicated clones must never regain inherited approval during edit/submit transitions.
            if ($duplicatedCloneTransitioned) {
                $report->user_id_approved = null;
                $approvalWasCleared = false;
            }

            if ($duplicatedCloneTransitioned) {
                $nextNaturalCode = $report->resolveNextNaturalCode();
                if ($nextNaturalCode !== '') {
                    $report->code = $nextNaturalCode;
                    $report->syncReportableCode($nextNaturalCode);
                }
            }

            // When an approved report is edited into a new reportable record,
            // force publish back to null to require an explicit re-publish.
            if ($approvalWasCleared && $reportableChanged) {
                $report->publish = null;
            }

            // Any report edit/revision state transition should invalidate publish
            // until a new PDF is uploaded via the dedicated upload endpoint.
            if ($reportableChanged || $editorStateChanged) {
                $report->publish = null;
            }
        });
    }

    public function job_request()
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    private function normalizeDuplicatedBaseCode(string $code): string
    {
        $code = trim($code);
        if ($code === '') {
            return '';
        }

        return trim((string) preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', $code));
    }

    private function resolveNextNaturalCode(): string
    {
        $jobRequestId = (int) ($this->job_request_id ?? 0);
        if ($jobRequestId <= 0) {
            return '';
        }

        $codes = self::query()
            ->where('job_request_id', $jobRequestId)
            ->where('id', '!=', (int) ($this->id ?? 0))
            ->where('code', 'not like', '%Duplicated%')
            ->pluck('code');

        return $this->incrementInspectionCode($codes);
    }

    private function incrementInspectionCode(Collection $codes): string
    {
        if ($codes->isEmpty()) {
            return '001';
        }

        $max = $codes
            ->map(function ($code): int {
                $code = trim((string) $code);
                if ($code === '') {
                    return 0;
                }

                if (preg_match('/(\d+)$/', $code, $matches)) {
                    return (int) $matches[1];
                }

                return is_numeric($code) ? (int) $code : 0;
            })
            ->max();

        $next = ((int) $max) + 1;

        return $next > 999 ? (string) $next : str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }

    private function syncReportableCode(string $normalizedCode): void
    {
        $reportableType = (string) ($this->reportable_type ?? '');
        $reportableId = (int) ($this->reportable_id ?? 0);

        if ($normalizedCode === '' || $reportableType === '' || $reportableId <= 0 || !class_exists($reportableType)) {
            return;
        }

        $reportable = $reportableType::query()->find($reportableId);
        if (!$reportable || !method_exists($reportable, 'getTable')) {
            return;
        }

        $table = (string) $reportable->getTable();
        if ($table === '' || !Schema::hasColumn($table, 'code')) {
            return;
        }

        if ((string) ($reportable->code ?? '') === $normalizedCode) {
            return;
        }

        $reportable->forceFill(['code' => $normalizedCode])->save();
    }
}
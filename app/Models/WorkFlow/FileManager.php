<?php

namespace App\Models\WorkFlow;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileManager extends Model
{
    use HasFactory;

    protected $fillable = [
        'disk',
        'path',
        'filename',
        'extension',
        'mime_type',
        'size_bytes',
        'module',
        'category',
        'entity_type',
        'entity_code',
        'job_request_code',
        'is_inspection',
        'is_available',
        'last_seen_at',
        'generated_at',
        'note',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function publicUrl(): string
    {
        return Storage::disk((string) $this->disk)->url((string) $this->path);
    }

    public static function upsertPublicFile(string $path, ?Carbon $lastSeenAt = null): ?self
    {
        $disk = 'public';
        $storage = Storage::disk($disk);
        $normalizedPath = ltrim(str_replace('\\', '/', trim($path)), '/');

        if ($normalizedPath === '' || !$storage->exists($normalizedPath)) {
            return null;
        }

        $extension = strtolower((string) pathinfo($normalizedPath, PATHINFO_EXTENSION));
        if ($extension === '') {
            return null;
        }

        $metadata = static::extractMetadataFromPath($normalizedPath);
        $sizeBytes = null;
        $mimeType = null;
        $generatedAt = null;

        try {
            $sizeBytes = $storage->size($normalizedPath);
        } catch (\Throwable $e) {
            $sizeBytes = null;
        }

        try {
            $mimeType = $storage->mimeType($normalizedPath);
        } catch (\Throwable $e) {
            $mimeType = null;
        }

        try {
            $modifiedTimestamp = $storage->lastModified($normalizedPath);
            $generatedAt = $modifiedTimestamp ? Carbon::createFromTimestamp($modifiedTimestamp) : null;
        } catch (\Throwable $e) {
            $generatedAt = null;
        }

        return static::updateOrCreate(
            ['disk' => $disk, 'path' => $normalizedPath],
            [
                'filename' => $metadata['filename'],
                'extension' => $extension,
                'mime_type' => $mimeType,
                'size_bytes' => $sizeBytes,
                'module' => $metadata['module'],
                'category' => $metadata['category'],
                'entity_type' => $metadata['entity_type'],
                'entity_code' => $metadata['entity_code'],
                'job_request_code' => $metadata['job_request_code'],
                'is_inspection' => $metadata['is_inspection'],
                'is_available' => 1,
                'last_seen_at' => $lastSeenAt ?? now(),
                'generated_at' => $generatedAt,
            ]
        );
    }

    public static function extractMetadataFromPath(string $path): array
    {
        $normalizedPath = ltrim(str_replace('\\', '/', trim($path)), '/');
        $segments = array_values(array_filter(explode('/', trim($normalizedPath, '/')), function ($segment) {
            return $segment !== '';
        }));
        $filename = basename($normalizedPath);
        $entityCode = (string) pathinfo($filename, PATHINFO_FILENAME);

        $module = 'other';
        $category = 'general';
        $entityType = null;
        $jobRequestCode = null;
        $isInspection = 0;

        if (($segments[0] ?? null) === 'uploads') {
            $module = $segments[2] ?? 'uploads';
            $category = $segments[3] ?? ($segments[1] ?? 'general');
            $entityType = 'manual-upload';
            $candidateJcf = $segments[4] ?? null;
            if ($candidateJcf !== null && !preg_match('/^\d{4}$/', (string) $candidateJcf)) {
                $jobRequestCode = $candidateJcf;
            }
        } elseif (($segments[0] ?? null) === 'pdf') {
            if (($segments[1] ?? null) === 'workflow') {
                $module = 'workflow';
                $workflowType = strtolower((string) ($segments[2] ?? 'workflow'));
                $entityType = $workflowType;
                $category = $workflowType === 'invoice'
                    ? 'invoice_'.strtolower((string) ($segments[3] ?? 'all'))
                    : $workflowType;
                $jobRequestCode = static::resolveWorkflowJobRequestCode($workflowType, $entityCode, $segments);
            } elseif (($segments[1] ?? null) === 'inspection') {
                $module = 'inspection';
                $isInspection = 1;
                $mainGroup = strtolower((string) ($segments[2] ?? 'inspection'));
                $subType = strtolower((string) ($segments[3] ?? 'inspection'));
                $category = $mainGroup.'/'.$subType;
                $entityType = $subType;
                $jobRequestCode = $segments[count($segments) - 2] ?? null;
            } else {
                $module = 'pdf';
                $category = strtolower((string) ($segments[1] ?? 'pdf'));
                $entityType = $category;
            }
        } elseif (($segments[0] ?? null) === 'images') {
            if (($segments[1] ?? null) === 'pdf' && ($segments[2] ?? null) === 'workflow') {
                $module = 'images';
                $workflowType = strtolower((string) ($segments[3] ?? 'workflow'));
                $category = 'workflow/'.$workflowType;
                $entityType = $workflowType.'_snapshot';
                $entityCode = (string) ($segments[4] ?? $entityCode);
                $jobRequestCode = static::resolveWorkflowJobRequestCode($workflowType, $entityCode, $segments);
            } elseif (($segments[1] ?? null) === 'pdf' && ($segments[2] ?? null) === 'inspection') {
                $module = 'images';
                $mainGroup = strtolower((string) ($segments[3] ?? 'inspection'));
                $subType = strtolower((string) ($segments[4] ?? 'inspection'));
                $category = 'inspection/'.$mainGroup.'/'.$subType;
                $entityType = $subType.'_snapshot';
                $entityCode = (string) ($segments[count($segments) - 2] ?? $entityCode);
                $jobRequestCode = $segments[count($segments) - 3] ?? null;
                $isInspection = 1;
            } else {
                $module = 'images';
                $category = strtolower((string) ($segments[1] ?? 'images'));
                $entityType = $category;
                if (($segments[1] ?? '') === 'jcf') {
                    $jobRequestCode = $segments[2] ?? null;
                }
            }
        }

        return [
            'filename' => $filename,
            'module' => $module,
            'category' => $category,
            'entity_type' => $entityType,
            'entity_code' => $entityCode,
            'job_request_code' => $jobRequestCode,
            'is_inspection' => $isInspection,
        ];
    }

    private static function resolveWorkflowJobRequestCode(string $workflowType, ?string $entityCode, array $segments = []): ?string
    {
        if ($entityCode === null || trim($entityCode) === '') {
            return null;
        }

        if ($workflowType === 'jcf') {
            return $entityCode;
        }

        if ($workflowType === 'quotation') {
            return optional(Qutation::query()->with('jobRequest:id,code')->where('code', $entityCode)->first())->jobRequest->code ?? null;
        }

        if ($workflowType === 'packingslip') {
            return optional(PackingSlip::query()->with('jobRequest:id,code')->where('code', $entityCode)->first())->jobRequest->code ?? null;
        }

        if ($workflowType === 'serviceticket') {
            return optional(ServiceTicket::query()->with('jobRequest:id,code')->where('code', $entityCode)->first())->jobRequest->code ?? null;
        }

        if ($workflowType === 'invoice') {
            $invoiceCode = $entityCode;
            if (($segments[3] ?? null) !== null && in_array(strtoupper((string) $segments[3]), [Invoice::$INVOICE_RSE_TYPE, Invoice::$INVOICE_LTD_TYPE], true)) {
                $invoice = Invoice::query()
                    ->with('jobRequest:id,code')
                    ->where('invoice_company_type', strtoupper((string) $segments[3]))
                    ->where('code', $invoiceCode)
                    ->first();

                return optional($invoice)->jobRequest->code ?? null;
            }

            return optional(Invoice::query()->with('jobRequest:id,code')->where('code', $invoiceCode)->first())->jobRequest->code ?? null;
        }

        return null;
    }
}

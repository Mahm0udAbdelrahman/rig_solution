<?php

namespace App\Http\Controllers\Dashboard\WorkFlow;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Http\Controllers\Controller;
use App\Services\WorkFlow\FileManagerExcelExportService;
use App\Services\WorkFlow\FileManagerInspectionExcelExportService;
use App\Services\WorkFlow\FileManagerInspectionWorkbookExportService;
use App\Models\WorkFlow\FileManager;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class FileManagerController extends Controller
{
    public $page_name = 'File Manager';

    public function __construct(
        private readonly FileManagerExcelExportService $excelExportService,
        private readonly FileManagerInspectionExcelExportService $inspectionExcelExportService,
        private readonly FileManagerInspectionWorkbookExportService $inspectionWorkbookExportService,
    )
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', FileManager::class);

        $filters = $this->readFiltersFromRequest($request);
        $filteredQuery = $this->applyFilters($this->inspectionFilesQuery(), $filters);
        $filteredCount = (clone $filteredQuery)->count();
        $pdfCount = (clone $filteredQuery)->where('extension', 'pdf')->count();
        $imageCount = (clone $filteredQuery)->whereIn('extension', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'])->count();
        $jcfCount = (clone $filteredQuery)->whereNotNull('job_request_code')->where('job_request_code', '!=', '')->distinct()->count('job_request_code');
        $totalSizeBytes = (int) ((clone $filteredQuery)->sum('size_bytes') ?? 0);
        $viewMode = $this->normalizeViewMode($request->get('view_mode'));
        $folderTree = $viewMode === 'folders'
            ? $this->buildFolderTree((clone $filteredQuery)->orderBy('category')->orderBy('job_request_code')->orderBy('path')->get())
            : [];

        $filterOptionsQuery = $this->inspectionFilesQuery();

        return view('layouts.work-flow.file-manager.index', [
            'page_name' => $this->page_name('All', $this->page_name),
            'files_count' => $filteredCount,
            'stats' => [
                'total' => $filteredCount,
                'pdf' => $pdfCount,
                'images' => $imageCount,
                'inspection' => $filteredCount,
                'jcf' => $jcfCount,
                'size_human' => $this->humanBytes($totalSizeBytes),
            ],
            'view_mode' => $viewMode,
            'folder_tree' => $folderTree,
            'selected_filters' => $filters,
            'category_options' => (clone $filterOptionsQuery)->whereNotNull('category')->distinct()->orderBy('category')->pluck('category')->values(),
            'extension_options' => (clone $filterOptionsQuery)->whereNotNull('extension')->distinct()->orderBy('extension')->pluck('extension')->values(),
            'job_request_code_options' => (clone $filterOptionsQuery)->whereNotNull('job_request_code')->where('job_request_code', '!=', '')->distinct()->orderBy('job_request_code')->pluck('job_request_code')->values(),
            'can_update_any' => auth()->user()->can('update', FileManager::make()),
            'can_delete_index_any' => auth()->user()->can('delete', FileManager::make()),
            'can_delete_physical_any' => auth()->user()->can('deletePhysicalAny', FileManager::class),
        ]);
    }

    public function getDataForDataTable(Request $request)
    {
        $this->authorize('viewAny', FileManager::class);

        $filters = $this->readFiltersFromRequest($request);

        $data = $this->applyFilters(
            $this->inspectionFilesQuery()->select([
                'id as file_manager_id',
                'filename',
                'path',
                'category',
                'entity_type',
                'entity_code',
                'job_request_code',
                'extension',
                'mime_type',
                'size_bytes',
                'generated_at',
                'is_available',
                'disk',
            ]),
            $filters
        );

        return Datatables::of($data)
            ->addColumn('select_row', function ($row) {
                $file = $this->fileFromDataTableRow($row);
                if (!$file || !auth()->user()->can('view', $file)) {
                    return '';
                }

                $workbookMeta = $this->workbookExportMeta($file);
                $exportable = $workbookMeta['supported'] ? '1' : '0';
                $familyKey = e((string) ($workbookMeta['family_key'] ?? ''));

                return '<div class="custom-control custom-checkbox text-center">
                            <input type="checkbox" class="custom-control-input js-file-select" id="file-select-'.$file->id.'" value="'.$file->id.'" data-exportable="'.$exportable.'" data-family-key="'.$familyKey.'">
                            <label class="custom-control-label" for="file-select-'.$file->id.'"></label>
                        </div>';
            })
            ->editColumn('filename', function ($row) {
                $file = $this->fileFromDataTableRow($row);
                if (!$file || !$file->is_available) {
                    return $row->filename;
                }
                if (!auth()->user()->can('view', $file)) {
                    return $row->filename;
                }

                return '<a href="'.route('fileManager.download', $file->id).'">'.$row->filename.'</a>';
            })
            ->editColumn('size_bytes', function ($row) {
                return $this->humanBytes((int) ($row->size_bytes ?? 0));
            })
            ->editColumn('generated_at', function ($row) {
                return $this->formatDateOutput($row->generated_at, 'd-m-Y H:i');
            })
            ->addColumn('action', function ($row) {
                $file = $this->fileFromDataTableRow($row);
                if (!$file || !auth()->user()->can('view', $file)) {
                    return '';
                }

                $btn = '';
                if ((int)$file->is_available === 1) {
                    $openUrl = Storage::disk($file->disk)->url($file->path);
                    $isPreviewable = Str::startsWith((string)$file->mime_type, 'image/') || strtolower((string)$file->extension) === 'pdf';
                    if ($isPreviewable) {
                        $btn .= '<button type="button" class="btn btn-icon btn-warning mr-1 js-preview-file" data-open-url="'.e($openUrl).'" data-file-name="'.e($file->filename).'" data-mime-type="'.e((string)$file->mime_type).'" title="Preview"><i class="la la-eye"></i></button>';
                    }
                    if ($this->canExportInspectionWorkbook($file)) {
                        $btn .= '<a href="'.route('fileManager.exportInspectionWorkbook', $file->id).'" class="btn btn-success btn-sm mr-1" title="Export Full Report Workbook (All Revisions)"><i class="la la-table"></i> Excel</a>';
                    }
                    $btn .= '<a target="_blank" href="'.$openUrl.'" class="btn btn-icon btn-info mr-1" title="Open"><i class="la la-external-link"></i></a>';
                    $btn .= '<a href="'.route('fileManager.download', $file->id).'" class="btn btn-icon btn-primary mr-1" title="Download"><i class="la la-download"></i></a>';
                }
                $btn .= '<button type="button" class="btn btn-icon btn-secondary mr-1 js-copy-path" data-path="'.e((string)$file->path).'" title="Copy Path"><i class="la la-copy"></i></button>';
                if (auth()->user()->can('delete', $file)) {
                    $btn .= '<button type="button" data-id="'.$file->id.'" class="btn btn-icon btn-danger delete" title="Remove Index"><i class="la la-trash"></i></button>';
                }
                if (auth()->user()->can('deletePhysical', $file)) {
                    $btn .= '<button type="button" class="btn btn-icon btn-outline-danger js-physical-delete" data-url="'.route('fileManager.destroyPhysical', $file->id).'" data-file-name="'.e($file->filename).'" title="Delete Physical File"><i class="la la-times-circle"></i></button>';
                }

                if ($btn === '') {
                    return '';
                }

                return '<div class="wf-inline-actions">'.$btn.'</div>';
            })
            ->orderColumn('filename', 'filename $1')
            ->orderColumn('path', 'path $1')
            ->orderColumn('category', 'category $1')
            ->orderColumn('entity_type', 'entity_type $1')
            ->orderColumn('job_request_code', 'job_request_code $1')
            ->orderColumn('extension', 'extension $1')
            ->orderColumn('size_bytes', 'size_bytes $1')
            ->orderColumn('generated_at', 'generated_at $1')
            ->filterColumn('filename', function ($query, $keyword) {
                $query->whereRaw('filename like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('path', function ($query, $keyword) {
                $query->whereRaw('path like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('category', function ($query, $keyword) {
                $query->whereRaw('category like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('entity_type', function ($query, $keyword) {
                $query->whereRaw('entity_type like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('job_request_code', function ($query, $keyword) {
                $query->whereRaw('job_request_code like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('extension', function ($query, $keyword) {
                $query->whereRaw('extension like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('size_bytes', function ($query, $keyword) {
                $query->whereRaw('size_bytes like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('generated_at', function ($query, $keyword) {
                $query->whereRaw('generated_at like ?', ["%{$keyword}%"]);
            })
            ->rawColumns(['select_row', 'filename', 'action'])
            ->make(true);
    }

    public function sync(Request $request)
    {
        $this->authorize('create', FileManager::class);

        $result = $this->syncPublicFilesIndex();
        $message = 'File index synced. New: '.$result['created'].' | Updated: '.$result['updated'].' | Scanned: '.$result['scanned'];

        return redirect()->route('fileManager.index', ['tab' => 'files'])->with('success', $message);
    }

    public function upload(Request $request)
    {
        $this->authorize('create', FileManager::class);

        $validated = $request->validate([
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['required', 'file', 'max:20480'],
            'upload_module' => ['nullable', 'string', 'max:100'],
            'upload_category' => ['nullable', 'string', 'max:120'],
            'upload_job_request_code' => ['nullable', 'string', 'max:191'],
            'upload_note' => ['nullable', 'string', 'max:2000'],
            'view_mode' => ['nullable', 'string'],
        ]);

        $files = $request->file('files', []);
        $module = $this->normalizeUploadSegment($validated['upload_module'] ?? '', 'manual');
        $category = $this->normalizeUploadSegment($validated['upload_category'] ?? '', 'general');
        $jobRequestCode = trim((string)($validated['upload_job_request_code'] ?? ''));
        $jobRequestCode = $jobRequestCode !== '' ? $jobRequestCode : null;
        $note = trim((string)($validated['upload_note'] ?? ''));
        $storedCount = 0;

        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $path = $this->storeUploadedFile($file, $module, $category, $jobRequestCode);
            $extension = strtolower((string)$file->getClientOriginalExtension());
            $mimeType = $file->getClientMimeType() ?: $file->getMimeType();
            $sizeBytes = $file->getSize();

            FileManager::updateOrCreate(
                ['disk' => 'public', 'path' => $path],
                [
                    'filename' => basename($path),
                    'extension' => $extension,
                    'mime_type' => $mimeType,
                    'size_bytes' => $sizeBytes,
                    'module' => $module,
                    'category' => $category,
                    'entity_type' => 'manual-upload',
                    'entity_code' => pathinfo((string)basename($path), PATHINFO_FILENAME),
                    'job_request_code' => $jobRequestCode,
                    'is_inspection' => $module === 'inspection' ? 1 : 0,
                    'is_available' => 1,
                    'last_seen_at' => now(),
                    'generated_at' => now(),
                    'note' => $note !== '' ? $note : null,
                ]
            );

            $storedCount++;
        }

        return redirect()->route('fileManager.index', ['view_mode' => $this->normalizeViewMode($request->get('view_mode'))])
            ->with('success', $storedCount.' file(s) uploaded and indexed successfully.');
    }

    public function download(FileManager $fileManager)
    {
        $this->authorize('view', $fileManager);

        if ((int)$fileManager->is_available !== 1 || !Storage::disk($fileManager->disk)->exists($fileManager->path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk($fileManager->disk)->download($fileManager->path, $fileManager->filename);
    }

    public function bulkDownload(Request $request)
    {
        $this->authorize('viewAny', FileManager::class);

        $files = $this->resolveBulkSelection($request, 'view', true);
        if ($files->isEmpty()) {
            return redirect()->route('fileManager.index', ['view_mode' => $this->normalizeViewMode($request->get('view_mode'))])
                ->with('error', 'No downloadable files were selected.');
        }

        $zipPath = tempnam(sys_get_temp_dir(), 'fm_zip_');
        if ($zipPath === false) {
            return redirect()->route('fileManager.index', ['view_mode' => $this->normalizeViewMode($request->get('view_mode'))])
                ->with('error', 'Unable to prepare archive file.');
        }

        $zipArchivePath = $zipPath.'.zip';
        @rename($zipPath, $zipArchivePath);

        $zip = new ZipArchive();
        if ($zip->open($zipArchivePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            @unlink($zipArchivePath);

            return redirect()->route('fileManager.index', ['view_mode' => $this->normalizeViewMode($request->get('view_mode'))])
                ->with('error', 'Unable to create ZIP archive.');
        }

        foreach ($files as $file) {
            try {
                $absolutePath = Storage::disk($file->disk)->path($file->path);
                if (is_file($absolutePath)) {
                    $entryName = ltrim(($file->job_request_code ? $file->job_request_code.'/' : '').$file->filename, '/');
                    $zip->addFile($absolutePath, $entryName);
                }
            } catch (\Throwable $e) {
                // skip invalid file path
            }
        }

        $zip->close();

        return response()->download($zipArchivePath, 'file-manager-selection-'.now()->format('Ymd_His').'.zip')->deleteFileAfterSend(true);
    }

    public function bulkExportInspectionWorkbooks(Request $request)
    {
        $this->authorize('viewAny', FileManager::class);
        $downloadMode = strtolower(trim((string) $request->input('download_mode', 'zip')));

        $files = $this->resolveBulkSelection($request, 'view', true)
            ->filter(function (FileManager $file) {
                return $this->canExportInspectionWorkbook($file);
            })
            ->values();

        if ($files->isEmpty()) {
            return redirect()->route('fileManager.index', ['view_mode' => $this->normalizeViewMode($request->get('view_mode'))])
                ->with('error', 'No selected files support report Excel export.');
        }

        $zipPath = tempnam(sys_get_temp_dir(), 'fm_xlsx_zip_');
        if ($zipPath === false) {
            return redirect()->route('fileManager.index', ['view_mode' => $this->normalizeViewMode($request->get('view_mode'))])
                ->with('error', 'Unable to prepare workbook archive file.');
        }

        $zipArchivePath = $zipPath.'.zip';
        @rename($zipPath, $zipArchivePath);

        $zip = new ZipArchive();
        if ($zip->open($zipArchivePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            @unlink($zipArchivePath);

            return redirect()->route('fileManager.index', ['view_mode' => $this->normalizeViewMode($request->get('view_mode'))])
                ->with('error', 'Unable to create workbook ZIP archive.');
        }

        $temporaryFiles = [];

        try {
            $groupedReports = $files
                ->map(function (FileManager $file) {
                    $report = $this->inspectionWorkbookExportService->resolveExportReport($file);
                    if (!$report) {
                        return null;
                    }

                    return [
                        'file' => $file,
                        'report' => $report,
                        'family_key' => $this->inspectionWorkbookExportService->buildFamilyKey($report),
                    ];
                })
                ->filter()
                ->unique('family_key')
                ->values();

            if ($groupedReports->isEmpty()) {
                throw new \RuntimeException('No selected files could be resolved to exportable report families.');
            }

            if ($downloadMode === 'single') {
                if ($groupedReports->count() !== 1) {
                    throw new \RuntimeException('Single report Excel export requires exactly one report family.');
                }

                $singleWorkbook = $this->inspectionWorkbookExportService->storeTemporaryWorkbook($groupedReports->first()['file']);
                $temporaryFiles[] = $singleWorkbook['path'];

                return response()->download(
                    $singleWorkbook['path'],
                    $singleWorkbook['filename']
                )->deleteFileAfterSend(true);
            }

            if ($downloadMode === 'combined') {
                return $this->inspectionWorkbookExportService->downloadCombinedWorkbook(
                    $files,
                    collect($groupedReports)->pluck('file')->first()?->job_request_code.'-'.strtolower(class_basename((string) (collect($groupedReports)->pluck('report')->first()?->reportable_type))).'-jcf-type-reports'
                );
            }

            foreach ($groupedReports as $groupedReport) {
                $file = $groupedReport['file'];
                $workbook = $this->inspectionWorkbookExportService->storeTemporaryWorkbook($file);
                $temporaryFiles[] = $workbook['path'];

                $entryName = ltrim(($file->job_request_code ? $file->job_request_code.'/' : '').$workbook['filename'], '/');
                $zip->addFile($workbook['path'], $entryName);
            }

            $zip->close();

            return response()->download(
                $zipArchivePath,
                'inspection-report-workbooks-'.now()->format('Ymd_His').'.zip'
            )->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            $zip->close();
            @unlink($zipArchivePath);

            return redirect()->route('fileManager.index', ['view_mode' => $this->normalizeViewMode($request->get('view_mode'))])
                ->with('error', $e->getMessage());
        } finally {
            foreach ($temporaryFiles as $temporaryFile) {
                if (is_string($temporaryFile) && is_file($temporaryFile)) {
                    @unlink($temporaryFile);
                }
            }
        }
    }

    public function destroy(FileManager $fileManager)
    {
        $this->authorize('delete', $fileManager);

        $removed = $fileManager->forceDelete();
        if ($removed) {
            return response()->json(['success' => $this->action_message(2, $this->page_name)]);
        }

        return response()->json(['success' => 'Unable to remove this file index record.'], 422);
    }

    public function bulkDestroy(Request $request)
    {
        $files = $this->resolveBulkSelection($request, 'delete', false);
        if ($files->isEmpty()) {
            return redirect()->route('fileManager.index', ['view_mode' => $this->normalizeViewMode($request->get('view_mode'))])
                ->with('error', 'No indexed files were selected for removal.');
        }

        $deleted = 0;
        foreach ($files as $file) {
            $this->authorize('delete', $file);
            $deleted += (int) $file->forceDelete();
        }

        return redirect()->route('fileManager.index', ['view_mode' => $this->normalizeViewMode($request->get('view_mode'))])
            ->with('success', $deleted.' file index record(s) removed successfully.');
    }

    public function bulkMove(Request $request)
    {
        $validated = $request->validate([
            'selected_ids' => ['required', 'array', 'min:1'],
            'move_module' => ['nullable', 'string', 'max:100'],
            'move_category' => ['nullable', 'string', 'max:120'],
            'move_job_request_code' => ['nullable', 'string', 'max:191'],
            'move_note' => ['nullable', 'string', 'max:2000'],
            'view_mode' => ['nullable', 'string'],
        ]);

        $files = $this->resolveBulkSelection($request, 'update', false);
        if ($files->isEmpty()) {
            return redirect()->route('fileManager.index', ['view_mode' => $this->normalizeViewMode($request->get('view_mode'))])
                ->with('error', 'No files were selected for bulk move / recategorize.');
        }

        $targetModule = trim((string) ($validated['move_module'] ?? ''));
        $targetCategory = trim((string) ($validated['move_category'] ?? ''));
        $targetJobRequestCode = trim((string) ($validated['move_job_request_code'] ?? ''));
        $targetNote = trim((string) ($validated['move_note'] ?? ''));

        if ($targetModule === '' && $targetCategory === '' && $targetJobRequestCode === '' && $targetNote === '') {
            return redirect()->route('fileManager.index', ['view_mode' => $this->normalizeViewMode($request->get('view_mode'))])
                ->with('error', 'Provide at least one target value to move or recategorize files.');
        }

        $moved = 0;
        $recategorized = 0;
        $noteUpdated = 0;
        $skippedProtected = 0;

        foreach ($files as $file) {
            $this->authorize('update', $file);

            $normalizedModule = $targetModule !== '' ? $this->normalizeUploadSegment($targetModule, $file->module ?: 'other') : (string) $file->module;
            $normalizedCategory = $targetCategory !== '' ? $this->normalizeUploadSegment($targetCategory, $file->category ?: 'general') : (string) $file->category;
            $normalizedJcf = $targetJobRequestCode !== '' ? $targetJobRequestCode : (string) $file->job_request_code;

            $didMove = false;
            $allowMetadataRetag = true;
            if ($this->canMoveManagedFile($file)) {
                $newPath = $this->resolveMovedPath($file, $normalizedModule, $normalizedCategory, $normalizedJcf !== '' ? $normalizedJcf : null);
                if ($newPath !== $file->path) {
                    Storage::disk($file->disk)->move($file->path, $newPath);
                    $file->path = $newPath;
                    $file->filename = basename($newPath);
                    $didMove = true;
                    $moved++;
                }
            } elseif ($targetModule !== '' || $targetCategory !== '' || $targetJobRequestCode !== '') {
                $skippedProtected++;
                $allowMetadataRetag = false;
            }

            $metadataChanged = false;
            if ($allowMetadataRetag && $targetModule !== '' && $file->module !== $normalizedModule) {
                $file->module = $normalizedModule;
                $metadataChanged = true;
            }
            if ($allowMetadataRetag && $targetCategory !== '' && $file->category !== $normalizedCategory) {
                $file->category = $normalizedCategory;
                $metadataChanged = true;
            }
            if ($allowMetadataRetag && $targetJobRequestCode !== '' && (string) $file->job_request_code !== $normalizedJcf) {
                $file->job_request_code = $normalizedJcf;
                $metadataChanged = true;
            }
            if ($targetNote !== '' && (string) $file->note !== $targetNote) {
                $file->note = $targetNote;
                $noteUpdated++;
            }

            if ($didMove || $metadataChanged || $targetNote !== '') {
                $file->last_seen_at = now();
                $file->save();
            }

            if ($metadataChanged) {
                $recategorized++;
            }
        }

        $message = 'Bulk file update completed. Moved: '.$moved.' | Recategorized: '.$recategorized.' | Notes updated: '.$noteUpdated;
        if ($skippedProtected > 0) {
            $message .= ' | Protected system files skipped: '.$skippedProtected;
        }

        return redirect()->route('fileManager.index', ['view_mode' => $this->normalizeViewMode($request->get('view_mode'))])
            ->with('success', $message);
    }

    public function destroyPhysical(FileManager $fileManager)
    {
        $this->authorize('deletePhysical', $fileManager);

        if (Storage::disk($fileManager->disk)->exists($fileManager->path)) {
            Storage::disk($fileManager->disk)->delete($fileManager->path);
        }

        $fileManager->forceDelete();

        return response()->json(['success' => 'Physical file and index deleted successfully.']);
    }

    public function bulkDestroyPhysical(Request $request)
    {
        $files = $this->resolveBulkSelection($request, 'deletePhysical', false);
        if ($files->isEmpty()) {
            return redirect()->route('fileManager.index', ['view_mode' => $this->normalizeViewMode($request->get('view_mode'))])
                ->with('error', 'No physical files were selected for deletion.');
        }

        $deleted = 0;
        foreach ($files as $file) {
            $this->authorize('deletePhysical', $file);
            if (Storage::disk($file->disk)->exists($file->path)) {
                Storage::disk($file->disk)->delete($file->path);
            }
            $deleted += (int) $file->forceDelete();
        }

        return redirect()->route('fileManager.index', ['view_mode' => $this->normalizeViewMode($request->get('view_mode'))])
            ->with('success', $deleted.' physical file(s) and index record(s) deleted successfully.');
    }

    public function exportExcel(Request $request)
    {
        $this->authorize('viewAny', FileManager::class);

        $filters = $this->readFiltersFromRequest($request);
        $rows = $this->applyFilters($this->inspectionFilesQuery(), $filters)->orderByDesc('generated_at')->get();

        return $this->excelExportService->download(
            'inspection-files-export-'.now()->format('Ymd_His').'.xlsx',
            'Inspection Files',
            [
                'File Name',
                'Path',
                'Category',
                'Entity Type',
                'Entity Code',
                'JCF Code',
                'Extension',
                'Mime Type',
                'Size (Bytes)',
                'Generated At',
                'Disk',
            ],
            $rows->map(function (FileManager $row) {
                return [
                    $row->filename,
                    $row->path,
                    $row->category,
                    $row->entity_type,
                    $row->entity_code,
                    $row->job_request_code,
                    $row->extension,
                    $row->mime_type,
                    (string) ($row->size_bytes ?? ''),
                    $this->formatDateOutput($row->generated_at, 'Y-m-d H:i:s'),
                    $row->disk,
                ];
            }),
            $this->buildExcelMetaLines('Inspection Files Export', $filters, $rows->count())
        );
    }

    public function exportPdf(Request $request)
    {
        $this->authorize('viewAny', FileManager::class);

        $filters = $this->readFiltersFromRequest($request);
        $rows = $this->applyFilters($this->inspectionFilesQuery(), $filters)->orderByDesc('generated_at')->get();

        return $this->exportRowsPdf($rows, $filters, 'inspection-files-export-'.now()->format('Ymd_His').'.pdf');
    }

    public function exportInspectionsExcel(Request $request)
    {
        $this->authorize('viewAny', FileManager::class);

        $filters = $this->readFiltersFromRequest($request);
        $rows = $this->applyInspectionExportSourceScope(
            $this->applyFilters($this->inspectionFilesQuery(), $filters)
        )->orderByDesc('generated_at')->get();

        $mappedRows = $this->inspectionExcelExportService->buildRows($rows);

        return $this->excelExportService->download(
            'inspection-files-export-'.now()->format('Ymd_His').'.xlsx',
            'Inspection Reports',
            $this->inspectionExcelExportService->headings(),
            $this->inspectionExcelExportService->rowValues($mappedRows),
            $this->buildExcelMetaLines('Inspection Reports Export', $filters, $mappedRows->count())
        );
    }

    public function exportInspectionsPdf(Request $request)
    {
        $this->authorize('viewAny', FileManager::class);

        $filters = $this->readFiltersFromRequest($request);
        $rows = $this->applyInspectionExportSourceScope(
            $this->applyFilters($this->inspectionFilesQuery(), $filters)
        )->orderByDesc('generated_at')->get();

        return $this->exportRowsPdf($rows, $filters, 'inspection-files-export-'.now()->format('Ymd_His').'.pdf');
    }

    public function exportInspectionWorkbook(FileManager $fileManager)
    {
        $this->authorize('view', $fileManager);

        try {
            return $this->inspectionWorkbookExportService->download($fileManager);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function exportRowsPdf($rows, array $filters, string $filename)
    {
        $pdf = PDF::loadView('layouts.work-flow.file-manager.export-pdf', [
            'rows' => $rows,
            'filters' => $filters,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    private function syncPublicFilesIndex(): array
    {
        $disk = 'public';
        $storage = Storage::disk($disk);
        $allFiles = $storage->allFiles();
        $now = now();

        FileManager::where('disk', $disk)->update(['is_available' => 0, 'last_seen_at' => $now]);

        $created = 0;
        $updated = 0;
        $scanned = 0;

        foreach ($allFiles as $path) {
            $normalizedPath = ltrim(str_replace('\\', '/', (string)$path), '/');
            $model = FileManager::upsertPublicFile($normalizedPath, $now);
            if (!$model) {
                continue;
            }

            if ($model->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }

            $scanned++;
        }

        return ['created' => $created, 'updated' => $updated, 'scanned' => $scanned];
    }

    private function extractMetadataFromPath(string $path): array
    {
        return FileManager::extractMetadataFromPath($path);
    }

    private function readFiltersFromRequest(Request $request): array
    {
        return [
            'category' => trim((string)$request->get('category', '')),
            'extension' => trim((string)$request->get('extension', '')),
            'job_request_code' => trim((string)$request->get('job_request_code', '')),
            'date_from' => $this->normalizeDateInput($request->get('date_from')),
            'date_to' => $this->normalizeDateInput($request->get('date_to')),
            'preset' => trim((string)$request->get('preset', '')),
        ];
    }

    private function applyFilters($query, array $filters)
    {
        if (($filters['category'] ?? '') !== '') {
            $query->where('category', $filters['category']);
        }
        if (($filters['extension'] ?? '') !== '') {
            $normalizedExtension = strtolower((string) $filters['extension']);
            if ($normalizedExtension === 'image') {
                $query->whereIn('extension', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
            } else {
                $query->where('extension', $normalizedExtension);
            }
        }
        if (($filters['job_request_code'] ?? '') !== '') {
            $query->where('job_request_code', 'like', '%'.$filters['job_request_code'].'%');
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('generated_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('generated_at', '<=', $filters['date_to']);
        }

        return $query;
    }

    private function applyInspectionExportSourceScope($query)
    {
        return $query
            ->where('is_inspection', 1)
            ->where('is_available', 1)
            ->where('extension', 'pdf')
            ->where('path', 'like', 'pdf/inspection/%');
    }

    private function buildExcelMetaLines(string $title, array $filters, int $rowCount): array
    {
        $summary = [];
        foreach ([
            'category' => 'Category',
            'extension' => 'Extension',
            'job_request_code' => 'JCF',
            'date_from' => 'From',
            'date_to' => 'To',
            'preset' => 'Preset',
        ] as $key => $label) {
            $value = trim((string) ($filters[$key] ?? ''));
            if ($value !== '') {
                $summary[] = $label.': '.$value;
            }
        }

        return [
            $title,
            'Generated At: '.now()->format('Y-m-d H:i:s'),
            'Rows: '.$rowCount,
            'Filters: '.(!empty($summary) ? implode(' | ', $summary) : 'None'),
        ];
    }

    private function normalizeDateInput($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string)$value);
        if ($value === '') {
            return null;
        }

        $formats = ['d-m-Y', 'Y-m-d', 'd/m/Y', 'Y/m/d', 'd.m.Y', 'm/d/Y'];
        foreach ($formats as $format) {
            try {
                $parsed = Carbon::createFromFormat($format, $value);
                if ($parsed !== false) {
                    return $parsed->format('Y-m-d');
                }
            } catch (\Throwable $e) {
                // try next format
            }
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function formatDateOutput($value, $format = 'd-m-Y'): string
    {
        if ($value === null || trim((string)$value) === '') {
            return '-';
        }

        try {
            return Carbon::parse($value)->format($format);
        } catch (\Throwable $e) {
            return '-';
        }
    }

    private function humanBytes(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = (int) floor(log($bytes, 1024));
        $power = max(0, min($power, count($units) - 1));
        $value = $bytes / (1024 ** $power);

        return number_format($value, $power === 0 ? 0 : 2, '.', '').' '.$units[$power];
    }

    private function fileFromDataTableRow($row): ?FileManager
    {
        if (!$row || empty($row->file_manager_id)) {
            return null;
        }

        $file = new FileManager();
        $file->id = (int) $row->file_manager_id;
        $file->filename = (string) ($row->filename ?? '');
        $file->path = (string) ($row->path ?? '');
        $file->disk = (string) ($row->disk ?? 'public');
        $file->is_available = (int) ($row->is_available ?? 0);
        $file->extension = (string) ($row->extension ?? '');
        $file->mime_type = (string) ($row->mime_type ?? '');
        $file->module = 'inspection';
        $file->category = (string) ($row->category ?? '');
        $file->entity_type = (string) ($row->entity_type ?? '');
        $file->entity_code = (string) ($row->entity_code ?? '');
        $file->job_request_code = (string) ($row->job_request_code ?? '');
        $file->generated_at = $row->generated_at ?? null;
        $file->size_bytes = $row->size_bytes ?? null;

        return $file;
    }

    private function inspectionFilesQuery()
    {
        return FileManager::query()
            ->where('is_inspection', 1)
            ->where('module', 'inspection')
            ->where('is_available', 1);
    }

    private function normalizeViewMode($value): string
    {
        $mode = strtolower(trim((string)$value));

        return in_array($mode, ['table', 'folders'], true) ? $mode : 'table';
    }

    private function normalizeUploadSegment($value, string $fallback): string
    {
        $value = trim((string)$value);
        if ($value === '') {
            return $fallback;
        }

        $slug = Str::of($value)->lower()->replace(['\\', '/'], '-')->slug('-')->value();

        return $slug !== '' ? $slug : $fallback;
    }

    private function storeUploadedFile(UploadedFile $file, string $module, string $category, ?string $jobRequestCode): string
    {
        $segments = ['uploads', 'manual', $module, $category];
        if ($jobRequestCode) {
            $segments[] = $this->normalizeUploadSegment($jobRequestCode, 'without-jcf');
        }
        $segments[] = now()->format('Y');
        $segments[] = now()->format('m');

        $directory = implode('/', $segments);
        $filename = $this->resolveUniqueStoredFilename($directory, $file);

        return $file->storeAs($directory, $filename, 'public');
    }

    private function resolveUniqueStoredFilename(string $directory, UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower((string)$file->getClientOriginalExtension());
        $baseName = Str::of($originalName)->trim()->replace(['\\', '/'], '-')->value();
        $baseName = $baseName !== '' ? $baseName : 'file';

        $candidate = $extension !== '' ? $baseName.'.'.$extension : $baseName;
        $counter = 1;
        while (Storage::disk('public')->exists(trim($directory, '/').'/'.$candidate)) {
            $suffix = '-'.str_pad((string)$counter, 2, '0', STR_PAD_LEFT);
            $candidate = $extension !== '' ? $baseName.$suffix.'.'.$extension : $baseName.$suffix;
            $counter++;
        }

        return $candidate;
    }

    private function resolveBulkSelection(Request $request, string $ability, bool $availableOnly)
    {
        $ids = collect($request->input('selected_ids', []))
            ->filter(function ($value) {
                return is_scalar($value) && (int)$value > 0;
            })
            ->map(function ($value) {
                return (int)$value;
            })
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return collect();
        }

        $query = FileManager::query()->whereIn('id', $ids->all());
        if ($availableOnly) {
            $query->where('is_available', 1);
        }

        return $query->get()->filter(function (FileManager $file) use ($ability) {
            return auth()->user()->can($ability, $file);
        })->values();
    }

    private function canMoveManagedFile(FileManager $file): bool
    {
        $path = ltrim(str_replace('\\', '/', (string) $file->path), '/');

        return (int) $file->is_available === 1
            && Storage::disk($file->disk)->exists($path)
            && Str::startsWith($path, ['uploads/manual/', 'uploads/managed/']);
    }

    private function resolveMovedPath(FileManager $file, string $module, string $category, ?string $jobRequestCode): string
    {
        $generatedAt = $file->generated_at ? Carbon::parse($file->generated_at) : now();
        $segments = [
            'uploads',
            'managed',
            $this->normalizeUploadSegment($module, 'other'),
            $this->normalizeUploadSegment($category, 'general'),
        ];

        if ($jobRequestCode !== null && trim($jobRequestCode) !== '') {
            $segments[] = $this->normalizeUploadSegment($jobRequestCode, 'without-jcf');
        }

        $segments[] = $generatedAt->format('Y');
        $segments[] = $generatedAt->format('m');
        $directory = implode('/', $segments);

        return $this->resolveUniquePath($directory, (string) $file->filename, (string) $file->path);
    }

    private function resolveUniquePath(string $directory, string $filename, string $currentPath = ''): string
    {
        $directory = trim(str_replace('\\', '/', $directory), '/');
        $filename = trim($filename);
        $extension = strtolower((string) pathinfo($filename, PATHINFO_EXTENSION));
        $basename = (string) pathinfo($filename, PATHINFO_FILENAME);
        $basename = $basename !== '' ? $basename : 'file';
        $candidate = $extension !== '' ? $basename.'.'.$extension : $basename;
        $candidatePath = $directory.'/'.$candidate;
        $counter = 1;

        while ($candidatePath !== ltrim(str_replace('\\', '/', $currentPath), '/') && Storage::disk('public')->exists($candidatePath)) {
            $suffix = '-'.str_pad((string) $counter, 2, '0', STR_PAD_LEFT);
            $candidate = $extension !== '' ? $basename.$suffix.'.'.$extension : $basename.$suffix;
            $candidatePath = $directory.'/'.$candidate;
            $counter++;
        }

        return $candidatePath;
    }

    private function buildFolderTree($rows): array
    {
        $tree = [];
        foreach ($rows as $row) {
            $moduleKey = 'inspection';
            $categoryKey = $this->displaySegmentValue($row->category, 'general');
            $jcfKey = $this->displaySegmentValue($row->job_request_code, 'without-jcf');

            if (!isset($tree[$moduleKey])) {
                $tree[$moduleKey] = [
                    'total_files' => 0,
                    'theme' => $this->resolveFolderTheme($moduleKey, 'module'),
                    'categories' => [],
                ];
            }
            if (!isset($tree[$moduleKey]['categories'][$categoryKey])) {
                $tree[$moduleKey]['categories'][$categoryKey] = [
                    'total_files' => 0,
                    'theme' => $this->resolveFolderTheme($categoryKey, 'category'),
                    'jcfs' => [],
                ];
            }
            if (!isset($tree[$moduleKey]['categories'][$categoryKey]['jcfs'][$jcfKey])) {
                $tree[$moduleKey]['categories'][$categoryKey]['jcfs'][$jcfKey] = [
                    'total_files' => 0,
                    'inspection_files' => 0,
                    'files' => [],
                ];
            }

            $openUrl = (int)$row->is_available === 1
                ? Storage::disk((string)$row->disk)->url((string)$row->path)
                : null;

            $tree[$moduleKey]['total_files']++;
            $tree[$moduleKey]['categories'][$categoryKey]['total_files']++;
            $tree[$moduleKey]['categories'][$categoryKey]['jcfs'][$jcfKey]['total_files']++;
            if ((int) $row->is_inspection === 1) {
                $tree[$moduleKey]['categories'][$categoryKey]['jcfs'][$jcfKey]['inspection_files']++;
            }
            $workbookMeta = $this->workbookExportMeta($row);
            $tree[$moduleKey]['categories'][$categoryKey]['jcfs'][$jcfKey]['files'][] = [
                'id' => (int)$row->id,
                'filename' => (string)$row->filename,
                'path' => (string)$row->path,
                'extension' => (string)$row->extension,
                'mime_type' => (string)$row->mime_type,
                'size_human' => $this->humanBytes((int)($row->size_bytes ?? 0)),
                'generated_at' => $this->formatDateOutput($row->generated_at, 'd-m-Y H:i'),
                'is_available' => (int)$row->is_available,
                'is_inspection' => (int)$row->is_inspection,
                'badge_label' => (int) $row->is_inspection === 1 ? 'Inspection' : 'File',
                'open_url' => $openUrl,
                'download_url' => route('fileManager.download', $row->id),
                'workbook_export_url' => $workbookMeta['supported'] ? route('fileManager.exportInspectionWorkbook', $row->id) : null,
                'workbook_exportable' => $workbookMeta['supported'],
                'workbook_family_key' => $workbookMeta['family_key'],
                'workbook_family_code' => $workbookMeta['family_code'] ?? null,
                'workbook_type_label' => $workbookMeta['type_label'] ?? null,
                'physical_delete_url' => route('fileManager.destroyPhysical', $row->id),
                'previewable' => $openUrl !== null && (
                    Str::startsWith((string)$row->mime_type, 'image/')
                    || strtolower((string)$row->extension) === 'pdf'
                ),
                'can_update' => auth()->user()->can('update', $row),
                'can_delete_index' => auth()->user()->can('delete', $row),
                'can_delete_physical' => auth()->user()->can('deletePhysical', $row),
            ];
        }

        ksort($tree, SORT_STRING);
        foreach ($tree as &$moduleData) {
            ksort($moduleData['categories'], SORT_STRING);
            foreach ($moduleData['categories'] as &$categoryData) {
                ksort($categoryData['jcfs'], SORT_STRING);
                foreach ($categoryData['jcfs'] as &$jcfData) {
                    usort($jcfData['files'], function ($a, $b) {
                        return strcmp($a['path'], $b['path']);
                    });

                    $familyGroups = [];
                    $jcfExportableIds = [];
                    foreach ($jcfData['files'] as $fileItem) {
                        $groupKey = !empty($fileItem['workbook_exportable']) && !empty($fileItem['workbook_family_key'])
                            ? (string) $fileItem['workbook_family_key']
                            : 'file:'.$fileItem['id'];

                        if (!isset($familyGroups[$groupKey])) {
                            $familyGroups[$groupKey] = [
                                'family_key' => $fileItem['workbook_family_key'] ?? null,
                                'family_label' => $fileItem['workbook_family_code'] ?: $fileItem['filename'],
                                'type_label' => $fileItem['workbook_type_label'] ?: ($fileItem['badge_label'] ?? 'File'),
                                'exportable' => !empty($fileItem['workbook_exportable']),
                                'export_url' => $fileItem['workbook_export_url'] ?? null,
                                'file_ids' => [],
                                'files' => [],
                            ];
                        }

                        $familyGroups[$groupKey]['file_ids'][] = (int) $fileItem['id'];
                        $familyGroups[$groupKey]['files'][] = $fileItem;

                        if (!empty($fileItem['workbook_exportable'])) {
                            $jcfExportableIds[] = (int) $fileItem['id'];
                        }
                    }

                    foreach ($familyGroups as &$familyGroup) {
                        $familyGroup['file_count'] = count($familyGroup['files']);
                        $familyGroup['revision_count'] = count($familyGroup['files']);
                        $familyGroup['file_ids'] = array_values(array_unique(array_filter($familyGroup['file_ids'])));
                    }
                    unset($familyGroup);

                    $jcfData['family_groups'] = array_values($familyGroups);
                    $jcfData['exportable_file_ids'] = array_values(array_unique(array_filter($jcfExportableIds)));
                    $jcfData['exportable_family_count'] = count(array_filter($jcfData['family_groups'], function ($familyGroup) {
                        return !empty($familyGroup['exportable']);
                    }));
                }
                unset($jcfData);
            }
            unset($categoryData);
        }
        unset($moduleData);

        return $tree;
    }

    private function canExportInspectionWorkbook($file): bool
    {
        return $this->workbookExportMeta($file)['supported'];
    }

    private function workbookExportMeta($file): array
    {
        if (!$file instanceof FileManager) {
            $file = $this->fileFromDataTableRow($file);
        }

        if (!$file) {
            return ['supported' => false, 'family_key' => null];
        }

        if (!$this->inspectionWorkbookExportService->supportsFile($file)) {
            return ['supported' => false, 'family_key' => null];
        }

        $report = $this->inspectionWorkbookExportService->resolveExportReport($file);
        if (!$report) {
            return ['supported' => false, 'family_key' => null];
        }

        return [
            'supported' => true,
            'family_key' => $this->inspectionWorkbookExportService->buildFamilyKey($report),
            'family_code' => $this->normalizeWorkbookFamilyCode((string) data_get($report->reportable, 'code', $report->code)),
            'type_label' => $this->humanizeReportableType((string) $report->reportable_type),
        ];
    }

    private function normalizeWorkbookFamilyCode(string $code): string
    {
        $code = trim((string) preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', trim($code)));

        return $code !== '' ? $code : 'Uncoded Report';
    }

    private function humanizeReportableType(string $reportableType): string
    {
        $basename = class_basename($reportableType);
        if ($basename === '') {
            return 'Inspection Report';
        }

        return trim((string) preg_replace('/(?<!^)[A-Z]/', ' $0', $basename));
    }

    private function resolveFolderTheme(string $value, string $scope): array
    {
        $value = strtolower(trim($value));

        if ($scope === 'module') {
            return match ($value) {
                'inspection' => ['class' => 'folder-theme-inspection', 'icon' => 'la-shield'],
                'workflow' => ['class' => 'folder-theme-workflow', 'icon' => 'la-sitemap'],
                'images' => ['class' => 'folder-theme-images', 'icon' => 'la-picture-o'],
                'pdf' => ['class' => 'folder-theme-pdf', 'icon' => 'la-file-pdf-o'],
                default => ['class' => 'folder-theme-neutral', 'icon' => 'la-folder-open'],
            };
        }

        if (str_contains($value, 'ndt')) {
            return ['class' => 'folder-theme-ndt', 'icon' => 'la-flask'];
        }
        if (str_contains($value, 'tubular')) {
            return ['class' => 'folder-theme-tubular', 'icon' => 'la-link'];
        }
        if (str_contains($value, 'lifting')) {
            return ['class' => 'folder-theme-lifting', 'icon' => 'la-anchor'];
        }
        if (str_contains($value, 'calibration')) {
            return ['class' => 'folder-theme-calibration', 'icon' => 'la-dashboard'];
        }

        return ['class' => 'folder-theme-neutral', 'icon' => 'la-folder'];
    }

    private function displaySegmentValue($value, string $fallback): string
    {
        $value = trim((string)$value);
        if ($value === '') {
            return $fallback;
        }

        return strtolower($value);
    }
}

<?php

namespace App\Http\Controllers;

// Illuminate\Http
use Illuminate\Http\Request;
use Illuminate\Http\Client\Pool;

// Illuminate\Support
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

use Illuminate\Bus\Batch;

// App\Jobs
use App\Jobs\SyncJob;

// App\Notifications
use App\Notifications\SyncNotification;
use App\Notifications\InspectionReportApprovedNotification;

// App\Models
use App\Models\Inspection\InspectionReport;
use App\Services\Inspection\InspectionReportLifecycleService;
use App\Models\User;
use App\Models\WorkFlow\FileManager;
use App\Models\WorkFlow\JobRequest;
use App\Support\MailCenterNavbarData;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Synchronization\Sync;

// Other
use DB;

class CustomController extends Controller
{
    private const JCF_TABLES = [
        'clients',
        'suppliers',
    ];

    private const APPROVABLE_TABLES = [
        'inspection_reports',
        'qutations',
    ];


    public function forJcf($table)
    {
            $table = strtolower((string) $table);
            if (!in_array($table, self::JCF_TABLES, true)) {
                abort(404);
            }

            return DB::table($table)->select('id', 'name', 'code')->get();

    }//end forJcf()


        /***********************************
         * Any Model Need To Approve
         *********************************/
    public function userApprove(Request $request)
    {
            $validated = $request->validate([
                'id' => ['required', 'integer', 'min:1'],
                'table' => ['required', 'string', Rule::in(self::APPROVABLE_TABLES)],
            ]);

            $user = Auth::user();
            if (!$user) {
                abort(403);
            }

            $table = (string) $validated['table'];
            $id = (int) $validated['id'];
            $update = 0;

            if ($table === 'inspection_reports') {
                $inspectionReport = InspectionReport::query()->findOrFail($id);
                $module = $this->resolveInspectionModuleKey((string) $inspectionReport->reportable_type);
                if (!$module || (!$user->hasPermission($module, 'approve') && !$user->hasPermission($module, 'all') && !$user->isSuperAdmin())) {
                    abort(403);
                }

                $update = $inspectionReport->update([
                    'user_id_approved' => $user->id,
                ]);

                if ($update) {
                    $this->notifyInspectionCreatorOnApproval($inspectionReport->fresh(['job_request']));
                }
            } elseif ($table === 'qutations') {
                if (!$user->hasPermission('qutation', 'approve') && !$user->hasPermission('qutation', 'all') && !$user->isSuperAdmin()) {
                    abort(403);
                }

                $update = DB::table('qutations')->where('id', $id)->update([
                    'user_id_approved' => $user->id,
                ]);
            }

        if ($update) {
                return response()->json(
                    ['success' => 'This Model Approved successfully !']
                );
        }

        return response()->json(
            ['error' => 'Unable to approve this record.'],
            422
        );

    }//end userApprove()

    private function resolveInspectionModuleKey(string $reportableType): ?string
    {
        $module = strtolower(class_basename($reportableType));
        return $module !== '' ? $module : null;
    }

    private function notifyInspectionCreatorOnApproval(InspectionReport $inspectionReport): void
    {
        if (!Schema::hasTable('notifications')) {
            return;
        }

        $settings = MailCenterNavbarData::settings();
        if (!(bool) ($settings->notify_on_approved ?? true)) {
            return;
        }

        $creatorId = (int) ($inspectionReport->user_id ?? 0);
        if ($creatorId <= 0 || $creatorId === (int) Auth::id()) {
            return;
        }

        $creator = User::query()
            ->with(['employee:id,name', 'mailCenterNotificationPreference'])
            ->where('is_active', 1)
            ->find($creatorId);

        if (!$creator) {
            return;
        }

        $preference = MailCenterNavbarData::userPreference($creator);
        if (!$preference->allowsEvent('approved')) {
            return;
        }

        Notification::send($creator, new InspectionReportApprovedNotification($inspectionReport));
    }


        /************************************************************************************************/


        /****************************
         * Collect ScreenShots And Collect In PDF
         ***************************/
    public function generatePdf(Request $request)
    {
        $request->validate([
            'pdf' => ['required', 'file'],
            'folder' => ['required', 'string'],
            'imageurl' => ['required', 'string'],
            'report_id' => ['nullable', 'integer', 'min:1'],
        ]);

        $pdfFile = $request->file('pdf');
            $storedPath = $pdfFile->storePubliclyAs(
                trim((string) $request->folder, '/'),
                trim((string) $request->imageurl).'.pdf',
                'public'
            );
        if ($storedPath) {
                FileManager::upsertPublicFile($storedPath, now());

                $reportId = (int) $request->input('report_id', 0);
                $folder = trim((string) $request->input('folder', ''), '/');
                if ($reportId > 0 && str_starts_with($folder, 'pdf/inspection')) {
                    $report = InspectionReport::query()->find($reportId);
                    if ($report) {
                        app(InspectionReportLifecycleService::class)->markPublished($report);
                    }
                }

                return response()->json(
                    ['success' => 'PDF Uploaded Successfully !']
                );
        }

    }//end generatePdf()


        /************************************************************************************************/


        /**********************************
         * Get Screenshot Of Page
         **************************************/
    public function makeImageForPdf(Request $request)
    {
        $request->validate([
            'folder' => ['required', 'string'],
            'imageurl' => ['required', 'string'],
            'image' => ['nullable'],
            'page_index' => ['nullable', 'integer', 'min:0'],
            'page_data' => ['nullable', 'string'],
        ]);

        $folder = trim((string) $request->folder, '/');
        $imageurl = trim((string) $request->imageurl, '/');

        if ($request->filled('page_index') && $request->filled('page_data')) {
            $key = (int) $request->input('page_index');
            $data = (string) $request->input('page_data');
            if (str_contains($data, 'base64,')) {
                $data = explode('base64,', $data)[1];
            }
            $content = base64_decode($data);
            if ($content !== false) {
                $path = 'images/' . $folder . '/' . $imageurl . '/file' . $key . '.png';
                Storage::disk('public')->put($path, $content);
                FileManager::upsertPublicFile($path, now());
            }
            return response()->json(['success' => 'Page capture saved']);
        }

        $images = json_decode((string) $request->image, true) ?: [];

        foreach ($images as $key => $value) {
            $data = (string) $value;
            if (str_contains($data, 'base64,')) {
                $data = explode('base64,', $data)[1];
            }
            $content = base64_decode($data);
            if ($content === false) {
                $content = @file_get_contents($value);
            }
            if ($content !== false && $content !== null) {
                $path = 'images/' . $folder . '/' . $imageurl . '/file' . $key . '.png';
                Storage::disk('public')->put($path, $content);
                FileManager::upsertPublicFile($path, now());
            }
        }

        return response()->json(
            ['success' => 'System Generate Capture']
        );

    }//end makeImageForPdf()


        /************************************************************************************************/


        /*************************
         * Get Report Data Based On Job Request
         ********************************/
    public static function getReportData(?JobRequest $jobRequest = null)
    {
        if (!$jobRequest) {
            return response()->json([
                'client_name'     => '',
                'client_location' => '',
                'address'         => '',
                'lastcode'        => '001',
                'deploc'          => '',
                'clientDepartment'=> null,
                'purchase_order'  => '-',
            ]);
        }

        $last = InspectionReport::where('job_request_id', $jobRequest->id)->orderBy('code', 'DESC')->where('code', 'not LIKE', '%Duplicated')->first();
        if (!$last) {
            $last = '001';
        } else {
            if (str_contains($last->code, '/')) {
                $emad = substr($last->code, (strpos($last->code, '/') + 1));
                $new_id = $emad+1;
                $last = $new_id > 999 ? $new_id : str_pad($new_id, 3, '0', STR_PAD_LEFT);
            } else {
                $new_id = $last->code + 1;
                $last = $new_id > 999 ? $new_id : str_pad($new_id, 3, '0', STR_PAD_LEFT);
            }
        }

        return response()->json(
            [
                'client_name'     => $jobRequest->client ? $jobRequest->client->name : ($jobRequest->supplier ? $jobRequest->supplier->name : ''),
                'client_location' => $jobRequest->client ? $jobRequest->client->location : ($jobRequest->supplier ? $jobRequest->supplier->location : ''),
                'address'         => preg_replace('~[\\\\/:*?"<>[]|]~', '', $jobRequest->work_location ?? ''),
                'lastcode'        => $last,
                'deploc'          => $jobRequest->deploc ?? '',
                'clientDepartment'=> $jobRequest->clientDepartment ?? null,
                'purchase_order'  => $jobRequest->purchase_order ?? '-',
            ]
        );

    }//end getReportData()
    /************************************************************************************************/

    /***********************************
     * Duplicate Any Report
     **************************************/
    public function duplicate(InspectionReport $inspectionReport)
    {
            $second                 = [
                'crane',
                'overheadcrane',
                'forklift',
            ];
            $lifecycle = app(InspectionReportLifecycleService::class);
            $store_inspectionReport = $inspectionReport->reportable->replicate();
            $store_inspectionReport->code       = $inspectionReport->code.' - Duplicated';
            $store_inspectionReport->created_at = now();
            $store_inspectionReport->save();
            if ($store_inspectionReport) {
                $this->cloneDuplicateInspectionMedia($inspectionReport->reportable, $store_inspectionReport);
                $report_model = strtolower(str_replace('App\Models\Inspection\Lifting\\', '', $inspectionReport->reportable_type));
                if (in_array($report_model, $second)) {
                        $report_model_2 = $report_model.'2';
                        $model_id       = $report_model.'_id';
                    if ($model_id == 'overheadcrane_id') {
                        $model_id = 'overhead_crane_id';
                    }

                    if ($inspectionReport->reportable->$report_model_2) {
                            $store_second_inspectionReport            = $inspectionReport->reportable->$report_model_2->replicate();
                            $store_second_inspectionReport->$model_id = $store_inspectionReport->id;
                            $store_second_inspectionReport->save();
                    }
                }

                $lifecycle->createDuplicateRecord($inspectionReport, $store_inspectionReport, (int) Auth::id());
                return response()->json(
                    ['success' => 'This Model Cloned Successfully !']
                );
            }//end if

    }//end duplicate()

    private function cloneDuplicateInspectionMedia($sourceReportable, $duplicatedReportable): void
    {
        if (!$sourceReportable || !$duplicatedReportable) {
            return;
        }

        $config = $this->duplicateMediaConfigForReportable(get_class($sourceReportable));
        if (empty($config)) {
            return;
        }

        $dirty = false;
        foreach ($config as $item) {
            $attribute = (string) ($item['attribute'] ?? '');
            $type = (string) ($item['type'] ?? 'single');
            $directory = trim((string) ($item['directory'] ?? ''), '/');
            $jsonKeys = $item['json_keys'] ?? [];

            if ($attribute === '' || $directory === '' || !isset($duplicatedReportable->{$attribute})) {
                continue;
            }

            if ($type === 'single') {
                $currentValue = (string) ($sourceReportable->{$attribute} ?? '');
                $clonedValue = $this->cloneDuplicateMediaValue($currentValue, $directory);
                if ($clonedValue !== $currentValue) {
                    $duplicatedReportable->{$attribute} = $clonedValue;
                    $dirty = true;
                }
                continue;
            }

            if ($type === 'json') {
                $rawJson = $sourceReportable->{$attribute} ?? null;
                $clonedJson = $this->cloneDuplicateMediaJson($rawJson, (array) $jsonKeys, $directory);
                if ((string) $clonedJson !== (string) $rawJson) {
                    $duplicatedReportable->{$attribute} = $clonedJson;
                    $dirty = true;
                }
            }
        }

        if ($dirty) {
            $duplicatedReportable->save();
        }
    }

    private function duplicateMediaConfigForReportable(string $reportableClass): array
    {
        $map = [
            'App\\Models\\Inspection\\Ndt\\Mpipt' => [
                ['type' => 'json', 'attribute' => 'nmpr_29', 'json_keys' => ['nmpr_50'], 'directory' => 'camera/inspection/ndt/mpipts'],
            ],
            'App\\Models\\Inspection\\Ndt\\Visual' => [
                ['type' => 'json', 'attribute' => 'nvr_22', 'json_keys' => ['nvr_23'], 'directory' => 'camera/inspection/ndt/visual'],
            ],
            'App\\Models\\Inspection\\Ndt\\Attached' => [
                ['type' => 'json', 'attribute' => 'nar_6', 'json_keys' => ['nar_11'], 'directory' => 'camera/inspection/ndt/attached'],
            ],
            'App\\Models\\Inspection\\Ndt\\Ultrasonic' => [
                ['type' => 'single', 'attribute' => 'nur_24', 'directory' => 'camera/inspection/ndt/ultrasonic'],
            ],
            'App\\Models\\Inspection\\Ndt\\WitnessHydro' => [
                ['type' => 'single', 'attribute' => 'nwhr_36', 'directory' => 'camera/inspection/ndt/witnesshydro'],
            ],
            'App\\Models\\Inspection\\Ndt\\High2Pressure' => [
                ['type' => 'single', 'attribute' => 'nh2pr_31', 'directory' => 'camera/inspection/ndt/high2Pressure'],
            ],
            'App\\Models\\Inspection\\Ndt\\TreatingIron' => [
                ['type' => 'single', 'attribute' => 'ntir_30', 'directory' => 'camera/inspection/ndt/treatingiron'],
                ['type' => 'single', 'attribute' => 'ntir_44', 'directory' => 'camera/inspection/ndt/treatingiron'],
            ],
            'App\\Models\\Inspection\\Ndt\\DrawingInspection' => [
                ['type' => 'single', 'attribute' => 'report_image', 'directory' => 'camera/inspection/ndt/drawinginspection'],
            ],
        ];

        return $map[$reportableClass] ?? [];
    }

    private function cloneDuplicateMediaJson($rawJson, array $keys, string $directory): string
    {
        $decoded = json_decode((string) $rawJson, true);
        if (!is_array($decoded)) {
            return (string) $rawJson;
        }

        $changed = false;
        $this->cloneMediaJsonNode($decoded, $keys, $directory, $changed);

        if (!$changed) {
            return (string) $rawJson;
        }

        return json_encode($decoded);
    }

    private function cloneMediaJsonNode(&$node, array $keys, string $directory, bool &$changed): void
    {
        if (!is_array($node)) {
            return;
        }

        foreach ($node as $key => &$value) {
            if (is_array($value)) {
                $this->cloneMediaJsonNode($value, $keys, $directory, $changed);
                continue;
            }

            if (in_array((string) $key, $keys, true) && is_string($value)) {
                $clonedValue = $this->cloneDuplicateMediaValue($value, $directory);
                if ($clonedValue !== $value) {
                    $value = $clonedValue;
                    $changed = true;
                }
            }
        }
    }

    private function cloneDuplicateMediaValue(?string $storedValue, string $directory): string
    {
        $value = trim((string) $storedValue);
        if ($value === '') {
            return '';
        }

        $sourceRelativePath = $this->resolveDuplicateMediaRelativePath($value, $directory);
        if ($sourceRelativePath === null) {
            return $value;
        }

        $sourceBase = pathinfo($sourceRelativePath, PATHINFO_FILENAME);
        $sourceExt = pathinfo($sourceRelativePath, PATHINFO_EXTENSION);
        $safeBase = preg_replace('/[^A-Za-z0-9._-]/', '_', (string) $sourceBase) ?: 'attachment';
        $newFilename = $safeBase.'_dup_'.Str::lower(Str::random(10)).($sourceExt !== '' ? '.'.$sourceExt : '');
        $targetRelativePath = trim(dirname($sourceRelativePath), '/').'/'.$newFilename;

        if (!$this->copyDuplicateMediaFile($sourceRelativePath, $targetRelativePath)) {
            return $value;
        }

        $this->mirrorPublicStorageFile($targetRelativePath);

        return basename($targetRelativePath);
    }

    private function resolveDuplicateMediaRelativePath(string $storedValue, string $directory): ?string
    {
        $normalized = str_replace('\\', '/', trim($storedValue));
        $normalized = ltrim($normalized, '/');

        if (str_starts_with($normalized, 'storage/')) {
            $normalized = ltrim(substr($normalized, strlen('storage/')), '/');
        }

        $directory = trim($directory, '/');
        $candidates = [];

        if (str_starts_with($normalized, 'camera/')) {
            $candidates[] = $normalized;
        }

        if (str_contains($normalized, '/')) {
            $candidates[] = $normalized;
            $candidates[] = $directory.'/'.$normalized;
            $candidates[] = $directory.'/'.basename($normalized);
        } else {
            $candidates[] = $directory.'/'.$normalized;
        }

        $candidates = array_values(array_unique(array_filter($candidates)));

        foreach ($candidates as $candidate) {
            $candidate = ltrim($candidate, '/');
            if (Storage::disk('public')->exists($candidate)) {
                return $candidate;
            }

            if (is_file(public_path('storage/'.$candidate)) || is_file(storage_path('app/public/'.$candidate))) {
                return $candidate;
            }
        }

        return null;
    }

    private function copyDuplicateMediaFile(string $sourceRelativePath, string $targetRelativePath): bool
    {
        $sourceRelativePath = ltrim($sourceRelativePath, '/');
        $targetRelativePath = ltrim($targetRelativePath, '/');

        if (Storage::disk('public')->exists($sourceRelativePath)) {
            Storage::disk('public')->copy($sourceRelativePath, $targetRelativePath);
            return true;
        }

        $sourcePublicPath = public_path('storage/'.$sourceRelativePath);
        if (!is_file($sourcePublicPath)) {
            return false;
        }

        Storage::disk('public')->put($targetRelativePath, file_get_contents($sourcePublicPath));
        return true;
    }

    private function mirrorPublicStorageFile(string $relativePath): void
    {
        $relativePath = ltrim($relativePath, '/');
        $source = storage_path('app/public/'.$relativePath);
        $target = public_path('storage/'.$relativePath);

        if (!is_file($source)) {
            return;
        }

        $targetDirectory = dirname($target);
        if (!is_dir($targetDirectory)) {
            @mkdir($targetDirectory, 0775, true);
        }

        @copy($source, $target);
    }


        /************************************************************************************************/


        /**************************************
         * Connect To API
         *****************************************/
    public function connect_server(Request $request)
    {
        if ($request->session()->get('local_headers') !== null) {
              return redirect(route('system.sync_view'));
        }

        return view('layouts.sync.connectserver', ['page_name' => 'Connect Server To Sync']);

    }//end connect_server()


        /************************************************************************************************/


        /**********************************
         * Get Server Access Token
         ************************************/
    public function get_api_access_token(Request $request)
    {
        $response = Http::connectTimeout(300)->asForm()->post(
            'https://socket.rigsolutionz.com/oauth/token',
            [
                'grant_type'    => 'password',
                'client_id'     => '980880b6-062a-4f01-bbc7-356f81a65d03',
                'client_secret' => 'GymQV8Ltk44hmW6BsCtBv6zPDB0bpEK5FQENN9Rp',
                'username'      => Auth::user()->employee_id,
                'password'      => $request->password,
                'scope'         => '*',
            ]
        );

        $request->session()->put(
            'local_headers',
            [
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer '.$response->json()['access_token'],
            ]
        );

        return response()->json(
            ['success' => 'The server has been successfully connected.']
        );

    }//end get_api_access_token()


        /************************************************************************************************/


        /***********************************
         * Sync Progress Screen
         **************************************/
    public function sync_view(Request $request)
    {
        if ($request->session()->get('local_headers') == null) {
              return redirect(route('system.connect_server'));
        }

            return view('layouts.sync.sync', ['page_name' => 'Synchronization Data']);

    }//end sync_view()


        /************************************************************************************************/


        /**********************
         * Get Tables And Convert To Model And Send Data To Api
         *******************/
    protected function tables_array_looping($local_headers, $batch, $tables, $type)
    {
        $route_api_link = '';
        foreach ($tables as $key12212 => $value) {
            foreach ($value as $key1 => $table) {
                $model            = rtrim(str_replace(' ', '', ucwords(str_replace('_', ' ', $table))), 's');
                $table_without    = rtrim($table, 's');
                $collection       = 'App\Http\Resources\\'.$model.'Collection';
                $model_full       = 'App\Models\\'.$key12212.'\\'.$model;
                $model_not_synced = $collection::collection($model_full::where('sync', 0)->get())->chunk(25);
                if (count($model_not_synced) > 0) {
                    foreach ($model_not_synced as $key => $model_not_synced_after_chunk) {
                        switch ($type) {
                            case 'w':
                                          $route_api_link = route(strtolower($model).'api.store');
                            break;

                            case 'i':
                                            $route_api_link = route('inspectionapireport.store', [$model_full, $table_without]);
                            break;

                            default:
                                  // code...
                            break;
                        }

                        $batch->add(new SyncJob($table, $local_headers, str_replace(env('APP_URL'), env('SECOND_APP_URL'), $route_api_link), $model_not_synced_after_chunk));
                        $percentage = ((($key + 1) / count($model_not_synced)) * 100);
                        switch ($percentage) {
                            case 100:
                                          $message = 'Done !';
                            break;

                            default:
                                            $message = 'Sync '.$model.' From Local To Server!';
                            break;
                        }

                        // Auth::user()->notify(new SyncNotification(strtolower($model) ,$percentage ,$message));
                    }//end foreach
                } else {
                                              $message    = 'No '.$model.' New Data !';
                                              $percentage = 1000;
                                              // Auth::user()->notify(new SyncNotification(strtolower($model) ,$percentage ,$message));
                }//end if
            }//end foreach
        }//end foreach

    }//end tables_array_looping()


        /************************************************************************************************/


        /**************************
         * Run Sync Job From Local To Server
         *********************************/
    public function sync(Request $request)
    {
        $local_headers = Session::get('local_headers');
        $message       = '';
        $type          = '';
        $batch         = Bus::batch([])->dispatch();
            // $tables = ['WorkFlow' => ['job_requests']];
            $tables = [
                'WorkFlow' => [
                    'job_requests',
                    'qutations',
                    'packing_slips',
                    'service_tickets',
                    'invoices',
                ],
            ];
            $this->tables_array_looping($local_headers, $batch, $tables, 'w');
            $reports_lifting = [
                'Inspection\Lifting' => [
                    'cranes',
                    'overhead_cranes',
                    'forklifts',
                    'through_examinations',
                    'defects',
                    'lregisters',
                ],
                'Inspection\Ndt'     => [
                    'mpipts',
                    'visuals',
                    'ultrasonics',
                ],
            ];
            $this->tables_array_looping($local_headers, $batch, $reports_lifting, 'i');
            return response()->json(
                ['success' => 'The Server Updated Successfully !']
            );
        // $reports_ndt = ['mpipts', 'visuals', 'ultrasonics'];
        // $this->tables_array_looping($local_headers, $batch, $reports_lifting, 'i');
        // $this->tables_array_looping($local_headers, $batch, $reports_ndt, 'i');

    }//end sync()


        /************************************************************************************************/


        /*********************************
         * Sync From Server To Local
         ***********************************/
    public static function sync_from_server_to_local()
    {
            $array = [
                'GeneralInfo' => [
                    'specification',
                    'tool',
                ],
                'Persons' => [
                	'client',
                	'supplier'
                ],
            ];

            foreach ($array as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $prepend = new Sync($key, $value1);
                    $prepend->upsertData();
                    $prepend->deleteIncrements();
                }
            }

            // $r = new Sync('GeneralInfo', 'specification');
            // $r->upsertData();
            // $r->deleteIncrements();
            // $rr = new Sync('GeneralInfo', 'tool');
            // $rr->upsertData();
            // $local_headers = \Session::get('local_headers');
            // $arr = [];
            // $table_columns = app(\App\Models\GeneralInfo\Specification::class)->getFillable();
            // $responses = Http::connectTimeout(3000)->withHeaders($local_headers)->get(str_replace(env('APP_URL'),env('SECOND_APP_URL'),route('specificationapi.index')));
            // foreach (json_decode($responses) as $key => $item)
            // {
            // foreach ($item as $key1 => $value) {
            // $arr[$key1] = $value->id;
            // $updateOrCreate = \App\Models\GeneralInfo\Specification::upsert((array)$value, $table_columns);
            // }
            // }
            // \App\Models\GeneralInfo\Specification::whereNotIn('id' ,$arr)->delete();
            // return $arr;
            // $table_names = ['roles', 'employees', 'departments', 'department_employee', 'users'];
            // $table_names = ['tools', 'specifications'];
            // $table_names = ['clients', 'suppliers', 'contact_people'];
            // $table_names = [
            // 'roles', 'employees',
            // 'departments', 'department_employee',
            // 'users', 'tools',
            // 'specifications',
            // 'clients', 'suppliers',
            // 'contact_people', 'job_requests', 'invoices', 'qutations',
            // 'service_tickets', 'packing_slips',
            // 'department_job_request', 'employee_job_request', 'jcf_statuses',
            // 'cranes', 'crane2s', 'defects', 'forklifts', 'forklift2s', 'mpipts',
            // 'high_pressures', 'high2_pressures', 'summaries', 'through_examinations',
            // 'treating_irons', 'ultrasonics', 'visuals', 'witness_hydros',
            // 'inspection_reports'
            // ];
            // $responses = Http::pool(function (Pool $pool) use (&$table_names, &$local_headers){
            // foreach ($table_names as $key => $table_name) {
            // $pool->as($table_name)->timeout(3000)->withHeaders($local_headers)->get(str_replace(env('APP_URL'),env('SECOND_APP_URL'),route('table.prepend_data_to_local', [$table_name])));
            // }
            // });
            //
            // foreach ($table_names as $table_name)
            // {
            // $table_columns = \Schema::getColumnListing($table_name);
            // $arr = [];
            // foreach (json_decode($responses[$table_name]) as $key => $item)
            // {
            // $store = \DB::table($table_name)->upsert((array)$item, $table_columns);
            // $arr[$key] = $item->id;
            // }
            // DB::table($table_name)->whereNotIn('id', $arr)->delete();
            // }
            // return response()->json([
            // 'success' => 'The Local Machine Updated Successfully !'
            // ]);

    }//end sync_from_server_to_local()


        /************************************************************************************************/
        /***********************************
         * Prepend Data To Local
         ************************************/
        // public static function prepend_data_to_local($table_name)
        // {
        // return DB::table($table_name)->get();
        // }
        /************************************************************************************************/


        /***********************************
         * Get Classes To Role Page
         *********************************/
    public static function get_classes($key, $key1, $roles, $type)
    {
        $req = '';
        $modules = self::resolveRoleModules($key, $key1);
        foreach ($modules as $after) {
            if ($after == 'crane') {
                $req = 'required';
            } else {
                $req = '';
            }

            if ($after != 'jcfstatus' && $after != 'item' && $after != 'contactperson') {
                echo '
										<tr class="top">
												<td>
														<div class="pb-1">
																<input type="checkbox" class="switchery" name="basic_switchery" id="'.$after.'"';
                if ($roles != '') {
                    foreach (json_decode($roles) as $role) {
                        if (strtolower($after) === $role->modules) {
                            echo 'checked';
                        }
                    }

                    if ($type != '') {
                        echo ' disabled';
                    }
                }

                echo '>
																<span class="ml-1"><label for="'.$after.'">'.ucwords($after).'</label></span>
														</div>
												</td>';
                if (
                    $key === 'Inspection' ||
                    $after == 'qutation' ||
                    $after == 'mailcenter' ||
                    in_array($after, ['financial', 'payment', 'inventory', 'accountant', 'bank', 'expense'], true)
                ) {
                    echo '<td>
																<div class="skin skin-square"><input type="checkbox" id="'.$after.'-approve" '.$req.' name="all" class="emad custom-control-input"';
                    if ($roles != '') {
                        foreach (json_decode($roles) as $role_bottom) {
                            if (strtolower($after) === $role_bottom->modules) {
                                foreach ($role_bottom->roles as $module) {
                                    if ($module === 'approve') {
                                        echo 'checked';
                                    }
                                }
                            }
                        }

                        if ($type != '') {
                            echo ' disabled';
                        }
                    }

                    echo '>
																		<label class="custom-control-label" for="'.$after.'-approve"></label>
																</div>
														</td>';
                } else {
                    echo '<td></td>';
                }//end if

                echo '<td>
														<div class="skin skin-square"><input type="checkbox" id="'.$after.'-all" '.$req.' name="all" class="emad custom-control-input"';
                if ($roles != '') {
                    foreach (json_decode($roles) as $role_bottom) {
                        if (strtolower($after) === $role_bottom->modules) {
                            foreach ($role_bottom->roles as $module) {
                                if ($module === 'all') {
                                    echo 'checked';
                                }
                            }
                        }

                        if ($type != '') {
                            echo ' disabled';
                        }
                    }
                }

                echo '>
														<label class="custom-control-label" for="'.$after.'-all"></label>
												</div>
										</td>
										<td>
												<div class="skin skin-square"><input type="checkbox" id="'.$after.'-show" name="all" class="emad custom-control-input"';
                if ($roles != '') {
                    foreach (json_decode($roles) as $role_bottom) {
                        if (strtolower($after) === $role_bottom->modules) {
                            foreach ($role_bottom->roles as $module) {
                                if ($module === 'show') {
                                    echo 'checked';
                                }
                            }
                        }
                    }

                    if ($type != '') {
                        echo ' disabled';
                    }
                }

                echo '>
														<label class="custom-control-label" for="'.$after.'-show"></label>
												</div>
										</td>
										<td>
												<div class="skin skin-square"><input type="checkbox" id="'.$after.'-create" name="all" class="emad custom-control-input"';
                if ($roles != '') {
                    foreach (json_decode($roles) as $role_bottom) {
                        if (strtolower($after) === $role_bottom->modules) {
                            foreach ($role_bottom->roles as $module) {
                                if ($module === 'create') {
                                    echo 'checked';
                                }
                            }
                        }
                    }

                    if ($type != '') {
                        echo ' disabled';
                    }
                }

                echo '>
														<label class="custom-control-label" for="'.$after.'-create"></label>
												</div>
										</td>
										<td>
												<div class="skin skin-square"><input type="checkbox" id="'.$after.'-edit" name="all" class="emad custom-control-input" ';
                if ($roles != '') {
                    foreach (json_decode($roles) as $role_bottom) {
                        if (strtolower($after) === $role_bottom->modules) {
                            foreach ($role_bottom->roles as $module) {
                                if ($module === 'edit') {
                                    echo 'checked';
                                }
                            }
                        }
                    }

                    if ($type != '') {
                        echo ' disabled';
                    }
                }

                echo '>
														<label class="custom-control-label" for="'.$after.'-edit"></label>
												</div>
										</td>
										<td>
												<div class="skin skin-square"><input type="checkbox" id="'.$after.'-delete" name="all" class="custom-control-input" ';
                if ($roles != '') {
                    foreach (json_decode($roles) as $role_bottom) {
                        if (strtolower($after) === $role_bottom->modules) {
                            foreach ($role_bottom->roles as $module) {
                                if ($module === 'delete') {
                                    echo 'checked';
                                }
                            }
                        }
                    }

                    if ($type != '') {
                        echo ' disabled';
                    }
                }

                echo '>
														<label class="custom-control-label" for="'.$after.'-delete"></label>
												</div>
										</td>';
                if ($key1 === 'WorkFlow') {
                    if ($after === 'filemanager') {
                        echo '<td>
												<div class="skin skin-square"><input type="checkbox" id="'.$after.'-physical-delete" name="all" class="custom-control-input" ';
                        if ($roles != '') {
                            foreach (json_decode($roles) as $role_bottom) {
                                if (strtolower($after) === $role_bottom->modules) {
                                    foreach ($role_bottom->roles as $module) {
                                        if ($module === 'physical-delete') {
                                            echo 'checked';
                                        }
                                    }
                                }
                            }

                            if ($type != '') {
                                echo ' disabled';
                            }
                        }

                        echo '>
														<label class="custom-control-label" for="'.$after.'-physical-delete"></label>
												</div>
										</td>';
                    } else {
                        echo '<td></td>';
                    }

                    if ($after === 'mailcenter') {
                        foreach (['send', 'manage-templates', 'manage-settings', 'view-logs'] as $customRole) {
                            echo '<td>
												<div class="skin skin-square"><input type="checkbox" id="'.$after.'-'.$customRole.'" name="all" class="custom-control-input" ';
                            if ($roles != '') {
                                foreach (json_decode($roles) as $role_bottom) {
                                    if (strtolower($after) === $role_bottom->modules) {
                                        foreach ($role_bottom->roles as $module) {
                                            if ($module === $customRole) {
                                                echo 'checked';
                                            }
                                        }
                                    }
                                }

                                if ($type != '') {
                                    echo ' disabled';
                                }
                            }

                            echo '>
														<label class="custom-control-label" for="'.$after.'-'.$customRole.'"></label>
												</div>
										</td>';
                        }
                    } else {
                        echo '<td></td><td></td><td></td><td></td>';
                    }
                }
                echo '
								</tr>
								';
            }//end if
        }//end foreach

    }//end get_classes()

    private static function resolveRoleModules($key, $key1): array
    {
        $relativePath = $key1;
        if ($key === 'Inspection') {
            $relativePath = 'Inspection/'.$key1;
        }

        $directory = app_path('Http/Controllers/Dashboard/'.$relativePath);
        if (!File::isDirectory($directory)) {
            return [];
        }

        $modules = [];
        foreach (File::files($directory) as $controllerFile) {
            $filename = $controllerFile->getFilename();
            if (!str_ends_with($filename, 'Controller.php')) {
                continue;
            }

            $module = strtolower(str_replace('Controller.php', '', $filename));
            if ($module === '' || str_ends_with($module, '2')) {
                continue;
            }

            $modules[] = $module;
        }

        // Financial is a grouped navigation tab (not a CRUD controller),
        // so expose it explicitly in role settings under WorkFlow modules.
        if (strcasecmp($key, 'App-flow') === 0 && strcasecmp($key1, 'WorkFlow') === 0) {
            $modules[] = 'financial';
        }

        $modules = array_values(array_unique($modules));
        sort($modules, SORT_STRING);

        return $modules;
    }


        /************************************************************************************************/


    public static function navbar(Array $routes)
    {
        echo '<div class="no-print main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
      <div class="main-menu-content">
      <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">';
        foreach ($routes as $key => $route) {
            echo '<li class=" nav-item '.((Request::segment(2) === $route['model']) ? 'active' : '').'"><a href="'.(($route['sub'] === '') ? $route['model'] : '').'"><i class="'.$route['icon'].'"></i><span class="menu-title" data-i18n="'.$route['model'].'">'.$route['slug'].'</span></a>';
            if ($route['sub'] !== '') {
                echo '<ul class="menu-content">';
                foreach ($route['sub'] as $value) {
                    if ($value['model'] != '') {
                        echo '<li><a class="menu-item" href="#"><i class="'.$value['icon'].'"></i><span data-i18n="'.$value['model'].'">'.$value['slug'].'</span></a><ul class="menu-content">';
                        echo '<li><a class="menu-item" href="'.route(''.$value['model'].'.index').'"><i></i><span data-i18n="all-'.$value['model'].'">All '.$value['slug'].'</span></a></li>';
                        echo '<li><a class="menu-item" href="'.route(''.$value['model'].'.create').'"><i></i><span data-i18n="create-'.$value['model'].'">Create '.$value['slug'].'</span></a></li>';
                        echo '</ul></li>';
                    } else {
                        echo '<li><a class="menu-item" href="#"><i class="'.$value['icon'].'"></i><span data-i18n="'.$value['model'].'">'.$value['slug'].'</span></a><ul class="menu-content">';
                        echo '</ul></li>';
                    }
                }

                echo '</ul>';
            }

            echo '</li>';
        }//end foreach

        echo '</ul>
      </div>
      </div>';

    }//end navbar()


        /**/
        // $online = Http::connectTimeout(300)->withHeaders(Session::get('local_headers'))->get('https://socket.rigsolutionz.com/api/v1/dashboard/work-flow/packingslipapi');
        // $offline = PackingSlipCollection::collection(PackingSlip::orderBy('id')->get());
        // $new = (array)json_decode(json_encode($offline));
        // return $new[0]['jcf_ref'];
        // // return $keys = $offline[0]['packing_slip'];
        // return $keys->all();
        // foreach ($offline as $key => $value) {
        // echo $key;
        // if (json_decode(collect($value))->jcf_ref == json_decode(collect($offline[$key]))->jcf_ref) {
        //
        // echo $key."yes <br />";
        //
        // } else {
        //
        // echo "noe";
        //
        // }
        //
        // }
        // $t = [];
        //
        // $s = [];
        // foreach ($offline as $key => $value) {
        // $s[$key]['a'] = json_decode(collect($value))->jcf_ref;
        // $s[$key]['b'] = $key + 20;
        // }
        // print_r($t);
        // $t = collect($t);
        //
        // $s = collect($s);
        // $diff = $t->diffAssoc($s);
        // $diff = array_diff($t, $s);
        // echo $key."<br />";
        // echo json_decode(collect($value))->jcf_ref;
        // foreach ($offline as $key1 => $value1) {
        // if (json_decode(collect($value))->jcf_ref == json_decode(collect($value1))->jcf_ref)
        // {
        // echo json_decode(collect($value))->jcf_ref;
        // }
        // else
        // {
        // echo "No";
        // }
        // echo "<br />";
        // }
        // $value = collect($value);
        // $value->forget(['id', 'code', 'job_request_id', 'updated_at', 'created_at']);
        // $t[$key] = [$value];
        // $online = array(json_decode(json_encode($online)));
        //
        //
        // $collection = collect($online);
        //
        // $collection1 = collect($offline);
        //
        //
        //
        // foreach ($collection1 as $key1 => $value1) {
        // $value1 = collect($value1);
        // $value1->forget(['id', 'code', 'job_request_id', 'updated_at', 'created_at']);
        // $s[$key1] = [$value1];
        // }
        //
        // $t = collect($t);
        // $s = collect($s);
        //
        // $diff = $t->diffAssoc($s);
        // echo json_encode($diff->all());
        // var_dump($collection->id);
        // $diff = $collection->diffAssoc($collection1);
        // $offline = array(json_decode(json_encode($offline)));
        //
        // $diff = $online->diffAssoc($offline);
        // var_dump($diff);
        //
        // return $diff->all();
        // $collection = collect([
        // 'color' => 'orange',
        // 'type' => 'fruit',
        // 'remain' => 6,
        // ]);
        // var_dump($online);
        //
        // $collection1 = collect([
        // 'color' => 'yellow',
        // 'type' => 'fruit',
        // 'remain' => 3,
        // 'used' => 6,
        // ]);
        //
        //
        //


        /**/
















        // foreach (json_decode($response, true) as $key => $value) {
        // foreach ($value as $key1 => $value1) {
        // echo $value1['jcf_ref'];
        // }
        // }
        // $response = json_decode($response, true);
        //
        //
        // $response1 = Http::connectTimeout(300)->withHeaders(Session::get('local_headers'))->get('https://socket.rigsolutionz.com/api/v1/dashboard/work-flow/packingslipapi');
        // $response1 = json_decode($response1, true);
        // $on_jcf = $response->object();
        // $on_jcf = unset($on_jcf['packing_slip']['id']);
        // return $collection = collect([1, 2, 3]);
        // $response = Http::connectTimeout(300)->withHeaders(Session::get('local_headers'))->get('https://socket.rigsolutionz.com/api/v1/dashboard/work-flow/packingslipapi');
        // $online = PackingSlipCollection::collection(PackingSlip::all());
        // $online = json_encode($online);
        //
        // $offline = PackingSlipCollection::collection(PackingSlip::all());
        // $offline = json_encode($offline);
        //
        // return $online->diffAssoc($offline)->all();
        // foreach ($collection as $key => $value) {
        // echo $key;
        // foreach ($value as $key1 => $value1) {
        // }
        // }
        // return array_diff($response, $response1);
        // $keys = $repo[0]->keys();
        // $repo = unset($repo->jcf_ref);
        // $sorted = $repo->sortBy(function ($product, $key) {
        // return $product['packing_slip'];
        // });
        // return $sorted->values()->all();
        // $flattened = $collection->flatMap(function ($values) {
        // return array_map('strtoupper', $values);
        // });
        //
        // $flattened->all();
        // $repo = unset($repo['packing_slip']['id']);
        // return $repo->diffAssoc($on_jcf)->all();
                // $batch->add(new SyncJob($table, $local_headers, str_replace(env('APP_URL'),env('SECOND_APP_URL'),route(strtolower($model).'api.store')), $model_not_synced_after_chunk));
}//end class

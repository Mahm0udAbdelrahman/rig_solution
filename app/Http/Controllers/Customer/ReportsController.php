<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DrillPipes;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {        
        return view('layouts.customer.reports.reports1', [
            'page_name' => 'All Reports'
        ]);
    }

    protected function getDataTableData(Request $request)
    // public function index()
    {
        $searchValue = $request->input('search.value');
        
        // Get column-specific search values
        $columnSearch = $request->input('columnSearch', []);
        
        $query = InspectionReport::query()
        ->whereHas('reportable', function($query) use ($searchValue) {
            // Get the model class
            $model = $query->getModel();
            $class = get_class($model);
            $table = $model->getTable();
            
            // List of allowed model classes to search in
            $allowedModels = [
                'App\Models\Inspection\Ndt\High2Pressure',
                'App\Models\Inspection\Tubular\DrillPipe',
                'App\Models\Inspection\Tubular\HeavyWeightPipe',
                'App\Models\Inspection\Tubular\DrillCollar',
                'App\Models\Inspection\Tubular\SubsDimensional',
                'App\Models\Inspection\Tubular\TubingString',
                'App\Models\Inspection\Tubular\Pbl',
            ];
            
            // Only apply model filtering when there's a search value
            if ($searchValue) {
                // Check if current model is in the allowed list
                if (!in_array($class, $allowedModels)) {
                    // If not in allowed list, add a condition that will return no results
                    $query->whereRaw('1 = 0');
                    return;
                }
                
                // Get searchable columns for this model
                $columns = Schema::getColumnListing($table);
                
                // Create a query that searches across all text/string columns
                $query->where(function($subQuery) use ($columns, $table, $searchValue) {
                    foreach ($columns as $column) {
                        // Skip non-searchable columns like timestamps and binary data
                        if (!in_array($column, ['created_at', 'updated_at', 'deleted_at']) && 
                            Schema::getColumnType($table, $column) != 'binary') {
                            $subQuery->orWhere($column, 'like', '%' . $searchValue . '%');
                        }
                    }
                });
            }
        })
        ->whereHas('job_request', function($query) {
            if(Auth::getDefaultDriver() == 'customer') {
                $query->where('client_id', Auth::id());
            } else if (Auth::getDefaultDriver() == 'clientDepartments') {
                $query->where('client_department_id', Auth::id());
            }
        })
        ->where('publish', '!=', NULL);
        
        // Apply column-specific filters
        if (!empty($columnSearch)) {
            // Debug the entire columnSearch array to see what's actually being received
            // Log::info('Column search data:', $columnSearch);
            
            // Map the frontend input fields to the correct backend columns
            $columnMappings = [
                'type' => 'report_no',
                'report_no' => 'id',
                'id' => 'equipment',
                'equipment' => 'exam_date',
                'exam_date' => 'po',
                'po' => 'internal_so',
                'internal_so' => 'work_location',
            ];
            
            // Create a new array with the correct mappings
            $mappedColumnSearch = [];
            foreach ($columnSearch as $key => $value) {
                if (isset($columnMappings[$key])) {
                    $mappedColumnSearch[$columnMappings[$key]] = $value;
                } else {
                    $mappedColumnSrearch[$key] = $value;
                }
            }

            if (isset($mappedColumnSearch['report_no']) && !empty($mappedColumnSearch['report_no'])) {
                $searchTerm = $mappedColumnSearch['report_no'];
                
                // Check if the search term contains a slash (/) which indicates a combined format
                if (strpos($searchTerm, '/') !== false) {
                    // Split the combined format into job code and report code
                    list($jobCode, $reportCode) = explode('/', $searchTerm, 2);
                    
                    $query->whereHas('job_request', function($q) use ($jobCode) {
                        $q->where('code', 'like', '%' . $jobCode . '%');
                    })->whereHas('reportable', function($q) use ($reportCode) {
                        $q->where('code', 'like', '%' . $reportCode . '%');
                    });
                } else {
                    // If no slash, search in both fields independently
                    $query->where(function($q) use ($searchTerm) {
                        $q->whereHas('job_request', function($subQ) use ($searchTerm) {
                            $subQ->where('code', 'like', '%' . $searchTerm . '%');
                        })->orWhereHas('reportable', function($subQ) use ($searchTerm) {
                            $subQ->where('code', 'like', '%' . $searchTerm . '%');
                        });
                    });
                }
            }

            if (isset($mappedColumnSearch['id']) && !empty($mappedColumnSearch['id'])) {
                $query->whereHas('reportable', function($q) use ($mappedColumnSearch) {
                    // Get the model and table
                    $model = $q->getModel();
                    $table = $model->getTable();
                    $class= get_class($model);
                    $columns = Schema::getColumnListing($table);

                    $allowedModels = [
                        'App\Models\Inspection\Ndt\Mpipt',
                        'App\Models\Inspection\Lifting\Crane',
                        'App\Models\Inspection\Lifting\OverheadCrane',
                        'App\Models\Inspection\Lifting\Forklift',
                        'App\Models\Inspection\Ndt\TreatingIron',
                        'App\Models\Inspection\Ndt\Visual',
                        'App\Models\Inspection\Ndt\Ultrasonic',
                        'App\Models\Inspection\Ndt\WitnessHydro',
                        'App\Models\Inspection\Ndt\HighPressure',
                        'App\Models\Inspection\Ndt\High2Pressure',
                        'App\Models\Inspection\Lifting\Defect',
                        'App\Models\Inspection\Tubular\LinkInspection',
                        'App\Models\Inspection\Lifting\ThroughExamination',
                        'App\Models\Inspection\Lifting\Defect'
                    ];

                    if (!in_array($class, $allowedModels)) {
                        $q->whereRaw('1 = 0');
                        return;
                    }
                    
                    // Only search in columns that actually exist in this table
                    $q->where(function($subQuery) use ($mappedColumnSearch, $columns, $table) {
                        $searchValue = '%' . $mappedColumnSearch['id'] . '%';
                        $first = true;
                        
                        $columnsToCheck = [
                            'lcr_12', 'locr_12', 'lfr_12', 'nmpr_28', 'ntir_13', 
                            'nvr_6', 'nur_26', 'nwhr_15', 'nhpr_10', 'nh2pr_10',
                            'material_no', 'lter_10', 'ldr_8'
                        ];
                        
                        foreach ($columnsToCheck as $column) {
                            
                            if (in_array($column, $columns)) {
                                if ($first) {
                                    $subQuery->where(function($q) use ($column, $searchValue) {
                                        $q->whereNotNull($column)
                                          ->where($column, '!=', '')
                                          ->where($column, 'like', $searchValue);
                                    });
                                    $first = false;
                                } else {
                                    $subQuery->orWhere(function($q) use ($column, $searchValue) {
                                        $q->whereNotNull($column)
                                          ->where($column, '!=', '')
                                          ->where($column, 'like', $searchValue);
                                    });
                                }
                                Log::info('Column does not exist in table:', ['column' => $column, 'table' => $table]);
                            }
                        }
                    });
                });
            }

            if (isset($mappedColumnSearch['equipment']) && !empty($mappedColumnSearch['equipment'])) {
                $query->whereHas('reportable', function($q) use ($mappedColumnSearch) {
                    // Get the model and table
                    $model = $q->getModel();
                    $table = $model->getTable();
                    $class= get_class($model);
                    $columns = Schema::getColumnListing($table);

                    // Log::info($model->getTable());

                    $allowedModels = [
                        // 'App\Models\Inspection\Ndt\Mpipt',
                        'App\Models\Inspection\Lifting\Crane',
                        'App\Models\Inspection\Lifting\OverheadCrane',
                        'App\Models\Inspection\Lifting\Forklift',
                        // 'App\Models\Inspection\Ndt\TreatingIron',
                        // 'App\Models\Inspection\Ndt\Visual',
                        'App\Models\Inspection\Ndt\Ultrasonic',
                        // 'App\Models\Inspection\Ndt\WitnessHydro',
                        'App\Models\Inspection\Ndt\HighPressure',
                        'App\Models\Inspection\Ndt\High2Pressure',
                        // 'App\Models\Inspection\Lifting\Defect',
                        'App\Models\Inspection\Tubular\LinkInspection',
                        'App\Models\Inspection\Lifting\ThroughExamination',
                        // 'App\Models\Inspection\Lifting\Defect', 
                        'App\Models\Inspection\Tubular\SubsDimensional',
                        // 'App\Models\Inspection\Tubular\TubingString',
                        'App\Models\Inspection\Tubular\Pbl',
                        'App\Models\Inspection\Tubular\DrillPipe',
                        'App\Models\Inspection\Tubular\HeavyWeightPipe',
                        'App\Models\Inspection\Tubular\DrillCollar',
                        'App\Models\Inspection\Tubular\StabilizerInspection',
                        'App\Models\Inspection\Calibration\CalibrationPressureTest'
                    ];

                    if (!in_array($class, $allowedModels)) {
                        $q->whereRaw('1 = 0');
                        return;
                    }
                    
                    // Only search in columns that actually exist in this table
                    $q->where(function($subQuery) use ($mappedColumnSearch, $columns) {
                        $searchValue = '%' . $mappedColumnSearch['equipment'] . '%';
                        $first = true;
                        
                        $columnsToCheck = [
                            'lcr_10', 'locr_10', 'lfr_10', 'nur_12', 
                            'desc', 'material_description', 'joint_description', 
                            'description', 'dc_description', 'lter_10'
                        ];
                        
                        foreach ($columnsToCheck as $column) {
                            if (in_array($column, $columns)) {
                                if ($first) {
                                    $subQuery->where(function($q) use ($column, $searchValue) {
                                        $q->whereNotNull($column)
                                          ->where($column, '!=', '')
                                          ->where($column, 'like', $searchValue);
                                    });
                                    $first = false;
                                } else {
                                    $subQuery->orWhere(function($q) use ($column, $searchValue) {
                                        $q->whereNotNull($column)
                                          ->where($column, '!=', '')
                                          ->where($column, 'like', $searchValue);
                                    });
                                }
                            }
                        }
                    });
                });
            }

            if (isset($mappedColumnSearch['exam_date']) && !empty($mappedColumnSearch['exam_date'])) {
                $query->whereHas('reportable', function($q) use ($mappedColumnSearch) {
                    // Get the model and table
                    $model = $q->getModel();
                    $table = $model->getTable();
                    $columns = Schema::getColumnListing($table);
                    
                    // Parse the search date to handle different formats
                    $searchDate = $mappedColumnSearch['exam_date'];
                    
                    // Check if the date is in dd/mm/yyyy or dd-mm-yyyy format
                    if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $searchDate, $matches)) {
                        $day = $matches[1];
                        $month = $matches[2];
                        $year = $matches[3];
                        
                        // Create different date format variations for searching
                        $dateFormats = [
                            "$year-$month-$day", // yyyy-mm-dd (SQL format)
                            "$day/$month/$year", // dd/mm/yyyy
                            "$day-$month-$year", // dd-mm-yyyy
                            "$year/$month/$day", // yyyy/mm/dd
                        ];
                        
                        // Only search in columns that actually exist in this table
                        $q->where(function($subQuery) use ($dateFormats, $columns, $searchDate) {
                            $first = true;
                            
                            $columnsToCheck = [
                                'ldr_6', 'lcr_6', 'locr_6', 'lfr_6', 'lter_6', 
                                'nmpr_6', 'ntir_6', 'nvr_4', 'nur_6', 'nsr_4', 'nwhr_6', 
                                'nhpr_6', 'nh2pr_6', 'nar_4', 'examination_date'
                            ];
                            
                            foreach ($columnsToCheck as $column) {
                                if (in_array($column, $columns)) {
                                    if ($first) {
                                        $subQuery->where(function($dateQuery) use ($column, $dateFormats) {
                                            $dateQuery->where($column, 'like', $dateFormats[0] . '%')
                                                    ->orWhere($column, 'like', $dateFormats[1] . '%')
                                                    ->orWhere($column, 'like', $dateFormats[2] . '%')
                                                    ->orWhere($column, 'like', $dateFormats[3] . '%');
                                        });
                                        $first = false;
                                    } else {
                                        $subQuery->orWhere(function($dateQuery) use ($column, $dateFormats) {
                                            $dateQuery->where($column, 'like', $dateFormats[0] . '%')
                                                    ->orWhere($column, 'like', $dateFormats[1] . '%')
                                                    ->orWhere($column, 'like', $dateFormats[2] . '%')
                                                    ->orWhere($column, 'like', $dateFormats[3] . '%');
                                        });
                                    }
                                }
                            }
                        });
                    } else {
                        // If not in expected format, fall back to the original LIKE search
                        $q->where(function($subQuery) use ($mappedColumnSearch, $columns) {
                            $searchValue = '%' . $mappedColumnSearch['exam_date'] . '%';
                            $first = true;
                            
                            $columnsToCheck = [
                                'ldr_6', 'lcr_6', 'locr_6', 'lfr_6', 'lter_6', 
                                'nmpr_6', 'ntir_6', 'nvr_4', 'nur_6', 'nsr_4', 'nwhr_6', 
                                'nhpr_6', 'nh2pr_6', 'nar_4', 'examination_date'
                            ];
                            
                            foreach ($columnsToCheck as $column) {
                                if (in_array($column, $columns)) {
                                    if ($first) {
                                        $subQuery->where(function($q) use ($column, $searchValue) {
                                            $q->whereNotNull($column)
                                              ->where($column, '!=', '')
                                              ->where($column, 'like', $searchValue);
                                        });
                                        $first = false;
                                    } else {
                                        $subQuery->orWhere(function($q) use ($column, $searchValue) {
                                            $q->whereNotNull($column)
                                              ->where($column, '!=', '')
                                              ->where($column, 'like', $searchValue);
                                        });
                                    }
                                }
                            }
                        });
                    }
                });
            }      
            
            if (isset($mappedColumnSearch['po']) && !empty($mappedColumnSearch['po'])) {
                $query->whereHas('job_request', function($q) use ($mappedColumnSearch) {
                    $q->where('purchase_order', 'like', '%' . $mappedColumnSearch['po'] . '%');
                });
            }

            if (isset($mappedColumnSearch['internal_so']) && !empty($mappedColumnSearch['internal_so'])) {
                $query->whereHas('reportable', function($q) use ($mappedColumnSearch) {
                    $q->where('internal_service_order', 'like', '%' . $mappedColumnSearch['internal_so'] . '%');
                });
            }

            if (isset($mappedColumnSearch['work_location']) && !empty($mappedColumnSearch['work_location'])) {
                $query->whereHas('job_request', function($q) use ($mappedColumnSearch) {
                    $q->where('deploc', 'like', '%' . $mappedColumnSearch['work_location'] . '%');
                });
            }
        }

        return DataTables::of($query)
            ->addColumn('type', function ($report) {
                return $this->getReportType($report);
            })
            ->addColumn('report_no', function ($report) {
                $t = strtolower(str_replace(['App\Models\\', '\\'], ['', '/'], $report->reportable_type));
                $path = 'pdf/'.$t.'/'.$report->job_request->code.'/'.$report->code.'.pdf';
                if(\Storage::disk('public')->exists($path)){
                    return '<a href="' . \Storage::url('pdf/'.$t.'/'.$report->job_request->code.'/'.$report->code.'.pdf') . 
                           '" class="btn btn-info mr-1 waves-effect waves-light d-block" data-path="' . 
                           'pdf/'.$t.'/'.$report->job_request->code.'/'.$report->code.'.pdf' . 
                           '" target="_blank">' . 
                           $report->job_request->code . '/' . $report->code . '</a>';
                }else{
                    return 'PDF Dose not Exists';
                }
            })
            ->addColumn('id', function ($report) {
                return $this->getReportIdentifier($report->reportable);
            })
            ->addColumn('equipment', function ($report) {
                return $this->getReportEquipment($report->reportable);
            })
            ->addColumn('exam_date', function ($report) {
                return $this->getExaminationDate($report->reportable);
            })
            ->addColumn('po', function ($report) {
                return $report->job_request->purchase_order;
            })
            ->addColumn('internal_so', function ($report) {
                return $report->reportable->internal_service_order;
            })
            ->addColumn('work_location', function ($report) {
                return $report->job_request->deploc;
            })
            ->orderColumn('type', function ($query, $order) {
                $query->orderBy('reportable_type', $order);
            })
            ->orderColumn('report_no', function ($query, $order) {
                $query->join('job_requests', 'inspection_reports.job_request_id', '=', 'job_requests.id')
                      ->orderBy(DB::raw('CONCAT(job_requests.code, "/", inspection_reports.code)'), $order)
                      ->select('inspection_reports.*', 'job_requests.code as job_code');
            })
            ->orderColumn('exam_date', function ($query, $order) {
                $query->orderByRaw('CASE 
                    WHEN reportable_type = "App\\Models\\Inspection\\Lifting\\Crane" THEN 
                        (SELECT lcr_6 FROM cranes WHERE id = reportable_id)
                    WHEN reportable_type = "App\\Models\\Inspection\\Lifting\\OverheadCrane" THEN 
                        (SELECT locr_6 FROM overhead_cranes WHERE id = reportable_id)
                    WHEN reportable_type = "App\\Models\\Inspection\\Lifting\\Forklift" THEN 
                        (SELECT lfr_6 FROM forklifts WHERE id = reportable_id)    
                    WHEN reportable_type = "App\\Models\\Inspection\\Lifting\\ThroughExamination" THEN 
                        (SELECT lter_6 FROM through_examinations WHERE id = reportable_id)
                    WHEN reportable_type = "App\\Models\\Inspection\\Lifting\\Defect" THEN 
                        (SELECT ldr_6 FROM defects WHERE id = reportable_id)    
                    WHEN reportable_type = "App\\Models\\Inspection\\Ndt\\Mpipt" THEN 
                        (SELECT nmpr_6 FROM mpipts WHERE id = reportable_id)   
                    WHEN reportable_type = "App\\Models\\Inspection\\Ndt\\TreatingIron" THEN 
                        (SELECT ntir_6 FROM treating_irons WHERE id = reportable_id) 
                    WHEN reportable_type = "App\\Models\\Inspection\\Ndt\\Visual" THEN 
                        (SELECT nvr_4 FROM visuals WHERE id = reportable_id)   
                    WHEN reportable_type = "App\\Models\\Inspection\\Ndt\\Ultrasonic" THEN 
                        (SELECT nur_6 FROM ultrasonics WHERE id = reportable_id)   
                    WHEN reportable_type = "App\\Models\\Inspection\\Ndt\\Summary" THEN 
                        (SELECT nsr_4 FROM summaries WHERE id = reportable_id)                           
                    WHEN reportable_type = "App\\Models\\Inspection\\Ndt\\WitnessHydro" THEN 
                        (SELECT nwhr_6 FROM witness_hydros WHERE id = reportable_id)  
                    WHEN reportable_type = "App\\Models\\Inspection\\Ndt\\HighPressure" THEN 
                        (SELECT nhpr_6 FROM high_pressures WHERE id = reportable_id)   
                    WHEN reportable_type = "App\\Models\\Inspection\\Ndt\\High2Pressure" THEN 
                        (SELECT nh2pr_6 FROM high2_pressures WHERE id = reportable_id)    
                    WHEN reportable_type = "App\\Models\\Inspection\\Ndt\\Attached" THEN 
                        (SELECT nar_4 FROM attacheds WHERE id = reportable_id) 
                    ELSE created_at
                END ' . $order);
            })
            ->orderColumn('po', function ($query, $order) {
                $query->join('job_requests', 'inspection_reports.job_request_id', '=', 'job_requests.id')
                      ->orderBy('job_requests.purchase_order', $order)
                      ->select('inspection_reports.*', 'job_requests.purchase_order as po');
            })
            ->orderColumn('work_location', function ($query, $order) {
                $query->join('job_requests', 'inspection_reports.job_request_id', '=', 'job_requests.id')
                      ->orderBy('job_requests.deploc', $order)
                      ->select('inspection_reports.*', 'job_requests.deploc as work_location');
            })
            ->rawColumns(['report_no', 'id', 'equipment', 'work_location'])
            ->make(true);
    }

    protected function getReportIdentifier($reportable)
    {

        if (isset($reportable->lter_10) && !empty($reportable->lter_10)) {
            if ($reportable->lter_10 && isset($reportable->lter_10['pop1'])) {
                return $reportable->lter_10['pop1'];
            }
        }

        if (isset($reportable->ldr_8)) {
            $identifier = '';
            foreach (json_decode($reportable->ldr_8) as $value) {
                $decodedValue = json_decode(json_encode($value));
                if (isset($decodedValue->lcr_10) && $decodedValue->lcr_10 != null) {
                    $identifier .= $decodedValue->lcr_10;
                    $identifier .= ' . <br />';
                }
            }
            if (!empty($identifier)) {
                return $identifier;
            }
        }

        $identifierFields = [
            'lcr_12', 'locr_12', 'lfr_12', 'nmpr_28', 'ntir_13', 
            'nvr_6', 'nur_26', 'nwhr_15', 'nhpr_10', 'nh2pr_10',
            'material_no'
        ];

        foreach ($identifierFields as $field) {
            if (isset($reportable->$field) && !empty($reportable->$field)) {
                return is_string($reportable->$field) ? 
                    $reportable->$field : 
                    json_encode($reportable->$field);
            }
        }

        return '';
    }

    protected function getReportEquipment($reportable)
    {

        if (isset($reportable->lter_10) && !empty($reportable->lter_10)) {
            if ($reportable->lter_10 && isset($reportable->lter_10['pop20'])) {
                return $reportable->lter_10['pop20'];
            }
        }
        
        $descriptionFields = [
            'lcr_10', 'locr_10', 'lfr_10', 'nur_12', 
            'desc', 'material_description', 'joint_description', 
            'description', 'dc_description'
        ];

        foreach ($descriptionFields as $field) {
            if (isset($reportable->$field) && !empty($reportable->$field)) {
                return is_string($reportable->$field) ? 
                    $reportable->$field : 
                    json_encode($reportable->$field);
            }
        }

        return '';
    }

    protected function getExaminationDate($reportable)
    {
        $descriptionFields = [
            'ldr_6', 'lcr_6', 'locr_6', 'lfr_6', 'lter_6', 
            'nmpr_6', 'ntir_6', 'nvr_4', 'nur_6', 'nsr_4', 'nwhr_6', 
            'nhpr_6', 'nh2pr_6', 'nar_4', 'examination_date'
        ];

        foreach ($descriptionFields as $field) {
            if (isset($reportable->$field) && !empty($reportable->$field)) {
                $value = $reportable->$field;
                
                // Try to convert to date format if it's a valid date
                if (is_string($value)) {
                    $timestamp = strtotime($value);
                    if ($timestamp !== false) {
                        return date('d/m/Y', $timestamp);
                    }
                    return $value;
                } else {
                    return json_encode($value);
                }
            }
        }

        return '';
    }

    private function getReportType($report)
    {
        if ($report->reportable) {
            $type = str_replace('App\Models\\', '', $report->reportable_type);
            $type = strtolower($type);
            $type = str_replace(['inspection\\', 'ndt\\', 'lifting\\', 'tubular\\', 'calibration\\'], '', $type);
            return ucfirst($type);
        }
        return '';
    }

    private function fileExists($report)
    {
        $path = 'pdf/' . $report->reportable_type . '/' . $report->job_request->code . '/' . $report->code . '.pdf';
        return \Storage::exists($path);
    }

    protected function getReportRepo($reportable)
    {
        // This method is no longer needed for server-side processing
        // but you can keep it if you need it for other purposes
        return null;
    }
} 
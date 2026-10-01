<?php

namespace App\Http\Controllers\Dashboard\GeneralInfo;

use App\Http\Controllers\Controller;
use App\Models\GeneralInfo\EquipmentControlledList;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EquipmentControlledListController extends Controller
{
    public $page_name = 'Equipment Controlled List';

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', EquipmentControlledList::class);

        $totalEquipments = EquipmentControlledList::count();
        $activeEquipments = EquipmentControlledList::where('status', 'Active')->count();
        $underMaintenance = EquipmentControlledList::where('status', 'Under Maintenance')->count();
        $underCalibration = EquipmentControlledList::where('status', 'Under Calibration')->count();
        $outOfService = EquipmentControlledList::where('status', 'Out of Service')->count();

        $today = Carbon::today()->format('Y-m-d');
        $alarmDueCount = EquipmentControlledList::where(function($q) use ($today) {
            $q->where('recalibration_alarm', 'LIKE', '%Re-Calibrate%')
              ->orWhere('recalibration_alarm', 'LIKE', '%Overdue%')
              ->orWhere(function($sub) use ($today) {
                  $sub->whereNotNull('calibration_due_date')
                      ->where('calibration_due_date', '<=', $today);
              });
        })->count();

        return view('layouts.general-info.equipmentControlledList.index', [
            'page_name' => $this->page_name('All', 'Equipment'),
            'totalEquipments' => $totalEquipments,
            'activeEquipments' => $activeEquipments,
            'underMaintenance' => $underMaintenance,
            'underCalibration' => $underCalibration,
            'outOfService' => $outOfService,
            'alarmDueCount' => $alarmDueCount,
        ]);
    }

    /**
     * Fetch data for DataTables
     */
    public function getDataForDataTable(Request $request)
    {
        $this->authorize('viewAny', EquipmentControlledList::class);

        $query = EquipmentControlledList::query();

        // Optional status filter
        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }

        // Optional alarm filter
        if ($request->filled('alarm_filter')) {
            if ($request->alarm_filter === 'alarm') {
                $query->where(function($q) {
                    $q->where('recalibration_alarm', 'LIKE', '%Re-Calibrate%')
                      ->orWhere('recalibration_alarm', 'LIKE', '%Overdue%')
                      ->orWhere(function($sub) {
                          $sub->whereNotNull('calibration_due_date')
                              ->where('calibration_due_date', '<=', Carbon::today()->format('Y-m-d'));
                      });
                });
            } elseif ($request->alarm_filter === 'calibrated') {
                $query->where('recalibration_alarm', 'LIKE', '%Calibrated%');
            }
        }

        return DataTables::of($query)
            ->editColumn('date_into_service', function ($row) {
                return $row->date_into_service ? Carbon::parse($row->date_into_service)->format('d-M-Y') : 'N/A';
            })
            ->editColumn('calibration_date', function ($row) {
                return $row->calibration_date ? Carbon::parse($row->calibration_date)->format('d-m-y') : 'N/A';
            })
            ->editColumn('calibration_due_date', function ($row) {
                if (!$row->calibration_due_date) return 'N/A';
                $dueDate = Carbon::parse($row->calibration_due_date);
                $formatted = $dueDate->format('d-m-y');
                if ($dueDate->isPast()) {
                    return '<span class="text-danger font-weight-bold" title="Overdue">' . $formatted . ' <i class="la la-warning"></i></span>';
                }
                return $formatted;
            })
            ->editColumn('recalibration_alarm', function ($row) {
                $alarm = $row->recalibration_alarm ?: $row->computed_alarm;
                if (!$alarm || $alarm === 'N/A') {
                    return '<span class="badge badge-secondary">N/A</span>';
                }
                $lower = strtolower($alarm);
                if (str_contains($lower, 're-calibrate') || str_contains($lower, 'overdue')) {
                    return '<span class="badge badge-danger text-white"><i class="la la-bell"></i> ' . e($alarm) . '</span>';
                } elseif (str_contains($lower, 'due soon')) {
                    return '<span class="badge badge-warning text-white"><i class="la la-clock-o"></i> ' . e($alarm) . '</span>';
                } else {
                    return '<span class="badge badge-success text-white"><i class="la la-check"></i> ' . e($alarm) . '</span>';
                }
            })
            ->editColumn('status', function ($row) {
                $status = $row->status ?: 'Active';
                switch ($status) {
                    case 'Active':
                        return '<span class="badge badge-success">' . e($status) . '</span>';
                    case 'Under Maintenance':
                        return '<span class="badge badge-warning text-white">' . e($status) . '</span>';
                    case 'Under Calibration':
                        return '<span class="badge badge-info">' . e($status) . '</span>';
                    case 'Out of Service':
                        return '<span class="badge badge-danger">' . e($status) . '</span>';
                    default:
                        return '<span class="badge badge-light">' . e($status) . '</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $btn = '';

                if (Auth::user()->can('update', $row)) {
                    $btn .= '<button type="button" class="btn btn-icon btn-primary mr-1 upload-certificate" data-id="' . $row->id . '" data-name="' . e($row->certificate_name) . '" data-url="' . e($row->certificate_url) . '" title="' . ($row->certificate_path ? 'Replace Certificate' : 'Upload Certificate') . '"><i class="la la-upload"></i></button>';
                }

                if ($row->certificate_path) {
                    $btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="' . e($row->certificate_url) . '">View Certificate</a>';
                }

                $btn .= '<button type="button" class="btn btn-dark mr-1 view-details" data-id="' . $row->id . '">Open Details</button>';

                if (Auth::user()->can('update', $row)) {
                    $btn .= '<a href="' . route('equipment-controlled-list.edit', $row->id) . '" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                }

                if (Auth::user()->can('delete', $row)) {
                    $btn .= '<button type="button" data-id="' . $row->id . '" class="btn btn-icon btn-danger delete mr-1"><i class="la la-trash"></i></button>';
                }

                return $btn;
            })
            ->rawColumns(['calibration_due_date', 'recalibration_alarm', 'status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', EquipmentControlledList::class);

        return view('layouts.general-info.equipmentControlledList.add', [
            'page_name' => $this->page_name(0, 'Equipment'),
            'route' => 'equipment-controlled-list.store',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', EquipmentControlledList::class);

        $validated = $request->validate([
            'equipment_description' => 'required|string|max:255',
            'internal_code' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'model_type' => 'nullable|string|max:255',
            'capacity_range' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'date_into_service' => 'nullable|date',
            'interval' => 'nullable|string|max:255',
            'calibration_date' => 'nullable|date',
            'calibration_due_date' => 'nullable|date',
            'calibrated_by' => 'nullable|string|max:255',
            'recalibration_alarm' => 'nullable|string|max:255',
            'location_department' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'date_removed_from_service' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        $dueDate = EquipmentControlledList::computeDueDate($validated['calibration_date'] ?? null, $validated['interval'] ?? null);
        if ($dueDate) {
            $validated['calibration_due_date'] = $dueDate;
        }

        // If alarm not specified, auto-compute default based on calibration
        if (empty($validated['recalibration_alarm'])) {
            if (!empty($validated['calibration_due_date'])) {
                $dueDate = Carbon::parse($validated['calibration_due_date']);
                $validated['recalibration_alarm'] = $dueDate->isPast() ? 'Re-Calibrate' : 'Calibrated';
            } else {
                $validated['recalibration_alarm'] = 'N/A';
            }
        }

        $equipment = EquipmentControlledList::create($validated);

        if ($equipment) {
            return response()->json([
                'success' => 'Equipment added to Controlled List successfully!',
                'redirect' => route('equipment-controlled-list.index'),
            ]);
        }

        return response()->json(['error' => 'Failed to save equipment.'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $equipment = EquipmentControlledList::findOrFail($id);
        $this->authorize('view', $equipment);
        return response()->json([
            'success' => true,
            'data' => $equipment,
            'formatted_service_date' => $equipment->date_into_service ? Carbon::parse($equipment->date_into_service)->format('d-M-Y') : 'N/A',
            'formatted_cal_date' => $equipment->calibration_date ? Carbon::parse($equipment->calibration_date)->format('d-M-Y') : 'N/A',
            'formatted_due_date' => $equipment->calibration_due_date ? Carbon::parse($equipment->calibration_due_date)->format('d-M-Y') : 'N/A',
            'formatted_certificate_date' => $equipment->certificate_uploaded_at ? $equipment->certificate_uploaded_at->format('d-M-Y H:i') : null,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $equipment = EquipmentControlledList::findOrFail($id);
        $this->authorize('update', $equipment);

        return view('layouts.general-info.equipmentControlledList.edit', [
            'page_name' => $this->page_name(1, 'Equipment'),
            'route' => 'equipment-controlled-list.update',
            'equipment' => $equipment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $equipment = EquipmentControlledList::findOrFail($id);
        $this->authorize('update', $equipment);

        $validated = $request->validate([
            'equipment_description' => 'required|string|max:255',
            'internal_code' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'model_type' => 'nullable|string|max:255',
            'capacity_range' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'date_into_service' => 'nullable|date',
            'interval' => 'nullable|string|max:255',
            'calibration_date' => 'nullable|date',
            'calibration_due_date' => 'nullable|date',
            'calibrated_by' => 'nullable|string|max:255',
            'recalibration_alarm' => 'nullable|string|max:255',
            'location_department' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'date_removed_from_service' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $dueDate = EquipmentControlledList::computeDueDate($validated['calibration_date'] ?? null, $validated['interval'] ?? null);
        if ($dueDate) {
            $validated['calibration_due_date'] = $dueDate;
        }

        $update = $equipment->update($validated);

        if ($update) {
            return response()->json([
                'success' => 'Equipment updated successfully!',
                'redirect' => route('equipment-controlled-list.index'),
            ]);
        }

        return response()->json(['error' => 'Failed to update equipment.'], 500);
    }

    /**
     * Upload or replace the calibration certificate (PDF or image) of an existing equipment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uploadCertificate(Request $request, $id)
    {
        $equipment = EquipmentControlledList::findOrFail($id);
        $this->authorize('update', $equipment);

        $request->validate([
            'certificate' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
        ], [
            'certificate.mimes' => 'The certificate must be a PDF or an image (JPG, PNG, WEBP).',
            'certificate.max' => 'The certificate must not be larger than 10 MB.',
        ]);

        $file = $request->file('certificate');
        $path = $file->store('equipment-certificates/' . $equipment->id, 'public');

        $oldPath = $equipment->certificate_path;

        $equipment->update([
            'certificate_path' => $path,
            'certificate_name' => $file->getClientOriginalName(),
            'certificate_uploaded_at' => now(),
        ]);

        if ($oldPath && $oldPath !== $path) {
            Storage::disk('public')->delete($oldPath);
        }

        return response()->json([
            'success' => $oldPath ? 'Certificate replaced successfully!' : 'Certificate uploaded successfully!',
            'url' => $equipment->certificate_url,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $equipment = EquipmentControlledList::findOrFail($id);
        $this->authorize('delete', $equipment);
        $certificatePath = $equipment->certificate_path;
        $remove = $equipment->delete();

        if ($remove) {
            if ($certificatePath) {
                Storage::disk('public')->delete($certificatePath);
            }

            return response()->json([
                'success' => 'Equipment deleted successfully!',
            ]);
        }

        return response()->json(['error' => 'Failed to delete equipment.'], 500);
    }
}

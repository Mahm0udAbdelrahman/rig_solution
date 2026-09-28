<?php

namespace App\Http\Controllers\Dashboard\GeneralInfo;

use App\Http\Controllers\Controller;
use App\Models\GeneralInfo\EquipmentControlledList;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
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
            'page_name' => $this->page_name,
            'totalEquipments' => $totalEquipments,
            'activeEquipments' => $activeEquipments,
            'underMaintenance' => $underMaintenance,
            'underCalibration' => $underCalibration,
            'outOfService' => $outOfService,
            'alarmDueCount' => $alarmDueCount,
            'route' => 'equipmentControlledList',
        ]);
    }

    /**
     * Fetch data for DataTables
     */
    public function getDataForDataTable(Request $request)
    {
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
                $btn = '<div class="btn-group" role="group">';
                
                $btn .= '<button type="button" class="btn btn-sm btn-info view-details" data-id="' . $row->id . '" title="View Details"><i class="la la-eye"></i></button>';

                if (Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('equipment_controlled_list', 'edit') || Auth::user()->hasPermission('equipment_controlled_list', 'all')) {
                    $btn .= '<a href="' . route('equipment-controlled-list.edit', $row->id) . '" class="btn btn-sm btn-primary" title="Edit"><i class="la la-pencil"></i></a>';
                }

                if (Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('equipment_controlled_list', 'delete') || Auth::user()->hasPermission('equipment_controlled_list', 'all')) {
                    $btn .= '<button type="button" data-id="' . $row->id . '" class="btn btn-sm btn-danger delete" title="Delete"><i class="la la-trash"></i></button>';
                }
                
                $btn .= '</div>';
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
        return view('layouts.general-info.equipmentControlledList.add', [
            'page_name' => 'Add Equipment',
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
        return response()->json([
            'success' => true,
            'data' => $equipment,
            'formatted_service_date' => $equipment->date_into_service ? Carbon::parse($equipment->date_into_service)->format('d-M-Y') : 'N/A',
            'formatted_cal_date' => $equipment->calibration_date ? Carbon::parse($equipment->calibration_date)->format('d-M-Y') : 'N/A',
            'formatted_due_date' => $equipment->calibration_due_date ? Carbon::parse($equipment->calibration_due_date)->format('d-M-Y') : 'N/A',
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

        return view('layouts.general-info.equipmentControlledList.edit', [
            'page_name' => 'Edit Equipment',
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $equipment = EquipmentControlledList::findOrFail($id);
        $remove = $equipment->delete();

        if ($remove) {
            return response()->json([
                'success' => 'Equipment deleted successfully!',
            ]);
        }

        return response()->json(['error' => 'Failed to delete equipment.'], 500);
    }
}

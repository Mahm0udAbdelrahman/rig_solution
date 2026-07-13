<?php

namespace App\Http\Controllers\Dashboard\WorkFlow;

use App\Http\Controllers\Controller;
use App\Models\Persons\Client;
use App\Models\WorkFlow\Inventory;
use App\Models\WorkFlow\JobRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;

class InventoryController extends Controller
{
    public $page_name = 'Inventory';

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Inventory::class, 'inventory');
    }

    public function index(Request $request)
    {
        $selectedJobRequestId = (int)$request->get('job_request_id');
        $inventoryQuery = Inventory::query();
        if ($selectedJobRequestId > 0) {
            $inventoryQuery->where('job_request_id', $selectedJobRequestId);
        }

        return view('layouts.work-flow.inventory.index', [
            'page_name' => $this->page_name('All', $this->page_name),
            'inventories' => $inventoryQuery->count(),
            'jcf_create_options' => $this->getJobRequestOptions(),
            'selected_job_request_id' => $selectedJobRequestId,
        ]);
    }

    public function getDataForDataTable(Request $request)
    {
        $selectedJobRequestId = (int)$request->get('job_request_id');

        $data = Inventory::query()
            ->leftJoin('users', 'inventories.user_id', '=', 'users.id')
            ->leftJoin('employees', 'employees.id', '=', 'users.employee_id')
            ->leftJoin('job_requests', 'job_requests.id', '=', 'inventories.job_request_id')
            ->leftJoin('clients', 'job_requests.client_id', '=', 'clients.id')
            ->leftJoin('suppliers', 'job_requests.supplier_id', '=', 'suppliers.id')
            ->select([
                'inventories.id as inventory_id',
                'inventories.code as code',
                'inventories.item_name as item_name',
                'inventories.sku as sku',
                'inventories.quantity as quantity',
                'inventories.unit as unit',
                'inventories.location as location',
                'inventories.min_quantity as min_quantity',
                'inventories.status as status',
                'employees.name as employee',
                'job_requests.id as job_request_id',
                'job_requests.code as job_request_code',
                'job_requests.client_id as client_id',
                'job_requests.supplier_id as supplier_id',
                'clients.name as cli',
                'suppliers.name as sup',
            ]);

        if ($selectedJobRequestId > 0) {
            $data->where('inventories.job_request_id', $selectedJobRequestId);
        }

        return Datatables::of($data)
            ->addColumn('job_request_code', function ($row) {
                if (!$row->job_request_id || !Auth::user()->can('view', JobRequest::find($row->job_request_id))) {
                    return $row->job_request_code ?: '-';
                }

                return '<a href="'.route('jobRequest.show', $row->job_request_id).'">'.$row->job_request_code.'</a>';
            })
            ->addColumn('client', function ($row) {
                if ($row->client_id != null && $row->supplier_id == null) {
                    if ($row->client_id && Auth::user()->can('view', Client::find($row->client_id))) {
                        return '<a href="'.route('client.show', $row->client_id).'">'.$row->cli.'</a>';
                    }
                    return $row->cli ?: '-';
                }

                return $row->sup ?: '-';
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                $inventory = Inventory::find($row->inventory_id);
                if (!$inventory) {
                    return '';
                }

                if (Auth::user()->can('update', $inventory)) {
                    $btn .= '<a href="'.route('inventory.edit', $row->inventory_id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                }

                if (Auth::user()->can('delete', $inventory)) {
                    $btn .= '<button type="button" data-id="'.$row->inventory_id.'" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>';
                }

                if ($btn === '') {
                    return '';
                }

                return '<div class="wf-inline-actions">'.$btn.'</div>';
            })
            ->editColumn('code', function ($row) {
                $inventory = Inventory::find($row->inventory_id);
                if (!$inventory || !Auth::user()->can('view', $inventory)) {
                    return $row->code;
                }
                return '<a href="'.route('inventory.show', $row->inventory_id).'">'.$row->code.'</a>';
            })
            ->editColumn('quantity', function ($row) {
                return number_format((float)$row->quantity, 2, '.', '');
            })
            ->editColumn('min_quantity', function ($row) {
                return number_format((float)$row->min_quantity, 2, '.', '');
            })
            ->orderColumn('code', 'inventories.code $1')
            ->orderColumn('job_request_code', 'job_requests.code $1')
            ->orderColumn('client', 'COALESCE(clients.name, suppliers.name) $1')
            ->orderColumn('item_name', 'inventories.item_name $1')
            ->orderColumn('sku', 'inventories.sku $1')
            ->orderColumn('quantity', 'inventories.quantity $1')
            ->orderColumn('unit', 'inventories.unit $1')
            ->orderColumn('location', 'inventories.location $1')
            ->orderColumn('status', 'inventories.status $1')
            ->orderColumn('employee', 'employees.name $1')
            ->filterColumn('code', function ($query, $keyword) {
                $query->whereRaw('inventories.code like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('job_request_code', function ($query, $keyword) {
                $query->whereRaw('job_requests.code like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('client', function ($query, $keyword) {
                $query->whereRaw('COALESCE(clients.name, suppliers.name) like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('item_name', function ($query, $keyword) {
                $query->whereRaw('inventories.item_name like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('sku', function ($query, $keyword) {
                $query->whereRaw('inventories.sku like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('quantity', function ($query, $keyword) {
                $query->whereRaw('inventories.quantity like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('unit', function ($query, $keyword) {
                $query->whereRaw('inventories.unit like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('location', function ($query, $keyword) {
                $query->whereRaw('inventories.location like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->whereRaw('inventories.status like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('employee', function ($query, $keyword) {
                $query->whereRaw('employees.name like ?', ["%{$keyword}%"]);
            })
            ->rawColumns(['code', 'job_request_code', 'client', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('layouts.work-flow.inventory.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'job_request' => null,
            'job_request_options' => $this->getJobRequestOptions(),
        ]);
    }

    public function inventoryWithJobRequest(JobRequest $jobRequest)
    {
        $this->authorize('create', Inventory::class);

        return view('layouts.work-flow.inventory.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'job_request' => $jobRequest,
            'job_request_options' => $this->getJobRequestOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $payload = $this->validatePayload($request);

        $inventory = Inventory::create([
            'code' => $this->nextCode(),
            'job_request_id' => $payload['job_request_id'],
            'item_name' => $payload['item_name'],
            'sku' => $payload['sku'],
            'quantity' => $payload['quantity'],
            'unit' => $payload['unit'],
            'location' => $payload['location'],
            'min_quantity' => $payload['min_quantity'],
            'status' => $payload['status'],
            'note' => $payload['note'],
            'user_id' => Auth::id(),
            'sync' => 0,
            'updated' => null,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => $this->action_message(0, $this->page_name)]);
        }

        return redirect()->route('inventory.show', $inventory->id);
    }

    public function show(Inventory $inventory)
    {
        return view('layouts.work-flow.inventory.show', [
            'page_name' => $this->page_name,
            'inventory' => $inventory->load(['jobRequest.client', 'jobRequest.supplier', 'user.employee']),
        ]);
    }

    public function edit(Inventory $inventory)
    {
        return view('layouts.work-flow.inventory.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'inventory' => $inventory,
            'job_request_options' => $this->getJobRequestOptions(),
        ]);
    }

    public function update(Request $request, Inventory $inventory)
    {
        $payload = $this->validatePayload($request);

        $updated = Inventory::where('id', $inventory->id)->update([
            'job_request_id' => $payload['job_request_id'],
            'item_name' => $payload['item_name'],
            'sku' => $payload['sku'],
            'quantity' => $payload['quantity'],
            'unit' => $payload['unit'],
            'location' => $payload['location'],
            'min_quantity' => $payload['min_quantity'],
            'status' => $payload['status'],
            'note' => $payload['note'],
            'updated' => 1,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => $this->action_message(1, $this->page_name)]);
        }

        if ($updated) {
            return redirect()->route('inventory.show', $inventory->id);
        }

        return back();
    }

    public function destroy(Inventory $inventory)
    {
        $removed = $inventory->forceDelete();

        if ($removed) {
            return response()->json(['success' => $this->action_message(2, $this->page_name)]);
        }

        return response()->json(['success' => 'Unable to delete this Inventory record.'], 422);
    }

    private function validatePayload(Request $request): array
    {
        $payload = $request->validate([
            'job_request_id' => ['required', 'integer', 'exists:job_requests,id'],
            'item_name' => ['required', 'string', 'max:191'],
            'sku' => ['nullable', 'string', 'max:191'],
            'quantity' => ['nullable'],
            'unit' => ['nullable', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:191'],
            'min_quantity' => ['nullable'],
            'status' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string'],
        ]);

        $payload['quantity'] = $this->normalizeAmount($payload['quantity'] ?? null);
        $payload['min_quantity'] = $this->normalizeAmount($payload['min_quantity'] ?? null);
        $payload['status'] = $payload['status'] ?: 'available';

        return $payload;
    }

    private function normalizeAmount($value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_numeric($value)) {
            return round((float)$value, 2);
        }

        $normalized = preg_replace('/[^\d\.\-]/', '', (string)$value);
        if ($normalized === '' || $normalized === '-' || $normalized === '.') {
            return 0.0;
        }

        return round((float)$normalized, 2);
    }

    private function nextCode(): string
    {
        $prefix = 'STK-'.date('y').'-';
        $lastCode = Inventory::where('code', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('code');

        $next = 1;
        if ($lastCode && preg_match('/(\d+)$/', $lastCode, $matches)) {
            $next = ((int)$matches[1]) + 1;
        }

        return $prefix.str_pad((string)$next, 3, '0', STR_PAD_LEFT);
    }

    private function getJobRequestOptions(): array
    {
        return JobRequest::query()
            ->with(['client:id,name', 'supplier:id,name'])
            ->orderBy('code', 'desc')
            ->get(['id', 'code', 'client_id', 'supplier_id'])
            ->mapWithKeys(function (JobRequest $jobRequest) {
                $ownerName = $jobRequest->client->name ?? $jobRequest->supplier->name ?? '';
                $label = $jobRequest->code.($ownerName !== '' ? ' - '.$ownerName : '');
                return [$jobRequest->id => $label];
            })
            ->toArray();
    }
}

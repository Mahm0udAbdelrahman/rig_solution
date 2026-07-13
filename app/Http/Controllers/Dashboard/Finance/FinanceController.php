<?php

namespace App\Http\Controllers\Dashboard\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFinanceRequest;
use App\Http\Requests\UpdateFinanceRequest;
use App\Models\Finance\Finance;
use App\Models\Persons\Client;
use App\Models\WorkFlow\Invoice;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use DB, Storage;

class FinanceController extends Controller
{
    public $page_name = 'Recivable';

    public function __construct()
    {
	      $this->middleware('auth');
	      $this->authorizeResource(Finance::class, 'finance');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $invoiceCompanyType = $request->get('type') ? $request->get('type') : Invoice::$INVOICE_RSE_TYPE;
        // $invoices = Invoice::query()->where('invoice_company_type', $invoiceCompanyType)->count();
        // $finances = Finance::all();
        $finances = Invoice::count();
        return view('layouts.finance.index', [
            'page_name' => $this->page_name('All', $this->page_name),
            'finances' => $finances,
            // 'invoice_company_type' => $invoiceCompanyType,
        ]);
    }

    // public function getDataForDataTable()
    // {
    //     $data = Invoice::with(['finance','jobRequest.clientDepartment.client'])->get();
    //     // return $data;
    //     return DataTables::of($data)
    //         ->setRowAttr([
    //             'data-id' => function($row) {
    //                 return $row->id;
    //             }
    //         ])
    //         ->addColumn('invoice_no', function($row) {
    //             $report_code = "";
    //             if(Auth::user()->can('view', Invoice::find($row->id)))
    //             {
    //                     $report_code .= "<a href=".route('invoice.show', $row->id).">";
    //             }

    //             $report_code .= $row->code;

    //             if(Auth::user()->can('view', Invoice::find($row->id)))
    //             {
    //                     $report_code .= "</a>";
    //             }
    //             return $report_code;
    //         })
    //         ->addColumn('client', function ($row) {
    //             $link = "";
    //             $client = $row->jobRequest->client;
    //             if ($client->id != null)
    //             {
    //                 if (Auth::user()->can('view', Client::find($client->id)))
    //                 {
    //                         $link .= "<a href=".route('client.show', $client->id).">";
    //                 }

    //                         $link .= $client->name;

    //                 if (Auth::user()->can('view', Client::find($client->id)))
    //                 {
    //                         $link .= "</a>";
    //                 }
    //             }
    //             return $link;
    //         })
    //         ->addColumn('department', function($row) {
    //             return $row->jobRequest->clientDepartment->name ?? '-';
    //         })
    //         ->addColumn('jcf_no', function($row) {
    //             return $row->jobRequest->code ?? '-';
    //         })
    //         ->addColumn('invoice_date', function($row) {
    //             return Carbon::parse($row->created_at)->format('Y-m-d') ?? '-';
    //         })
    //         ->addColumn('invoice_currency', function($row) {
    //             return $row->type ?? '-';
    //         })
    //         ->addColumn('sub_total', function($row) {
    //             return $row->sub_total ?? '';
    //         })
    //         ->addColumn('total_amount', function($row) {
    //             return $row->total ?? '';
    //         })
    //         ->addColumn('receipt_date', function($row) {
    //             return $row->finance ?? '-';
    //         })
    //         ->addColumn('outstand_days', function($row) {
    //             if($row->finance && $row->finance->receipt_date) {
    //                 $pastDate = Carbon::parse($row->finance->receipt_date);
    //                 return $pastDate->diffInDays(now());
    //             }

    //             return "-";
    //         })
    //         ->addColumn('paid_amount', function($row) {
    //             return $row->finance->paid_amount ?? '-';
    //         })
    //         ->addColumn('currency', function($row) {
    //             return $row->finance->paid_currency ?? '-';
    //         })
    //         ->addColumn('rate', function($row) {
    //             return $row->finance->exchange_rate ?? '-';
    //         })
    //         ->addColumn('method', function($row) {
    //             return $row->finance->method ?? '-';
    //         })
    //         ->addColumn('method_ref', function($row) {
    //             return $row->finance->method_number ?? '-';
    //         })
    //         ->addColumn('method_amount', function($row) {
    //             return $row->finance->method_amount ?? '-';
    //         })
    //         ->addColumn('collect_date', function($row) {
    //             if($row->finance && $row->finance->receipt_date) {
    //                 return Carbon::parse($row->finance->collect_date)->format('Y-m-d');
    //             }
    //             return "-";
    //         })
    //         ->addColumn('outstand_amount', function($row) {
    //             return $row->finance->outstand_amount ?? '-';
    //         })
    //         ->addColumn('status', function($row) {
    //             return $row->finance->status ?? '-';
    //         })
    //         ->addColumn('vat_amount', function($row) {
    //             return $row->tax ?? '';
    //         })
    //         ->addColumn('holding_tax_amount', function($row) {
    //             return $row->withholding ?? '';
    //         })
    //         // ->addColumn('action', function ($row) {
    //         //     $btn = "";

    //         //     if (Auth::user()->can('create', Finance::class)) {
    //                 // $btn .= '<a href="'.route('finance.create').'" class="btn btn-icon btn-success mr-1 convert">
    //                 //             <i class="la la-plus"></i>
    //                 //         </a>';
    //             //     $btn .= '<button type="button" class="btn btn-warning convert" data-id="'.$row->id.'" data-type="in">
    //             //         <i class="la la-plus"></i>
    //             //     </button>';
    //             // }

    //             // if (Auth::user()->can('update', Finance::find($row->finance->id))) {
    //             //     $btn .= '<a href="'.route('finance', $row->finance->id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
    //             // }

    //             // if (Auth::user()->can('delete', PackingSlip::find($row->qutation_id))) {
    //             //     $btn .= '<button type="button" data-id="'.$row->qutation_id.'" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>';
    //             // }

    //             // $btn .= '<div class="btn-group ml-1">
    //             //             <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"></button>
    //             //             <div class="dropdown-menu">';

    //             // if (Storage::disk('public')->exists('packingslip/'.$row->code.'.pdf')) {
    //             //     $btn .= '<a class="dropdown-item" target="_blank" href="'.URL('storage/packingslip/'.$row->code.'.pdf').'">Download PDF</a>';
    //             // } else {
    //             //     $btn .= '<a class="dropdown-item" href="'.route('packingSlip.show', $row->qutation_id).'">Upload PDF</a>';
    //             // }

    //             // $btn .= '</div></div>';
    //             // return $btn;
    //         // })
    //         ->rawColumns([
    //             'invoice_no', 'client'
    //         ])
    //         ->make(true);

    // }

public function getDataForDataTable()
{
    $data = Invoice::where('invoice_company_type', 'LTD')->with(['finance','jobRequest.clientDepartment.client'])->get();

    return DataTables::of($data)
        ->setRowAttr([
            'data-id' => function($row) {
                return $row->id;
            }
        ])
        ->addColumn('invoice_no', function($row) {
            $report_code = "";
            if(Auth::user()->can('view', Invoice::find($row->id))) {
                $report_code .= "<a href=".route('invoice.show', $row->id).">";
            }

            $report_code .= $row->code;

            if(Auth::user()->can('view', Invoice::find($row->id))) {
                $report_code .= "</a>";
            }
            return $report_code;
        })
        ->addColumn('client', function ($row) {
            $link = "";
            $client = optional($row->jobRequest)->client;
            if ($client && $client->id) {
                if (Auth::user()->can('view', Client::find($client->id))) {
                    $link .= "<a href=".route('client.show', $client->id).">";
                }

                $link .= $client->name;

                if (Auth::user()->can('view', Client::find($client->id))) {
                    $link .= "</a>";
                }
            }
            return $link ?: '-';
        })
        ->addColumn('department', function($row) {
            return optional(optional($row->jobRequest)->clientDepartment)->name ?? '-';
        })
        ->addColumn('jcf_no', function($row) {
            return optional($row->jobRequest)->code ?? '-';
        })
        ->addColumn('invoice_date', function($row) {
            return Carbon::parse($row->created_at)->format('Y-m-d');
        })
        ->addColumn('invoice_currency', function($row) {
            return $row->type ?? '-';
        })
        ->addColumn('sub_total', function($row) {
            return $row->sub_total ?? '';
        })
        ->addColumn('total_amount', function($row) {
            return $row->total ?? '';
        })
        ->addColumn('receipt_date', function($row) {
            return optional($row->finance)->receipt_date
                ? Carbon::parse($row->finance->receipt_date)->format('Y-m-d')
                : '-';
        })
        ->addColumn('outstand_days', function($row) {
            if(optional($row->finance)->receipt_date) {
                $pastDate = Carbon::parse($row->finance->receipt_date);
                return $pastDate->diffInDays(now());
            }
            return "-";
        })
        ->addColumn('paid_amount', function($row) {
            return optional($row->finance)->paid_amount ?? '-';
        })
        ->addColumn('currency', function($row) {
            return optional($row->finance)->paid_currency ?? '-';
        })
        ->addColumn('rate', function($row) {
            return optional($row->finance)->exchange_rate ?? '-';
        })
        ->addColumn('method', function($row) {
            return optional($row->finance)->method ?? '-';
        })
        ->addColumn('method_ref', function($row) {
            return optional($row->finance)->method_number ?? '-';
        })
        ->addColumn('method_amount', function($row) {
            return optional($row->finance)->method_amount ?? '-';
        })
        ->addColumn('collect_date', function($row) {
            return optional($row->finance)->collect_date
                ? Carbon::parse($row->finance->collect_date)->format('Y-m-d')
                : '-';
        })
        ->addColumn('outstand_amount', function($row) {
            return optional($row->finance)->outstand_amount ?? '-';
        })
        ->addColumn('status', function($row) {
            return optional($row->finance)->status ?? '-';
        })
        ->addColumn('vat_amount', function($row) {
            return $row->tax ?? '';
        })
        ->addColumn('holding_tax_amount', function($row) {
            return $row->withholding ?? '';
        })
        ->rawColumns(['invoice_no', 'client'])
        ->make(true);
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFinanceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFinanceRequest $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'receipt_date' => 'nullable|date',
            // 'outstand_days' => 'nullable|integer',
            'paid_amount' => 'nullable|numeric',
            'paid_currency' => 'nullable|string',
            'exchange_rate' => 'nullable|numeric',
            'method' => 'nullable|string',
            'method_number' => 'nullable|string',
            'method_amount' => 'nullable|numeric',
            'collect_date' => 'nullable|date',
            'outstand_amount' => 'nullable|numeric',
        ]);

        $financeData = $request->except('invoice_id');

        $finance = Finance::updateOrCreate(
            ['invoice_id' => $request->invoice_id],
            $financeData
        );

        return response()->json([
            'success' => true,
            'finance_id' => $finance->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Finance  $finance
     * @return \Illuminate\Http\Response
     */
    public function show(Finance $finance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Finance  $finance
     * @return \Illuminate\Http\Response
     */
    public function edit(Finance $finance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFinanceRequest  $request
     * @param  \App\Models\Finance  $finance
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFinanceRequest $request, Finance $finance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Finance  $finance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Finance $finance)
    {
        //
    }
}

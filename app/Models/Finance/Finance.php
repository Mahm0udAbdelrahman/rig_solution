<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkFlow\Invoice;

class Finance extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'receipt_date',
        'outstand_days',
        'paid_amount',
        'paid_currency',
        'exchange_rate',
        'method',
        'method_number',
        'method_amount',
        'collect_date',
        'outstand_amount',
        'status',
        'vat_amount',
        'holding_tax_amount',
    ];

}

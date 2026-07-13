<?php

namespace App\Models\WorkFlow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountantEntryLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'accountant_id',
        'chart_account_id',
        'line_type',
        'amount',
        'currency',
        'line_order',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'line_order' => 'integer',
    ];

    public function accountant()
    {
        return $this->belongsTo(Accountant::class, 'accountant_id');
    }

    public function chartAccount()
    {
        return $this->belongsTo(ChartAccount::class, 'chart_account_id');
    }
}

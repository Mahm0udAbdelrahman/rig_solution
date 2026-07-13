<?php

namespace App\Models\WorkFlow;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $table = 'bank_accounts';

    protected $fillable = [
        'code',
        'bank_name',
        'account_name',
        'account_number',
        'iban',
        'currency',
        'chart_account_id',
        'opening_balance',
        'opening_date',
        'current_balance',
        'is_active',
        'note',
        'user_id',
        'approved_by',
        'sync',
        'updated',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'opening_date' => 'date',
        'is_active' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function chartAccount()
    {
        return $this->belongsTo(ChartAccount::class, 'chart_account_id');
    }

    public function transactions()
    {
        return $this->hasMany(BankTransaction::class, 'bank_account_id');
    }
}

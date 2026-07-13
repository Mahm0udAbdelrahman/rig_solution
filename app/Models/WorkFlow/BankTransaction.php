<?php

namespace App\Models\WorkFlow;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'bank_account_id',
        'job_request_id',
        'payment_id',
        'accountant_id',
        'counter_account_id',
        'direction',
        'category',
        'source_type',
        'transaction_date',
        'amount',
        'reference_no',
        'status',
        'note',
        'approved_by',
        'approved_at',
        'is_posted',
        'user_id',
        'sync',
        'updated',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'approved_at' => 'datetime',
        'amount' => 'decimal:2',
        'is_posted' => 'integer',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_account_id');
    }

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class, 'job_request_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function expense()
    {
        return $this->hasOne(Expense::class, 'bank_transaction_id');
    }

    public function accountant()
    {
        return $this->belongsTo(Accountant::class, 'accountant_id');
    }

    public function counterAccount()
    {
        return $this->belongsTo(ChartAccount::class, 'counter_account_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getSignedAmountAttribute(): float
    {
        $amount = (float)$this->amount;
        return $this->direction === 'out' ? ($amount * -1) : $amount;
    }
}

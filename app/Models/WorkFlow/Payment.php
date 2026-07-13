<?php

namespace App\Models\WorkFlow;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'job_request_id',
        'invoice_id',
        'bank_account_id',
        'payment_date',
        'amount',
        'method',
        'reference_no',
        'status',
        'note',
        'user_id',
        'approved_by',
        'sync',
        'updated',
    ];

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class, 'job_request_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_account_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'payment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

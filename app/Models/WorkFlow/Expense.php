<?php

namespace App\Models\WorkFlow;

use App\Models\Organization\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'employee_id',
        'job_request_id',
        'bank_account_id',
        'bank_transaction_id',
        'accountant_id',
        'payment_id',
        'expense_account_id',
        'expense_date',
        'category',
        'amount',
        'currency',
        'reference_no',
        'status',
        'is_posted',
        'note',
        'approved_by',
        'approved_at',
        'user_id',
        'sync',
        'updated',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'approved_at' => 'datetime',
        'amount' => 'decimal:2',
        'is_posted' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class, 'job_request_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_account_id');
    }

    public function bankTransaction()
    {
        return $this->belongsTo(BankTransaction::class, 'bank_transaction_id');
    }

    public function accountant()
    {
        return $this->belongsTo(Accountant::class, 'accountant_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function expenseAccount()
    {
        return $this->belongsTo(ChartAccount::class, 'expense_account_id');
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

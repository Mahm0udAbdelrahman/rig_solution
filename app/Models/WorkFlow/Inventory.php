<?php

namespace App\Models\WorkFlow;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'job_request_id',
        'item_name',
        'sku',
        'quantity',
        'unit',
        'location',
        'min_quantity',
        'status',
        'note',
        'user_id',
        'sync',
        'updated',
    ];

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class, 'job_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

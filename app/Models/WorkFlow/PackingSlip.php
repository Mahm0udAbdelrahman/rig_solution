<?php

namespace App\Models\WorkFlow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JobRequest;
use App\Models\User;

class PackingSlip extends Model
{
    use HasFactory;
    protected $fillable = [
      'code',
      'job_request_id',
      'user_id',
      'dlocation',
      'po',
      'shppingmethods',
      'orderdate',
      'items',
      'notice',
      'transportation',
      'received',
      'delivered',
      'sync',
      'updated',
    ];

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class, 'job_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

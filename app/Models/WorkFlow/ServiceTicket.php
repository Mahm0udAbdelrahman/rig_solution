<?php

namespace App\Models\WorkFlow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JobRequest;
use App\Models\User;

class ServiceTicket extends Model
{
    use HasFactory;
    protected $fillable = [
      'code',
      'location',
      'start',
      'end',
      'job_request_id',
      'services',
      'user_id',
      'notice',
      'approval_date',
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

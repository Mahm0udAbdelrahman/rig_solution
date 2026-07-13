<?php

namespace App\Models\WorkFlow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JobRequest;
use App\Models\User;

class Qutation extends Model
{
    use HasFactory;
    protected $fillable = [
      'id',
      'job_request_id',
      'code',
      'delivery',
      'location',
      'payment_method',
      'terms',
      'items',
      'subject',
      'user_id',
      'type',
      'sync',
      'updated',
      'creation_date'
    ];

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class, 'job_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_approved()
    {
        return $this->belongsTo(User::class, 'user_id_approved');
    }

}

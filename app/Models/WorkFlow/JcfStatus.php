<?php

namespace App\Models\WorkFlow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\WorkFlow\JobRequest;

class JcfStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_request_id',
        'status',
        'comment',
        'start_at',
        'end_at',
    ];

    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class);
    }
}

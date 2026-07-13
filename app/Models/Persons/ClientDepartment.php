<?php

namespace App\Models\Persons;

use App\Models\Persons\Client;
use App\Models\WorkFlow\JobRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class ClientDepartment extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'password',
        'description',
        'client_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function jobRequests()
    {
        return $this->hasMany(JobRequest::class, 'client_department_id');
    }
}


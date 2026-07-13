<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // protected $guarded;

    protected $fillable = [
        'name',
        'roles',
    ];

    protected $hidden = ['created_at', 'updated_at'];

}

<?php

namespace App\Models\GeneralInfo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'desc',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}//end class

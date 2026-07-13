<?php

namespace App\Models\Persons;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'tel',
        'postion',
    ];

    public function responseable()
    {
        return $this->morphTo();
    }
}

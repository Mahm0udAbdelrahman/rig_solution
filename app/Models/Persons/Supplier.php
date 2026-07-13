<?php

namespace App\Models\Persons;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Persons\ContactPerson;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'email',
        'tel',
        'fax',
        'tax_card',
        'location',
        'url',
        'desc',
        'logo',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function conatctPerson()
    {
        return $this->morphMany(ContactPerson::class, 'responseable');

    }//end conatctPerson()


}//end class

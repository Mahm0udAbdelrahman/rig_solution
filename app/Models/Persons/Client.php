<?php

namespace App\Models\Persons;

use App\Models\WorkFlow\MailCenter;
use App\Models\Persons\ClientDepartment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Persons\ContactPerson;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'password',
        'email',
        'fax',
        'tax_card',
        'tel',
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

    }

    public function departments()
    {
        return $this->hasMany(ClientDepartment::class);
    }

    public function mailMessages()
    {
        return $this->hasMany(MailCenter::class, 'client_id');
    }


}//end class

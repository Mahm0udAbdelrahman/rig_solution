<?php

namespace App\Models\WorkFlow;

use App\Models\Persons\ContactPerson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailCenterRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_center_id',
        'contact_person_id',
        'recipient_type',
        'name',
        'email',
        'delivery_status',
    ];

    public function message()
    {
        return $this->belongsTo(MailCenter::class, 'mail_center_id');
    }

    public function contactPerson()
    {
        return $this->belongsTo(ContactPerson::class, 'contact_person_id');
    }
}

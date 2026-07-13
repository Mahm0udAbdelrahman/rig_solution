<?php

namespace App\Models\WorkFlow;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailCenterEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_center_id',
        'event_type',
        'description',
        'payload_json',
        'created_by',
    ];

    public function message()
    {
        return $this->belongsTo(MailCenter::class, 'mail_center_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

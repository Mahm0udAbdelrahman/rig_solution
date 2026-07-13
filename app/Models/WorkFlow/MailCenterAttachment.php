<?php

namespace App\Models\WorkFlow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailCenterAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_center_id',
        'file_manager_id',
        'disk',
        'path',
        'filename',
        'mime_type',
        'size_bytes',
    ];

    public function message()
    {
        return $this->belongsTo(MailCenter::class, 'mail_center_id');
    }

    public function fileManager()
    {
        return $this->belongsTo(FileManager::class, 'file_manager_id');
    }
}

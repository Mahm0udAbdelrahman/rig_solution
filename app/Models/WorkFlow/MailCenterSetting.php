<?php

namespace App\Models\WorkFlow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailCenterSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'default_mailbox_id',
        'default_signature_html',
        'default_footer_html',
        'require_approval',
        'track_events',
        'show_navbar_mail',
        'show_navbar_notifications',
        'navbar_polling_enabled',
        'navbar_polling_interval_seconds',
        'notify_on_pending_approval',
        'notify_on_approved',
        'notify_on_sent',
        'notify_on_failed',
        'attachment_limit_mb',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'require_approval' => 'boolean',
        'track_events' => 'boolean',
        'show_navbar_mail' => 'boolean',
        'show_navbar_notifications' => 'boolean',
        'navbar_polling_enabled' => 'boolean',
        'notify_on_pending_approval' => 'boolean',
        'notify_on_approved' => 'boolean',
        'notify_on_sent' => 'boolean',
        'notify_on_failed' => 'boolean',
    ];

    public function defaultMailbox()
    {
        return $this->belongsTo(MailCenterMailbox::class, 'default_mailbox_id');
    }
}

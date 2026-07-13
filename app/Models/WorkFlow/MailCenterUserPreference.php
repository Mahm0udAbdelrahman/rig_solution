<?php

namespace App\Models\WorkFlow;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailCenterUserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notify_on_pending_approval',
        'notify_on_approved',
        'notify_on_sent',
        'notify_on_failed',
        'show_pending_approval_queue',
        'show_action_center_items',
    ];

    protected $casts = [
        'notify_on_pending_approval' => 'boolean',
        'notify_on_approved' => 'boolean',
        'notify_on_sent' => 'boolean',
        'notify_on_failed' => 'boolean',
        'show_pending_approval_queue' => 'boolean',
        'show_action_center_items' => 'boolean',
    ];

    public static function defaultAttributes(): array
    {
        return [
            'notify_on_pending_approval' => true,
            'notify_on_approved' => true,
            'notify_on_sent' => true,
            'notify_on_failed' => true,
            'show_pending_approval_queue' => true,
            'show_action_center_items' => true,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function allowsEvent(string $eventType): bool
    {
        $field = match ($eventType) {
            'pending_approval' => 'notify_on_pending_approval',
            'approved' => 'notify_on_approved',
            'sent' => 'notify_on_sent',
            'failed' => 'notify_on_failed',
            default => null,
        };

        if (!$field) {
            return true;
        }

        return (bool) $this->{$field};
    }
}

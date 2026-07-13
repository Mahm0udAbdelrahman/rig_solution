<?php

namespace App\Notifications;

use App\Models\WorkFlow\MailCenter;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MailCenterEventNotification extends Notification
{
    use Queueable;

    public function __construct(
        private MailCenter $message,
        private string $eventType,
        private string $title,
        private string $body
    ) {
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'mail_center_id' => $this->message->id,
            'mailbox_id' => $this->message->mailbox_id,
            'event_type' => $this->eventType,
            'title' => $this->title,
            'message' => $this->body,
            'status' => $this->message->status,
            'code' => $this->message->code,
            'subject' => $this->message->subject,
            'mailbox_name' => optional($this->message->mailbox)->name,
            'client_name' => optional($this->message->client)->name ?: optional(optional($this->message->jobRequest)->client)->name,
            'url' => route('mailCenter.show', $this->message->id),
            'created_at' => now()->toDateTimeString(),
        ];
    }
}

<?php

namespace App\Models\WorkFlow;

use App\Models\Persons\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailCenter extends Model
{
    use HasFactory;

    protected $table = 'mail_center_messages';

    protected $fillable = [
        'code',
        'mailbox_id',
        'template_id',
        'job_request_id',
        'client_id',
        'related_type',
        'related_id',
        'subject',
        'body_html',
        'body_text',
        'to_emails',
        'cc_emails',
        'bcc_emails',
        'status',
        'approval_required',
        'approved_at',
        'approved_by',
        'sent_at',
        'created_by',
        'updated_by',
        'provider_message_id',
        'last_error',
    ];

    protected $casts = [
        'approval_required' => 'boolean',
        'approved_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function mailbox()
    {
        return $this->belongsTo(MailCenterMailbox::class, 'mailbox_id');
    }

    public function template()
    {
        return $this->belongsTo(MailCenterTemplate::class, 'template_id');
    }

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class, 'job_request_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function recipients()
    {
        return $this->hasMany(MailCenterRecipient::class, 'mail_center_id');
    }

    public function attachments()
    {
        return $this->hasMany(MailCenterAttachment::class, 'mail_center_id');
    }

    public function events()
    {
        return $this->hasMany(MailCenterEvent::class, 'mail_center_id')->latest();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeVisibleToUser(Builder $query, ?User $user): Builder
    {
        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->isSuperAdmin()) {
            return $query;
        }

        $mailboxIds = MailCenterMailbox::visibleIdsForUser($user);

        return $query->where(function (Builder $builder) use ($user, $mailboxIds) {
            if (count($mailboxIds) > 0) {
                $builder->whereIn('mailbox_id', $mailboxIds);
            }

            $builder->orWhere('created_by', $user->id)
                ->orWhere('approved_by', $user->id);
        });
    }

    public function isVisibleToUser(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return (int) $this->created_by === (int) $user->id
            || (int) $this->approved_by === (int) $user->id
            || in_array((int) $this->mailbox_id, MailCenterMailbox::visibleIdsForUser($user), true);
    }

    public static function actionableCountForUser(User $user): int
    {
        return static::query()
            ->visibleToUser($user)
            ->where(function (Builder $builder) use ($user) {
                $builder->where(function (Builder $draftBuilder) use ($user) {
                    $draftBuilder->where('created_by', $user->id)
                        ->whereIn('status', ['draft', 'failed']);
                });

                if ($user->can('approve', static::class)) {
                    $builder->orWhere('status', 'pending_approval');
                }
            })
            ->count();
    }
}

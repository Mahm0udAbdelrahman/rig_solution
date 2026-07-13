<?php

namespace App\Support;

use App\Models\User;
use App\Models\WorkFlow\MailCenter;
use App\Models\WorkFlow\MailCenterSetting;
use App\Models\WorkFlow\MailCenterUserPreference;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MailCenterNavbarData
{
    public static function settings(): MailCenterSetting
    {
        return Schema::hasTable('mail_center_settings')
            ? (MailCenterSetting::query()->first() ?: new MailCenterSetting(self::defaultSettings()))
            : new MailCenterSetting(self::defaultSettings());
    }

    public static function userPreference(?User $user): MailCenterUserPreference
    {
        $defaults = MailCenterUserPreference::defaultAttributes();

        if (!$user || !Schema::hasTable('mail_center_user_preferences')) {
            return new MailCenterUserPreference($defaults);
        }

        $loadedPreference = $user->relationLoaded('mailCenterNotificationPreference')
            ? $user->getRelation('mailCenterNotificationPreference')
            : null;

        if ($loadedPreference) {
            return $loadedPreference;
        }

        return MailCenterUserPreference::query()->firstOrNew(
            ['user_id' => $user->id],
            $defaults
        );
    }

    public static function approvalQueueEnabledForUser(User $user, ?MailCenterUserPreference $preference = null): bool
    {
        $preference = $preference ?: self::userPreference($user);

        return $user->can('approve', MailCenter::class)
            && (bool) ($preference->show_pending_approval_queue ?? true);
    }

    public static function actionCenterEnabledForUser(User $user, ?MailCenterUserPreference $preference = null): bool
    {
        $preference = $preference ?: self::userPreference($user);

        return (bool) ($preference->show_action_center_items ?? true);
    }

    public static function actionCenterCountForUser(User $user, ?MailCenterUserPreference $preference = null): int
    {
        if (!self::actionCenterEnabledForUser($user, $preference)) {
            return 0;
        }

        return ActionCenterData::countForUser($user);
    }

    public static function actionCenterItemsForUser(User $user, int $limit = 6, ?MailCenterUserPreference $preference = null): Collection
    {
        if (!self::actionCenterEnabledForUser($user, $preference)) {
            return collect();
        }

        return ActionCenterData::notificationItemsForUser($user, $limit);
    }

    public static function approvalQueueCountForUser(User $user, ?MailCenterUserPreference $preference = null): int
    {
        if (!self::approvalQueueEnabledForUser($user, $preference)) {
            return 0;
        }

        return MailCenter::query()
            ->visibleToUser($user)
            ->where('status', 'pending_approval')
            ->count();
    }

    public static function approvalQueueItemsForUser(User $user, int $limit = 6, ?MailCenterUserPreference $preference = null): Collection
    {
        if (!self::approvalQueueEnabledForUser($user, $preference)) {
            return collect();
        }

        return MailCenter::query()
            ->visibleToUser($user)
            ->with(['mailbox:id,name', 'client:id,name', 'jobRequest:id,code,client_id', 'jobRequest.client:id,name'])
            ->where('status', 'pending_approval')
            ->latest('updated_at')
            ->limit($limit)
            ->get()
            ->map(function (MailCenter $message) {
                $clientName = optional($message->client)->name
                    ?: optional(optional($message->jobRequest)->client)->name
                    ?: 'Client';

                $date = $message->updated_at ?: $message->created_at;

                return [
                    'type' => 'approval_queue',
                    'id' => (string) $message->id,
                    'module' => 'mail_center',
                    'icon' => 'mail',
                    'title' => 'Approval required',
                    'message' => $message->code.' for '.$clientName.' is waiting for manager/admin approval.',
                    'subject' => $message->subject,
                    'status' => $message->status,
                    'url' => route('mailCenter.show', $message->id),
                    'is_unread' => true,
                    'created_at_human' => optional($date)->diffForHumans(),
                    'sort_at' => optional($date)->timestamp ?: 0,
                ];
            })
            ->values();
    }

    public static function notificationItemsForUser(User $user, int $limit = 6): Collection
    {
        return $user->notifications()
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($notification) {
                $date = $notification->created_at;

                return [
                    'type' => 'notification',
                    'id' => (string) $notification->id,
                    'module' => $notification->data['module'] ?? 'notifications',
                    'icon' => $notification->data['icon'] ?? 'notifications',
                    'title' => $notification->data['title'] ?? 'Notification',
                    'message' => $notification->data['message'] ?? '',
                    'subject' => $notification->data['subject'] ?? null,
                    'status' => $notification->data['status'] ?? null,
                    'url' => route('mailCenter.notifications.open', $notification->id),
                    'is_unread' => !$notification->read_at,
                    'created_at_human' => optional($date)->diffForHumans(),
                    'sort_at' => optional($date)->timestamp ?: 0,
                ];
            })
            ->values();
    }

    public static function compose(?User $user): array
    {
        $payload = [
            'navbar_mailcenter_enabled' => false,
            'navbar_mailcenter_messages' => [],
            'navbar_mailcenter_action_count' => 0,
            'navbar_notifications_enabled' => false,
            'navbar_notifications_items' => [],
            'navbar_notifications_unread_count' => 0,
            'navbar_notifications_approval_count' => 0,
            'navbar_notifications_action_center_count' => 0,
            'navbar_notifications_badge_count' => 0,
            'navbar_polling_enabled' => false,
            'navbar_polling_interval_ms' => 60000,
            'navbar_polling_url' => null,
        ];

        if (!$user || !Schema::hasTable('mail_center_messages') || !Schema::hasTable('mail_center_mailboxes')) {
            return $payload;
        }

        $settings = self::settings();
        $preference = self::userPreference($user);

        if ($user->can('viewAny', MailCenter::class) && ($settings->show_navbar_mail ?? true)) {
            $payload['navbar_mailcenter_enabled'] = true;
            $payload['navbar_mailcenter_messages'] = MailCenter::query()
                ->visibleToUser($user)
                ->with(['mailbox:id,name,from_email', 'client:id,name', 'jobRequest:id,code,client_id', 'jobRequest.client:id,name'])
                ->latest()
                ->limit(6)
                ->get()
                ->map(function (MailCenter $message) {
                    return [
                        'id' => $message->id,
                        'code' => $message->code,
                        'status' => $message->status,
                        'subject' => Str::limit((string) $message->subject, 60),
                        'updated_at_human' => optional($message->updated_at)->diffForHumans(),
                        'url' => route('mailCenter.show', $message->id),
                    ];
                })
                ->all();
            $payload['navbar_mailcenter_action_count'] = MailCenter::actionableCountForUser($user);
        }

        if (Schema::hasTable('notifications') && ($settings->show_navbar_notifications ?? true)) {
            $payload['navbar_notifications_enabled'] = true;
            $payload['navbar_notifications_unread_count'] = $user->unreadNotifications()->count();
            $payload['navbar_notifications_approval_count'] = self::approvalQueueCountForUser($user, $preference);
            $payload['navbar_notifications_action_center_count'] = self::actionCenterCountForUser($user, $preference);
            $payload['navbar_notifications_badge_count'] = $payload['navbar_notifications_unread_count']
                + $payload['navbar_notifications_approval_count']
                + $payload['navbar_notifications_action_center_count'];

            $notificationItems = self::notificationItemsForUser($user, 6);
            $approvalQueueItems = self::approvalQueueItemsForUser($user, 6, $preference);
            $actionCenterItems = self::actionCenterItemsForUser($user, 6, $preference);

            $payload['navbar_notifications_items'] = $notificationItems
                ->concat($approvalQueueItems)
                ->concat($actionCenterItems)
                ->sortByDesc('sort_at')
                ->take(6)
                ->values()
                ->all();
        }

        $payload['navbar_polling_enabled'] = (bool) ($settings->navbar_polling_enabled ?? true);
        $payload['navbar_polling_interval_ms'] = max(15, (int) ($settings->navbar_polling_interval_seconds ?? 60)) * 1000;
        $payload['navbar_polling_url'] = route('mailCenter.navbar.snapshot');

        return $payload;
    }

    private static function defaultSettings(): array
    {
        return [
            'show_navbar_mail' => true,
            'show_navbar_notifications' => true,
            'navbar_polling_enabled' => true,
            'navbar_polling_interval_seconds' => 60,
        ];
    }
}

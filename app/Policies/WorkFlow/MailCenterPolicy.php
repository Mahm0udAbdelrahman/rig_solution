<?php

namespace App\Policies\WorkFlow;

use App\Models\User;
use App\Models\WorkFlow\MailCenter;
use Illuminate\Auth\Access\HandlesAuthorization;

class MailCenterPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->hasPermission('mailcenter', 'all');
    }

    public function view(User $user, MailCenter $mailCenter)
    {
        return $user->hasPermission('mailcenter', 'show') && $mailCenter->isVisibleToUser($user);
    }

    public function create(User $user)
    {
        return $user->hasPermission('mailcenter', 'create');
    }

    public function update(User $user, MailCenter $mailCenter)
    {
        return $user->hasPermission('mailcenter', 'edit') && $mailCenter->isVisibleToUser($user);
    }

    public function delete(User $user, MailCenter $mailCenter)
    {
        return $user->hasPermission('mailcenter', 'delete') && $mailCenter->isVisibleToUser($user);
    }

    public function approve(User $user)
    {
        return $user->hasPermission('mailcenter', 'approve');
    }

    public function send(User $user)
    {
        return $user->hasPermission('mailcenter', 'send');
    }

    public function manageTemplates(User $user)
    {
        return $user->hasPermission('mailcenter', 'manage-templates');
    }

    public function manageSettings(User $user)
    {
        return $user->hasPermission('mailcenter', 'manage-settings');
    }

    public function viewLogs(User $user)
    {
        return $user->hasPermission('mailcenter', 'view-logs');
    }
}

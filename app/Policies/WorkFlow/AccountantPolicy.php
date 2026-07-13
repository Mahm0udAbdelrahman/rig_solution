<?php

namespace App\Policies\WorkFlow;

use App\Models\User;
use App\Models\WorkFlow\Accountant;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountantPolicy
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
        return $user->hasPermission('accountant', 'all');
    }

    public function view(User $user, Accountant $accountant)
    {
        return $user->hasPermission('accountant', 'show');
    }

    public function create(User $user)
    {
        return $user->hasPermission('accountant', 'create');
    }

    public function update(User $user, Accountant $accountant)
    {
        return $user->hasPermission('accountant', 'edit');
    }

    public function delete(User $user, Accountant $accountant)
    {
        return $user->hasPermission('accountant', 'delete');
    }

    public function approve(User $user, Accountant $accountant = null)
    {
        return $user->hasPermission('accountant', 'approve') || $user->hasPermission('accountant', 'all');
    }
}

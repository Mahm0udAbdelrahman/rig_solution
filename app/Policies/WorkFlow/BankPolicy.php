<?php

namespace App\Policies\WorkFlow;

use App\Models\User;
use App\Models\WorkFlow\Bank;
use Illuminate\Auth\Access\HandlesAuthorization;

class BankPolicy
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
        return $user->hasPermission('bank', 'all');
    }

    public function view(User $user, Bank $bank)
    {
        return $user->hasPermission('bank', 'show');
    }

    public function create(User $user)
    {
        return $user->hasPermission('bank', 'create');
    }

    public function update(User $user, Bank $bank)
    {
        return $user->hasPermission('bank', 'edit');
    }

    public function delete(User $user, Bank $bank)
    {
        return $user->hasPermission('bank', 'delete');
    }

    public function approve(User $user, Bank $bank = null)
    {
        return $user->hasPermission('bank', 'approve') || $user->hasPermission('bank', 'all');
    }
}

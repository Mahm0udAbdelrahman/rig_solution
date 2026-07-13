<?php

namespace App\Policies\WorkFlow;

use App\Models\User;
use App\Models\WorkFlow\Expense;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
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
        return $user->hasPermission('expense', 'all');
    }

    public function view(User $user, Expense $expense)
    {
        return $user->hasPermission('expense', 'show');
    }

    public function create(User $user)
    {
        return $user->hasPermission('expense', 'create');
    }

    public function update(User $user, Expense $expense)
    {
        return $user->hasPermission('expense', 'edit');
    }

    public function delete(User $user, Expense $expense)
    {
        return $user->hasPermission('expense', 'delete');
    }

    public function approve(User $user, Expense $expense = null)
    {
        return $user->hasPermission('expense', 'approve') || $user->hasPermission('expense', 'all');
    }
}

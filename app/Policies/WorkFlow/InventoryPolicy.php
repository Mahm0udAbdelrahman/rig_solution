<?php

namespace App\Policies\WorkFlow;

use App\Models\User;
use App\Models\WorkFlow\Inventory;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryPolicy
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
        return $user->hasPermission('inventory', 'all');
    }

    public function view(User $user, Inventory $inventory)
    {
        return $user->hasPermission('inventory', 'show');
    }

    public function create(User $user)
    {
        return $user->hasPermission('inventory', 'create');
    }

    public function update(User $user, Inventory $inventory)
    {
        return $user->hasPermission('inventory', 'edit');
    }

    public function delete(User $user, Inventory $inventory)
    {
        return $user->hasPermission('inventory', 'delete');
    }
}

<?php

namespace App\Policies\GeneralInfo;

use App\Models\GeneralInfo\EquipmentControlledList;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EquipmentControlledListPolicy
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
        return $user->hasPermission('equipmentcontrolledlist', 'all') || $user->hasPermission('equipmentcontrolledlist', 'show');
    }

    public function view(User $user, EquipmentControlledList $equipment)
    {
        return $user->hasPermission('equipmentcontrolledlist', 'show') || $user->hasPermission('equipmentcontrolledlist', 'all');
    }

    public function create(User $user)
    {
        return $user->hasPermission('equipmentcontrolledlist', 'create') || $user->hasPermission('equipmentcontrolledlist', 'all');
    }

    public function update(User $user, EquipmentControlledList $equipment)
    {
        return $user->hasPermission('equipmentcontrolledlist', 'edit') || $user->hasPermission('equipmentcontrolledlist', 'all');
    }

    public function delete(User $user, EquipmentControlledList $equipment)
    {
        return $user->hasPermission('equipmentcontrolledlist', 'delete') || $user->hasPermission('equipmentcontrolledlist', 'all');
    }

    public function restore(User $user, EquipmentControlledList $equipment)
    {
        return $user->hasPermission('equipmentcontrolledlist', 'edit') || $user->hasPermission('equipmentcontrolledlist', 'all');
    }

    public function forceDelete(User $user, EquipmentControlledList $equipment)
    {
        return $user->hasPermission('equipmentcontrolledlist', 'delete') || $user->hasPermission('equipmentcontrolledlist', 'all');
    }
}

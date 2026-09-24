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
        return $user->hasPermission('equipment_controlled_list', 'all') || $user->hasPermission('equipment_controlled_list', 'show');
    }

    public function view(User $user, EquipmentControlledList $equipment)
    {
        return $user->hasPermission('equipment_controlled_list', 'show') || $user->hasPermission('equipment_controlled_list', 'all');
    }

    public function create(User $user)
    {
        return $user->hasPermission('equipment_controlled_list', 'create') || $user->hasPermission('equipment_controlled_list', 'all');
    }

    public function update(User $user, EquipmentControlledList $equipment)
    {
        return $user->hasPermission('equipment_controlled_list', 'edit') || $user->hasPermission('equipment_controlled_list', 'all');
    }

    public function delete(User $user, EquipmentControlledList $equipment)
    {
        return $user->hasPermission('equipment_controlled_list', 'delete') || $user->hasPermission('equipment_controlled_list', 'all');
    }

    public function restore(User $user, EquipmentControlledList $equipment)
    {
        return $user->hasPermission('equipment_controlled_list', 'edit') || $user->hasPermission('equipment_controlled_list', 'all');
    }

    public function forceDelete(User $user, EquipmentControlledList $equipment)
    {
        return $user->hasPermission('equipment_controlled_list', 'delete') || $user->hasPermission('equipment_controlled_list', 'all');
    }
}

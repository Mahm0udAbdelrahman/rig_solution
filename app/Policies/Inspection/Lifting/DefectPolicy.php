<?php

namespace App\Policies\Inspection\Lifting;

use App\Models\Inspection\Lifting\Defect;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class DefectPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('defect', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Defect  $defect
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Defect $defect)
    {
        return $user->hasPermission('defect', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('defect', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Defect  $defect
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Defect $defect)
    {
        return $user->hasPermission('defect', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Defect  $defect
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Defect $defect)
    {
        return $user->hasPermission('defect', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Defect  $defect
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Defect $defect)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Defect  $defect
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Defect $defect)
    {
        //
    }
}

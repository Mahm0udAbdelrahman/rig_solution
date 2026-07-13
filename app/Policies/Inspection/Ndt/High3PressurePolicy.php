<?php

namespace App\Policies\Inspection\Ndt;

use App\Models\Inspection\Ndt\High3Pressure;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class High3PressurePolicy
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
        return $user->hasPermission('high3pressure', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\High3Pressure  $high3Pressure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, High3Pressure $high3Pressure)
    {
        return $user->hasPermission('high3pressure', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('high3pressure', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\High3Pressure  $high3Pressure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, High3Pressure $high3Pressure)
    {
        return $user->hasPermission('high3pressure', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\High3Pressure  $high3Pressure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, High3Pressure $high3Pressure)
    {
        return $user->hasPermission('high3pressure', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\High3Pressure  $high3Pressure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, High3Pressure $high3Pressure)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\High3Pressure  $high3Pressure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, High3Pressure $high3Pressure)
    {
        //
    }
}

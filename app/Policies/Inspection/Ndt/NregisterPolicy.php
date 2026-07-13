<?php

namespace App\Policies\Inspection\Ndt;

use App\Models\Inspection\Ndt\Nregister;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NregisterPolicy
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
        return $user->hasPermission('nregister', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Nregister  $nregister
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Nregister $nregister)
    {
        return $user->hasPermission('nregister', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('nregister', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Nregister  $nregister
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Nregister $nregister)
    {
        return $user->hasPermission('nregister', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Nregister  $nregister
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Nregister $nregister)
    {
        return $user->hasPermission('nregister', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Nregister  $nregister
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Nregister $nregister)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Nregister  $nregister
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Nregister $nregister)
    {
        //
    }
}

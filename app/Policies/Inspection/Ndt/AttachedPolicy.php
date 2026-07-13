<?php

namespace App\Policies\Inspection\Ndt;

use App\Models\Inspection\Ndt\Attached;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttachedPolicy
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
        return $user->hasPermission('attached', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attached  $attached
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Attached $attached)
    {
        return $user->hasPermission('attached', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('attached', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attached  $attached
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Attached $attached)
    {
        return $user->hasPermission('attached', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attached  $attached
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Attached $attached)
    {
        return $user->hasPermission('attached', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attached  $attached
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Attached $attached)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attached  $attached
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Attached $attached)
    {
        //
    }
}

<?php

namespace App\Policies\Inspection\Tubular;

use App\Models\Inspection\Tubular\Pbl;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class PblPolicy
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
        return $user->hasPermission('pbl', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param Pbl $pbl
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Pbl $pbl)
    {
        return $user->hasPermission('pbl', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('pbl', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param Pbl $pbl
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Pbl $pbl)
    {
        return $user->hasPermission('pbl', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param Pbl $pbl
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Pbl $pbl)
    {
        return $user->hasPermission('pbl', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param Pbl $pbl
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Pbl $pbl)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param Pbl $subsDimensional
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Pbl $pbl)
    {
        //
    }
}

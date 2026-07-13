<?php

namespace App\Policies\Inspection\Tubular;

use App\Models\Inspection\Tubular\SubsDimensional;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class SubsDimensionalPolicy
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
        return $user->hasPermission('subsdimensional', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param SubsDimensional $subsDimensional
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, SubsDimensional $subsDimensional)
    {
        return $user->hasPermission('subsdimensional', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('subsdimensional', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param SubsDimensional $subsDimensional
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, SubsDimensional $subsDimensional)
    {
        return $user->hasPermission('subsdimensional', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param SubsDimensional $subsDimensional
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, SubsDimensional $subsDimensional)
    {
        return $user->hasPermission('subsdimensional', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param SubsDimensional $subsDimensional
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, SubsDimensional $subsDimensional)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param SubsDimensional $subsDimensional
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, SubsDimensional $subsDimensional)
    {
        //
    }
}

<?php

namespace App\Policies\Inspection\Tubular;

use App\Models\Inspection\Tubular\TubingString;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class TubingStringPolicy
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
        return $user->hasPermission('tubingstring', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param TubingString $tubingString
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TubingString $tubingString)
    {
        return $user->hasPermission('tubingstring', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('tubingstring', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param TubingString $tubingString
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TubingString $tubingString)
    {
        return $user->hasPermission('tubingstring', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param TubingString $tubingString
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TubingString $tubingString)
    {
        return $user->hasPermission('tubingstring', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param TubingString $tubingString
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TubingString $tubingString)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param TubingString $tubingString
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TubingString $tubingString)
    {
        //
    }
}

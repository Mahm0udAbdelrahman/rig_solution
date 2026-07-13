<?php

namespace App\Policies\Inspection\Tubular;

use App\Models\Inspection\Tubular\TubingCasing;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class TubingCasingPolicy
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
        return $user->hasPermission('tubingcasing', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param TubingCasing $tubingCasing
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TubingCasing $tubingCasing)
    {
        return $user->hasPermission('tubingcasing', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('tubingcasing', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param TubingCasing $tubingCasing
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TubingCasing $tubingCasing)
    {
        return $user->hasPermission('tubingcasing', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param TubingCasing $tubingCasing
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TubingCasing $tubingCasing)
    {
        return $user->hasPermission('tubingcasing', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param TubingCasing $tubingCasing
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TubingCasing $tubingCasing)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param TubingCasing $tubingCasing
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TubingCasing $tubingCasing)
    {
        //
    }
}

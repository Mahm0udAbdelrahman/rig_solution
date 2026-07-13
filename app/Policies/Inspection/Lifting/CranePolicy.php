<?php

namespace App\Policies\Inspection\Lifting;

use App\Models\Inspection\Lifting\Crane;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class CranePolicy
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
      return $user->hasPermission('crane', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Crane  $crane
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Crane $crane)
    {
        return $user->hasPermission('crane', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('crane', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Crane  $crane
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Crane $crane)
    {
        return $user->hasPermission('crane', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Crane  $crane
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Crane $crane)
    {
        return $user->hasPermission('crane', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Crane  $crane
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Crane $crane)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Crane  $crane
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Crane $crane)
    {
        //
    }
}

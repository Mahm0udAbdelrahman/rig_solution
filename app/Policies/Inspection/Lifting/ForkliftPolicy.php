<?php

namespace App\Policies\Inspection\Lifting;

use App\Models\Inspection\Lifting\Forklift;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class ForkliftPolicy
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
        return $user->hasPermission('forklift', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Forklift  $forklift
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Forklift $forklift)
    {
        return $user->hasPermission('forklift', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('forklift', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Forklift  $forklift
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Forklift $forklift)
    {
        return $user->hasPermission('forklift', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Forklift  $forklift
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Forklift $forklift)
    {
        return $user->hasPermission('forklift', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Forklift  $forklift
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Forklift $forklift)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Forklift  $forklift
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Forklift $forklift)
    {
        //
    }
}

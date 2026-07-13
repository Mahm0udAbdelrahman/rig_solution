<?php

namespace App\Policies\Inspection\Ndt;

use App\Models\Inspection\Ndt\Visual;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class VisualPolicy
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
        return $user->hasPermission('visual', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Visual  $visual
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Visual $visual)
    {
        return $user->hasPermission('visual', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('visual', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Visual  $visual
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Visual $visual)
    {
        return $user->hasPermission('visual', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Visual  $visual
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Visual $visual)
    {
        return $user->hasPermission('visual', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Visual  $visual
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Visual $visual)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Visual  $visual
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Visual $visual)
    {
        //
    }
}

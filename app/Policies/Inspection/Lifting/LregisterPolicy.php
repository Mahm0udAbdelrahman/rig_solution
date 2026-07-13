<?php

namespace App\Policies\Inspection\Lifting;

use App\Models\Inspection\Lifting\Lregister;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class LregisterPolicy
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
      return $user->hasPermission('lregister', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Lregister  $lregister
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Lregister $lregister)
    {
      return $user->hasPermission('lregister', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
      return $user->hasPermission('lregister', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Lregister  $lregister
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Lregister $lregister)
    {
      return $user->hasPermission('lregister', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Lregister  $lregister
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Lregister $lregister)
    {
      return $user->hasPermission('lregister', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Lregister  $lregister
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Lregister $lregister)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Lregister  $lregister
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Lregister $lregister)
    {
        //
    }
}

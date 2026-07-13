<?php

namespace App\Policies\Inspection\Ndt;

use App\Models\Inspection\Ndt\Mpipt;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class MpiptPolicy
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
      return $user->hasPermission('mpipt', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Mpipt  $mpipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Mpipt $mpipt)
    {
      return $user->hasPermission('mpipt', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
      return $user->hasPermission('mpipt', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Mpipt  $mpipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Mpipt $mpipt)
    {
      return $user->hasPermission('mpipt', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Mpipt  $mpipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Mpipt $mpipt)
    {
      return $user->hasPermission('mpipt', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Mpipt  $mpipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Mpipt $mpipt)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Mpipt  $mpipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Mpipt $mpipt)
    {
        //
    }
}

<?php

namespace App\Policies\Inspection\Ndt;

use App\Models\Inspection\Ndt\TreatingIron;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class TreatingIronPolicy
{
    use HandlesAuthorization;

		public function before(User $user, $ability)
    {
        if ($user->isSuperAdmin())
				{
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
        return $user->hasPermission('treatingiron', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TreatingIron  $treatingIron
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TreatingIron $treatingIron)
    {
        return $user->hasPermission('treatingiron', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('treatingiron', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TreatingIron  $treatingIron
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TreatingIron $treatingIron)
    {
        return $user->hasPermission('treatingiron', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TreatingIron  $treatingIron
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TreatingIron $treatingIron)
    {
        return $user->hasPermission('treatingiron', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TreatingIron  $treatingIron
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TreatingIron $treatingIron)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TreatingIron  $treatingIron
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TreatingIron $treatingIron)
    {
        //
    }
}

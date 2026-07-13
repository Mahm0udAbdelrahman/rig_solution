<?php

namespace App\Policies\GeneralInfo;

use App\Models\GeneralInfo\Specification;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class SpecificationPolicy
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
        return $user->hasPermission('specification', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Specification  $specification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Specification $specification)
    {
        return $user->hasPermission('specification', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('specification', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Specification  $specification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Specification $specification)
    {
        return $user->hasPermission('specification', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Specification  $specification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Specification $specification)
    {
        return $user->hasPermission('specification', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Specification  $specification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Specification $specification)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Specification  $specification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Specification $specification)
    {
        //
    }
}

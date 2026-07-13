<?php

namespace App\Policies\WorkFlow;

use App\Models\WorkFlow\Qutation;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class QutationPolicy
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
        return $user->hasPermission('qutation', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Qutation  $qutation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Qutation $qutation)
    {
        return $user->hasPermission('qutation', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('qutation', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Qutation  $qutation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Qutation $qutation)
    {
        return $user->hasPermission('qutation', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Qutation  $qutation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Qutation $qutation)
    {
        return $user->hasPermission('qutation', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Qutation  $qutation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Qutation $qutation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Qutation  $qutation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Qutation $qutation)
    {
        //
    }
}

<?php

namespace App\Policies\Inspection\Ndt;

use App\Models\Inspection\Ndt\Mpipt;
use App\Models\User;
use App\Models\Inspection\Ndt\Summary;

use Illuminate\Auth\Access\HandlesAuthorization;

class SummaryPolicy
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
        return $user->hasPermission('summary', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  Summary  $summary
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Summary $summary)
    {
        return $user->hasPermission('summary', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('summary', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  Summary  $summary
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Summary $summary)
    {
        return $user->hasPermission('summary', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  Summary  $summary
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Summary $summary)
    {
        return $user->hasPermission('summary', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  Summary  $summary
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Summary $summary)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  Summary  $summary
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Summary $summary)
    {
        //
    }
}

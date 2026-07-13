<?php

namespace App\Policies\Inspection\Tubular;

use App\Models\Inspection\Tubular\DrillPipe;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class DrillPipePolicy
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
        return $user->hasPermission('drillpipe', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param DrillPipe $drillPipe
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, DrillPipe $drillPipe)
    {
        return $user->hasPermission('drillpipe', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('drillpipe', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param DrillPipe $drillPipe
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, DrillPipe $drillPipe)
    {
        return $user->hasPermission('drillpipe', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param DrillPipe $drillPipe
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, DrillPipe $drillPipe)
    {
        return $user->hasPermission('drillpipe', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param DrillPipe $drillPipe
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, DrillPipe $drillPipe)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param DrillPipe $drillPipe
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, DrillPipe $drillPipe)
    {
        //
    }
}

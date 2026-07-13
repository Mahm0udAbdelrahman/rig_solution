<?php

namespace App\Policies\Inspection\Tubular;

use App\Models\Inspection\Tubular\DrillCollar;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class DrillCollarPolicy
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
        return $user->hasPermission('drillcollar', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param DrillCollar $drillCollar
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, DrillCollar $drillCollar)
    {
        return $user->hasPermission('drillcollar', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('drillcollar', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param DrillCollar $drillCollar
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, DrillCollar $drillCollar)
    {
        return $user->hasPermission('drillcollar', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param DrillCollar $drillCollar
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, DrillCollar $drillCollar)
    {
        return $user->hasPermission('drillcollar', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param DrillCollar $drillCollar
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, DrillCollar $drillCollar)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param DrillCollar $drillCollar
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, DrillCollar $drillCollar)
    {
        //
    }
}

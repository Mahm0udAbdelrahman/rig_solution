<?php

namespace App\Policies\Inspection\Tubular;

use App\Models\Inspection\Tubular\HeavyWeightPipe;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class HeavyWeightPipePolicy
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
        return $user->hasPermission('heavyweightpipe', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param HeavyWeightPipe $heavyWeightPipe
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, HeavyWeightPipe $heavyWeightPipe)
    {
        return $user->hasPermission('heavyweightpipe', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('heavyweightpipe', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param HeavyWeightPipe $heavyWeightPipe
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, HeavyWeightPipe $heavyWeightPipe)
    {
        return $user->hasPermission('heavyweightpipe', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param HeavyWeightPipe $heavyWeightPipe
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, HeavyWeightPipe $heavyWeightPipe)
    {
        return $user->hasPermission('heavyweightpipe', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param HeavyWeightPipe $heavyWeightPipe
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, HeavyWeightPipe $heavyWeightPipe)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param HeavyWeightPipe $heavyWeightPipe
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, HeavyWeightPipe $heavyWeightPipe)
    {
        //
    }
}

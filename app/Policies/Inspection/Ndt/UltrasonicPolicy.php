<?php

namespace App\Policies\Inspection\Ndt;

use App\Models\Inspection\Ndt\Ultrasonic;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class UltrasonicPolicy
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
        return $user->hasPermission('ultrasonic', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ultrasonic  $ultrasonic
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Ultrasonic $ultrasonic)
    {
        return $user->hasPermission('ultrasonic', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('ultrasonic', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ultrasonic  $ultrasonic
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Ultrasonic $ultrasonic)
    {
        return $user->hasPermission('ultrasonic', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ultrasonic  $ultrasonic
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Ultrasonic $ultrasonic)
    {
        return $user->hasPermission('ultrasonic', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ultrasonic  $ultrasonic
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Ultrasonic $ultrasonic)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ultrasonic  $ultrasonic
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Ultrasonic $ultrasonic)
    {
        //
    }
}

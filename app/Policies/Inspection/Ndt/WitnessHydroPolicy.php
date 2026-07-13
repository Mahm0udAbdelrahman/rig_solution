<?php

namespace App\Policies\Inspection\Ndt;

use App\Models\Inspection\Ndt\WitnessHydro;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class WitnessHydroPolicy
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
        return $user->hasPermission('witnesshydro', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WitnessHydro  $witnessHydro
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, WitnessHydro $witnessHydro)
    {
        return $user->hasPermission('witnesshydro', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('witnesshydro', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WitnessHydro  $witnessHydro
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, WitnessHydro $witnessHydro)
    {
        return $user->hasPermission('witnesshydro', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WitnessHydro  $witnessHydro
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, WitnessHydro $witnessHydro)
    {
        return $user->hasPermission('witnesshydro', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WitnessHydro  $witnessHydro
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, WitnessHydro $witnessHydro)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WitnessHydro  $witnessHydro
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, WitnessHydro $witnessHydro)
    {
        //
    }
}

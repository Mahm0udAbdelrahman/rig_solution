<?php

namespace App\Policies\WorkFlow;

use App\Models\WorkFlow\PackingSlip;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class PackingSlipPolicy
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
        return $user->hasPermission('packingslip', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PackingSlip  $packingSlip
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PackingSlip $packingSlip)
    {
        return $user->hasPermission('packingslip', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('packingslip', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PackingSlip  $packingSlip
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PackingSlip $packingSlip)
    {
        return $user->hasPermission('packingslip', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PackingSlip  $packingSlip
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PackingSlip $packingSlip)
    {
        return $user->hasPermission('packingslip', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PackingSlip  $packingSlip
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PackingSlip $packingSlip)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PackingSlip  $packingSlip
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PackingSlip $packingSlip)
    {
        //
    }
}

<?php

namespace App\Policies\WorkFlow;

use App\Models\WorkFlow\ServiceTicket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceTicketPolicy
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
        return $user->hasPermission('serviceticket', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTicket  $serviceTicket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ServiceTicket $serviceTicket)
    {
        return $user->hasPermission('serviceticket', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('serviceticket', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTicket  $serviceTicket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ServiceTicket $serviceTicket)
    {
        return $user->hasPermission('serviceticket', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTicket  $serviceTicket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ServiceTicket $serviceTicket)
    {
        return $user->hasPermission('serviceticket', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTicket  $serviceTicket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ServiceTicket $serviceTicket)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceTicket  $serviceTicket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ServiceTicket $serviceTicket)
    {
        //
    }
}

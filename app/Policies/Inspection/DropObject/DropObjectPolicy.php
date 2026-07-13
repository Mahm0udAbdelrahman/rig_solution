<?php

namespace App\Policies\Inspection\DropObject;

use App\Models\Inspection\DropObject\DropObject;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class DropObjectPolicy
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
        return $user->hasPermission('dropobject', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param DropObject $dropObject
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, DropObject $dropObject)
    {
        return $user->hasPermission('dropobject', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('dropobject', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param DropObject $dropObject
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, DropObject $dropObject)
    {
        return $user->hasPermission('dropobject', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param DropObject $dropObject
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, DropObject $dropObject)
    {
        return $user->hasPermission('dropobject', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param DropObject $dropObject
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, DropObject $dropObject)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param DropObject $dropObject
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, DropObject $dropObject)
    {
        //
    }
}


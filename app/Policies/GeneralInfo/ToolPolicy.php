<?php

namespace App\Policies\GeneralInfo;

use App\Models\GeneralInfo\Tool;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class ToolPolicy
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
        return $user->hasPermission('tool', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Tool $tool)
    {
        return $user->hasPermission('tool', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('tool', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Tool $tool)
    {
        return $user->hasPermission('tool', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Tool $tool)
    {
        return $user->hasPermission('tool', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Tool $tool)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Tool $tool)
    {
        //
    }
}

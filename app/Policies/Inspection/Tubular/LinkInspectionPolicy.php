<?php

namespace App\Policies\Inspection\Tubular;

use App\Models\Inspection\Tubular\LinkInspection;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class LinkInspectionPolicy
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
        return $user->hasPermission('linkinspection', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param LinkInspection $linkInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, LinkInspection $linkInspection)
    {
        return $user->hasPermission('linkinspection', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('linkinspection', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param LinkInspection $linkInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, LinkInspection $linkInspection)
    {
        return $user->hasPermission('linkinspection', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param LinkInspection $linkInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, LinkInspection $linkInspection)
    {
        return $user->hasPermission('linkinspection', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param LinkInspection $linkInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, LinkInspection $linkInspection)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param LinkInspection $linkInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, LinkInspection $linkInspection)
    {
        //
    }

    /**
     * Determine whether the user can approve the model.
     *
     * @param  \App\Models\User $user
     * @param LinkInspection $stabilizerInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approve(User $user, LinkInspection $linkInspection)
    {
        return $user->hasPermission('linkinspection', 'approve');
    }
}

<?php

namespace App\Policies\Inspection\Ndt;

use App\Models\Inspection\Ndt\DrawingInspection;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class DrawingInspectionPolicy
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
        return $user->hasPermission('drawinginspection', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param DrawingInspection $drawingInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, DrawingInspection $drawingInspection)
    {
        return $user->hasPermission('drawinginspection', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('drawinginspection', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param DrawingInspection $drawingInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, DrawingInspection $drawingInspection)
    {
        return $user->hasPermission('drawinginspection', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param DrawingInspection $drawingInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, DrawingInspection $drawingInspection)
    {
        return $user->hasPermission('drawinginspection', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param DrawingInspection $drawingInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, DrawingInspection $drawingInspection)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param DrawingInspection $drawingInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, DrawingInspection $drawingInspection)
    {
        //
    }

    /**
     * Determine whether the user can approve the model.
     *
     * @param  \App\Models\User $user
     * @param DrawingInspection $stabilizerInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approve(User $user, DrawingInspection $drawingInspection)
    {
        return $user->hasPermission('drawinginspection', 'approve');
    }
}

<?php

namespace App\Policies\Inspection\Tubular;

use App\Models\Inspection\Tubular\StabilizerInspection;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class StabilizerInspectionPolicy
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
        return $user->hasPermission('stabilizerinspection', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param StabilizerInspection $stabilizerInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, StabilizerInspection $stabilizerInspection)
    {
        return $user->hasPermission('stabilizerinspection', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('stabilizerinspection', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param StabilizerInspection $stabilizerInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, StabilizerInspection $stabilizerInspection)
    {
        return $user->hasPermission('stabilizerinspection', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param StabilizerInspection $stabilizerInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, StabilizerInspection $stabilizerInspection)
    {
        return $user->hasPermission('stabilizerinspection', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param StabilizerInspection $stabilizerInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, StabilizerInspection $stabilizerInspection)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param StabilizerInspection $stabilizerInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, StabilizerInspection $stabilizerInspection)
    {
        //
    }

    /**
     * Determine whether the user can approve the model.
     *
     * @param  \App\Models\User $user
     * @param StabilizerInspection $stabilizerInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approve(User $user, StabilizerInspection $stabilizerInspection)
    {
        return $user->hasPermission('stabilizerinspection', 'approve');
    }
}

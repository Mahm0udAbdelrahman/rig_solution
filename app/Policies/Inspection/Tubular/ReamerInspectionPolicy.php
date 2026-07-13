<?php

namespace App\Policies\Inspection\Tubular;

use App\Models\Inspection\Tubular\ReamerInspection;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class ReamerInspectionPolicy
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
        return $user->hasPermission('reamerinspection', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param ReamerInspection $reamerInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ReamerInspection $reamerInspection)
    {
        return $user->hasPermission('reamerinspection', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('reamerinspection', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param ReamerInspection $reamerInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ReamerInspection $reamerInspection)
    {
        return $user->hasPermission('reamerinspection', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param ReamerInspection $reamerInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ReamerInspection $reamerInspection)
    {
        return $user->hasPermission('reamerinspection', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param ReamerInspection $reamerInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ReamerInspection $reamerInspection)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param ReamerInspection $reamerInspection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ReamerInspection $reamerInspection)
    {
        //
    }

    /**
     * Determine whether the user can approve the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReamerInspection  $mpipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approve(User $user, ReamerInspection $reamerInspection)
    {
        return $user->hasPermission('reamerinspection', 'approve');
    }
}

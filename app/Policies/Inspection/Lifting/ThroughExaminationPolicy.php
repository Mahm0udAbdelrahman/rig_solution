<?php

namespace App\Policies\Inspection\Lifting;

use App\Models\Inspection\Lifting\ThroughExamination;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class ThroughExaminationPolicy
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
        return $user->hasPermission('throughexamination', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ThroughExamination  $throughExamination
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ThroughExamination $throughExamination)
    {
        return $user->hasPermission('throughexamination', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('throughexamination', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ThroughExamination  $throughExamination
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ThroughExamination $throughExamination)
    {
        return $user->hasPermission('throughexamination', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ThroughExamination  $throughExamination
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ThroughExamination $throughExamination)
    {
        return $user->hasPermission('throughexamination', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ThroughExamination  $throughExamination
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ThroughExamination $throughExamination)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ThroughExamination  $throughExamination
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ThroughExamination $throughExamination)
    {
        //
    }
}

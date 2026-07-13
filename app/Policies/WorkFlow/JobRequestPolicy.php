<?php

namespace App\Policies\WorkFlow;

use App\Models\WorkFlow\JobRequest;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class JobRequestPolicy
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
        return $user->hasPermission('jobrequest', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\JobRequest  $jobRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, JobRequest $jobRequest)
    {
        return $user->hasPermission('jobrequest', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('jobrequest', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\JobRequest  $jobRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, JobRequest $jobRequest)
    {
        return $user->hasPermission('jobrequest', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\JobRequest  $jobRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, JobRequest $jobRequest)
    {
        return $user->hasPermission('jobrequest', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\JobRequest  $jobRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, JobRequest $jobRequest)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\JobRequest  $jobRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, JobRequest $jobRequest)
    {
        //
    }
}

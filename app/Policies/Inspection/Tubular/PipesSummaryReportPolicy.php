<?php

namespace App\Policies\Inspection\Tubular;

use App\Models\Inspection\Tubular\PipesSummaryReport;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class PipesSummaryReportPolicy
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
        return $user->hasPermission('pipessummaryreport', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param PipesSummaryReport $pipesSummaryReport
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PipesSummaryReport $pipesSummaryReport)
    {
        return $user->hasPermission('pipessummaryreport', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('pipessummaryreport', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param PipesSummaryReport $pipesSummaryReport
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PipesSummaryReport $pipesSummaryReport)
    {
        return $user->hasPermission('pipessummaryreport', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param PipesSummaryReport $pipesSummaryReport
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PipesSummaryReport $pipesSummaryReport)
    {
        return $user->hasPermission('pipessummaryreport', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param PipesSummaryReport $pipesSummaryReport
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PipesSummaryReport $pipesSummaryReport)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param PipesSummaryReport $pipesSummaryReport
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PipesSummaryReport $pipesSummaryReport)
    {
        //
    }
}

<?php

namespace App\Policies\Inspection\Calibration;

use App\Models\Inspection\Calibration\CalibrationPressureGauge as Calibration;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class CalibrationPressureGaugePolicy
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
        return $user->hasPermission('calibrationpressuregauge', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param Calibration $calibration
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Calibration $calibration)
    {
        return $user->hasPermission('calibrationpressuregauge', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('calibrationpressuregauge', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param Calibration $calibration
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Calibration $calibration)
    {
        return $user->hasPermission('calibrationpressuregauge', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param Calibration $calibration
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Calibration $calibration)
    {
        return $user->hasPermission('calibrationpressuregauge', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param Calibration $calibration
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Calibration $calibration)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param Calibration $calibration
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Calibration $calibration)
    {
        //
    }
}


<?php

namespace App\Policies\Inspection\Lifting;

use App\Models\Inspection\Lifting\OverheadCrane;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OverheadCranePolicy
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
        return $user->hasPermission('overheadcrane', 'all');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OverheadCrane  $overheadCrane
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, OverheadCrane $overheadCrane)
    {
      return $user->hasPermission('overheadcrane', 'show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
      return $user->hasPermission('overheadcrane', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OverheadCrane  $overheadCrane
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, OverheadCrane $overheadCrane)
    {
      return $user->hasPermission('overheadcrane', 'edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OverheadCrane  $overheadCrane
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, OverheadCrane $overheadCrane)
    {
      return $user->hasPermission('overheadcrane', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OverheadCrane  $overheadCrane
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, OverheadCrane $overheadCrane)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OverheadCrane  $overheadCrane
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, OverheadCrane $overheadCrane)
    {
        //
    }
}

<?php

namespace App\Policies\WorkFlow;

use App\Models\User;
use App\Models\WorkFlow\Payment;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->hasPermission('payment', 'all');
    }

    public function view(User $user, Payment $payment)
    {
        return $user->hasPermission('payment', 'show');
    }

    public function create(User $user)
    {
        return $user->hasPermission('payment', 'create');
    }

    public function update(User $user, Payment $payment)
    {
        return $user->hasPermission('payment', 'edit');
    }

    public function delete(User $user, Payment $payment)
    {
        return $user->hasPermission('payment', 'delete');
    }
}

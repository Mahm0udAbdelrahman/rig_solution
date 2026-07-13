<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Auth;

class Authenticate extends Middleware
{


    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            return null;
        }

        if ($request->is('customer') || $request->is('customer/*') || $request->is('department') || $request->is('department/*')) {
            return route('customer.login');
        }

        return route('login');

    }//end redirectTo()


}//end class

<?php

namespace App\Http\Controllers\Auth\Customer;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// use App\Models\Organization\Employee;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::CUSTOMER;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:customer')->except('logout');
        $this->middleware('guest:clientDepartments')->except('logout');
    }

    public function showCustomerLogin()
    {
      return view('auth.customer.login');
    }

    public function attemptLogin(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $identifier = trim((string) $validated['code']);
        $password = (string) $validated['password'];

        if (Auth::guard('customer')->attempt(['code' => $identifier, 'password' => $password])) {
            Auth::guard('clientDepartments')->logout();
            $request->session()->regenerate();
            return redirect()->intended(route('customer.overview'));
        }

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            if (Auth::guard('clientDepartments')->attempt(['email' => $identifier, 'password' => $password])) {
                Auth::guard('customer')->logout();
                $request->session()->regenerate();
                return redirect()->intended(route('department.overview'));
            }
        }

        return back()
            ->withErrors(['code' => 'Invalid client/department credentials.'])
            ->onlyInput('code');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        Auth::guard('clientDepartments')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }

    public function username()
    {
        return 'code';
    }

}

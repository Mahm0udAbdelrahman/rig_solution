<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;

// Illuminate\Support
use Illuminate\Support\Facades\Auth;

// Illuminate\Foundation
use Illuminate\Foundation\Auth\AuthenticatesUsers;

//App\Providers\
use App\Providers\RouteServiceProvider;

// App\Models
use App\Models\Organization\Employee;

//Other
use DB;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function attemptLogin(Request $request)
    {
        $employee = Employee::select('id')->where('email', $request->employee_id)->first();

        if ($employee != NULL) {
            $request->merge(['employee_id' => $employee->id]);
        } else {
            $request->merge(['employee_id' => NULL]);
        }

        $credentials = $request->only('employee_id', 'password');

        return Auth::attempt($credentials, $request->filled('remember'));
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user && $user->is_suspended && !$user->isSuperAdmin()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withInput($request->only('employee_id'))
                ->withErrors(['employee_id' => 'تم إيقاف حسابك مؤقتاً']);
        }

        DB::table('users')->where('id', $user->id)->update([
            'is_active' => 1,
            'last_active_at' => \Carbon\Carbon::now(),
        ]);

        return redirect()->intended($this->redirectPath());
    }

    public function username()
    {
        return 'employee_id';
    }

    public function logout(Request $request)
    {
        DB::table('users')->where('id', Auth::id())->update([
            'is_active' => null,
            'last_active_at' => \Carbon\Carbon::now(),
        ]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

}

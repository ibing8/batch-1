<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

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
        $this->middleware('guest:merchant')->except('logout');
    }

    public function showMerchantLoginForm()
    {
        return view('auth.login', ['url' => 'merchant']);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if(Auth::guard('merchant')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember_token'))) {
            return redirect()->intended('/merchant');
        }

        if(Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember_token'))) {
            return redirect()->intended('/home');
        }

        return back()->withInput($request->only('email', 'remember'));
    }
}

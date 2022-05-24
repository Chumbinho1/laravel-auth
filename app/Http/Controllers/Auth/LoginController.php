<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

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

    public function loginSocial(Request $request)
    {
        $this->validate($request, [
            'social_type' => ['required', 'in:google,github']
        ]);
        $socialType = $request->get('social_type');
        Socialite::driver($socialType)->redirect();
    }

    public function redirectTo()
    {
        return Auth::user()->role == User::ROLE_ADMIN ? '/admin/home' : 'home';
    }

    protected function credentials(Request $request)
    {
        $data = $request->only($this->username(), 'password');
        $usernameKey = $this->usernameKey();
        if ($usernameKey != $this->username()) {
            $data[$this->usernameKey()] = $data[$this->username()];
            unset($data[$this->username()]);
        }
        return $data;
    }

    protected function usernameKey()
    {
        $email = Request::get('email');
        $validator = Validator::make([
            'email' => $email
        ], [
            'email' => 'cpf'
        ]);

        if (!$validator->fails()) {
            return 'cpf';
        }

        if (!is_numeric($email)) {
            return 'phone';
        }

        return 'email';
    }
}

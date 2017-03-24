<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Socialite;
use App\User;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('github')->stateless()->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('github')->stateless()->user();

        $user = User::create([
            'name'      => $user->name,
            'avatar'    => $user->avatar,
            'login'     => $user->nickname,
            'email'     => $user->email,
            'bio'       => $user->user['bio'],
            'token'     => $user->token,
            'refresh_token'   => $user->refreshToken,
        ]);

        return response()->json([
            'name'      => $user->name,
            'avatar'    => $user->avatar,
            'login'     => $user->nickname,
            'email'     => $user->email,
            'bio'       => $user->user['bio'],
        ]);
        
    }
}
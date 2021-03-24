<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Auth\Socialite\Manager;
use App\Enumerators\Socialite as SocialiteDrivers;
use Illuminate\Http\RedirectResponse as IlluminateRedirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthController extends Controller
{
    public function login(): RedirectResponse
    {
        return Socialite::driver(SocialiteDrivers::SURFCONEXT)
            ->redirect();
    }

    public function surfconext(Manager $manager): IlluminateRedirect
    {
        $user = $manager->user(
            Socialite::driver(SocialiteDrivers::SURFCONEXT)->user()
        );

        Auth::login($user);

        return redirect()->intended();
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        return redirect()->intended($request->get('logout_uri'));
    }
}

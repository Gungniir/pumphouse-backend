<?php

namespace App\Http\Controllers;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Testing\Fluent\Concerns\Has;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
     * @return string
     */
    public function authenticate(Request $request): string
    {
        $credentials = $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return sprintf('You are successfully authenticated as "%s"', Auth::user()->login);
        }

        return 'Bad login or password';
    }

    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
     * @return string
     */
    public function logout(Request $request): string
    {
        if (Auth::guest()) {
            return 'You are not authenticated';
        }

        Auth::logout();
        $request->session()->regenerate();

        return 'Ok';
    }
}

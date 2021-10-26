<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

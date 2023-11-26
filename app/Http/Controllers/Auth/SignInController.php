<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SignInController extends Controller
{
    public function signin()
    {
        return view('pages.auth.sign-in');
    }

    public function signinaction(Request $request)
    {
        $credentials = $request->only('email', 'password', 'role_id');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role_id == 1) {
                return redirect()->intended('/admin/complaint');
            } elseif (Auth::user()->role_id == 2) {
                return redirect()->intended('/user/complaint');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function signout()
    {
        Auth::logout();
        return redirect()->route('signin');
    }
}

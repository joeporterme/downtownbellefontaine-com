<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Fire the reset regardless, but always return the same neutral message
        // so the response can't be used to enumerate which emails have accounts.
        Password::sendResetLink($request->only('email'));

        return back()->with('status', 'If an account exists for that email, a password reset link is on its way.');
    }
}

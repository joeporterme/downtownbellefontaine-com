<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /** "Please verify your email" notice page. */
    public function notice(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('business.dashboard')
            : view('auth.verify-email');
    }

    /** Signed verification link handler. */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill(); // marks verified + fires Verified event (idempotent)

        return redirect()->route('business.dashboard')
            ->with('success', 'Your email is verified — welcome aboard!');
    }

    /** Resend the verification email. */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('business.dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'A fresh verification link has been sent to your email.');
    }
}

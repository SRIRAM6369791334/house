<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthPageController extends Controller
{
    public function register()
    {
        return view('pages.register');
    }

    public function login()
    {
        return view('pages.login');
    }

    public function forgotPassword()
    {
        return view('pages.forgot-password');
    }

    public function verifyOtp(Request $request)
    {
        $email = $request->session()->get('password_reset_email');
        if (! $email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Please request a new OTP.']);
        }

        return view('pages.verify-password-otp', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $email = $request->session()->get('password_reset_email');
        if (! $email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Please request a new OTP.']);
        }

        return view('pages.reset-password', compact('email'));
    }
}

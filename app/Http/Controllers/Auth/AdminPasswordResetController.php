<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AdminPasswordResetController extends Controller
{
    // Forgot Password form dikhayein
    public function create()
    {
        return view('auth.admin-forgot-password');
    }

    // Email address le kar reset link bhejein
    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withErrors(['email' => __($status)]);
    }

    // Password reset form dikhayein
    public function edit(Request $request, $token)
    {
        return view('auth.admin-reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Naya password set karein
    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::RESET_SUCCESS
                    ? redirect()->route('admin.login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
}

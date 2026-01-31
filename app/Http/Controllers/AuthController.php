<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Invalid credentials. Please try again.'])
            ->withInput($request->only('email'));
    }

    /**
     * Show the registration form
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // All new registrations get 'user' role by default
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Force user role for all new registrations
        ]);

        // Don't auto-login, redirect to login page
        return redirect()->route('login')
            ->with('success', 'Registration successful! Please log in with your credentials.');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset code
     */
    public function sendResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Delete any existing codes for this email
        \DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->delete();

        // Store new code (expires in 15 minutes)
        \DB::table('password_reset_codes')->insert([
            'email' => $request->email,
            'code' => $code,
            'expires_at' => now()->addMinutes(15),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send email with code
        $user = User::where('email', $request->email)->first();
        $user->notify(new \App\Notifications\PasswordResetCodeNotification($code));

        return redirect()->route('password.verify.show')
            ->with('email', $request->email)
            ->with('success', 'A verification code has been sent to your email.');
    }

    /**
     * Show reset password form
     */
    public function showVerifyCode()
    {
        if (!session('email')) {
            return redirect()->route('password.forgot')
                ->with('error', 'Please request a password reset first.');
        }

        return view('auth.verify-code');
    }

    /**
     * Verify the reset code
     */
    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        // Verify code
        $resetCode = \DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$resetCode) {
            return redirect()->back()
                ->withErrors(['code' => 'Invalid or expired verification code. Please try again.'])
                ->withInput($request->only('email'));
        }

        // Code is valid, proceed to password reset
        return redirect()->route('password.reset.show')
            ->with('email', $request->email)
            ->with('code', $request->code)
            ->with('code_verified', true)
            ->with('success', 'Code verified! Please enter your new password.');
    }

    /**
     * Show reset password form (only after code verification)
     */
    public function showResetPassword()
    {
        if (!session('code_verified')) {
            return redirect()->route('password.verify.show')
                ->with('error', 'Please verify your code first.');
        }

        return view('auth.reset-password');
    }

    /**
     * Reset password with code
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email', 'code'));
        }

        // Verify code
        $resetCode = \DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$resetCode) {
            return redirect()->back()
                ->withErrors(['code' => 'Invalid or expired verification code.'])
                ->withInput($request->only('email', 'code'));
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete used code
        \DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->delete();

        return redirect()->route('login')
            ->with('success', 'Password has been reset successfully. You can now login.');
    }
}

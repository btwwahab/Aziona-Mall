<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Show User Login Form
     */
    public function showUserLoginForm() {
        return view('frontend.auth.login-register');
    }

    /**
     * Handle User Login (Customers Only)
     */
    public function userLogin(LoginRequest $request) 
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('home')->with('success', 'Login Successfully');
        }

        return back()->withErrors(['email' => 'Invalid Credentials.'])->withInput();
    }

    /**
     * Handle User Registration (Customers Only)
     */
    public function userRegister(RegisterRequest $request) 
    {
        
        $user = User::create([
            'name' => $request->register_name,
            'email' => $request->register_email,
            'password' => Hash::make($request->register_password),
            'role' => 'user' // Ensure all registered users are "user" by default
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registration Successful!');
    }

    /**
     * Handle User Logout
     */
    public function userLogout() 
    {
        Auth::logout();
        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }

    // ================== ADMIN AUTH START HERE ==================

    /**
     * Show Admin Login Form
     */
    public function showAdminLoginForm() {

        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.admin-auth.login');
    }

    /**
     * Handle Admin Login
     */
    public function adminLogin(LoginRequest $request) 
{
    $credentials = $request->only('email', 'password');

    $admin = User::where('email', $credentials['email'])->where('role', 'admin')->first();

    if ($admin && Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
        return redirect()->route('admin.dashboard')->with('success', 'Welcome to the Admin Panel');
    }

    return back()->withErrors(['email' => 'Invalid Credentials or not an admin.'])->withInput();
}

public function adminLogout()
{
    Auth::guard('admin')->logout();
    return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
}
    
}

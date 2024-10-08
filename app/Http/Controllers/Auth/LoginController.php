<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {

        return view('auth.login', [
            'title' => 'Welcome to Crops Bontoc'
        ]); // Assumes you have a 'login.blade.php' in 'resources/views/auth/'
    }

    // Handle the login request
    public function login(Request $request)
    {
        // Validate the login form inputs
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Attempt to authenticate the user using the provided credentials
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {

            $request->session()->regenerate();
            // Authentication passed, check user role and redirect accordingly
            $user = Auth::user();
            switch ($user->role->role_name) { // Assuming 'name' is the column in the roles table
                case 'Admin':
                    return redirect()->route('admin.dashboard');
                case 'User':
                    return redirect()->route('user.dashboard');
                case 'Aggregator':
                    return redirect()->route('aggregator.dashboard');
                default:
                    return redirect()->route('login')->withErrors(['email' => 'Unauthorized access.']);
            }
        }

        return redirect()->intended('dashboard');
        // Authentication failed, redirect back to login with error message
        return redirect()->route('login')->withErrors(['email' => 'Invalid credentials provided.']);
    }

    // Logout the user
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
    
}

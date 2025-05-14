<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'max:55'],
            'middle_name' => ['nullable', 'regex:/^[A-Za-z\s]*$/', 'max:55'],
            'last_name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'max:55'],
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'terms' => 'required|accepted'
        ], [
            'first_name.regex' => 'First name can only contain letters and spaces',
            'middle_name.regex' => 'Middle name can only contain letters and spaces',
            'last_name.regex' => 'Last name can only contain letters and spaces',
            'terms.required' => 'You must accept the Terms and Conditions'
        ]);

        try {
            $role_id = $request->email === 'mccoldplay123@gmail.com' ? 1 : 3;
            
            $user = User::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $role_id,
            ]);

            auth()->login($user);
            return redirect()->route('home')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['email' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }
}
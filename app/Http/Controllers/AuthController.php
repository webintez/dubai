<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show registration page (Free Registration)
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('user.bookings');
        }
        $supportPhone = Setting::get('support_phone', '+971 4 301 7777');
        return view('auth.register', compact('supportPhone'));
    }

    /**
     * Handle user registration (100% Free)
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:25',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        Auth::login($user);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Account registered successfully! Welcome.',
                'redirect' => route('user.bookings')
            ]);
        }

        return redirect()->route('user.bookings')->with('success', 'Registration successful! Welcome to your Dubai VIP portal.');
    }

    /**
     * Show login page
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('user.bookings');
        }
        $supportPhone = Setting::get('support_phone', '+971 4 301 7777');
        return view('auth.login', compact('supportPhone'));
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged in successfully!',
                    'redirect' => redirect()->intended(route('user.bookings'))->getTargetUrl()
                ]);
            }

            return redirect()->intended(route('user.bookings'))->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'errors' => ['email' => ['The provided credentials do not match our records.']]
            ], 422);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }

    /**
     * User's Booked Meetings Dashboard
     */
    public function myBookings()
    {
        $user = Auth::user();
        $bookings = $user->bookings()->with('meeting')->orderBy('created_at', 'desc')->get();
        $supportPhone = Setting::get('support_phone', '+971 4 301 7777');

        return view('user.my-bookings', compact('bookings', 'user', 'supportPhone'));
    }
}

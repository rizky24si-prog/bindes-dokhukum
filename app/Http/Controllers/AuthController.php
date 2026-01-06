<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; // TAMBAHKAN INI

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Cek jika user sudah login, redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('pages.admin.auth.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Gunakan $request->validate() yang lebih sederhana
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Set session last activity time
            Session::put('last_activity', time());
            
            // Regenerate session ID untuk keamanan
            $request->session()->regenerateToken();
            
            // Log aktivitas login
            Log::info('User logged in: ' . Auth::user()->email . ' - ' . Auth::user()->name);

            return redirect()->intended('dashboard')
                ->with('success', 'Login berhasil! Selamat datang ' . Auth::user()->name);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function logout(Request $request)
    {
        // Log aktivitas logout
        if (Auth::check()) {
            Log::info('User logged out: ' . Auth::user()->email . ' - ' . Auth::user()->name);
        }
        
        // Clear semua session data
        Auth::logout();
        Session::flush();
        
        // Invalidate session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        // Clear cache headers
        return redirect()->route('login.index')
            ->withHeaders([
                'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT',
            ])
            ->with('success', 'Logout berhasil!');
    }
}
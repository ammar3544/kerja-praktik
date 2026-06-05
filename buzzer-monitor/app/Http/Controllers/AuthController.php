<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses autentikasi
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Diarahkan ke Monitoring Tasks jika sukses
            return redirect()->intended('/tasks');
        }

        // Jika gagal, balik ke login dengan pesan error
        return back()->with('loginError', 'Login failed! Please check your credentials.');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

   public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            ]);

            \Illuminate\Support\Facades\Auth::login($user);
            return redirect('/dashboard');

        } catch (\Exception $e) {
            // Ini akan menghentikan aplikasi dan menunjukkan error database yang sebenarnya
            dd($e->getMessage()); 
        }
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
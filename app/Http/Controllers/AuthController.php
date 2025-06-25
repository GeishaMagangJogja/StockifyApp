<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    public function register(Request $request)
    {
        $rules = [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ];

        if ($request->has('role')) {
            $rules['role'] = 'required|in:Admin,Staff Gudang,Manajer Gudang';
        }

        $validated = $request->validate($rules);

        if (!isset($validated['role'])) {
            $validated['role'] = 'Staff Gudang';
        }

        // Buat user via service
        $user = $this->auth->register($validated);

        // Login langsung pakai session
        Auth::login($user);
        $request->session()->regenerate();

        // Redirect sesuai role
        return $this->redirectByRole($user);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            return $this->redirectByRole($user);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    protected function redirectByRole($user)
    {
        return match ($user->role) {
            'Admin'          => redirect()->intended('/admin/dashboard'),
            'Manajer Gudang' => redirect()->intended('/manajergudang/dashboard'),
            'Staff Gudang'   => redirect()->intended('/staff/dashboard'),
            default          => redirect()->intended('/'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

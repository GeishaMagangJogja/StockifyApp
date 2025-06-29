<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $rules = [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ];

        // Validasi role jika dikirim
        if ($request->has('role')) {
            $rules['role'] = 'required|in:Admin,Staff Gudang,Manajer Gudang';
        }

        $validated = $request->validate($rules);

        // Default role: Staff Gudang
        if (!isset($validated['role'])) {
            $validated['role'] = 'Staff Gudang';
        }

        try {
            // Buat user
            $user = $this->auth->register($validated);

            // Auto login setelah register
            $loginData = [
                'email'    => $validated['email'],
                'password' => $validated['password'],
            ];

            $token = $this->auth->login($loginData);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Akun Anda telah dibuat.',
                'data' => [
                    'user' => [
                        'id'         => $user->id,
                        'name'       => $user->name,
                        'email'      => $user->email,
                        'role'       => $user->role,
                        'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                    ],
                    'access_token' => $token,
                    'token_type'   => 'Bearer',
                    'expires_in'   => config('jwt.ttl') * 60,
                    'redirect_to'  => $this->getRedirectUrlByRole($user->role),
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registrasi gagal. Silakan coba lagi.',
                'error'   => 'REGISTRATION_FAILED',
            ], 500);
        }
    }

    /**
     * Handle user login
     */
   public function login(Request $request)
{
    $validated = $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
        'remember' => 'boolean',
    ]);

    // Ambil user dari database
    $user = User::where('email', $validated['email'])->first();

    // Jika user tidak ditemukan atau password salah
    if (!$user || !Hash::check($validated['password'], $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah.',
        ], 401);
    }

    // Login user
    Auth::login($user, $validated['remember'] ?? false);

    // Regenerate session
    $request->session()->regenerate();

    // Update last login (opsional)
    $user->update(['last_login_at' => now()]);

    // Berhasil login
    return response()->json([
        'success' => true,
        'message' => 'Login berhasil! Selamat datang, ' . $user->name,
        'data' => [
            'user' => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'role'       => $user->role,
                'last_login' => now()->format('Y-m-d H:i:s'),
            ],
            'redirect_to' => $this->getRedirectUrlByRole($user->role),
        ]
    ]);
}


    /**
     * Alternative simple login method (fallback jika AuthService bermasalah)
     */
    public function simpleLogin(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
            'remember' => 'boolean'
        ]);

        // Manual authentication
        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login gagal! Email atau password salah.',
                'error'   => 'INVALID_CREDENTIALS',
            ], 401);
        }

        // Login user
        Auth::login($user, $validated['remember'] ?? false);

        // Update last login
        $user->update(['last_login_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil! Selamat datang, ' . $user->name,
            'data' => [
                'user' => [
                    'id'         => $user->id,
                    'name'       => $user->name,
                    'email'      => $user->email,
                    'role'       => $user->role,
                    'last_login' => now()->format('Y-m-d H:i:s'),
                ],
                'redirect_to'  => $this->getRedirectUrlByRole($user->role),
            ]
        ], 200);
    }

    /**
     * Get redirect path based on user role
     */
    protected function getRedirectUrlByRole($role)
    {
        return match ($role) {
            'Admin'          => '/admin/dashboard',
            'Manajer Gudang' => '/manajergudang/dashboard',
            'Staff Gudang'   => '/staff/dashboard',
            default          => '/',
        };
    }

    /**
     * Logout user
     */

    /**
     * Get current authenticated user info
     */
    public function me(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terautentikasi',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id'         => $user->id,
                    'name'       => $user->name,
                    'email'      => $user->email,
                    'role'       => $user->role,
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                ]
            ]
        ]);
    }

    public function logout(Request $request)
    {
        // Untuk API (token-based, Sanctum/JWT)
        if ($request->expectsJson() || $request->is('api/*')) {
            // Sanctum: hapus token user
            if ($request->user()) {
                $request->user()->currentAccessToken()?->delete();
            }
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil.'
            ]);
        }

        // Untuk web (session-based)
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Logout berhasil.');
    }

    /**
     * Check if user has specific role
     */
    public function checkRole($role)
    {
        $user = Auth::user();
        return $user && $user->role === $role;
    }
}

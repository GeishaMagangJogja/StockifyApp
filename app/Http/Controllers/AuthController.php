<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Enums\UserRole;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    public function register(Request $request)
    {
        // Validasi dasar tanpa role dulu
        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ];

        // Tambahkan validasi role hanya jika ada di request
        if ($request->has('role')) {
            $rules['role'] = 'required|in:Admin,Staff Gudang,Manajer Gudang';
        }

        $validated = $request->validate($rules);

        // Set default role jika tidak ada role yang dikirim
        if (!isset($validated['role'])) {
            $validated['role'] = 'Staff Gudang';
        }

        $user = $this->auth->register($validated);

        // Generate token untuk user yang baru register
        $token = $this->auth->login([
            'email' => $validated['email'],
            'password' => $validated['password']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil! Akun Anda telah dibuat dan login otomatis.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'created_at' => $user->created_at->format('Y-m-d H:i:s')
                ],
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => config('jwt.ttl') * 60 // dalam detik
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $token = $this->auth->login($validated);

            // Ambil data user berdasarkan email dari request
            $user = \App\Models\User::where('email', $validated['email'])->first();

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil! Selamat datang kembali.',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'last_login' => now()->format('Y-m-d H:i:s')
                    ],
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => config('jwt.ttl') * 60, // dalam detik
                    'instructions' => 'Gunakan token ini di header: Authorization: Bearer {token}'
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login gagal! Kredensial yang Anda masukkan tidak valid.',
                'error' => 'Email atau password salah',
                'code' => 'INVALID_CREDENTIALS'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server',
                'error' => 'Internal server error',
                'code' => 'SERVER_ERROR'
            ], 500);
        }
    }
}

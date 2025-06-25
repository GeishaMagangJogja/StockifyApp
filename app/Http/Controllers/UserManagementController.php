<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Get all users (Admin only)
     */
    public function index(Request $request)
    {
        try {
            $query = User::query();

            // Filter by role if provided
            if ($request->has('role') && !empty($request->role)) {
                $query->where('role', $request->role);
            }

            // Search by name or email
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 10);
            $users = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data users berhasil diambil.',
                'data' => [
                    'users' => $users->items(),
                    'pagination' => [
                        'current_page' => $users->currentPage(),
                        'total_pages' => $users->lastPage(),
                        'per_page' => $users->perPage(),
                        'total_items' => $users->total(),
                        'has_next' => $users->hasMorePages(),
                        'has_prev' => $users->currentPage() > 1
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data users.',
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get single user details
     */
    public function show($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.',
                    'code' => 'USER_NOT_FOUND'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail user berhasil diambil.',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $user->updated_at->format('Y-m-d H:i:s')
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail user.',
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Update user role (Admin only)
     */
    public function updateRole(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.',
                    'code' => 'USER_NOT_FOUND'
                ], 404);
            }

            // Validasi role baru
            $validated = $request->validate([
                'role' => 'required|in:Admin,Staff Gudang,Manajer Gudang'
            ]);

            // Cek jika admin mencoba mengubah role dirinya sendiri
            if ($user->id === Auth::id() && $validated['role'] !== 'Admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat mengubah role diri sendiri dari Admin.',
                    'code' => 'CANNOT_CHANGE_OWN_ADMIN_ROLE'
                ], 400);
            }

            $oldRole = $user->role;
            $user->role = $validated['role'];
            $user->save();

            return response()->json([
                'success' => true,
                'message' => "Role user {$user->name} berhasil diubah dari {$oldRole} menjadi {$validated['role']}.",
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'updated_at' => $user->updated_at->format('Y-m-d H:i:s')
                    ],
                    'changes' => [
                        'field' => 'role',
                        'old_value' => $oldRole,
                        'new_value' => $validated['role']
                    ]
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah role user.',
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Update user info (name, email)
     */
    public function updateInfo(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.',
                    'code' => 'USER_NOT_FOUND'
                ], 404);
            }

            // Validasi data
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:100',
                'email' => [
                    'sometimes',
                    'required',
                    'email',
                    Rule::unique('users')->ignore($user->id)
                ]
            ]);

            $changes = [];
            $oldData = [
                'name' => $user->name,
                'email' => $user->email
            ];

            // Update name jika ada
            if (isset($validated['name']) && $validated['name'] !== $user->name) {
                $changes['name'] = [
                    'old' => $user->name,
                    'new' => $validated['name']
                ];
                $user->name = $validated['name'];
            }

            // Update email jika ada
            if (isset($validated['email']) && $validated['email'] !== $user->email) {
                $changes['email'] = [
                    'old' => $user->email,
                    'new' => $validated['email']
                ];
                $user->email = $validated['email'];
            }

            if (empty($changes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada perubahan data yang dikirim.',
                    'code' => 'NO_CHANGES'
                ], 400);
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => "Data user {$user->name} berhasil diperbarui.",
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'updated_at' => $user->updated_at->format('Y-m-d H:i:s')
                    ],
                    'changes' => $changes
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data user.',
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Delete user (Admin only)
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.',
                    'code' => 'USER_NOT_FOUND'
                ], 404);
            }

            // Cek jika admin mencoba menghapus dirinya sendiri
            if ($user->id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menghapus akun diri sendiri.',
                    'code' => 'CANNOT_DELETE_SELF'
                ], 400);
            }

            $userName = $user->name;
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => "User {$userName} berhasil dihapus.",
                'data' => [
                    'deleted_user' => [
                        'id' => $id,
                        'name' => $userName,
                        'deleted_at' => now()->format('Y-m-d H:i:s')
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user.',
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get users statistics
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'role_distribution' => [
                    'Admin' => User::where('role', 'Admin')->count(),
                    'Manajer Gudang' => User::where('role', 'Manajer Gudang')->count(),
                    'Staff Gudang' => User::where('role', 'Staff Gudang')->count()
                ],
                'recent_registrations' => User::where('created_at', '>=', now()->subDays(7))
                    ->count(),
                'latest_users' => User::orderBy('created_at', 'desc')
                    ->take(5)
                    ->select('id', 'name', 'email', 'role', 'created_at')
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'message' => 'Statistik users berhasil diambil.',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik users.',
                'error' => 'Internal server error'
            ], 500);
        }
    }
}

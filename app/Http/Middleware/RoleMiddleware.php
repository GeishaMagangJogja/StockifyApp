<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                    'error' => 'UNAUTHENTICATED'
                ], 401);
            }
            return redirect('/login');
        }

        $user = Auth::user();

        // Check if user has required role
        if (!in_array($user->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Anda tidak memiliki permission untuk mengakses halaman ini.',
                    'error' => 'FORBIDDEN'
                ], 403);
            }

            // Redirect based on user role
            $redirectUrl = match ($user->role) {
                'Admin'          => '/admin/dashboard',
                'Manajer Gudang' => '/manajergudang/dashboard',
                'Staff Gudang'   => '/staff/dashboard',
                default          => '/',
            };

            return redirect($redirectUrl)->with('error', 'Akses ditolak. Anda tidak memiliki permission untuk mengakses halaman tersebut.');
        }

        return $next($request);
    }
}

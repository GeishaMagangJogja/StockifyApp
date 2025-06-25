<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Silakan login terlebih dahulu.',
                'code' => 'UNAUTHENTICATED'
            ], 401);
        }

        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'Admin') {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Hanya admin yang dapat mengakses fitur ini.',
                'code' => 'INSUFFICIENT_PRIVILEGES'
            ], 403);
        }

        return $next($request);
    }
}

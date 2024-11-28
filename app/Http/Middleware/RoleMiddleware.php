<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Cek apakah user terautentikasi
        if (!$request->user()) {
            abort(403, 'Unauthorized');  // Jika tidak terautentikasi, beri pesan error
        }
    
        // Cek apakah role yang diberikan adalah 'admin' atau 'owner'
        if ($role == 'admin' || $role == 'owner') {
            return $next($request);  // Jika role adalah admin atau owner, lanjutkan request
        }
    
        // Jika role tidak sesuai, beri response Unauthorized
        abort(403, 'Unauthorized');
    }
    
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// ✅ This is the fix
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware {
    public function handle( Request $request, Closure $next, $role ): Response {
        if ( Auth::check() ) {
            $user = Auth::user();
            if ( $user->role === $role ) {
                return $next( $request );
            }
        }

        return redirect( '/' )->with( 'error', 'You do not have access to this page.' );
    }
}
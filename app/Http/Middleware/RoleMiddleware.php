<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {

        $user = $request->user();

        /*
         * User must be authenticated.
         */
        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }


        /*
         * Check user role.
         */
        if (!in_array($user->role, $roles, true)) {

            return response()->json([
                'success' => false,
                'message' =>
                    'Unauthorized. You do not have permission to access this resource.',
            ], 403);
        }


        return $next($request);
    }
}
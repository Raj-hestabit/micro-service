<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->user_type != 1) {
            return response()->json([
                'status'    => 'failure',
                'message'   => 'You are not authorized person'
            ]);
        }

        return $next($request);
    }

}

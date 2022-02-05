<?php

namespace App\Http\Middleware;

use App\Models\Users;
use Closure;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Users::checkAuth($request->input('id'), $request->input('session'))) {
            return response()->json(['confirm' => 'error'], 200);
        }
        return $next($request);
    }
}

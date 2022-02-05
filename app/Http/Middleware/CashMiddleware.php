<?php

namespace App\Http\Middleware;

use App\Models\Cash;
use Closure;

class CashMiddleware
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
        $candidate = Cash::get(url()->current());
        if($candidate->isEmpty())return $next($request);
        else {
            $data = json_decode($candidate[0]->data,true);
            $data['body']['cash'] = 'true';
            return response()->json($data, 200);
        }
    }
}

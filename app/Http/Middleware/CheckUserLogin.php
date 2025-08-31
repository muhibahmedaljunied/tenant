<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class CheckUserLogin
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

        // Log::info('CheckUserLogin_middelware'.$request->user());
    
        if ($request->user()->user_type != 'user') {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}

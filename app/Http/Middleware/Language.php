<?php

namespace App\Http\Middleware;

use Closure;

use Config;
use Illuminate\Support\Facades\App;

class Language
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
        // dd($request->session());

        $locale = config('app.locale');

        if ($request->session()->has('user.language')) {
            $locale = $request->session()->get('user.language');
        }

        $locale = trim($locale);
        App::setLocale($locale);
        return $next($request);
    }
}

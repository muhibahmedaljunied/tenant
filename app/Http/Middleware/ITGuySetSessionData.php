<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Utils\BusinessUtil;

use App\Business;

class ITGuySetSessionData
{
    /**
     * Checks if session data is set or not for a user. If data is not set then set it.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!$request->session()->has('user')) {


            $user = Auth::user();
            $session_data = [
                'id' => $user->id,
                'surname' => $user->surname,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                // 'business_id' => $user->business_id,
                'language' => $user->language,
            ];

            $request->session()->put('user', $session_data);
        }

        return $next($request);
    }
}

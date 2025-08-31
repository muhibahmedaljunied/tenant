<?php 
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomLogoutController
{
    public function __invoke(Request $request)
    {
            Log::info("User  is logging out");
        $user = Auth::user();

        // Custom logic here
    

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

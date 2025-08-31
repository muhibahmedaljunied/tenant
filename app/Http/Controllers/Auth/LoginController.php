<?php

namespace App\Http\Controllers\Auth;

use App\Utils\ModuleUtil;
use App\Utils\BusinessUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    /**
     * All Utils instance.
     *
     */
    protected $businessUtil;
    protected $moduleUtil;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        BusinessUtil $businessUtil,
        ModuleUtil $moduleUtil
    ) {



        $this->middleware('guest')->except('logout');
        $this->businessUtil = $businessUtil;
        $this->moduleUtil = $moduleUtil;
    }

    /**
     * Change authentication from email to username
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    public function logout()
    {

        // dd(auth()->user());
        // $this->businessUtil->activityLog(auth()->user(), 'logout');

        // $this->businessUtil->activityLog($user, 'login');
        request()->session()->flush();

        Auth::logout();
        return redirect('/login');
    }

    /**
     * The user has been authenticated.
     * Check if the business is active or not.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {



        // $this->businessUtil->activityLog($user, 'login');
        // --------------------------------------set this for tenant----------------------------------------------------

        $currentHost = request()->getHost();
        $centralDomain = config('tenancy.central_domains');

        if (!in_array($currentHost, $centralDomain)) {
            Log::info("authenticated from tenant = " . DB::connection()->getDatabaseName() . 'user = ' . $user);

            if ($user->business == null) {
                Auth::logout();
                return redirect('/login')
                    ->with(
                        'status',
                        ['success' => 0, 'msg' => __('lang_v1.business_inactive')]
                    );
            }


            if (!$user->business->is_active) {
                Auth::logout();
                return redirect('/login')
                    ->with(
                        'status',
                        ['success' => 0, 'msg' => __('lang_v1.business_inactive')]
                    );
            } elseif ($user->status != 'active') {
                Auth::logout();
                return redirect('/login')
                    ->with(
                        'status',
                        ['success' => 0, 'msg' => __('lang_v1.user_inactive')]
                    );
            } elseif (!$user->allow_login) {
                Auth::logout();
                return redirect('/login')
                    ->with(
                        'status',
                        ['success' => 0, 'msg' => __('lang_v1.login_not_allowed')]
                    );
            } elseif (($user->user_type == 'user_customer') && !$this->moduleUtil->hasThePermissionInSubscription($user->business_id, 'crm_module')) {
                Auth::logout();
                return redirect('/login')
                    ->with(
                        'status',
                        ['success' => 0, 'msg' => __('lang_v1.business_dont_have_crm_subscription')]
                    );
            }
        }

        Log::info("authenticated from tenant = " . DB::connection()->getDatabaseName() . 'user = ' . $user);



        // ------------------------------------------------------------------------------------------

    }

    protected function redirectTo()
    {

        if (in_array(request()->getHost(), config('tenancy.central_domains'))) {

            return '/home';
        }
        // foreach ($centralDomain as $value) {

        //     if ($currentHost === $value) {

        //         return '/home';
        //     }
        // }
        Log::info("redirectTo" . DB::connection()->getDatabaseName());
        $user = Auth::user();
        if (!$user->can('dashboard.data') && $user->can('sell.create')) {

            return '/pos/create';
        }

        if ($user->user_type == 'user_customer') {
            return 'contact/contact-dashboard';
        }
        $B = DB::table('subscriptions')->where('business_id', $user->business->id)->first();
        if ($B == NULL || $B->status !== 'approved') {
            return '/subscription';
        }
        return '/home';
    }
}

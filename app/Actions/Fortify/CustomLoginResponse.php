<?php
// app/Actions/Fortify/CustomLoginResponse.php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomLoginResponse implements LoginResponseContract
{

    protected $businessUtil;
    protected $moduleUtil;

    public function __construct(\App\Utils\BusinessUtil $businessUtil, \App\Utils\ModuleUtil $moduleUtil)
    {
        log::info('CustomLoginResponseCustomLoginResponse');
        $this->businessUtil = $businessUtil;
        $this->moduleUtil = $moduleUtil;
    }

    public function toResponse($request)
    {
        $user = Auth::user();

        // ---------------------this if user authenticated------------------------------------
        $currentHost = $request->getHost();
        $centralDomains = config('tenancy.central_domains');

        if (!in_array($currentHost, $centralDomains)) {
            Log::info("authenticated from tenant: " . DB::connection()->getDatabaseName());

            if ($user->business == null || !$user->business->is_active || $user->status !== 'active' || !$user->allow_login) {
                Auth::logout();
                return redirect('/login')->with('status', [
                    'success' => 0,
                    'msg' => __('lang_v1.business_inactive'),
                ]);
            }

            if ($user->user_type === 'user_customer' && !app('moduleUtil')->hasThePermissionInSubscription($user->business_id, 'crm_module')) {
                Auth::logout();
                return redirect('/login')->with('status', [
                    'success' => 0,
                    'msg' => __('lang_v1.business_dont_have_crm_subscription'),
                ]);
            }
        }

        Log::info("authenticated from central: " . DB::connection()->getDatabaseName());
        // ----------------------------------------------------------------------------
        return redirect()->intended($this->redirectTo($user, $currentHost));
    }

    protected function redirectTo($user, $host)
    {
        $centralDomains = config('tenancy.central_domains');

        if (in_array($host, $centralDomains)) {
            return '/home';
        }

        if (!$user->can('dashboard.data') && $user->can('sell.create')) {
            return '/pos/create';
        }

        if ($user->user_type === 'user_customer') {
            return 'contact/contact-dashboard';
        }

        $subscription = DB::table('subscriptions')->where('business_id', $user->business->id)->first();
        if (!$subscription || $subscription->status !== 'approved') {
            return '/subscription';
        }
    }
}

<?php

namespace Modules\Tracker\Http\Controllers;

use Exception;
use App\System;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\{Artisan, DB};

class InstallController extends Controller
{
    protected $module_name;
    protected $appVersion;
    public function __construct()
    {
        $this->module_name = 'tracker';
        $this->appVersion = config('tracker.module_version');
    }

    public function index()
    {
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');

        $this->installSettings();

        //Check if Crm installed or not.
        $is_installed = System::getProperty($this->module_name . '_version');
        if (!empty($is_installed)) {
            abort(404);
        }

        $action_url = action('\Modules\Tracker\Http\Controllers\InstallController@install');

        return view('install.install-module')
            ->with(compact('action_url'));
    }

    /**
     * Initialize all install functions
     */
    private function installSettings()
    {
        config(['app.debug' => true]);
        Artisan::call('config:clear');
    }

    public function install()
    {
        try {

            $is_installed = System::getProperty($this->module_name . '_version');
            if (!empty($is_installed)) {
                abort(404);
            }

            DB::beginTransaction();

            DB::statement('SET default_storage_engine=INNODB;');
            Artisan::call('module:migrate', ['module' => "Tracker"]);
            Artisan::call('module:publish', ['module' => "Tracker"]);
            System::addProperty($this->module_name . '_version', $this->appVersion);

            DB::commit();

            $output = [
                'success' => 1,
                'msg' => 'Tracker module installed successfully'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()}");

            $output = [
                'success' => false,
                'msg' => $e->getMessage()
            ];
        }
        return redirect()
            ->action('\App\Http\Controllers\Install\ModulesController@index')
            ->with('status', $output);
    }

    public function uninstall()
    {
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            System::removeProperty($this->module_name . '_version');

            $output = [
                'success' => true,
                'msg' => __("lang_v1.success")
            ];
        } catch (Exception $e) {
            $output = [
                'success' => false,
                'msg' => $e->getMessage()
            ];
        }

        return redirect()->back()->with(['status' => $output]);
    }
}

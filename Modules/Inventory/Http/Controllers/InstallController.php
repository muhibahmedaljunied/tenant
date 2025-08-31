<?php

namespace Modules\Inventory\Http\Controllers;

use Exception;
use App\System;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Composer\Semver\Comparator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Install\ModulesController;

class InstallController extends Controller
{
    public static $module_name = 'Inventory';
    public $appVersion;
    public function __construct()
    {
        $this->appVersion = config('inventory.module_version'); // get data from config\config.php parameters module_version
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if (! auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }
        // dd('ss');
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');

        $this->installSettings();

        /*
         *   Check if installed or not.
         *  system Model get data fro table :  $row = System::where('key', $key)->first();
        //  */
        // $is_installed = System::getProperty(strtolower(self::$module_name) . '_version');

        // if (! empty ($is_installed)) {
        //     abort(404);
        // }
        abort_unless(empty(System::getProperty(strtolower(self::$module_name) . '_version')), 404);

        $action_url = action([InstallController::class, 'install']);

        return view('install.install-module')
            ->with(compact('action_url'));
    }

    private function installSettings()
    {
        config(['app.debug' => true]);
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
    }

    public function install()
    {
        try {
            request()->validate([
                'license_code' => 'required',
                'login_username' => 'required'
            ], [
                'license_code.required' => 'License code is required',
                'login_username.required' => 'Username is required'
            ]);

            // $license_code = request()->license_code;
            // $email = request()->email;
            // $login_username = request()->login_username;
            // $pid = config('inventory.pid');


            abort_unless(empty(System::getProperty(strtolower(self::$module_name) . '_version')), 404);


            DB::statement('SET default_storage_engine=INNODB;');

            Artisan::call('module:enable', ['module' => self::$module_name]);       // add tables that in migartion
            Artisan::call('module:publish', ['module' => self::$module_name]);

            Artisan::call('module:migrate-reset', ['module' => self::$module_name]); // delete tabels that in migration used by module
            Artisan::call('module:migrate', ['module' => self::$module_name]);       // add tables that in migartion

            System::addProperty(strtolower(self::$module_name) . '_version', $this->appVersion);



            $output = [
                'success' => 1,
                'msg' => 'Inventory module installed succesfully'
            ];
        } catch (Exception $e) {
            // DB::rollBack();
            throw $e;
            $output = [
                'success' => false,
                'msg' => $e->getMessage()
            ];
        }
        // dd(
        //     ModulesController::class
        // );
        return redirect()
            ->action([ModulesController::class, 'index'])
            ->with('status', $output);
    }

    /**
     * Uninstall
     */
    public function uninstall()
    {
        if (! auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            System::removeProperty(strtolower(self::$module_name) . '_version');

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

    /**
     * update module
     */
    public function update()
    {
        //Check if superhero_version is same as appVersion then 404
        //If appVersion > superhero_version - run update script.
        //Else there is some problem.
        if (! auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '512M');

            $superhero_version = System::getProperty(strtolower(self::$module_name) . '_version');

            if (Comparator::greaterThan($this->appVersion, $superhero_version)) {
                ini_set('max_execution_time', 0);
                ini_set('memory_limit', '512M');
                $this->installSettings();

                DB::statement('SET default_storage_engine=INNODB;');
                Artisan::call('module:migrate', ['module' => self::$module_name]);
                System::setProperty(strtolower(self::$module_name) . '_version', $this->appVersion);
            } else {
                abort(404);
            }

            DB::commit();

            $output = [
                'success' => 1,
                'msg' => 'Inventory module updated Succesfully to version ' . $this->appVersion . ' !!'
            ];

            return redirect()->back()->with(['status' => $output]);
        } catch (Exception $e) {
            DB::rollBack();
            die ($e->getMessage());
        }
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('inventory::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('inventory::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('inventory::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}

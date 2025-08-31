<?php


namespace App\Http\Controllers\AcMaster;

use Exception;
use App\Utils\ModuleUtil;
use App\Utils\ProductUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Imports\AcMaster\MasterImport;
use App\Actions\CreateAccountsTemplate;
use App\Models\AcMaster;

class ImportAcMasterController extends Controller
{
    protected ProductUtil $productUtil;
    protected ModuleUtil $moduleUtil;

    public function __construct(ProductUtil $productUtil, ModuleUtil $moduleUtil)
    {
        $this->productUtil = $productUtil;
        $this->moduleUtil = $moduleUtil;
        $this->middleware('can:product.create', ['only' => ['create', 'store']]);
    }

    public function index(Request $request)
    {

        $menuItems = $request->menuItems;
        return view('ac_master.imports.index',compact('menuItems'));
    }

    public function store(Request $request)
    {
        try {
            //Set maximum php execution time
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', -1);
            // ---------------------------------------------------- \\
            DB::beginTransaction();
            $createAccounts = new CreateAccountsTemplate(session('user.business_id'));
            // ---------------------------------------------------- \\
            $file = $request->file('import_file');
            $this->clearAllEntriesData();
            // ---------------------------------------------------- \\
            $convertImportAccounts = (new MasterImport)->toArray($file->path());
            $firstSheet = head($convertImportAccounts);
            // ---------------------------------------------------- \\
            $createAccounts->setDefaultAccounts()->saveAccounts();
            $createAccounts->setAccounts($firstSheet)->saveAccounts();
            // ---------------------------------------------------- \\
            $output = [
                'success' => 1,
                'msg' => __('chart_of_accounts.file_imported_successfully')
            ];
            // ---------------------------------------------------- \\
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            logEmergency($e);
            $output = [
                'success' => 0,
                'msg' => __('chart_of_accounts.file_imported_failed')
            ];
        }
        // ---------------------------------------------------- \\

        return redirect(route('ac.import-master.index'))->with('status', $output);
    }
    private function clearAllEntriesData()
    {
        AcMaster::query()->delete();
    }
}

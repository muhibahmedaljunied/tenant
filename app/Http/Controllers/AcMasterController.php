<?php

namespace App\Http\Controllers;

use App\Models\AcMaster;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AcMaster\MasterExport;

class AcMasterController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('chartofaccounts.view');

        $accountMasters = AcMaster::select(
            'id',
            'parent_acct_no',
            'account_number',
            'account_level',
            'account_type',
            'account_name_ar',
            'account_name_en'
        )->get();
                $menuItems = $request->menuItems;
        return view('ac_master.index', compact('accountMasters','menuItems'));
    }
    public function export()
    {
        $this->authorize('chartofaccounts.view');

        return Excel::download(new MasterExport(), 'accounts.xlsx');
    }
    public function store(Request $request)
    {
        $this->authorize('chartofaccounts.create');

        try {
            $input = $request->only(['account_name_ar', 'account_name_en', 'account_number', 'account_level', 'parent_acct_no', 'account_type', 'pay_collect']);

            $input['account_name_en'] = ! empty($input['account_name_en']) ? $input['account_name_en'] : NULL;

            $input['pay_collect'] = ! empty($input['pay_collect']) ? $input['pay_collect'] : 0;

            AcMaster::create($input);
            $output = [
                'success' => true,
                'data' => $input,
                'msg' => __("lang_v1.success")
            ];
        } catch (\Exception $e) {
            logger()->emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }

        return $output;
    }
    public function edit($id)
    {
        $this->authorize('chartofaccounts.edit');
        if (request()->ajax()) {
            $ac_master = AcMaster::find($id);
            $masters = AcMaster::where('id', '!=', $id)->where('account_level', '!=', 7)->get();

            return view('ac_master.edit_modal')
                ->with(compact('ac_master', 'masters'));
        }
    }




    public function update(Request $request, $id)
    {
        $this->authorize('chartofaccounts.edit');
        if (request()->ajax()) {
            try {
                $input = $request->only(['account_name_ar', 'account_name_en', 'parent_acct_no', 'pay_collect']);

                $input['account_name_en'] = ! empty($input['account_name_en']) ? $input['account_name_en'] : null;
                $input['parent_acct_no'] = ! empty($input['parent_acct_no']) ? $input['parent_acct_no'] : null;

                $input['pay_collect'] = ! empty($input['pay_collect']) ? $input['pay_collect'] : 0;

                $ac_master = AcMaster::findOrFail($id);
                // dd(
                //     $input
                // );
                $ac_master->update($input);

                $output = [
                    'success' => true,
                    'msg' => __("lang_v1.success")
                ];
            } catch (\Exception $e) {
                logger()->emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }

    public function destroy($id)
    {
        $this->authorize('chartofaccounts.delete');
        if (request()->ajax()) {
            try {
                // $business_id = request()->user()->business_id;

                $ac_master = AcMaster::findOrFail($id);
                $ac_master->delete();

                $output = [
                    'success' => true,
                    'msg' => __("lang_v1.success")
                ];
            } catch (\Exception $e) {
                logger()->emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }
}

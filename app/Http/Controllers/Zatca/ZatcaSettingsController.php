<?php

namespace App\Http\Controllers\Zatca;

use Exception;
use App\Models\Zatca;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Zatca\ZatcaService;
use App\Services\Zatca\Cert\OnBoarding;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\ZatcaSettingRequest;

class ZatcaSettingsController extends Controller
{
    protected $zatcaService;
    public function __construct(ZatcaService $zatcaService)
    {
        $this->zatcaService = $zatcaService;
    }
    public function index()
    {
        if (request()->ajax()) {
            $zatcas = Zatca::query()->orderBy('id', 'desc');

            return Datatables::of($zatcas)
                ->removeColumn('id')
                ->editColumn('action', function ($zatca) {
                    return view('zatca_setting.datatable.action', compact('zatca'));
                })
                ->editColumn('certificate', function ($zatca) {
                    if ($zatca->certificate) {
                        return "PASS";
                    } else {
                        return "INVALID";
                    }
                })
                ->editColumn('production_certificate', function ($zatca) {
                    if ($zatca->production_certificate) {
                        return "PASS";
                    } else {
                        return "INVALID";
                    }
                })
                ->rawColumns([
                    'name',
                    'trn',
                    'crn',
                    'egs_serial_number',
                    'certificate',
                    'production_certificate',
                    'action',
                ])->make(true);
        }
        return view('zatca_setting.index');
    }
    public function create()
    {
        $issuingTypes = array_map_assoc(function ($key, $val) {
            return [
                $val => __("zatca.invoices_issuing_types.{$val}")
            ];
        }, config('zatca.invoices_issuing_types'));

        $countries = array_map_assoc(function ($key, $val) {
            return [
                $val => __("zatca.countries_iso_codes.{$val}")
            ];
        }, config('zatca.countries_iso_codes'));
        return view('zatca_setting.create', compact('issuingTypes', 'countries'));
    }

    public function store(ZatcaSettingRequest $request)
    {
        try {
            DB::beginTransaction();
            $zatcaSettings = $this->zatcaService->create($request);
            $setting_obj = $this->zatcaService->settings($zatcaSettings);
            $zatcaOnBoarding = new OnBoarding($setting_obj);
            $zatca_response = $zatcaOnBoarding->generatePemsKeys()->IssueCert509();
            $output = [
                'success' => 1,
                'msg' => __('zatca.msg.added_success')
            ];
            DB::commit();
            return redirect()->route('zatca.settings.index')->with('status', $output);
        } catch (Exception $e) {
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('messages.something_went_wrong')
            ];
            if (config('app.debug')) {
                dd($e->getMessage());
            }
            logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()}");
            return redirect()->route('zatca.settings.index')->with('status', $output);
        }
    }


    public function edit($zatca)
    {
        $zatca = Zatca::whereId($zatca)->firstOrFail();
        $issuingTypes = array_map_assoc(function ($key, $val) {
            return [
                $val => __("zatca.invoices_issuing_types.{$val}")
            ];
        }, config('zatca.invoices_issuing_types'));
        $countries = array_map_assoc(function ($key, $val) {
            return [
                $val => __("zatca.countries_iso_codes.{$val}")
            ];
        }, config('zatca.countries_iso_codes'));
        return view('zatca_setting.edit', compact('issuingTypes', 'zatca', 'countries'));
    }
    public function show(Zatca $zatca)
    {
        $issuingTypes = array_map_assoc(function ($key, $val) {
            return [
                $val => __("zatca.invoices_issuing_types.{$val}")
            ];
        }, config('zatca.invoices_issuing_types'));
        $countries = array_map_assoc(function ($key, $val) {
            return [
                $val => __("zatca.countries_iso_codes.{$val}")
            ];
        }, config('zatca.countries_iso_codes'));
        return view('zatca_setting.show', compact('issuingTypes', 'zatca', 'countries'));
    }

    public function update(ZatcaSettingRequest $request, Zatca $zatca)
    {

        try {
            DB::beginTransaction();
            $this->zatcaService->update($request, $zatca);
            $settings = $this->zatcaService->settings($zatca);
            $zatcaOnBoarding = new OnBoarding($settings);
            $zatca_response = $zatcaOnBoarding->generatePemsKeys()->IssueCert509();
            $output = [
                'success' => 1,
                'msg' => __('zatca.msg.added_success')
            ];
            DB::commit();
            return redirect()->back()->with('status', $output);
        } catch (Exception $e) {
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('messages.something_went_wrong')
            ];
            if (config('app.debug') && app()->get('env') == 'local') {
                dd($e->getMessage());
            }
            logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()}");
            return redirect()->route('zatca.settings.index')->with('status', $output);
        }

    }

    public function destroy(Zatca $zatca)
    {
        $zatca->delete();
        $output = [
            'success' => 1,
            'msg' => __('zatca.msg.deleted_success')
        ];
        return redirect()->route('zatca.settings.index')->with('status', $output);
    }
}

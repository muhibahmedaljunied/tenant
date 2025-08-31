<?php
namespace App\Services\AccountMaster;

use Exception;
use App\Business;
use App\Models\{AcMaster, AcSetting};
use Illuminate\Support\Facades\DB;

class AccountMasterService
{
    public function setNewMasterAccount(Business|int $business = null, $parentId, $number, $name, $type)
    {
        $businessId = $business instanceof Business ? $business->id : $business;
        if (! $business instanceof Business && ! is_null($business)) {
            $businessModel = Business::whereId($business)->select('id')->firstOrFail();
            $businessId = $businessModel->id;
        }

        AcMaster::withoutEvents(function () use ($businessId, $parentId, $name, $type, $number, &$acMaster) {
            $acMaster = AcMaster::updateOrCreate(['account_number' => $number, 'business_id' => $businessId], [
                'business_id' => $businessId,
                'account_name_ar' => $name,
                'account_name_en' => '',
                'account_number' => $number,
                'parent_acct_no' => $parentId,
                'account_type' => $type,
                'transaction_made' => 0,
                'archived' => 0,
                'account_status' => 'active',
                'account_level' => 3
            ]);
        });
        return $acMaster;
    }
    public function syncNewAccountToBusiness($parentId, $name, $type, $settingsKey)
    {
        $settingsKey = (string) $settingsKey;

        $allBusiness = Business::select('id')->get();

        $businessIds = $allBusiness->pluck('id')->toArray();

        $accountsSettings = AcSetting::withoutGlobalScopes()->select('id', 'business_id', $settingsKey)->whereIn('business_id', $businessIds)->groupBy('business_id')->latest()->get();
        $acMasters = AcMaster::withoutGlobalScopes()->select('account_number', 'parent_acct_no', 'business_id')->whereIn('business_id', $businessIds)->where('parent_acct_no', $parentId)->get();

        try {
            DB::beginTransaction();
            foreach ($allBusiness as $business) {
                $businessId = $business->id;
                $accountsSetting = $accountsSettings->firstWhere('business_id', $businessId);

                if ($accountsSetting) {
                    $lastAccount = $acMasters->where('business_id', $businessId)->sortBy('id')->last();

                    $parentLen = strlen((string) $parentId);
                    
                    $number = $lastAccount ? $lastAccount->account_number : str_pad($parentId, ($parentLen + 2), 00, STR_PAD_RIGHT);

                    $acMaster = $this->setNewMasterAccount($businessId, $parentId, $number + 1, $name, $type);

                    $accountsSetting->update([
                        "$settingsKey" => $acMaster->account_number,
                    ]);
                }
            }
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()} Trace: \n {$e->getTraceAsString()}");
        }
    }
}
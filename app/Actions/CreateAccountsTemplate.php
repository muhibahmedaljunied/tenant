<?php

namespace App\Actions;

use App\Models\AcMaster;
use App\Models\AcSetting;
use Illuminate\Support\Facades\Log;

class CreateAccountsTemplate
{
    private $business;
    private static $accounts = [];
    public function __construct($business)
    {
  
        $this->business = $business;
    }

    public function setAccounts(array $listOfAccount = [])
    {
      
        self::$accounts = $listOfAccount;
        return $this;
    }
    public static function getMasterAccounts(): array
    {
     
   

        if (($json = file_get_contents(public_path("accountant/ac_masters.json"))) !== false) {
            $accounts = json_decode($json, true);
        }

      
      
        return $accounts;
    }
    public function setDefaultAccounts(): self
    {
       
       
        return $this->setAccounts(self::getMasterAccounts());
    }
    private function getAccounts(): array
    {
        return self::$accounts;
    }

    public function getAccountsRouting(): array
    {
        $settings = [];
        if (($settingsFile = file_get_contents(public_path("accountant/ac_settings.json"))) !== false) {
            $settings = json_decode($settingsFile, true);
        }
        return $settings;
    }
    public function saveAccounts()
    {
        $accounts = $this->getAccounts();
        $baseAccounts = collect(self::getMasterAccounts());
   
        foreach ($accounts as $key => $account) {
            $values = $account;
            $values['created_at'] = now()->toDateTimeString();
            $values['updated_at'] = now()->toDateTimeString();
            // ---------------------------- \\
            $masterAccount = $baseAccounts->firstWhere('account_number', $values['account_number']);
            if($masterAccount) {
                $values['account_number'] = $masterAccount['account_number'];
                $values['parent_acct_no'] = $masterAccount['parent_acct_no'];
                $values['account_level'] = $masterAccount['account_level'];
                $values['account_type'] = $masterAccount['account_type'];
                $values['account_status'] = $masterAccount['account_status'];
            }
            // ---------------------------- \\

            AcMaster::updateOrCreate([
                'account_number' => $values['account_number'],
                'business_id' => $this->business
            ], $values);
        }
        return true;
    }
    public function create()
    {
     
        $this->saveAccounts();

      
        $routing = $this->getAccountsRouting();
        $routing['business_id'] = $this->business;
        // dd(130,$routing);
        return AcSetting::create($routing);
    }
}
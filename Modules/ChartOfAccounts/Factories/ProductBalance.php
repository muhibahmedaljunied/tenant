<?php

namespace Modules\ChartOfAccounts\Factories;

use App\Models\AcJournalEntry;
use App\Models\AcJournalEntryDetail;
use App\Models\AcMaster;
use App\Models\AcSetting;
use Modules\ChartOfAccounts\Actions\CreateOpeningStock;
use phpDocumentor\Reflection\Location;

class ProductBalance extends OpeningBalanceImpl
{
    private $openStock = true;

    public function setOpenStock($openStock): ProductBalance
    {
        $this->openStock = $openStock;
        return $this;
    }
    public function create($data)
    {
        $amount = $data['amount'] * $data['qty'];
        $setting = AcSetting::where('business_id', request()->session()->get('user.business_id'))->first();
        $journalEntry = $this->createOpeningAccount($data['date'], $data['description'], $data['opening_account']);
        AcJournalEntryDetail::create([
            'account_number' => $setting->inventory,
            'amount' => $amount,
            'ac_journal_entries_id' => $journalEntry->id,
            'product_id' => $data['product'],
            'location_id' => $data['location'],
        ]);
        AcJournalEntryDetail::create([
            'account_number' => $data['opening_account'],
            'amount' => $amount,
            'ac_journal_entries_id' => $journalEntry->id,
            'product_id' => $data['product'],
            'location_id' => $data['location'],
        ]);
        if($this->openStock) {
            app(CreateOpeningStock::class)->create($data['product'], $data['location'], $data['amount'], $data['qty']);
        }
    }

    public function update($product_id, $location_id,$data) {
        $detail1 = AcJournalEntryDetail::where('product_id', $product_id)->where('location_id', $location_id)->where('amount', '>', 1)->first();
        $detail2 = AcJournalEntryDetail::where('product_id', $product_id)->where('location_id', $location_id)->where('amount', '<', 1)->first();
        $data['product'] = $product_id;
        $data['location'] = $location_id;
        if(!$detail1 || !$detail2){
            $this->create($data);
        } else {
            $amount = $data['amount'] * $data['qty'];
            $data['amount'] = $amount;
            $detail1->update($data);
            $data['amount'] = -$amount;
            $detail2->update($data);
        }
    }
}

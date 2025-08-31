<?php

namespace Modules\ChartOfAccounts\Factories;

use App\Models\AcJournalEntryDetail;
use App\Models\AcMaster;

class VendorBalance extends OpeningBalanceImpl
{
    public function create($data)
    {
        $customer = AcMaster::find($data['account']);
        $amount = (double)$data['amount'];
        $opening_account = $data['opening_account'];
        $journalEntry = $this->createOpeningAccount($data['date'], $data['description'], $opening_account);
        AcJournalEntryDetail::create([
            'account_number' => $amount < 0 ? $customer->account_number : $opening_account,
            'amount' => $amount * -1,
            'ac_journal_entries_id' => $journalEntry->id,
        ]);
        AcJournalEntryDetail::create([
            'account_number' => $amount < 0 ? $opening_account : $customer->account_number,
            'amount' => $amount,
            'ac_journal_entries_id' => $journalEntry->id
        ]);
    }
}

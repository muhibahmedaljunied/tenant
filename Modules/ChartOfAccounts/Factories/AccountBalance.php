<?php

namespace Modules\ChartOfAccounts\Factories;

use App\Models\AcJournalEntryDetail;

class AccountBalance extends OpeningBalanceImpl
{
    public function create($data)
    {
        $journalEntry = $this->createOpeningAccount($data['date'], $data['description'], $data['opening_account']);
        $amount = (double)$data['amount'];
        AcJournalEntryDetail::create([
            'account_number' => $amount < 0 ? $data['opening_account'] : $data['account'],
            'amount' => $amount,
            'ac_journal_entries_id' => $journalEntry->id,
        ]);
        AcJournalEntryDetail::create([
            'account_number' => $amount < 0 ? $data['account'] : $data['opening_account'],
            'amount' => $amount,
            'ac_journal_entries_id' => $journalEntry->id
        ]);
    }


}

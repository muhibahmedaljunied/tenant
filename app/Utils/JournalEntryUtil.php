<?php

namespace App\Utils;

use App\Contact;
use App\Models\AcJournalEntry;
use App\Models\AcJournalEntryDetail;
use App\Models\AcMaster;

class JournalEntryUtil
{

    public function createOpeningAccount()
    {

    }

    public function createAccountOpeningBalance($opening_account, $date, $description, $account, $amount)
    {
        $journalEntry = AcJournalEntry::create([
            'entry_no' => 1,
            'entry_date' => $date,
            'entry_desc' => "أرصدة إفتتاحية: {$description}",
            'entry_type' => "opening_balance",
        ]);
        AcJournalEntryDetail::create([
            'account_number' => $amount < 0 ? $account : $opening_account,
            'amount' => (double) $amount,
            'ac_journal_entries_id' => $journalEntry->id,
        ]);
        AcJournalEntryDetail::create([
            'account_number' => $amount < 0 ? $opening_account : $account,
            'amount' => -(double) $amount,
            'ac_journal_entries_id' => $journalEntry->id
        ]);
    }

    public function createCustomersOpeningBalance($opening_account, $date, $description, $account, $amount)
    {
        $customer = AcMaster::find($account);
        $ac_journal_entry_inv['entry_no'] = 1;
        $ac_journal_entry_inv['entry_date'] = $date;
        $ac_journal_entry_inv['entry_desc'] = "أرصدة إفتتاحية: {$description}";
        $ac_journal_entry_inv['entry_type'] = "opening_balance";
        $journalEntry = AcJournalEntry::create($ac_journal_entry_inv);
        AcJournalEntryDetail::create([
            'account_number' => $amount > 0 ? $customer->account_number : $opening_account,
            'amount' => (double) $amount,
            'ac_journal_entries_id' => $journalEntry->id,
        ]);
        AcJournalEntryDetail::create([
            'account_number' => $amount > 0 ? $opening_account : $customer->account_number,
            'amount' => -(double) $amount,
            'ac_journal_entries_id' => $journalEntry->id
        ]);
    }

    public function createVendorsOpeningBalance($opening_account, $date, $description, $account, $amount)
    {
        $vendor = AcMaster::find($account);
        $ac_journal_entry_inv['entry_no'] = 1;
        $ac_journal_entry_inv['entry_date'] = $date;
        $ac_journal_entry_inv['entry_desc'] = "أرصدة إفتتاحية: {$description}";
        $ac_journal_entry_inv['entry_type'] = "opening_balance";
        $journalEntry = AcJournalEntry::create($ac_journal_entry_inv);
        AcJournalEntryDetail::create([
            'account_number' => $amount < 0 ? $vendor->account_number : $opening_account,
            'amount' => (double) $amount,
            'ac_journal_entries_id' => $journalEntry->id,
        ]);
        AcJournalEntryDetail::create([
            'account_number' => $amount < 0 ? $opening_account : $vendor->account_number,
            'amount' => (double) $amount,
            'ac_journal_entries_id' => $journalEntry->id
        ]);
    }

    public function createProductsOpeningBalance($opening_account, $date, $description, $account, $amount)
    {
        $vendor = AcMaster::find($account);
        $ac_journal_entry_inv['entry_no'] = 1;
        $ac_journal_entry_inv['entry_date'] = $date;
        $ac_journal_entry_inv['entry_desc'] = "أرصدة إفتتاحية: {$description}";
        $ac_journal_entry_inv['entry_type'] = "opening_balance";
        // $this->store;
        $journalEntry = AcJournalEntry::create($ac_journal_entry_inv);
        AcJournalEntryDetail::create([
            'account_number' => $amount < 0 ? $vendor->account_number : $opening_account,
            'amount' => (double) $amount,
            'ac_journal_entries_id' => $journalEntry->id,
        ]);
        AcJournalEntryDetail::create([
            'account_number' => $amount < 0 ? $opening_account : $vendor->account_number,
            'amount' => (double) $amount,
            'ac_journal_entries_id' => $journalEntry->id
        ]);
    }
}

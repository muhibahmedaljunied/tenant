<?php
namespace App\Services\JournalEntries;

use App\Models\AcJournalEntry;
use App\Models\AcJournalEntryDetail;
use App\Transaction;

trait RecordEntriesTransaction
{
    public function storeEntry(string $date, string $description, ?int $branchCostCenterId = null, ?Transaction $transaction = null): AcJournalEntry
    {
        return AcJournalEntry::create([
            'entry_no' => 1,
            'entry_date' => $date,
            'entry_desc' => $description,
            'entry_type' => "daily",
            'cost_cen_branche_id' => $branchCostCenterId,
            'transaction_id' => optional($transaction)->id,
        ]);
    }

    public function storeEntryDetails(int $journalEntryId, $accountNumber, $amount, ?int $extraCostCenterId = null): AcJournalEntryDetail
    {
        return AcJournalEntryDetail::create([
            'account_number' => $accountNumber,
            'amount' => $amount,
            'ac_journal_entries_id' => $journalEntryId,
            'cost_cen_field_id' => $extraCostCenterId,
        ]);
    }
}
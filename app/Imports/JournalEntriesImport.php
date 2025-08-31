<?php

namespace App\Imports;

use App\Models\AcMaster;
use App\Models\AcJournalEntry;
use App\Utils\Util as CommonUtil;
use Illuminate\Support\Collection;
use App\Models\AcJournalEntryDetail;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;

class JournalEntriesImport implements ToCollection
{
    private CommonUtil $commonUtil;
    private static array $errors = [];
    private static array $info = [];

    public function __construct()
    {
        $this->commonUtil = new CommonUtil();
    }

    protected $columnMapping = [
        0 => 'comment',
        1 => 'debit',
        2 => 'credit',
        3 => 'account_number',
        4 => 'entry_description',
        5 => 'date',
        6 => 'sequence_number',
    ];

    public function collection(Collection $rows)
    {
        $entriesContent = [];
        $mappedRows = collect(array_slice($rows->toArray(), 1))->map(function ($row) {
            $data = [];
            foreach ($this->columnMapping as $index => $key) {
                $data[$key] = $row[$index] ?? null; // Map column index to its corresponding key
            }
            return $data;
        });
        // ---------------------------------------- \\
        $groupedRows = $mappedRows->groupBy('sequence_number');
        // ---------------------------------------- \\
        foreach ($groupedRows as $sequenceNumber => $entries) {
            if (blank($sequenceNumber)) {
                continue;
            }
            $totalDebit = $entries->sum('debit');
            $totalCredit = $entries->sum('credit');
            if ($totalDebit != $totalCredit) {
                self::$errors[] = [
                    'entry' => null,
                    "msg" => "القيد $sequenceNumber لا يتوافق بين الدائن والدين",
                ];

                continue;
            }
            // ---------------------------------------- \\
            $date = $entries[0]['date'] ?? today();
            $entriesContent[$sequenceNumber] = [
                'entry_date' => Carbon::parse($date)->toDateString(),
                'entry_description' => $entries[0]['entry_description'],
            ];
            // ---------------------------------------- \\
            foreach ($entries as $key => $entry) {
                $entriesContent[$sequenceNumber]['accounts'][] = [
                    'debtor' => $entry['debit'] ?? 0,
                    'creditor' => $entry['credit'] ?? 0,
                    'account_number' => $entry['account_number'],
                    'comment' => $entry['comment'],
                ];
            }
        }
        // ---------------------------------------- \\
        $imported = 0;

        if (count($entriesContent) > 0) {
            $imported = $this->storeEntries($entriesContent);
        }
        // ---------------------------------------- \\
        if ($entriesContent) {
            self::$info = [
                'entries_imported' => $imported,
                'entries_count' => count($entriesContent),
                'failed' => count(self::$errors),
                'errors' => self::$errors,
            ];
        }
        // ---------------------------------------- \\
    }
    // ---------------------------------------- \\
    public function rules(): array
    {
        return [
            'sequence_number' => 'required|integer',
            'date' => 'nullable|date',
            'entry_description' => 'nullable|string',
            'account_code' => 'required|string',
            'debit' => 'nullable|numeric',
            'credit' => 'nullable|numeric',
        ];
    }
    // ----------------------------------- \\
    public function getInfo(): array
    {
        return self::$info;
    }
    // ----------------------------------- \\
    private function storeEntries($entries)
    {
        $imported = 0;
        foreach ($entries as $entry) {
            $journalEntry = AcJournalEntry::create([
                'entry_no' => 1,
                'entry_date' => $entry['entry_date'],
                // ------------------------------------------ \\
                'entry_desc' => $entry['entry_description'],
                'cost_cen_branche_id' => $entry['cost_cen_branche_id'] ?? null,
                'entry_type' => "daily",
            ]);
            $journalEntryId = $journalEntry->id;
            // ------------------------------------------ \\
            foreach ($entry['accounts'] as $journal_entry) {
                $account_type = AcMaster::where('account_number', $journal_entry['account_number'])->value('account_type');
                if (! filled($account_type)) {
                    self::$errors[] = [
                        'entry' => $entry,
                        "msg" => __("lang_v1.account_not_found", ["account" => $journal_entry['account_number']]),
                    ];
                    $journalEntry->forceDelete();
                    continue 2;
                }
                $details['ac_journal_entries_id'] = $journalEntryId;
                $details['account_number'] = $journal_entry['account_number'];
                $details['entry_desc'] = $journal_entry['comment'] ?? null;
                // if account_type is debtor
                if ($account_type == 'debtor') {
                    if ($journal_entry['debtor'] > 0) {
                        //+
                        $details['amount'] = $journal_entry['debtor'];
                    } else {
                        //-
                        $details['amount'] = $journal_entry['creditor'] * (-1);
                    }
                    // if account_type is creditor
                } else {
                    if ($journal_entry['creditor'] > 0) {
                        //+
                        $details['amount'] = $journal_entry['creditor'];
                    } else {
                        //-
                        $details['amount'] = $journal_entry['debtor'] * (-1);
                    }
                }
                // $details['cost_cen_field_id'] = $journal_entry['cost_cen_field_id'];
                AcJournalEntryDetail::create($details);
            }
            $imported++;
            // ------------------------------------------ \\

        }
        return $imported;
    }

}

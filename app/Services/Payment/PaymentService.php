<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\AcJournalEntry;
use App\Models\AcJournalEntryDetail;
use App\Models\AcJournalEntryDebtAges;

class PaymentService
{
    public function debtAges($account_number, $amount, $type, $accountType = null)
    {
        $debtDatesRanges = getDebtDatesRanges();

        $DaysDetails = AcJournalEntryDetail::where('account_number', $account_number)
            ->with('ac_journal_entry.journal_entry_debt_ages')
            ->forEntryDate(now()->toDateString(), [])
            // ->forDebtJournalEntry([minDateRange($debtDatesRanges), now()->toDateString()], [])
            ->get();

        $newDaysDetails = collect();
        $DaysDetails = $DaysDetails
            ->each(function ($item) use ($newDaysDetails) {
                if ($item->ac_journal_entry->journal_entry_debt_ages->count()) {
                    return $item->ac_journal_entry->journal_entry_debt_ages->each(function ($content) use ($newDaysDetails) {
                        return $newDaysDetails->push(['amount' => $content->amount, 'entry_date' => $content->debtages_date]);
                    });
                }
                return $newDaysDetails->push(['amount' => $item->amount, 'entry_date' => $item->ac_journal_entry->entry_date]);
            });


        $debtDatesAmount = [];
        $leftAmount = $amount;

        foreach (sortByOldDate($debtDatesRanges) as $index => $dateRange) {
            $debtBetweenDates = $newDaysDetails->whereBetween('entry_date', array_reverse($dateRange));
            if ($index == 0) {
                $debtBetweenDates = $newDaysDetails->where('entry_date', '<=', min($dateRange));
            } else {
                $debtBetweenDates = $newDaysDetails->whereBetween('entry_date', array_reverse($dateRange));
            }
            $sumAmount = $debtBetweenDates->sum(function ($item) use ($accountType) {
                $positive = $item['amount'] > 0 ? (int) $item['amount'] : 0;
                $negative = $item['amount'] < 0 ? (int) $item['amount'] : 0;
                $debtor   = $accountType == 'debtor' ? $positive  : -$negative;
                $creditor = $accountType == 'debtor' ? -$negative : $positive;
                return ($creditor - $debtor);
            });

            if (empty($sumAmount)) {
                continue;
            }


            if ($type == 'send') {
                $sumAmount = $debtBetweenDates->sum('amount');

                if ($sumAmount > 0) {
                    $debt_amount = 0;
                    $takedAmount = $leftAmount;
                    if ($sumAmount < $leftAmount) {
                        $debt_amount = $sumAmount - $leftAmount;
                        $takedAmount = abs($debt_amount + $leftAmount);
                    }
                } else {
                    continue;
                }
            } else {
                if ($sumAmount < 0) {
                    $takedAmount = min(abs($sumAmount), $leftAmount);
                    $debt_amount =  $takedAmount - $leftAmount;
                } else {
                    continue;
                }
            }

            $leftAmount = abs($debt_amount);

            if (!empty($debt_amount) || !empty($takedAmount)) {
                $debtDatesAmount[min($dateRange)] = abs($takedAmount);
            }

            if ($leftAmount <= 0) {
                break;
            }
        }
        if ($leftAmount > 0) {
            $debtDatesAmount[null] = $leftAmount;
            $leftAmount = 0;
        }
        return $debtDatesAmount;
    }
    public function createPayment(
        $request,
        $date,
        $contact_type,
        $amount,
        $account
    ) {
        return Payment::create([
            'reference_number' => $request->input('name'),
            'contact_id' => $request->input('contact'),
            'description' => $request->input('description'),
            'type' => $request->input('type'),
            'date' => $date,
            'contact_type' => $contact_type,
            'amount' => $amount,
            'account_id' => $account->id,
            'branch_cost_center_id' => $request->input('branch_cost_center_id'),
            'extra_cost_center_id' => $request->input('extra_cost_center_id'),
        ]);
    }
    public function entryDeptPay($journalEntryContent, $account, $type, $request, $description, $account_number, $contact_type, $amount, $debtDate)
    {
        $journalEntryContent['entry_desc'] = $description;
        $journalEntryContent['cost_cen_branche_id'] = $request->input('branch_cost_center_id');
        $journalEntryContent['entry_type'] = "daily";

        $journalEntry = AcJournalEntry::create($journalEntryContent);

        $data = [
            'account_number' => $account_number,
            'ac_journal_entries_id' => $journalEntry->id,
            'cost_cen_field_id' => $request->input('extra_cost_center_id'),
        ];

        if ($type == 'sales') {
            $data['amount'] = ($contact_type == 'vendor') ? -$amount : ($request->input('type') == "send" ? $amount : -$amount);
        } else {
            $data['amount'] = ($contact_type == 'vendor') ? ($request->input('type') == "send" ? -$amount : $amount) : ($request->input('type') == "send" ? -$amount : $amount);
        }

        AcJournalEntryDetail::create($data);

        $data['account_number'] = $account->account_number;

        if ($type == 'sales') {
            $data['amount'] = ($contact_type == 'vendor') ? $amount : ($request->input('type') == 'send' ? -$amount : $amount);
        } else {
            $data['amount'] = ($contact_type == 'vendor') ? ($request->input('type') == "send" ? -$amount : $amount) : ($request->input('type') == "send" ? -$amount : $amount);
        }

        AcJournalEntryDetail::create($data);
        return $journalEntry;
    }

    public function createDeptPay($entry, $type, $amount, $contactType, $request, $debtDate)
    {
        if ($type == 'sales') {
            $amount = ($contactType == 'vendor') ? -$amount : ($request->input('type') == "send" ? $amount : -$amount);
        } else {
            $amount = ($contactType == 'vendor') ? ($request->input('type') == "send" ? -$amount : $amount) : ($request->input('type') == "send" ? -$amount : $amount);
        }

        // dd($amount);


        return AcJournalEntryDebtAges::create([
            'ac_journal_entries_id'   => $entry->id,
            'amount'                  => $amount,
            'debtages_date'           => $debtDate,
        ]);
    }
    protected function avgDate($startDate,$endDate)
    {
        $averageTimestamp = (strtotime($startDate) + strtotime($endDate)) / 2;
        return date('Y-m-d', $averageTimestamp);
    }
}

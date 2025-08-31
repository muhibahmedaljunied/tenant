<?php

namespace App\Services\AccountStatment;

use stdClass;
use App\Models\AcMaster;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\AcJournalEntryDetail;
use App\Services\AccountStatment\Traits\CostCenter;
use App\Services\AccountStatment\Traits\CostCenterField;

class AccountStatementService
{
    use CostCenter, CostCenterField;
    public $accounts;
    private $closedEntries = [];
    private static $openedEntries = [];
    private $acccountMasters = null;
    public $found_firest = false;
    public $found_last = false;
    public function getAccountMaster()
    {
       
        if (! $this->acccountMasters) {
            $content = DB::query()->select(
                'id',
                'account_name_ar',
                'account_name_en',
                'account_number',
                'parent_acct_no',
                'account_level',
                'account_type'
            )->where('business_id', session('user.business_id'))->from((new AcMaster)->getTable())->get();

            $this->acccountMasters = collect($content);
        }
        
        return $this->acccountMasters;
    }
    public function searchInBranchWithRange($account_number, $account_from, $account_to)
    {
        $this->accounts = [];
        $accounts_top = $this->getAccountMaster()->where('parent_acct_no', $account_number);
        if ($accounts_top) {
            foreach ($accounts_top as $acc) {
                if (($acc->account_number == $account_from) && ($this->found_firest == false)) {
                    $this->accounts[] = $acc->account_number;
                    $this->found_firest = true;
                }

                if (($acc->account_number == $account_to) && ($this->found_firest == true) && ($this->found_last == false)) {
                    $this->accounts[] = $acc->account_number;
                    $this->found_last = true;
                }
                if ($this->found_last == false) {
                    $parentacc = $this->parents($acc->account_number);

                    $this->getchildrange($parentacc, $account_from, $account_to);
                }
            }
        }
        if ($this->found_last == false) {
            $account_number += 1;
            $this->searchInBranchWithRange($account_number, $account_from, $account_to);
        }
        return $this->accounts;
    }
    public function getchildrange($parents, $account_from, $account_to)
    {
        if (count($parents)) {
            foreach ($parents as $par) {
                // dd('hire');
                if (($par->account_number == $account_from) && ($this->found_firest == false)) {
                    // $this->accounts[] = $par->account_number;
                    $this->found_firest = true;
                }

                if (($par->account_number == $account_to) && ($this->found_firest == true) && ($this->found_last == false)) {
                    $this->accounts[] = $par->account_number;
                    $this->found_last = true;
                }

                $parentacc = $this->parents($par->account_number);
                if (count($parentacc)) {
                    if ($this->found_last == false) {
                        $this->getchildrange($parentacc, $account_from, $account_to);
                    }
                } else {
                    if (($this->found_firest == true) && ($this->found_last == false)) {
                        $this->accounts[] = $par->account_number;
                    }
                }
            }
        }
        return $this->accounts;
    }
    public function openedEntriesData($previous_date, $field_cost_centers, $branch_cost_centers)
    {
        $field_cost_centersValues = implode(',', $field_cost_centers);
        $key = "{$previous_date}|" . implode(',', $branch_cost_centers) . "|$field_cost_centersValues";

        if (! array_key_exists($key, $this::$openedEntries)) {
            $query = AcJournalEntryDetail::whereHas('ac_journal_entry', function ($date) use ($previous_date, $branch_cost_centers) {

                $date->whereDate('entry_date',  $previous_date)->when(count($branch_cost_centers) > 0, function ($q) use ($branch_cost_centers) {
                    $q->whereIn('cost_cen_branche_id', $branch_cost_centers);
                });
            })->forAccountType($field_cost_centers)
                ->select('amount', 'account_number');

            self::$openedEntries[$key] = collect(DB::select($query->toSql(), $query->getBindings()))->map(function ($item) {
                $item->amount = doubleval($item->amount);
                return $item;
            });
        }
        return self::$openedEntries[$key];
    }
    public function closedEntriesData($date_from, $date_to, $branch_cost_centers, $field_cost_centers)
    {
        $costCenterValues = implode(',', $branch_cost_centers);

        $field_cost_centersValues = implode(',', $field_cost_centers);
        $key = "$date_from|$date_to|$costCenterValues|$field_cost_centersValues";

        if (! array_key_exists($key, $this->closedEntries)) {
            $query = AcJournalEntryDetail::query();
            if (is_null($date_to)) {
                $query->forEntryDate($date_from, $branch_cost_centers);
            } else {
                $query->forJournalEntry([$date_from, $date_to], $branch_cost_centers);
            }

            $query->forAccountType($field_cost_centers)
                ->select('amount', 'account_number');

            $this->closedEntries[$key] = collect(DB::select($query->toSql(), $query->getBindings()))->map(function ($item) {
                $item->amount = doubleval($item->amount);
                return $item;
            });
        }
        return $this->closedEntries[$key];
    }
    public function formatParents()
    {
        foreach ($this->getAccountMaster() as $account) {
            $this->parents($account);
        }
        return $this->getAccountMaster();
    }
    public function parents($account)
    {
        if (! $account instanceof stdClass || $account instanceof AcMaster) {
            $account = $this->getAccountMaster()->where('account_number', $account)->first();
        }
        if ($account) {

            if (! property_exists($account, "subParents")) {
                $account->subParents = $this->getAccountMaster()->where('parent_acct_no', $account->account_number)->all();
            }
            return $account->subParents;
        }
    }
    public function getParents($account_number, $exceptMe = false)
    {
        $this->accounts = [];
        if (! $exceptMe) {
            $this->accounts[] = $account_number;
        }
        $accounts_top = $this->parents($account_number);
        if ($accounts_top) {
            foreach ($accounts_top as $account) {
                $this->accounts[] = $account->account_number;
                $this->getchiledtree($this->parents($account->account_number));
            }
        }
        return $this->accounts;
    }

    public function getchiledtree($parents)
    {
        if (count($parents)) {
            foreach ($parents as $par) {
                $this->accounts[] = $par->account_number;
                $subParents = $this->parents($par->account_number);

                if (! empty($subParents)) {
                    $this->getchiledtree($par->subParents);
                }
            }
        }
        return $this->accounts;
    }
}

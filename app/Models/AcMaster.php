<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AcMaster extends Model
{
    use SoftDeletes, BelongsToBusiness;
    protected $table = 'ac_masters';
    public $accounts = array();
    public $found_firest = false;
    public $found_last = false;
    protected $fillable = [
        'account_name_ar',
        'account_name_en',
        'account_number',
        'parent_acct_no',
        'account_level',
        'account_type',
        'pay_collect',
        'transaction_made',
        'archived',
        'current_balance',
        'account_status',
        'stop_flag',
        'com_code',
        'business_id'
    ];



    // public function parent()
    // {
    //     return $this->belongsTo(\App\Models\AcMaster::class, 'parent_acct_no', 'account_number');
    // }
    public function parents()
    {
        return $this->hasMany(self::class, 'parent_acct_no', 'account_number');
    }

    public function getParents($account_number)
    {
        $this->accounts[] = $account_number;
        $accounts_top = AcMaster::where('parent_acct_no', $account_number)->get();
        if ($accounts_top) {
            foreach ($accounts_top as $acc) {
                // $this->accounts = array_push($this->accounts ,$acc->account_number); 
                $this->accounts[] = $acc->account_number;
                $this->getchiledtree($acc->parents);
            }
        }
        return $this->accounts;
    }

    public function getchiledtree($parents)
    {
        if (count($parents)) {
            foreach ($parents as $par) {
                $this->accounts[] = $par->account_number;
                if (count($par->parents)) {
                    $this->getchiledtree($par->parents);
                }
            }
        }
        return $this->accounts;
    }

    public function getParentsExceptMe($account_number)
    {
        $accounts_top = AcMaster::where('parent_acct_no', $account_number)->get();
        if ($accounts_top) {
            foreach ($accounts_top as $acc) {
                $this->accounts[] = $acc->account_number;
                $this->getchiledtree($acc->parents);
            }
        }
        return $this->accounts;
    }

    public function children()
    {
        return $this->hasMany(\App\Models\AcMaster::class, 'parent_acct_no', 'account_number');
    }

    public function searchInBranchWithRange($account_number, $account_from, $account_to)
    {
        // $this->accounts[] = $account_number;
        $accounts_top = self::where('parent_acct_no', $account_number)->get();
        if ($accounts_top) {
            foreach ($accounts_top as $acc) {
                if (($acc->account_number == $account_from) &&  ($this->found_firest == false)) {
                    $this->accounts[] = $acc->account_number;
                    $this->found_firest = true;
                }

                if (($acc->account_number == $account_to) &&  ($this->found_firest == true) &&  ($this->found_last == false)) {
                    $this->accounts[] = $acc->account_number;
                    $this->found_last = true;
                }
                if ($this->found_last == false) {

                    $this->getchildrange($acc->parents, $account_from, $account_to);
                }
            }
        } else {
            // $this->accounts = [];
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
                if (($par->account_number == $account_from) &&  ($this->found_firest == false)) {
                    // $this->accounts[] = $par->account_number;
                    $this->found_firest = true;
                }

                if (($par->account_number == $account_to) &&  ($this->found_firest == true) &&  ($this->found_last == false)) {
                    $this->accounts[] = $par->account_number;
                    $this->found_last = true;
                }



                if (count($par->parents)) {
                    if ($this->found_last == false) {
                        $this->getchildrange($par->parents, $account_from, $account_to);
                    }
                } else {
                    if (($this->found_firest == true) &&  ($this->found_last == false)) {
                        $this->accounts[] = $par->account_number;
                    }
                }
            }
        }
        return $this->accounts;
    }

    public static function forDropdown($parent = 1101)
    {
        $parents = self::whereHas('parents')->where('parent_acct_no', $parent)->pluck('account_number');
        $parents[] = $parent;
        $accounts = self::whereDoesntHave('children')->whereIn('parent_acct_no', $parents)->selectRaw("id, parent_acct_no, account_number, concat(account_name_ar, ' (', account_number , ')') as account_name_number")->get();
        $dropdown = [];
        foreach ($accounts as $account) {
            $dropdown[$account->id] = $account->account_name_number;
        }
        return $dropdown;
    }
    public static function AllForDropdown()
    {
        $accounts = self::selectRaw("id, account_number, concat(account_name_ar, ' (', account_number , ')') as account_name_number")->get();
        $dropdown = [];
        foreach ($accounts as $account) {
            $dropdown[$account->account_number] = $account->account_name_number;
        }
        return $dropdown;
    }

    public static function getCashAccounts()
    {
        $parents = self::whereHas('parents')->where('parent_acct_no', 1101)->pluck('account_number');
        $parents[] = 1101;
        return self::whereDoesntHave('children')->whereIn('parent_acct_no', $parents);
    }
    public function scopeParentsOnly($query)
    {
        return $query->whereNull('parent_acct_no');
    }
}

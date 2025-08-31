<?php

namespace Modules\ChartOfAccounts\Factories;

use Carbon\Carbon;
use ReflectionClass;
use App\Models\AcJournalEntry;

abstract class OpeningBalanceImpl implements OpeningBalance
{
    public function type(){
        $class_name = strtolower((new ReflectionClass($this))->getShortName());
        return str_replace('balance', '', $class_name);
    }
    public function createOpeningAccount($date, $description, $opening_account=null)
    {
        return AcJournalEntry::create([
            'entry_no' => 1,
            'entry_date' => Carbon::parse($date),
            "opening_account" => $opening_account,
            'entry_desc' => "أرصدة إفتتاحية: {$description}",
            "entry_type" => "opening",
            "type" => $this->type(),
        ]);
    }

    abstract public function create($data);

    public function createMany($data, $accounts)
    {
        foreach ($accounts as $account) {
            $this->create(array_merge($data, $account));
        }
    }
}

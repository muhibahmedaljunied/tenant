<?php

namespace App\Imports\AcMaster;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MasterImport implements WithHeadingRow, WithValidation, WithMapping
{
    use Importable;

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function rules(): array
    {
        return [
            'ArabicName' => 'required|string|max:255',
            'EnglishName' => 'required|string|max:255',
            'AccountNumber' => ['required', 'numeric'],
            'ParentAccountNumber' => 'required|numeric',
            'AccountType' => 'required|string|in:debtor,creditor',
            'AccountLevel' => 'required|string|max:255',
        ];
    }

    public function map($row): array
    {
        return [
            'account_name_ar' => $row['arabicname'] ?? "",
            'account_name_en' => $row['englishname'] ?? "",
            'account_number' => $row['accountnumber'],
            'parent_acct_no' => $row['parentaccountnumber'],
            'account_type' => strtolower($row['accounttype']) ?? 'debtor',
            'account_level' => $row['accountlevel'] ?? 1,
            "pay_collect" => 0,
            "transaction_made" => 0,
            "archived" => 0,
            "current_balance" => 0,
            "account_status" => "active",
            "stop_flag" => 0,
            "com_code" => 111
        ];
    }
}

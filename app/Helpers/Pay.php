<?php

namespace App\Helpers;

use App\Models\PaymentRecord;

class Pay extends Qs
{
    public static function getYears($st_id)
    {
        return PaymentRecord::where(['student_id' => $st_id])->pluck('year')->unique();
    }

    public static function genRefCode()
    {
        return date('Y') . '/' . mt_rand(10000, 999999);
    }

    public static function getPaymentStatuses(): array
    {
        return [
            'paid' => 'Paid',
            'unpaid' => 'Unpaid',
            'in_progress' => 'In Progress',
        ];
    }

    public static function getCurrencyUnit(): string
    {
        return 'GHS';
    }
}

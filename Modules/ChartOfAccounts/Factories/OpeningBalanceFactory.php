<?php

namespace Modules\ChartOfAccounts\Factories;

use InvalidArgumentException;

final class OpeningBalanceFactory
{
    public static function factory(string $type): OpeningBalance
    {
        switch ($type) {
            case 'accounts':
                return new AccountBalance();
            case 'customers':
                return new CustomerBalance();
            case 'vendors':
                return new VendorBalance();
            case 'products':
                return new ProductBalance();
        }
        throw new InvalidArgumentException("Unknown Opening Balance Type");
    }
}

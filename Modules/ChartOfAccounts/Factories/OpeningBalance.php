<?php

namespace Modules\ChartOfAccounts\Factories;

interface OpeningBalance
{
    public function create($data);

    public function createMany($data, $accounts);
}

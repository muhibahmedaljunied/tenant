<?php

namespace App\Listeners;

use App\Services\Zatca\ZatcaInvoiceService;
use App\Events\SellReturnTransactionCreated;

class SendSellReturnInvoiceToZatcaListener
{
    public function handle(SellReturnTransactionCreated $event)
    {
        return (new ZatcaInvoiceService())->reportSellReturnInvoice($event->transaction);
    }
}
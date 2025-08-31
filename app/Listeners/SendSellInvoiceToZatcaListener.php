<?php

namespace App\Listeners;

use App\Events\SellTransactionCreated;
use App\Services\Zatca\ZatcaInvoiceService;

class SendSellInvoiceToZatcaListener
{
    public function handle(SellTransactionCreated $event)
    {
        return (new ZatcaInvoiceService())->reportSellInvoice($event->transaction);
    }
}

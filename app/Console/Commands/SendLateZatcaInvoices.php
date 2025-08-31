<?php

namespace App\Console\Commands;

use Exception;
use App\Models\ZatcaInvoice;
use Illuminate\Console\Command;
use App\Services\Zatca\ZatcaInvoiceService;

class SendLateZatcaInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:resend-failed-zatca';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'try Send Failed Zatca Invoices Again (Last 24 Hour) ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): void
    {
        $zatcaInvoiceService = new ZatcaInvoiceService;
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');
        try {
            $zatcaInvoices = ZatcaInvoice::where('sent_to_zatca', 0)->whereDate('created_at', today())->with('transaction')->get();
            $this->info("Invoice Count {$zatcaInvoices}!");
            dd(
                $zatcaInvoices
            );
            foreach (array_chunk($zatcaInvoices, 100) as $zatcaInvoicesChunked) {
                foreach ($zatcaInvoicesChunked as $invoice) {
                    if ($invoice->transaction->type == 'sell') {
                        $zatcaInvoiceService->reportSellInvoice($invoice->transaction);
                    } elseif ($invoice->transaction->type == 'sell_return') {
                        $zatcaInvoiceService->reportSellReturnInvoice($invoice->transaction);
                    }
                }
            }
        } catch (Exception $e) {
            logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()}");
            dd($e->getMessage());
        }
    }
}

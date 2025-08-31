@unless ($invoice->sent_to_zatca)
    <a data-href="{{ action('Zatca\ZatcaInvoiceController@send', $invoice->id) }}" class="btn btn-xs btn-primary"><i
            class="fas fa-arrows-rotate"></i> @lang('zatca.invoices.send') </a>
@endunless
<button data-href="{{ action('Zatca\ZatcaInvoiceController@show', $invoice->id) }}"
    class="btn btn-xs btn-success m-2 btn-modal" data-container=".zatca_invoice_show">
    <i class="fas fa-eye"></i>@lang('messages.view')
</button>

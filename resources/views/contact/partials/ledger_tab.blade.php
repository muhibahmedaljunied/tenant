@php
    $transaction_types = [];
    if(in_array($contact->type, ['both', 'supplier'])){
        $transaction_types['purchase'] = __('lang_v1.purchase');
        $transaction_types['purchase_return'] = __('lang_v1.purchase_return');
    }

    if(in_array($contact->type, ['both', 'customer'])){
        $transaction_types['sell'] = __('sale.sale');
        $transaction_types['sell_return'] = __('lang_v1.sell_return');
    }

    $transaction_types['opening_balance'] = __('lang_v1.opening_balance');
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="col-md-3">
            <div class="form-group">
                <label for="ledger_date_range">{{ __('report.date_range') }}:</label>
                <input type="text" name="ledger_date_range" id="ledger_date_range" placeholder="{{ __('lang_v1.select_a_date_range') }}" class="form-control" readonly>
            </div>
        </div>
        <div class="col-md-9 text-right">
            <button data-href="{{action('ContactController@getLedger')}}?contact_id={{$contact->id}}&action=pdf" class="btn btn-default btn-xs" id="print_ledger_pdf"><i class="fas fa-file-pdf"></i></button>

            <button type="button" class="btn btn-default btn-xs" id="send_ledger"><i class="fas fa-envelope"></i></button>
        </div>
    </div>
    <div id="contact_ledger_div"></div>
</div>
<span id="view_contact_page"></span>
<div class="row">
    <div class="col-md-12">
        <div class="col-sm-3">
            @include('contact.contact_basic_info')
        </div>
        <div class="col-sm-3 mt-56">
            @include('contact.contact_more_info')
        </div>

        @if(optional($contact)->type != 'customer')
            <div class="col-sm-3 mt-56">
                @include('contact.contact_tax_info')
            </div>
        @endif

        {{-- Optional block for sell return --}}
        @if(optional($contact)->type == 'customer' || optional($contact)->type == 'both')
            <div class="col-sm-3 @if(optional($contact)->type != 'both') mt-56 @endif">
                <strong>@lang('lang_v1.total_sell_return')</strong>
                <p class="text-muted">
                    <span class="display_currency" data-currency_symbol="true">
                        {{ optional($contact)->total_sell_return }}
                    </span>
                </p>
                <strong>@lang('lang_v1.total_sell_return_due')</strong>
                <p class="text-muted">
                    <span class="display_currency" data-currency_symbol="true">
                        {{ optional($contact)->total_sell_return - optional($contact)->total_sell_return_paid }}
                    </span>
                </p>
            </div>
        @endif

        @if(optional($contact)->type == 'supplier' || optional($contact)->type == 'both')
            <div class="clearfix"></div>
            <div class="col-sm-12">
                @if((optional($contact)->total_purchase - optional($contact)->purchase_paid) > 0)
                    <a href="{{ action('TransactionPaymentController@getPayContactDue', [optional($contact)->id]) }}?type=purchase"
                       class="pay_purchase_due btn btn-primary btn-sm pull-right">
                        <i class="fas fa-money-bill-alt" aria-hidden="true"></i>
                        @lang("contact.pay_due_amount")
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

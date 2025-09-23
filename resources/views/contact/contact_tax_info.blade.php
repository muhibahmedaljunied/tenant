@if(optional($contact)->tax_number)
    <strong><i class="fa fa-info margin-r-5"></i> @lang('contact.tax_no')</strong>
    <p class="text-muted">
        {{ optional($contact)->tax_number }}
    </p>
@endif

@if(optional($contact)->pay_term_type)
    <strong><i class="fa fa-calendar margin-r-5"></i> @lang('contact.pay_term_period')</strong>
    <p class="text-muted">
        {{ __('lang_v1.' . optional($contact)->pay_term_type) }}
    </p>
@endif

@if(optional($contact)->pay_term_number)
    <strong><i class="fas fa fa-handshake margin-r-5"></i> @lang('contact.pay_term')</strong>
    <p class="text-muted">
        {{ optional($contact)->pay_term_number }}
    </p>
@endif

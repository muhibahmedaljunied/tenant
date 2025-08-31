@extends('layouts.app')
@section('title', __('purchase.add_purchase'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('purchase.add_purchase') <i class="fa fa-keyboard-o hover-q text-muted" aria-hidden="true" data-container="body"
                data-toggle="popover" data-placement="bottom" data-content="@include('purchase.partials.keyboard_shortcuts_details')" data-html="true"
                data-trigger="hover" data-original-title="" title=""></i></h1>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Page level currency setting -->
        <input type="hidden" id="p_code" value="{{ $currency_details->code }}">
        <input type="hidden" id="p_symbol" value="{{ $currency_details->symbol }}">
        <input type="hidden" id="p_thousand" value="{{ $currency_details->thousand_separator }}">
        <input type="hidden" id="p_decimal" value="{{ $currency_details->decimal_separator }}">

        @include('layouts.partials.error')

        <form action="{{ action('PurchaseController@store') }}" method="POST" id="add_purchase_form"
            enctype="multipart/form-data">
            @csrf

            @component('components.widget', ['class' => 'box-primary'])
                <div class="row">



                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="location_id">{{ __('purchase.business_location') }}:*</label>
                            @show_tooltip(__('tooltip.purchase_location'))
                            <select name="location_id" id="location_id" class="form-control select2" required
                                placeholder="{{ __('messages.please_select') }}">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach ($business_locations as $key => $label)
                                    <option value="{{ $key }}">
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>






                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="store_id">{{ __('store.store') }}:*</label>
                            @show_tooltip(__('tooltip.purchase_store'))
                            <select name="store_id" id="store_id" class="form-control select2" required
                                placeholder="{{ __('messages.please_select') }}">

                            </select>
                        </div>
                    </div>
                    <div class="{{ !empty($default_purchase_status) ? 'col-sm-4' : 'col-sm-3' }}">
                        <div class="form-group">
                            <label for="ref_no">{{ __('purchase.ref_no') }}:</label>
                            @show_tooltip(__('lang_v1.leave_empty_to_autogenerate'))
                            <input type="text" name="ref_no" class="form-control" value="">
                        </div>
                    </div>


                    <div class="{{ !empty($default_purchase_status) ? 'col-sm-4' : 'col-sm-3' }}">
                        <div class="form-group">
                            <label for="transaction_date">{{ __('purchase.purchase_date') }}:*</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" name="transaction_date" class="form-control"
                                    value="{{ @format_datetime('now') }}" readonly required>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3 {{ !empty($default_purchase_status) ? 'hide' : '' }}">
                        <div class="form-group">
                            <label for="status">{{ __('purchase.purchase_status') }}:*</label>
                            @show_tooltip(__('tooltip.order_status'))
                            <select name="status" class="form-control select2" required>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach ($orderStatuses as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ $default_purchase_status == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="supplier_id">{{ __('purchase.supplier') }}:*</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                <select name="contact_id" id="supplier_id" class="form-control" required>
                                    <option value="">{{ __('messages.please_select') }}</option>
                                    {{-- Options to be loaded dynamically --}}
                                </select>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default bg-white btn-flat add_new_supplier"
                                        data-name="">
                                        <i class="fa fa-plus-circle text-primary fa-lg"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <strong>{{ __('business.address') }}:</strong>
                        <div id="supplier_address_div"></div>
                    </div>


                    <div class="col-sm-3 {{ !$currency_details->purchase_in_diff_currency ? 'hide' : '' }}">
                        <div class="form-group">
                            <label for="exchange_rate">{{ __('purchase.p_exchange_rate') }}:*</label>
                            @show_tooltip(__('tooltip.currency_exchange_factor'))
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-info"></i>
                                </span>
                                <input type="number" name="exchange_rate" id="exchange_rate" class="form-control" required
                                    step="0.001" value="{{ $currency_details->p_exchange_rate }}">
                            </div>
                            <span class="help-block text-danger">
                                {{ __('purchase.diff_purchase_currency_help', ['currency' => $currency_details->name]) }}
                            </span>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="multi-input">
                                <label for="pay_term_number">{{ __('contact.pay_term') }}:</label>
                                @show_tooltip(__('tooltip.pay_term'))<br>
                                <input type="number" name="pay_term_number" class="form-control width-40 pull-left"
                                    placeholder="{{ __('contact.pay_term') }}">
                                <select name="pay_term_type" id="pay_term_type" class="form-control width-60 pull-left"
                                    placeholder="{{ __('messages.please_select') }}">
                                    <option value="months">{{ __('lang_v1.months') }}</option>
                                    <option value="days">{{ __('lang_v1.days') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="upload_document">{{ __('purchase.attach_document') }}:</label>
                            <input type="file" name="document" id="upload_document"
                                accept="{{ implode(',', array_keys(config('constants.document_upload_mimes_types'))) }}">
                            <p class="help-block">
                                {{ __('purchase.max_file_size', ['size' => config('constants.document_size_limit') / 1000000]) }}
                                @includeIf('components.document_help_text')
                            </p>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="branch_cost_center_id">{{ __('purchase.branch_cost_center') }}:*</label>
                            @show_tooltip(__('tooltip.order_status'))
                            <select name="branch_cost_center_id" class="form-control select2"
                                placeholder="{{ __('messages.please_select') }}">
                                @foreach ($branch_cost_centers as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="extra_cost_center_id">{{ __('purchase.extra_cost_center') }}:*</label>
                            @show_tooltip(__('tooltip.order_status'))
                            <select name="extra_cost_center_id" class="form-control select2"
                                placeholder="{{ __('messages.please_select') }}">
                                @foreach ($extra_cost_centers as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                </div>
            @endcomponent



            @component('components.widget', ['class' => 'box-primary'])
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </span>
                                <input type="text" name="search_product" class="form-control mousetrap"
                                    id="search_product" placeholder="{{ __('lang_v1.search_product_placeholder') }}">



                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group">
                            <button type="button" tabindex="-1" class="btn btn-link btn-modal"
                                data-href="{{ action('ProductController@quickAdd') }}"
                                data-container=".quick_add_product_modal">
                                <i class="fa fa-plus"></i> {{ __('product.add_new_product') }}
                            </button>
                        </div>
                    </div>
                </div>

                @php
                    $hide_tax = session()->get('business.enable_inline_tax') == 0 ? 'hide' : '';
                @endphp


                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-condensed table-bordered table-th-green text-center table-striped"
                                id="purchase_entry_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('product.product_name')</th>
                                        <th>@lang('purchase.purchase_quantity')</th>
                                        <th>@lang('lang_v1.unit_cost_before_discount')</th>
                                        <th>@lang('lang_v1.discount_percent')</th>
                                        <th>@lang('purchase.unit_cost_before_tax')</th>
                                        <th class="{{ $hide_tax }}">@lang('purchase.subtotal_before_tax')</th>
                                        <th class="{{ $hide_tax }}">@lang('purchase.product_tax')</th>
                                        <th class="{{ $hide_tax }}">@lang('purchase.net_cost')</th>
                                        <th>@lang('purchase.line_total')</th>
                                        <th class="@if (!session('business.enable_editing_product_from_purchase')) hide @endif">
                                            @lang('lang_v1.profit_margin')
                                        </th>
                                        <th>@lang('purchase.unit_selling_price') <small>(@lang('product.inc_of_tax'))</small></th>
                                        @if (session('business.enable_lot_number'))
                                            <th>@lang('lang_v1.lot_number')</th>
                                        @endif
                                        @if (session('business.enable_patch_number'))
                                            <th>@lang('lang_v1.patch_number')</th>
                                        @endif
                                        @if (session('business.enable_product_expiry'))
                                            <th>@lang('product.mfg_date') / @lang('product.exp_date')</th>
                                        @endif
                                        <th><i class="fa fa-trash" aria-hidden="true"></i></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <hr />
                        <div class="pull-right col-md-5">
                            <table class="pull-right col-md-12">
                                <tr>
                                    <th class="col-md-7 text-right">@lang('lang_v1.total_items'):</th>
                                    <td class="col-md-5 text-left">
                                        <span id="total_quantity" class="display_currency"
                                            data-currency_symbol="false"></span>
                                    </td>
                                </tr>
                                <tr class="hide">
                                    <th class="col-md-7 text-right">@lang('purchase.total_before_tax'):</th>
                                    <td class="col-md-5 text-left">
                                        <span id="total_st_before_tax" class="display_currency"></span>
                                        <input type="hidden" id="st_before_tax_input" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="col-md-7 text-right">@lang('purchase.net_total_amount'):</th>
                                    <td class="col-md-5 text-left">
                                        <span id="total_subtotal" class="display_currency"></span>
                                        <input type="hidden" id="total_subtotal_input" value="0"
                                            name="total_before_tax">
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <input type="hidden" id="row_count" value="0">
                    </div>
                </div>
            @endcomponent

            @component('components.widget', ['class' => 'box-primary'])
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table">
                            <tr>
                                <td class="col-md-3">
                                    <div class="form-group">
                                        <label for="discount_type">@lang('purchase.discount_type'):</label>
                                        <select name="discount_type" id="discount_type" class="form-control select2">
                                            <option value="">@lang('lang_v1.none')</option>
                                            <option value="fixed">@lang('lang_v1.fixed')</option>
                                            <option value="percentage">@lang('lang_v1.percentage')</option>
                                        </select>
                                    </div>
                                </td>
                                <td class="col-md-3">
                                    <div class="form-group">
                                        <label for="discount_amount">@lang('purchase.discount_amount'):</label>
                                        <input type="text" name="discount_amount" id="discount_amount"
                                            class="form-control input_number" required value="0">
                                    </div>
                                </td>
                                <td class="col-md-3">&nbsp;</td>
                                <td class="col-md-3">
                                    <b>@lang('purchase.discount'):</b> (-)
                                    <span id="discount_calculated_amount" class="display_currency">0</span>
                                </td>
                            </tr>
                            <tr class="hide">
                                <td>
                                    <div class="form-group">
                                        <label for="tax_id">@lang('purchase.purchase_tax'):</label>
                                        <select name="tax_id" id="tax_id" class="form-control select2">
                                            <option value="" data-tax_amount="0" data-tax_type="fixed" selected>
                                                @lang('lang_v1.none')</option>
                                            @foreach ($taxes as $tax)
                                                <option value="{{ $tax->id }}" data-tax_amount="{{ $tax->amount }}"
                                                    data-tax_type="{{ $tax->calculation_type }}">
                                                    {{ $tax->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="tax_amount" id="tax_amount" value="0">
                                    </div>
                                </td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>
                                    <b>@lang('purchase.purchase_tax'):</b> (+)
                                    <span id="tax_calculated_amount" class="display_currency">0</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label for="shipping_details">@lang('purchase.shipping_details'):</label>
                                        <input type="text" name="shipping_details" id="shipping_details"
                                            class="form-control">
                                    </div>
                                </td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>
                                    <div class="form-group">
                                        <label for="shipping_charges">(+) @lang('purchase.additional_shipping_charges'):</label>
                                        <input type="text" name="shipping_charges" id="shipping_charges"
                                            class="form-control input_number" required value="0">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>
                                    <input type="hidden" name="final_total" id="grand_total_hidden" value="0">
                                    <b>@lang('purchase.purchase_total'):</b>
                                    <span id="grand_total" class="display_currency" data-currency_symbol="true">0</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <div class="form-group">
                                        <label for="additional_notes">@lang('purchase.additional_notes')</label>
                                        <textarea name="additional_notes" id="additional_notes" class="form-control" rows="3"></textarea>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endcomponent

            @component('components.widget', ['class' => 'box-primary', 'title' => __('purchase.add_payment')])
                <div class="box-body payment_row">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>@lang('purchase.purchase_total'):</strong>
                            <span style="color:red;" id="grand_total2">0</span>
                        </div>
                        <div class="col-md-6">
                            <strong>@lang('lang_v1.advance_balance'):</strong>
                            <span id="advance_balance_text">0</span>
                            <input type="hidden" name="advance_balance" id="advance_balance"
                                data-error-msg="@lang('lang_v1.required_advance_balance_not_available')">
                        </div>
                    </div>

                    <hr>
                    @include('sale_pos.partials.payment_row_form', ['row_index' => 0, 'show_date' => true])
                    <hr>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pull-right">
                                <strong>@lang('purchase.payment_due'):</strong>
                                <span id="payment_due">0.00</span>
                            </div>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" id="submit_purchase_form" class="btn btn-primary pull-right btn-flat">
                                @lang('messages.save')
                            </button>
                        </div>
                    </div>
                </div>
            @endcomponent

        </form>
    </section>
    <!-- quick product modal -->
    <div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>
    <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        @include('contact.create', ['quick_add' => true])
    </div>
    <!-- /.content -->
@endsection

@section('javascript')
    <script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            __page_leave_confirmation('#add_purchase_form');
            $('.paid_on').datetimepicker({
                format: moment_date_format + ' ' + moment_time_format,
                ignoreReadonly: true,
            });
        });
        $('#location_id').on('change', function() {

            console.log('hebo_change');
            var locationIds = $(this).val();
            if (!locationIds) {
                $('#store_id').html(
                    '<option value="">{{ __('messages.please_select') }}</option>');
                $('#store_id').trigger('change');
                return;
            }
            $.ajax({
                url: '{{ route('getStoresByLocationsPurchase') }}',
                type: 'POST',
                data: {
                    location_ids: locationIds,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    var options =
                        '<option value="">{{ __('messages.please_select') }}</option>';
                    $.each(data, function(key, value) {
                        options += '<option value="' + key + '">' +
                            value + '</option>';
                    });
                    $('#store_id').html(options).trigger('change');
                }
            });
        });

        $(document).on('change', '.payment_types_dropdown, #location_id', function(e) {
            var default_accounts = $('select#location_id').length ?
                $('select#location_id')
                .find(':selected')
                .data('default_payment_accounts') : [];
            var payment_types_dropdown = $('.payment_types_dropdown');
            var payment_type = payment_types_dropdown.val();
            var payment_row = payment_types_dropdown.closest('.payment_row');
            var row_index = payment_row.find('.payment_row_index').val();

            var account_dropdown = payment_row.find('select#account_' + row_index);
            if (payment_type && payment_type != 'advance') {
                var default_account = default_accounts && default_accounts[payment_type]['account'] ?
                    default_accounts[payment_type]['account'] : '';
                if (account_dropdown.length && default_accounts) {
                    account_dropdown.val(default_account);
                    account_dropdown.change();
                }
            }

            if (payment_type == 'advance') {
                if (account_dropdown) {
                    account_dropdown.prop('disabled', true);
                    account_dropdown.closest('.form-group').addClass('hide');
                }
            } else {
                if (account_dropdown) {
                    account_dropdown.prop('disabled', false);
                    account_dropdown.closest('.form-group').removeClass('hide');
                }
            }
        });
    </script>
    @include('purchase.partials.keyboard_shortcuts')
@endsection

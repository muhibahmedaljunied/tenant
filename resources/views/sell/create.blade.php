@extends('layouts.app')

@php
    if (!empty($status) && in_array($status, ['quotation', 'draft'])) {
        $title = __("lang_v1.add_{$status}");
    } else {
        $title = __('sale.add_sale');
    }
@endphp

@section('title', $title)

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>{{ $title }}</h1>
    </section>
    <!-- Main content -->
    <section class="content no-print">
        <input type="hidden" id="amount_rounding_method" value="{{ $pos_settings['amount_rounding_method'] ?? '' }}">
        @if (!empty($pos_settings['allow_overselling']))
            <input type="hidden" id="is_overselling_allowed">
        @endif
        @if (session('business.enable_rp') == 1)
            <input type="hidden" id="reward_point_enabled">
        @endif
        @if (count($business_locations) > 0)
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-map-marker"></i>
                            </span>
                            <select name="select_location_id" id="select_location_id" class="form-control" required
                                autofocus>
                                <option value="">{{ __('lang_v1.select_location') }}</option>
                                @foreach ($business_locations as $key => $value)
                                    <option value="{{ $key }}" @if (old('select_location_id') == $key) selected @endif>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                            <span class="input-group-addon">
                                @show_tooltip(__('tooltip.sale_location'))
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-map-marker"></i>
                            </span>
                            <select name="select_store_id" id="select_store_id" class="form-control" required autofocus>
                                <option value="">{{ __('lang_v1.select_store') }}</option>
                            </select>
                            <span class="input-group-addon">
                                @show_tooltip(__('tooltip.select_store'))
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif




        @php
            $custom_labels = json_decode(session('business.custom_labels'), true);
        @endphp
        <input type="hidden" id="item_addition_method" value="{{ $business_details->item_addition_method }}">
        <form action="{{ action('SellPosController@store') }}" method="post" id="add_sell_form"
            enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    @component('components.widget', ['class' => 'box-solid'])
              
                        <input type="hidden" name="location_id" id="location_id"
                            value="{{ !empty($default_location) ? $default_location->id : '' }}"
                            data-receipt_printer_type="{{ !empty($default_location->receipt_printer_type) ? $default_location->receipt_printer_type : 'browser' }}"
                            data-default_payment_accounts="{{ !empty($default_location) ? $default_location->default_payment_accounts : '' }}">

                        <input type="hidden" name="store_id" id="store_id">

                        <div class="col-sm-3">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </span>
                                    <select name="branch_cost_center_id" id="branch_cost_center_id" class="form-control"
                                        autofocus>
                                        <option value="">{{ __('chart_of_accounts.please_select_cost_center') }}</option>
                                        @foreach ($branch_cost_centers as $key => $value)
                                            <option value="{{ $key }}"
                                                @if (old('branch_cost_center_id') == $key) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </span>
                                    <select name="extra_cost_center_id" id="extra_cost_center_id" class="form-control"
                                        autofocus>
                                        <option value="">{{ __('lang_v1.select_extra_cost_center') }}</option>
                                        @foreach ($extra_cost_centers as $key => $value)
                                            <option value="{{ $key }}"
                                                @if (old('extra_cost_center_id') == $key) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-addon">
                                        @show_tooltip(__('tooltip.sale_location'))
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if (in_array('types_of_service', $enabled_modules) && !empty($types_of_service))
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-external-link-square-alt text-primary service_modal_btn"></i>
                                        </span>
                                        <select name="types_of_service_id" id="types_of_service_id" class="form-control"
                                            style="width: 100%;">
                                            <option value="">{{ __('lang_v1.select_types_of_service') }}</option>
                                            @foreach ($types_of_service as $key => $value)
                                                <option value="{{ $key }}"
                                                    @if (old('types_of_service_id') == $key) selected @endif>{{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="types_of_service_price_group"
                                            id="types_of_service_price_group">
                                        <span class="input-group-addon">
                                            @show_tooltip(__('lang_v1.types_of_service_help'))
                                        </span>
                                    </div>
                                    <small>
                                        <p class="help-block hide" id="price_group_text">@lang('lang_v1.price_group'): <span></span></p>
                                    </small>
                                </div>
                            </div>
                            <div class="modal fade types_of_service_modal" tabindex="-1" role="dialog"
                                aria-labelledby="gridSystemModalLabel"></div>
                        @endif
                        @if (in_array('subscription', $enabled_modules))
                            <div class="col-md-4 pull-right col-sm-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="is_recurring" id="is_recurring" class="input-icheck"
                                            value="1"> @lang('lang_v1.subscribe')?
                                    </label><button type="button" data-toggle="modal" data-target="#recurringInvoiceModal"
                                        class="btn btn-link"><i
                                            class="fa fa-external-link"></i></button>@show_tooltip(__('lang_v1.recurring_invoice_help'))
                                </div>
                            </div>
                        @endif
                        <div class="clearfix"></div>
                        <div class="@if (!empty($commission_agent)) col-sm-3 @else col-sm-4 @endif">
                         



                            <div class="form-group">
                                <label for="contact_id">{{ __('contact.customer') }}:*</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <input type="hidden" id="default_customer_id" value="{{ $walk_in_customer['id'] }}">
                                    <input type="hidden" id="default_customer_name"
                                        value="{{ $walk_in_customer['name'] }}">
                                    <input type="hidden" id="default_customer_balance"
                                        value="{{ $walk_in_customer['balance'] ?? '' }}">
                                    <input type="hidden" id="default_customer_address"
                                        value="{{ $walk_in_customer['shipping_address'] ?? '' }}">
                                    @if (
                                        !empty($walk_in_customer['price_calculation_type']) &&
                                            $walk_in_customer['price_calculation_type'] == 'selling_price_group')
                                        <input type="hidden" id="default_selling_price_group"
                                            value="{{ $walk_in_customer['selling_price_group_id'] ?? '' }}">
                                    @endif
                                    <select name="contact_id" id="customer_id" class="form-control mousetrap select2"
                                        required>
                                        <option value="">Enter Customer name / phone</option>
                                        {{-- Options will be loaded dynamically via JS --}}
                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default bg-white btn-flat add_new_customer"
                                            data-name=""><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                    </span>
                                </div>
                            </div>
                            
                            
                            <small>
                                <strong>@lang('lang_v1.billing_address'):</strong>
                                <div id="billing_address_div">
                                    {!! $walk_in_customer['contact_address'] ?? '' !!}
                                </div>
                                <br>
                                <strong>@lang('lang_v1.shipping_address'):</strong>
                                <div id="shipping_address_div">
                                    {{ $walk_in_customer['supplier_business_name'] ?? '' }},<br>
                                    {{ $walk_in_customer['name'] ?? '' }},<br>
                                    {{ $walk_in_customer['shipping_address'] ?? '' }}
                                </div>
                            </small>
                        </div>
                        @if (!empty($price_groups))
                            @if (count($price_groups) > 0)
                                <div class="col-sm-3">
                                    <label for="pay_term_number">{{ __('lang_v1.pricgroup') }}:</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fas fa-money-bill-alt"></i>
                                            </span>
                                            @php reset($price_groups); @endphp
                                            <input type="hidden" name="hidden_price_group" id="hidden_price_group"
                                                value="{{ key($price_groups) }}">
                                            <select name="price_group" id="price_group" class="form-control select2">
                                                <option value="">{{ __('lang_v1.select.pricgroup') }}</option>
                                                @foreach ($price_groups as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (old('price_group') == $key) selected @endif>{{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-addon">
                                                @show_tooltip(__('lang_v1.price_group_help_text'))
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @php reset($price_groups); @endphp
                                <input type="hidden" name="price_group" id="price_group" value="{{ key($price_groups) }}">
                            @endif
                        @endif
                        <input type="hidden" name="default_price_group" id="default_price_group">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="multi-input">
                                    <label for="pay_term_number">{{ __('contact.pay_term') }}:</label>
                                    @show_tooltip(__('tooltip.pay_term'))
                                    <br />
                                    <input type="number" name="pay_term_number" class="form-control width-40 pull-left"
                                        placeholder="{{ __('contact.pay_term') }}"
                                        value="{{ $walk_in_customer['pay_term_number'] }}">
                                    <select name="pay_term_type" class="form-control width-60 pull-left">
                                        <option value="">{{ __('messages.please_select') }}</option>
                                        <option value="months" @if ($walk_in_customer['pay_term_type'] == 'months') selected @endif>
                                            {{ __('lang_v1.months') }}</option>
                                        <option value="days" @if ($walk_in_customer['pay_term_type'] == 'days') selected @endif>
                                            {{ __('lang_v1.days') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if (!empty($commission_agent))
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="commission_agent">{{ __('lang_v1.commission_agent') }}:</label>
                                    <select name="commission_agent" class="form-control select2">
                                        <option value="">{{ __('messages.please_select') }}</option>
                                        @foreach ($commission_agent as $key => $value)
                                            <option value="{{ $key }}"
                                                @if (old('commission_agent') == $key) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="@if (!empty($commission_agent)) col-sm-3 @else col-sm-4 @endif">
                            <div class="form-group">
                                <label for="transaction_date">{{ __('sale.sale_date') }}:*</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" name="transaction_date" class="form-control"
                                        value="{{ $default_datetime }}" readonly required>
                                </div>
                            </div>
                        </div>
                        @if (!empty($status))
                            <input type="hidden" name="status" id="status" value="{{ $status }}">
                        @else
                            <div class="@if (!empty($commission_agent)) col-sm-3 @else col-sm-4 @endif">
                                <div class="form-group">
                                    <label for="status">{{ __('sale.status') }}:*</label>
                                    <select name="status" id="status" class="form-control select2" required>
                                        <option value="">{{ __('messages.please_select') }}</option>
                                        <option value="final">{{ __('sale.final') }}</option>
                                        <option value="draft">{{ __('sale.draft') }}</option>
                                        <option value="proforma">{{ __('lang_v1.proforma') }}</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="invoice_scheme_id">{{ __('invoice.invoice_scheme') }}:</label>
                                <select name="invoice_scheme_id" class="form-control select2">
                                    <option value="">{{ __('messages.please_select') }}</option>
                                    @foreach ($invoice_schemes as $key => $value)
                                        <option value="{{ $key }}"
                                            @if ($default_invoice_schemes->id == $key) selected @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @can('edit_invoice_number')
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="invoice_no">{{ __('sale.invoice_no') }}:</label>
                                    <input type="text" name="invoice_no" class="form-control"
                                        placeholder="{{ __('sale.invoice_no') }}">
                                    <p class="help-block">@lang('lang_v1.keep_blank_to_autogenerate')</p>
                                </div>
                            </div>
                        @endcan
                        @php
                            $custom_field_1_label = !empty($custom_labels['sell']['custom_field_1'])
                                ? $custom_labels['sell']['custom_field_1']
                                : '';

                            $is_custom_field_1_required =
                                !empty($custom_labels['sell']['is_custom_field_1_required']) &&
                                $custom_labels['sell']['is_custom_field_1_required'] == 1
                                    ? true
                                    : false;

                            $custom_field_2_label = !empty($custom_labels['sell']['custom_field_2'])
                                ? $custom_labels['sell']['custom_field_2']
                                : '';

                            $is_custom_field_2_required =
                                !empty($custom_labels['sell']['is_custom_field_2_required']) &&
                                $custom_labels['sell']['is_custom_field_2_required'] == 1
                                    ? true
                                    : false;

                            $custom_field_3_label = !empty($custom_labels['sell']['custom_field_3'])
                                ? $custom_labels['sell']['custom_field_3']
                                : '';

                            $is_custom_field_3_required =
                                !empty($custom_labels['sell']['is_custom_field_3_required']) &&
                                $custom_labels['sell']['is_custom_field_3_required'] == 1
                                    ? true
                                    : false;

                            $custom_field_4_label = !empty($custom_labels['sell']['custom_field_4'])
                                ? $custom_labels['sell']['custom_field_4']
                                : '';

                            $is_custom_field_4_required =
                                !empty($custom_labels['sell']['is_custom_field_4_required']) &&
                                $custom_labels['sell']['is_custom_field_4_required'] == 1
                                    ? true
                                    : false;
                        @endphp
                        @if (!empty($custom_field_1_label))
                            @php
                                $label_1 = $custom_field_1_label . ':';
                                if ($is_custom_field_1_required) {
                                    $label_1 .= '*';
                                }
                            @endphp

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="custom_field_1">{{ $label_1 }}</label>
                                    <input type="text" name="custom_field_1" class="form-control"
                                        placeholder="{{ $custom_field_1_label }}"
                                        @if ($is_custom_field_1_required) required @endif>
                                </div>
                            </div>
                        @endif
                        @if (!empty($custom_field_2_label))
                            @php
                                $label_2 = $custom_field_2_label . ':';
                                if ($is_custom_field_2_required) {
                                    $label_2 .= '*';
                                }
                            @endphp

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="custom_field_2">{{ $label_2 }}</label>
                                    <input type="text" name="custom_field_2" class="form-control"
                                        placeholder="{{ $custom_field_2_label }}"
                                        @if ($is_custom_field_2_required) required @endif>
                                </div>
                            </div>
                        @endif
                        @if (!empty($custom_field_3_label))
                            @php
                                $label_3 = $custom_field_3_label . ':';
                                if ($is_custom_field_3_required) {
                                    $label_3 .= '*';
                                }
                            @endphp

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="custom_field_3">{{ $label_3 }}</label>
                                    <input type="text" name="custom_field_3" class="form-control"
                                        placeholder="{{ $custom_field_3_label }}"
                                        @if ($is_custom_field_3_required) required @endif>
                                </div>
                            </div>
                        @endif
                        @if (!empty($custom_field_4_label))
                            @php
                                $label_4 = $custom_field_4_label . ':';
                                if ($is_custom_field_4_required) {
                                    $label_4 .= '*';
                                }
                            @endphp

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="custom_field_4">{{ $label_4 }}</label>
                                    <input type="text" name="custom_field_4" class="form-control"
                                        placeholder="{{ $custom_field_4_label }}"
                                        @if ($is_custom_field_4_required) required @endif>
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="upload_document">{{ __('purchase.attach_document') }}:</label>
                                <input type="file" name="sell_document" id="upload_document"
                                    accept="{{ implode(',', array_keys(config('constants.document_upload_mimes_types'))) }}">
                                <p class="help-block">
                                    @lang('purchase.max_file_size', ['size' => config('constants.document_size_limit') / 1000000])
                                    @includeIf('components.document_help_text')
                                </p>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- Call restaurant module if defined -->
                        @if (in_array('tables', $enabled_modules) || in_array('service_staff', $enabled_modules))
                            <span id="restaurant_module_span">
                            </span>
                        @endif
                    @endcomponent

                    @component('components.widget', ['class' => 'box-solid'])
                        <div class="col-sm-10 col-sm-offset-1">

                            <h4>@lang('lang_v1.currency') : {{ $currency_data['symbol'] }} </h4>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default bg-white btn-flat" data-toggle="modal"
                                            data-target="#configure_search_modal"
                                            title="{{ __('lang_v1.configure_product_search') }}"><i
                                                class="fa fa-barcode"></i></button>
                                    </div>
                                    <input type="text" name="search_product" id="search_product"
                                        class="form-control mousetrap"
                                        placeholder="{{ __('lang_v1.search_product_placeholder') }}" disabled="true"
                                        autofocus="{{ is_null($default_location) ? 'false' : 'true' }}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default bg-white btn-flat pos_add_quick_product"
                                            data-href="{{ action('ProductController@quickAdd') }}"
                                            data-container=".quick_add_product_modal"><i
                                                class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row col-sm-12 pos_product_div" style="min-height: 0">

                            <input type="hidden" name="sell_price_tax" id="sell_price_tax"
                                value="{{ $business_details->sell_price_tax }}">

                            <!-- Keeps count of product rows -->
                            <input type="hidden" id="product_row_count" value="0">
                            @php
                                $hide_tax = '';
                                if (session()->get('business.enable_inline_tax') == 0) {
                                    $hide_tax = 'hide';
                                }
                            @endphp



                            {{-- table for products  used for  direct sell --}}
                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered table-striped table-responsive"
                                    id="pos_table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                @lang('sale.product')
                                            </th>
                                            <th class="text-center">
                                                @lang('sale.qty')
                                            </th>
                                            @if (!empty($pos_settings['inline_service_staff']))
                                                <th class="text-center">
                                                    @lang('restaurant.service_staff')
                                                </th>
                                            @endif

                                            <th @can('edit_product_price_from_sale_screen')) hide @endcan>
                                                @lang('sale.unit_price')
                                            </th>

                                            <th @can('edit_product_discount_from_sale_screen') hide @endcan>
                                                @lang('receipt.discount')
                                            </th>

                                            <th class="text-center {{ $hide_tax }}">
                                                @lang('sale.tax')
                                            </th>

                                            <th class="text-center {{ $hide_tax }}">
                                                @lang('sale.price_inc_tax')
                                            </th>

                                            @if (!empty($warranties))
                                                <th>@lang('lang_v1.warranty')</th>
                                            @endif

                                            <th class="text-center">
                                                @lang('sale.subtotal')
                                            </th>

                                            <th class="text-center"><i class="fas fa-times" aria-hidden="true"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>



                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered table-striped">
                                    <tr>
                                        <td>
                                            <div class="pull-right">
                                                <b>@lang('sale.item'):</b>
                                                <span class="total_quantity">0</span>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                <b>@lang('sale.total'): </b>
                                                <span class="price_total">0</span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endcomponent
                    @component('components.widget', ['class' => 'box-solid'])
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="discount_type">{{ __('sale.discount_type') }}:*</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <select name="discount_type" id="discount_type" class="form-control" required
                                        data-default="percentage">
                                        <option value="fixed">{{ __('lang_v1.fixed') }}</option>
                                        <option value="percentage" selected>{{ __('lang_v1.percentage') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @php
                            $max_discount = !is_null(auth()->user()->max_sales_discount_percent)
                                ? auth()->user()->max_sales_discount_percent
                                : '';

                            //if sale discount is more than user max discount change it to max discount
                            $sales_discount = $business_details->default_sales_discount;
                            if ($max_discount != '' && $sales_discount > $max_discount) {
                                $sales_discount = $max_discount;
                            }
                        @endphp
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="discount_amount">{{ __('sale.discount_amount') }}:*</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <input type="text" name="discount_amount" id="discount_amount"
                                        class="form-control input_number" data-default="{{ $sales_discount }}"
                                        data-max-discount="{{ $max_discount }}"
                                        data-max-discount-error_msg="{{ __('lang_v1.max_discount_error_msg', ['discount' => $max_discount != '' ? @num_format($max_discount) : '']) }}"
                                        value="{{ @num_format($sales_discount) }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4"><br>
                            <b>@lang('sale.discount_amount'):</b>(-)
                            <span class="display_currency" id="total_discount">0</span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-12 well well-sm bg-light-gray @if (session('business.enable_rp') != 1) hide @endif">
                            <input type="hidden" name="rp_redeemed" id="rp_redeemed" value="0">
                            <input type="hidden" name="rp_redeemed_amount" id="rp_redeemed_amount" value="0">
                            <div class="col-md-12">
                                <h4>{{ session('business.rp_name') }}</h4>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="rp_redeemed_modal">{{ __('lang_v1.redeemed') }}:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-gift"></i>
                                        </span>
                                        <input type="number" name="rp_redeemed_modal" id="rp_redeemed_modal"
                                            class="form-control direct_sell_rp_input"
                                            data-amount_per_unit_point="{{ session('business.redeem_amount_per_unit_rp') }}"
                                            min="0" data-max_points="0"
                                            data-min_order_total="{{ session('business.min_order_total_for_redeem') }}">
                                        <input type="hidden" id="rp_name" value="{{ session('business.rp_name') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <p><strong>@lang('lang_v1.available'):</strong> <span id="available_rp">0</span></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>@lang('lang_v1.redeemed_amount'):</strong> (-)<span id="rp_redeemed_amount_text">0</span></p>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-4 hide">
                            <div class="form-group">
                                <label for="tax_rate_id">{{ __('sale.order_tax') }}:*</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <select name="tax_rate_id" id="tax_rate_id" class="form-control"
                                        data-default="{{ $business_details->default_sales_tax }}">
                                        <option value="">{{ __('messages.please_select') }}</option>
                                        @foreach ($taxes['tax_rates'] as $key => $value)
                                            <option value="{{ $key }}"
                                                @if ($business_details->default_sales_tax == $key) selected @endif>{{ $value }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount"
                                        value="@if (empty($edit)) {{ @num_format($business_details->tax_calculation_amount) }} @else {{ @num_format(optional($transaction->tax)->amount) }} @endif"
                                        data-default="{{ $business_details->tax_calculation_amount }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-md-offset-4">
                            <b>@lang('sale.order_tax'):</b>(+)
                            <span class="display_currency" id="order_tax">0</span>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="sell_note">{{ __('sale.sell_note') }}</label>
                                <textarea name="sale_note" id="sell_note" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="is_direct_sale" value="1">
                    @endcomponent
                    @component('components.widget', ['class' => 'box-solid'])
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="shipping_details">{{ __('sale.shipping_details') }}</label>
                                <textarea name="shipping_details" id="shipping_details" class="form-control"
                                    placeholder="{{ __('sale.shipping_details') }}" rows="3" cols="30"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="shipping_address">{{ __('lang_v1.shipping_address') }}</label>
                                <textarea name="shipping_address" id="shipping_address" class="form-control"
                                    placeholder="{{ __('lang_v1.shipping_address') }}" rows="3" cols="30"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="shipping_charges">{{ __('sale.shipping_charges') }}</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <input type="text" name="shipping_charges" id="shipping_charges"
                                        class="form-control input_number" placeholder="{{ __('sale.shipping_charges') }}"
                                        value="{{ @num_format(0.0) }}">
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="shipping_status">{{ __('lang_v1.shipping_status') }}</label>
                                <select name="shipping_status" id="shipping_status" class="form-control">
                                    <option value="">{{ __('messages.please_select') }}</option>
                                    @foreach ($shipping_statuses as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="delivered_to">{{ __('lang_v1.delivered_to') }}:</label>
                                <input type="text" name="delivered_to" id="delivered_to" class="form-control"
                                    placeholder="{{ __('lang_v1.delivered_to') }}">
                            </div>
                        </div>
                        @php
                            $shipping_custom_label_1 = !empty($custom_labels['shipping']['custom_field_1'])
                                ? $custom_labels['shipping']['custom_field_1']
                                : '';

                            $is_shipping_custom_field_1_required =
                                !empty($custom_labels['shipping']['is_custom_field_1_required']) &&
                                $custom_labels['shipping']['is_custom_field_1_required'] == 1
                                    ? true
                                    : false;

                            $shipping_custom_label_2 = !empty($custom_labels['shipping']['custom_field_2'])
                                ? $custom_labels['shipping']['custom_field_2']
                                : '';

                            $is_shipping_custom_field_2_required =
                                !empty($custom_labels['shipping']['is_custom_field_2_required']) &&
                                $custom_labels['shipping']['is_custom_field_2_required'] == 1
                                    ? true
                                    : false;

                            $shipping_custom_label_3 = !empty($custom_labels['shipping']['custom_field_3'])
                                ? $custom_labels['shipping']['custom_field_3']
                                : '';

                            $is_shipping_custom_field_3_required =
                                !empty($custom_labels['shipping']['is_custom_field_3_required']) &&
                                $custom_labels['shipping']['is_custom_field_3_required'] == 1
                                    ? true
                                    : false;

                            $shipping_custom_label_4 = !empty($custom_labels['shipping']['custom_field_4'])
                                ? $custom_labels['shipping']['custom_field_4']
                                : '';

                            $is_shipping_custom_field_4_required =
                                !empty($custom_labels['shipping']['is_custom_field_4_required']) &&
                                $custom_labels['shipping']['is_custom_field_4_required'] == 1
                                    ? true
                                    : false;

                            $shipping_custom_label_5 = !empty($custom_labels['shipping']['custom_field_5'])
                                ? $custom_labels['shipping']['custom_field_5']
                                : '';

                            $is_shipping_custom_field_5_required =
                                !empty($custom_labels['shipping']['is_custom_field_5_required']) &&
                                $custom_labels['shipping']['is_custom_field_5_required'] == 1
                                    ? true
                                    : false;
                        @endphp

                        @if (!empty($shipping_custom_label_1))
                            @php
                                $label_1 = $shipping_custom_label_1 . ':';
                                if ($is_shipping_custom_field_1_required) {
                                    $label_1 .= '*';
                                }
                            @endphp

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="shipping_custom_field_1">{{ $label_1 }}</label>
                                    <input type="text" name="shipping_custom_field_1" class="form-control"
                                        placeholder="{{ $shipping_custom_label_1 }}"
                                        @if ($is_shipping_custom_field_1_required) required @endif>
                                </div>
                            </div>
                        @endif
                        @if (!empty($shipping_custom_label_2))
                            @php
                                $label_2 = $shipping_custom_label_2 . ':';
                                if ($is_shipping_custom_field_2_required) {
                                    $label_2 .= '*';
                                }
                            @endphp

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="shipping_custom_field_2">{{ $label_2 }}</label>
                                    <input type="text" name="shipping_custom_field_2" class="form-control"
                                        placeholder="{{ $shipping_custom_label_2 }}"
                                        @if ($is_shipping_custom_field_2_required) required @endif>
                                </div>
                            </div>
                        @endif
                        @if (!empty($shipping_custom_label_3))
                            @php
                                $label_3 = $shipping_custom_label_3 . ':';
                                if ($is_shipping_custom_field_3_required) {
                                    $label_3 .= '*';
                                }
                            @endphp

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="shipping_custom_field_3">{{ $label_3 }}</label>
                                    <input type="text" name="shipping_custom_field_3" class="form-control"
                                        placeholder="{{ $shipping_custom_label_3 }}"
                                        @if ($is_shipping_custom_field_3_required) required @endif>
                                </div>
                            </div>
                        @endif
                        @if (!empty($shipping_custom_label_4))
                            @php
                                $label_4 = $shipping_custom_label_4 . ':';
                                if ($is_shipping_custom_field_4_required) {
                                    $label_4 .= '*';
                                }
                            @endphp

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="shipping_custom_field_4">{{ $label_4 }}</label>
                                    <input type="text" name="shipping_custom_field_4" class="form-control"
                                        placeholder="{{ $shipping_custom_label_4 }}"
                                        @if ($is_shipping_custom_field_4_required) required @endif>
                                </div>
                            </div>
                        @endif
                        @if (!empty($shipping_custom_label_5))
                            @php
                                $label_5 = $shipping_custom_label_5 . ':';
                                if ($is_shipping_custom_field_5_required) {
                                    $label_5 .= '*';
                                }
                            @endphp

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="shipping_custom_field_5">{{ $label_5 }}</label>
                                    <input type="text" name="shipping_custom_field_5" class="form-control"
                                        placeholder="{{ $shipping_custom_label_5 }}"
                                        @if ($is_shipping_custom_field_5_required) required @endif>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="shipping_documents">{{ __('lang_v1.shipping_documents') }}:</label>
                                <input type="file" name="shipping_documents[]" id="shipping_documents" multiple
                                    accept="{{ implode(',', array_keys(config('constants.document_upload_mimes_types'))) }}">
                                <p class="help-block">
                                    @lang('purchase.max_file_size', ['size' => config('constants.document_size_limit') / 1000000])
                                    @includeIf('components.document_help_text')
                                </p>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-4 col-md-offset-8">
                            @if (!empty($pos_settings['amount_rounding_method']) && $pos_settings['amount_rounding_method'] > 0)
                                <small id="round_off"><br>(@lang('lang_v1.round_off'): <span id="round_off_text">0</span>)</small>
                                <br />
                                <input type="hidden" name="round_off_amount" id="round_off_amount" value=0>
                            @endif
                            <div><b>@lang('sale.total_payable'): </b>
                                <input type="hidden" name="final_total" id="final_total_input">
                                <span id="total_payable">0</span>
                            </div>
                        </div>
                    @endcomponent
                </div>
            </div>
            @if (empty($status) || !in_array($status, ['quotation', 'draft']))
                @can('sell.payments')
                    @component('components.widget', [
                        'class' => 'box-solid',
                        'id' => 'payment_rows_div',
                        'title' => __('purchase.add_payment'),
                    ])
                        <div class="payment_row">
                            <div class="row">
                                <div class="col-md-12 mb-12">
                                    <strong>@lang('lang_v1.advance_balance'):</strong> <span id="advance_balance_text"></span>
                                    <input type="hidden" name="advance_balance" id="advance_balance"
                                        data-error-msg="{{ __('lang_v1.required_advance_balance_not_available') }}">
                                </div>
                            </div>
                            @include('sale_pos.partials.payment_row_form', [
                                'row_index' => 0,
                                'show_date' => true,
                            ])
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="pull-right"><strong>@lang('lang_v1.balance'):</strong> <span
                                            class="balance_due">0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcomponent
                @endcan
            @endif

            <div class="row">
                <input type="hidden" name="is_save_and_print" id="is_save_and_print" value="0">
                <div class="col-sm-12 text-right">
                    <button type="button" id="submit-sell" class="btn btn-primary btn-flat">@lang('messages.save')</button>
                    <button type="button" id="save-and-print"
                        class="btn btn-primary btn-flat">@lang('lang_v1.save_and_print')</button>
                </div>
            </div>

            @if (empty($pos_settings['disable_recurring_invoice']))
                @include('sale_pos.partials.recurring_invoice_modal')
            @endif

        </form>
    </section>

    <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        @include('contact.create', ['quick_add' => true])
    </div>
    <!-- /.content -->
    <div class="modal fade register_details_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade close_register_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <!-- quick product modal -->
    <div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>

    @include('sale_pos.partials.configure_search_modal')

@stop

@section('javascript')
    <script src="{{ url('js/pos.js?v=' . $asset_v) }}"></script>
    <script src="{{ url('js/product.js?v=' . $asset_v) }}"></script>
    <script src="{{ url('js/opening_stock.js?v=' . $asset_v) }}"></script>
    <!-- Call restaurant module if defined -->
    @if (in_array('tables', $enabled_modules) ||
            in_array('modifiers', $enabled_modules) ||
            in_array('service_staff', $enabled_modules))
        <script src="{{ url('js/restaurant.js?v=' . $asset_v) }}"></script>
    @endif
    <script type="text/javascript">
        $(document).ready(function() {

            $('#select_location_id').on('change', function() {

                // console.log('hebo_change');
                var locationIds = $(this).val();
                if (!locationIds) {
                    $('#select_store_id').html(
                        '<option value="">{{ __('messages.please_select') }}</option>');
                    $('#select_store_id').trigger('change');
                    return;
                }
                $.ajax({
                    url: '{{ route('getStoresByLocationsSell') }}',
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
                        $('#select_store_id').html(options).trigger('change');
                    }
                });
            });


            $('#status').change(function() {
                if ($(this).val() == 'final') {
                    $('#payment_rows_div').removeClass('hide');
                } else {
                    $('#payment_rows_div').addClass('hide');
                }
            });
            $('.paid_on').datetimepicker({
                format: moment_date_format + ' ' + moment_time_format,
                ignorereadonly: true,
            });

            $('#shipping_documents').fileinput({
                showUpload: false,
                showPreview: false,
                browseLabel: LANG.file_browse_label,
                removeLabel: LANG.remove,
            });
        });
    </script>
@endsection

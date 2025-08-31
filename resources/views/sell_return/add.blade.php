@extends('layouts.app')
@section('title', __('lang_v1.sell_return'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header no-print">
        <h1>@lang('lang_v1.sell_return')</h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">

        <input type="hidden" name="location_id" id="location_id" value="{{ $sell->location->id }}" data-receipt_printer_type="{{ $sell->location->receipt_printer_type }}">

        <form action="{{ action('SellReturnController@store') }}" method="post" id="sell_return_form">
            @csrf
            <input type="hidden" name="transaction_id" value="{{ $sell->id }}">
            <div class="box box-solid">
                <div class="box-header">
                    <h3 class="box-title">@lang('lang_v1.parent_sale')</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>@lang('sale.invoice_no'):</strong> {{ $sell->invoice_no }} <br>
                            <strong>@lang('messages.date'):</strong> {{ @format_date($sell->transaction_date) }}
                        </div>
                        <div class="col-sm-4">
                            <strong>@lang('contact.customer'):</strong> {{ $sell->contact->name }} <br>
                            <strong>@lang('purchase.business_location'):</strong> {{ $sell->location->name }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="box box-solid">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="invoice_no">@lang('sale.invoice_no'):</label>
                                <input type="text" name="invoice_no" id="invoice_no" class="form-control" value="{{ !empty($sell->return_parent->invoice_no) ? $sell->return_parent->invoice_no : null }}">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="transaction_date">@lang('messages.date') :*</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    @php
                                        $transaction_date = !empty($sell->return_parent->transaction_date) ? $sell->return_parent->transaction_date : 'now';
                                    @endphp
                                    <input type="text" name="transaction_date" id="transaction_date" class="form-control" value="{{ @format_datetime($transaction_date) }}" readonly required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <table class="table bg-gray" id="sell_return_table">
                                <thead>
                                    <tr class="bg-green">
                                        <th>#</th>
                                        <th>@lang('product.product_name')</th>
                                        <th>@lang('sale.unit_price')</th>
                                        <th>@lang('lang_v1.sell_quantity')</th>
                                        <th>@lang('lang_v1.item_tax')</th>
                                        <th>@lang('lang_v1.return_quantity')</th>
                                        <th>@lang('lang_v1.return_subtotal')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sell->sell_lines as $sell_line)
                                        @php
                                            $check_decimal = 'false';
                                            if ($sell_line->product->unit->allow_decimal == 0) {
                                                $check_decimal = 'true';
                                            }

                                            $unit_name = $sell_line->related_variations->unit->short_name ?? $sell_line->product->unit->short_name;

                                            if (!empty($sell_line->sub_unit)) {
                                                $unit_name = $sell_line->sub_unit->short_name;

                                                if ($sell_line->sub_unit->allow_decimal == 0) {
                                                    $check_decimal = 'true';
                                                } else {
                                                    $check_decimal = 'false';
                                                }
                                            }

                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $sell_line->product->name }}
                                                @if ($sell_line->product->type == 'variable')
                                                    - {{ $sell_line->variations->product_variation->name }}
                                                    - {{ $sell_line->variations->name }}
                                                @endif
                                            </td>
                                            <td><span class="display_currency"
                                                    data-currency_symbol="true">{{ $sell_line->unit_price_inc_tax }}</span></td>
                                            <td>{{ $sell_line->formatted_qty }} {{ $unit_name }}</td>
                                            <td>
                                                {{ $sell_line->formatted_line_tax }}
                                                <input type="hidden" class="unit_tax_price"
                                                    value="{{ @num_format($sell_line->item_tax) }}">
                                            </td>

                                            <td>
                                                <input type="text" name="products[{{ $loop->index }}][quantity]"
                                                    value="{{ @format_quantity($sell_line->quantity_returned) }}"
                                                    class="form-control input-sm input_number return_qty input_quantity"
                                                    data-rule-abs_digit="{{ $check_decimal }}"
                                                    data-msg-abs_digit="@lang('lang_v1.decimal_value_not_allowed')"
                                                    data-rule-max-value="{{ $sell_line->quantity }}"
                                                    data-msg-max-value="@lang('validation.custom-messages.quantity_not_available', ['qty' => $sell_line->formatted_qty, 'unit' => $unit_name])">
                                                <input name="products[{{ $loop->index }}][unit_price_inc_tax]" type="hidden"
                                                    class="unit_price"
                                                    value="{{ @num_format($sell_line->unit_price_inc_tax) }}">
                                                <input name="products[{{ $loop->index }}][sell_line_id]" type="hidden"
                                                    value="{{ $sell_line->id }}">
                                            </td>
                                            <td>
                                                <div class="return_subtotal"></div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        @php
                            $discount_type = !empty($sell->return_parent->discount_type) ? $sell->return_parent->discount_type : $sell->discount_type;
                            $discount_amount = !empty($sell->return_parent->discount_amount) ? $sell->return_parent->discount_amount : $sell->discount_amount;
                        @endphp
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="discount_type">@lang('purchase.discount_type'):</label>
                                <select name="discount_type" id="discount_type" class="form-control">
                                    <option value="">@lang('lang_v1.none')</option>
                                    <option value="fixed" @if($discount_type == 'fixed') selected @endif>@lang('lang_v1.fixed')</option>
                                    <option value="percentage" @if($discount_type == 'percentage') selected @endif>@lang('lang_v1.percentage')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="discount_amount">@lang('purchase.discount_amount'):</label>
                                <input type="text" name="discount_amount" id="discount_amount" class="form-control input_number" value="{{ @num_format($discount_amount) }}">
                            </div>
                        </div>
                    </div>
                    @php
                        $tax_percent = 0;
                        if (!empty($sell->tax)) {
                            $tax_percent = $sell->tax->amount;
                        }
                    @endphp
                    <input type="hidden" name="tax_id" value="{{ $sell->tax_id }}">
                    <input type="hidden" name="tax_amount" id="tax_amount" value="0">
                    <input type="hidden" name="tax_percent" id="tax_percent" value="{{ $tax_percent }}">
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <strong>@lang('lang_v1.total_return_discount'):</strong>
                            &nbsp;(-) <span id="total_return_discount"></span>
                        </div>
                        <div class="col-sm-12 text-right">
                            <strong>@lang('lang_v1.total_products_tax'):</strong>
                            &nbsp; <span id="total_products_tax"></span>
                        </div>
                        <div class="col-sm-12 text-right">
                            <strong>@lang('lang_v1.total_return_tax') - @if (!empty($sell->tax))
                                    ({{ $sell->tax->name }} - {{ $sell->tax->amount }}%)
                                @endif : </strong>
                            &nbsp;(+) <span id="total_return_tax"></span>
                        </div>
                        <div class="col-sm-12 text-right">
                            <strong>@lang('lang_v1.return_total'): </strong>&nbsp;
                            <span id="net_return">0</span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </section>
@stop
@section('javascript')
    <script src="{{ asset('js/printer.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/sell_return.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('form#sell_return_form').validate();
            update_sell_return_total();
            //Date picker
        });

      

        $(document).on('change', 'input.return_qty, #discount_amount, #discount_type', function() {
            update_sell_return_total()
        });

        function update_sell_return_total() {
            var net_return = 0;
            let total_products_tax = 0;
            $('table#sell_return_table tbody tr').each(function() {
                var quantity = __read_number($(this).find('input.return_qty'));
                var unit_tax_price = __read_number($(this).find('input.unit_tax_price'));
                var unit_price = __read_number($(this).find('input.unit_price'));
                var subtotal = quantity * unit_price;
                $(this).find('.return_subtotal').text(__currency_trans_from_en(subtotal, true));
                net_return += subtotal;
                total_products_tax += (unit_tax_price * quantity);
            });
            var discount = 0;
            if ($('#discount_type').val() == 'fixed') {
                discount = __read_number($("#discount_amount"));
            } else if ($('#discount_type').val() == 'percentage') {
                var discount_percent = __read_number($("#discount_amount"));
                discount = __calculate_amount('percentage', discount_percent, net_return);
            }
            discounted_net_return = net_return - discount;

            var tax_percent = $('input#tax_percent').val();
            var total_tax = __calculate_amount('percentage', tax_percent, discounted_net_return);
            var net_return_inc_tax = total_tax + discounted_net_return;

            $('input#tax_amount').val(total_tax);
            $('span#total_return_discount').text(__currency_trans_from_en(discount, true));
            $('span#total_return_tax').text(__currency_trans_from_en(total_tax, true));
            $('span#total_products_tax').text(__currency_trans_from_en(total_products_tax, true));
            $('span#net_return').text(__currency_trans_from_en(net_return_inc_tax, true));
        }
    </script>
@endsection

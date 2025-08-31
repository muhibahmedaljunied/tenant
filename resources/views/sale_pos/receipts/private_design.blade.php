<!-- business information here -->
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt-{{ $receipt_details->invoice_no }}</title>
</head>
{{--
<!-- @include('sale_pos.receipts.invoice_style_a4') --> --}}

<body class="invoice">
    <style>
        .private_invoice tfoot>tr {
            background-color: #a9a9a9
        }

        table.private_invoice {
            table-layout: fixed;
            margin: auto;
        }

        .private_invoice tr td:nth-child(1) {
            font-weight: 800;
        }

        .private_invoice tr td:nth-child(2) {
            text-align: center;
        }

        .private_invoice tr td:nth-child(3) {
            font-weight: 800;
            text-align: right;
        }

        .invoice .col-sm-3 {
            width: 25%;
            float: left;
        }

        .invoice .col-sm-4 {
            width: 33.33333333%;
        }

        .invoice .col-sm-6 {
            width: 50%;
            float: left;
        }

        table.private_invoice,
        .private_invoice th,
        .private_invoice td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .private_invoice td {
            padding: 5px;
            word-wrap: break-word;
        }

        .table-details tfoot>tr {
            background-color: #a9a9a9;
        }

        .private_invoice.address {
            vertical-align: top;
            height: 80px;
        }

        .flex-evenly {
            display: flex;
            justify-content: space-evenly;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
        }

        .private_invoice.products thead tr th {
            text-align: center;

        }

        .private_invoice.products thead tr {
            background-color: #ccc;
        }

        .align-center {
            align-items: center !important;
        }

        .justify-center {
            justify-content: center !important;
        }
    </style>
    <div class="">
        <div class="row align-center">
            <div class="col-sm-3">
                @include('sale_pos.partials.qr_code')

            </div>
            <div class="col-sm-6">
                <!-- Logo -->
                @if (!empty($receipt_details->logo))
                <img style="max-height: 120px; width: auto;" src="{{ $receipt_details->logo }}"
                    class="img img-responsive center-block">
                @endif

                <!-- Header text -->
                @if (!empty($receipt_details->header_text))
                <div class="col-xs-12">
                    {!! $receipt_details->header_text !!}
                </div>
                @endif
            </div>
            <div class="col-sm-3">

                @if (!empty($receipt_details->display_name))
                <span>
                    {{ $receipt_details->display_name }}
                    @if (!empty($receipt_details->address))
                    <br />{!! $receipt_details->address !!}
                    @endif

                    @if (!empty($receipt_details->contact))
                    <br />{!! $receipt_details->contact !!}
                    @endif

                    @if (!empty($receipt_details->website))
                    <br />{{ $receipt_details->website }}
                    @endif

                    @if (!empty($receipt_details->tax_info1))
                    <br />{{ $receipt_details->tax_label1 }} {{ $receipt_details->tax_info1 }}
                    @endif

                    @if (!empty($receipt_details->tax_info2))
                    <br />{{ $receipt_details->tax_label2 }} {{ $receipt_details->tax_info2 }}
                    @endif

                    @if (!empty($receipt_details->location_custom_fields))
                    <br />{{ $receipt_details->location_custom_fields }}
                    @endif
                </span>
                @endif
            </div>
        </div>
        <h1 style="text-align: center"><b>فاتوره ضريبيه</b></h1>
        <div class="flex-evenly">
            <table style="width:50%;text-align:center;margin:5px;" class="table-details private_invoice">
                <thead></thead>



                <tbody>
                    <tr>
                        <td>Customr Number</td>
                        <td>

                            @if (!empty($receipt_details->client_id_label))
                            <br />
                            <strong>{{ $receipt_details->client_id_label }}</strong>
                            {{ $receipt_details->client_id }}
                            @endif
                        </td>
                        <td>رقم العميل</td>
                    </tr>
                    @if (!empty($receipt_details->customer_name))
                    <tr>
                        <td>Customr Name</td>
                        <td>

                            {{ $receipt_details->customer_name }}<br>
                        </td>
                        <td>اسم العميل</td>
                    </tr>
                    @endif
                    @if (!empty($receipt_details->customer_tax_label) && !empty($receipt_details->customer_tax_number))
                    <tr>
                        <td>Customr VAT Number</td>
                        <td>{{ $receipt_details->customer_tax_number }}</td>
                        <td>الرقم الضريبي للعميل</td>
                    </tr>
                    @endif
                    @if (!empty($receipt_details->customer_info))
                    <tr class="address">
                        <td>Customer Details</td>
                        <td>
                            {!! $receipt_details->customer_info !!}
                        </td>
                        <td>تفاصيل العميل</td>
                    </tr>
                    @endif

                    @if (!empty($receipt_details->customer_rp_label))
                    <tr class="address">
                        <td>{{ $receipt_details->customer_rp_label }}</td>
                        <td>
                            {!! $receipt_details->customer_total_rp !!}
                        </td>
                        <td>{{ $receipt_details->customer_rp_label }}</td>
                    </tr>
                    @endif
                    @if (!empty($receipt_details->customer_custom_fields))
                    <tr class="address">
                        <td>Customer Details</td>
                        <td>
                            {!! $receipt_details->customer_custom_fields !!}
                        </td>
                        <td>تفاصيل اخري</td>
                    </tr>
                    @endif
                    @if (!empty($receipt_details->shipping_custom_field_1_label))
                    <tr class="">
                        <td colspan="2">{!! $receipt_details->shipping_custom_field_1_label !!}
                        </td>
                        <td>
                            {!! $receipt_details->shipping_custom_field_1_value !!}
                        </td>
                    </tr>
                    @endif
                    @if (!empty($receipt_details->shipping_custom_field_2_label))
                    <tr class="">
                        <td colspan="2">{!! $receipt_details->shipping_custom_field_2_label !!}
                        </td>
                        <td>
                            {!! $receipt_details->shipping_custom_field_2_value !!}
                        </td>
                    </tr>
                    @endif
                    @if (!empty($receipt_details->shipping_custom_field_3_label))
                    <tr class="">
                        <td colspan="2">{!! $receipt_details->shipping_custom_field_3_label !!}
                        </td>
                        <td>
                            {!! $receipt_details->shipping_custom_field_3_value !!}
                        </td>
                    </tr>
                    @endif
                    @if (!empty($receipt_details->shipping_custom_field_4_label))
                    <tr class="">
                        <td colspan="2">{!! $receipt_details->shipping_custom_field_4_label !!}
                        </td>
                        <td>
                            {!! $receipt_details->shipping_custom_field_4_value !!}
                        </td>
                    </tr>
                    @endif
                    @if (!empty($receipt_details->shipping_custom_field_5_label))
                    <tr class="">
                        <td colspan="2">{!! $receipt_details->shipping_custom_field_5_label !!}
                        </td>
                        <td>
                            {!! $receipt_details->shipping_custom_field_5_value !!}
                        </td>
                    </tr>
                    @endif

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" rowspan="2">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
            <table style="width:50%;text-align:center;margin:5px;margin-bottom: auto;"
                class="private_invoice table-details">
                <thead>

                </thead>
                <tbody>
                    <tr>
                        <td>Invoice Number</td>
                        <td>
                            {{-- @if (!empty($receipt_details->invoice_no_prefix))
                            <span class="pull-left">{!! $receipt_details->invoice_no_prefix !!}</span>
                            @endif --}}
                            {{ $receipt_details->invoice_no }}

                        </td>
                        <td>رقم الفاتوره</td>
                    </tr>

                    @if (!empty($receipt_details->date_label))
                    <tr>
                        <td>Invoice Date</td>
                        <td>{{ $receipt_details->invoice_date }}</td>
                        <td>تاريخ الفاتوره</td>
                    </tr>
                    @endif
                    @if (!empty($receipt_details->due_date_label))
                    <tr>
                        <td>Due Date</td>
                        <td>{{ $receipt_details->due_date ?? '' }}</td>
                        <td>تاريخ الاستحقاق</td>
                    </tr>
                    @endif
                    @if (!empty($receipt_details->sales_person_label))
                    <tr>
                        <td>Sales Person</td>
                        <td>{{ $receipt_details->commission_agent }}</td>
                        <td>موظف المبيعات</td>
                    </tr>
                    @endif


                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" rowspan="2">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div>
            <table class="products private_invoice" style="width: 100%; margin-top: 25px;">
                <thead>
                    <tr>
                        <th>التسلسل</th>
                        <th colspan="2">الوصف</th>
                        {{-- @if ($receipt_details->show_cat_code == 1)
                        <td style="">{{ $receipt_details->cat_code_label }}</td>
                        @endif --}}
                        <th>الكميه</th>
                        <th>الوحده</th>
                        <th>سعر الوحده</th>
                        <th>الضريبه</th>
                        <th>الاجمالي</th>
                        {{-- <th>رقم الصنف</th> --}}
                        {{-- <th>خصم الصحه</th> --}}
                        {{-- @if (!empty($line['modifiers']))
                        <th>الخصم</th>
                        @endif --}}
                        {{-- <th>القيمة المضافه</th> --}}
                        {{-- <th>صافي القيمه</th> --}}

                    </tr>
                </thead>
                <tbody>
                    @foreach ($receipt_details->lines as $line)
                    <tr>
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td colspan="2" style="word-break: break-all;">
                            @if (!empty($line['image']))
                            <img src="{{ $line['image'] }}" alt="Image" width="50"
                                style="float: left; margin-right: 8px;">
                            @endif
                            {{ $line['name'] }} {{ $line['product_variation'] }} {{ $line['variation'] }}
                            @if (!empty($line['sub_sku']))
                            <br /> {{ $line['sub_sku'] }}
                            @endif @if (!empty($line['brand']))
                            <br /> {{ $line['brand'] }}
                            @endif
                            @if (!empty($line['product_custom_fields']))
                            <br /> {{ $line['product_custom_fields'] }}
                            @endif
                            @if (!empty($line['sell_line_note']))
                            <br>
                            <small>{{ $line['sell_line_note'] }}</small>
                            @endif
                            @if (!empty($line['lot_number']))
                            <br> {{ $line['lot_number_label'] }}: {{ $line['lot_number'] }}
                            @endif
                            @if (!empty($line['product_expiry']))
                            , {{ $line['product_expiry_label'] }}: {{ $line['product_expiry'] }}
                            @endif
                            @if (!empty($line['patch_number']))
                            - {{ $line['patch_number_label'] }}: {{ $line['patch_number'] }}
                            @endif

                            @if (!empty($line['warranty_name']))
                            <br><small>{{ $line['warranty_name'] }} </small>
                            @endif @if (!empty($line['warranty_exp_date']))
                            <small>- {{ @format_date($line['warranty_exp_date']) }} </small>
                            @endif
                            @if (!empty($line['warranty_description']))
                            <small> {{ $line['warranty_description'] ?? '' }}</small>
                            @endif
                        </td>

                        {{-- @if ($receipt_details->show_cat_code == 1)
                        <td>
                            @if (!empty($line['cat_code']))
                            {{ $line['cat_code'] }}
                            @endif
                        </td>
                        @endif --}}

                        <td class="text-right">
                            {{ $line['quantity'] }}
                        </td>
                        <td class="text-right">
                            {{ $line['units'] }}
                        </td>


                        <td class="text-right">
                            {{ $line['unit_price'] }}
                        </td>
                        <td class="text-right">
                            {{ $line['tax'] }}
                        </td>
                        <td class="text-right">
                            {{ $line['line_total'] }}
                        </td>
                    </tr>
                    @if (!empty($line['modifiers']))
                    @foreach ($line['modifiers'] as $modifier)
                    <tr>
                        <td class="text-center">
                            &nbsp;
                        </td>
                        <td>
                            {{ $modifier['name'] }} {{ $modifier['variation'] }}
                            @if (!empty($modifier['sub_sku']))
                            , {{ $modifier['sub_sku'] }}
                            @endif
                            @if (!empty($modifier['sell_line_note']))
                            ({{ $modifier['sell_line_note'] }})
                            @endif
                        </td>

                        @if ($receipt_details->show_cat_code == 1)
                        <td>
                            @if (!empty($modifier['cat_code']))
                            {{ $modifier['cat_code'] }}
                            @endif
                        </td>
                        @endif

                        <td class="text-right">
                            {{ $modifier['quantity'] }} {{ $modifier['units'] }}
                        </td>
                        <td class="text-right">
                            {{ $modifier['unit_price_exc_tax'] }}
                        </td>
                        <td class="text-right">
                            {{ $modifier['line_total'] }}
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    @endforeach




                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <span class="flex-between">
                                <span>الاجمالي</span>
                                <span>Total</span>
                            </span>
                        </td>
                        <td colspan="5">
                            {{ $receipt_details->total }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr />
            </div>
            <div class="col-xs-6">

                <table class="table table-slim">
                    @if (!empty($receipt_details->payments))
                    @foreach ($receipt_details->payments as $payment)
                    <tr>
                        <td>{{ $payment['method'] }}</td>
                        <td class="text-right">{{ $payment['amount'] }}</td>
                        <td class="text-right">{{ $payment['date'] }}</td>
                    </tr>
                    @endforeach
                    @endif

                    <!-- Total Paid-->
                    @if (!empty($receipt_details->total_paid))
                    <tr>
                        <th>
                            {!! $receipt_details->total_paid_label !!}
                        </th>
                        <td class="text-right">
                            {{ $receipt_details->total_paid }}
                        </td>
                    </tr>
                    @endif

                    <!-- Total Due-->
                    @if (!empty($receipt_details->total_due))
                    <tr>
                        <th>
                            {!! $receipt_details->total_due_label !!}
                        </th>
                        <td class="text-right">
                            {{ $receipt_details->total_due }}
                        </td>
                    </tr>
                    @endif

                    @if (!empty($receipt_details->all_due))
                    <tr>
                        <th>
                            {!! $receipt_details->all_bal_label !!}
                        </th>
                        <td class="text-right">
                            {{ $receipt_details->all_due }}
                        </td>
                    </tr>
                    @endif
                </table>
            </div>

            <div class="col-xs-6">
                <div class="table-responsive">
                    <table class="table table-slim">
                        <tbody>
                            @if (!empty($receipt_details->total_quantity_label))
                            <tr class="color-555">
                                <th style="width:70%">
                                    {!! $receipt_details->total_quantity_label !!}
                                </th>
                                <td class="text-right">
                                    {{ $receipt_details->total_quantity }}
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th style="width:70%">
                                    {!! $receipt_details->subtotal_label !!}
                                </th>
                                <td class="text-right">
                                    {{ $receipt_details->subtotal }}
                                </td>
                            </tr>
                            @if (!empty($receipt_details->total_exempt_uf))
                            <tr>
                                <th style="width:70%">
                                    @lang('lang_v1.exempt')
                                </th>
                                <td class="text-right">
                                    {{ $receipt_details->total_exempt }}
                                </td>
                            </tr>
                            @endif
                            <!-- Shipping Charges -->
                            @if (!empty($receipt_details->shipping_charges))
                            <tr>
                                <th style="width:70%">
                                    {!! $receipt_details->shipping_charges_label !!}
                                </th>
                                <td class="text-right">
                                    {{ $receipt_details->shipping_charges }}
                                </td>
                            </tr>
                            @endif

                            @if (!empty($receipt_details->packing_charge))
                            <tr>
                                <th style="width:70%">
                                    {!! $receipt_details->packing_charge_label !!}
                                </th>
                                <td class="text-right">
                                    {{ $receipt_details->packing_charge }}
                                </td>
                            </tr>
                            @endif

                            <!-- Discount -->
                            @if (!empty($receipt_details->discount))
                            <tr>
                                <th>
                                    {!! $receipt_details->discount_label !!}
                                </th>

                                <td class="text-right">
                                    (-) {{ $receipt_details->total_discount }}
                                </td>
                            </tr>
                            @endif
                            <!-- Tax -->
                            @if (!empty($receipt_details->tax_label))
                            <tr>
                                <th>
                                    {!! $receipt_details->tax_label !!}
                                </th>
                                <td class="text-right">
                                    (+) {{ $receipt_details->tax }}
                                </td>
                            </tr>
                            @endif
                            {{-- @if (!empty($receipt_details->total_line_discount))
                            <tr>
                                <th>
                                    {!! $receipt_details->line_discount_label !!}
                                </th>

                                <td class="text-right">
                                    (-) {{$receipt_details->total_line_discount}}
                                </td>
                            </tr>
                            @endif --}}

                            @if (!empty($receipt_details->reward_point_label))
                            <tr>
                                <th>
                                    {!! $receipt_details->reward_point_label !!}
                                </th>

                                <td class="text-right">
                                    (-) {{ $receipt_details->reward_point_amount }}
                                </td>
                            </tr>
                            @endif



                            @if ($receipt_details->round_off_amount > 0)
                            <tr>
                                <th>
                                    {!! $receipt_details->round_off_label !!}
                                </th>
                                <td class="text-right">
                                    {{ $receipt_details->round_off }}
                                </td>
                            </tr>
                            @endif

                            <!-- Total -->
                            <tr>
                                <th>
                                    {!! $receipt_details->total_label !!}
                                </th>
                                <td class="text-right">
                                    {{ $receipt_details->total }}
                                    @if (!empty($receipt_details->total_in_words))
                                    <br>
                                    <small>({{ $receipt_details->total_in_words }})</small>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-xs-12">
                <p>{!! nl2br($receipt_details->additional_notes) !!}</p>
            </div>
        </div>

        <div>
            <table class="private_invoice" style="width: 100%; margin-top: 40px;">
                <thead>

                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="flex-between">
                                <span dir="auto">Receiver Name:</span>
                                <span dir="auto">اسم المستلم:</span>
                            </div>
                            <div class="flex-between">
                                <span dir="auto">Signature:</span>
                                <span dir="auto">التوقيع:</span>
                            </div>

                        </td>
                        <td>
                            <div class="flex-between">
                                <span dir="auto">Checked By:</span>
                                <span dir="auto">المراجع :</span>
                            </div>
                            <div class="flex-between">
                                <span dir="auto">Signature:</span>
                                <span dir="auto">التوقيع:</span>
                            </div>

                        </td>
                        <td>
                            <div class="flex-between">
                                <span dir="auto">Storekeeper:</span>
                                <span dir="auto">أمين المستودع:</span>
                            </div>
                            <div class="flex-between">
                                <span dir="auto">Signature:</span>
                                <span dir="auto">التوقيع:</span>
                            </div>

                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr style="background-color: #fff;">
                        <td colspan="3" style="color:black">
                            @if (!empty($receipt_details->footer_text))
                            <p class="centered">
                                {!! $receipt_details->footer_text !!}
                            </p>
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


</body>

</html>
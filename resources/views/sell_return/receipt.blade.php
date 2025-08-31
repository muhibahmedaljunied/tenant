<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt-{{ $receipt_details->invoice_no }}</title>
    <style>
        #private_invoice .border-black {
            border-color: black;
        }

        #private_invoice .border-left {
            border-left: 1px solid black;
        }

        #private_invoice .table-border,
        #private_invoice .table-border th {
            border: 1px solid black;
            border-collapse: collapse;
        }

        #private_invoice .qr {
            margin-top: -67px;
        }

        #private_invoice {
            font-size: 1.5rem;
        }

        #private_invoice .items {
            margin-top: 1rem;
        }

        #private_invoice .items th {
            text-align: center;
        }

        #private_invoice .items tr {
            border: 1px solid black;
        }

        #private_invoice .items td {
            border-collapse: collapse;
            border: 1px solid black;

            text-align: center;
            font-weight: 600;
        }

        #private_invoice .table-items td {
            padding: 6px 1rem;
            text-align: center;
            font-weight: 600;
        }

        #private_invoice .table-items tr {
            border: 1px solid black;
        }

        #private_invoice .text-center {
            text-align: center !important;
        }

        #private_invoice .m-auto {
            margin: auto !important;
        }

        #private_invoice .invoice-title {
            display: inline-block;
            padding: 8px 6rem;
            border: 3px solid black;
            margin-top: 0;
        }

        #private_invoice .d-flex {
            display: flex;
        }

        #private_invoice .justify-content-between {
            justify-content: space-between;
        }

        #private_invoice .w-50 {
            width: 50%;
        }

        #private_invoice .w-100 {
            width: 100%;
        }

        #private_invoice .text-left {
            text-align: left;
        }

        #private_invoice .ml-auto {
            margin-left: auto;
        }

        #private_invoice .mr-auto {
            margin-right: auto;
        }

        #private_invoice .payments_table table th {
            text-align: right;
        }
    </style>
</head>

<body dir="rtl">

    <div id="private_invoice" dir="rtl">
        <header>
            <div class="">
                <div class="col-12">
                    <div class="row">
                        <!-- Logo -->
                        <div>
                            @if (!empty($receipt_details->logo))
                                <div class="col-12">
                                    <img style="max-height:120px;width:100%;object-fit:contain;" src="{{ $receipt_details->logo }}"
                                        class="img img-responsive center-block">
                                </div>
                            @endif
                        </div>

                        <!-- Header text -->
                        @if (!empty($receipt_details->header_text))
                            <div class="col-xs-12">
                                {!! $receipt_details->header_text !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </header>
        <hr class="border-black" style="margin-top:0;" />
        <div class="m-auto text-center">
            <h4 class="text-center invoice-title">فاتوره مرتجع</h4>
        </div>
        <div class="table-items">
            <div class="d-flex justify-content-between">
                <div class="qr">
                    @include('sale_pos.partials.qr_code')
                </div>
                <div class="w-50">
                    <div class="ml-auto text-left">
                        <div class="bar_code">
                            @if ($receipt_details->show_barcode)
                                <img width="150"
                                    src="data:image/png;base64,{{ DNS1D::getBarcodePNG($receipt_details->invoice_no, 'C128', 2, 30, [39, 48, 54], true) }}">
                            @endif
                        </div>
                        <table class="mr-auto table-border" style="width: 100%">
                            <tbody>
                                <tr>
                                    <td>رقم الفاتوره</td>
                                    <td>{{ $receipt_details->invoice_no }}</td>
                                    <td>Invoice Number</td>
                                </tr>
                                <tr>
                                    <td>تاريخ الفاتوره</td>
                                    <td>{{ $receipt_details->invoice_date }}</td>
                                    <td>Invoice Date</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- {{
            dd($receipt_details)
        }} --}}
        <div class="user_info">
            <div class="d-flex table-border text-center">
                <table class="w-50 border-left">

                    <tr>
                        <td>العميل</td>
                        <td>
                            @if (!empty($receipt_details->customer_name))
                                {!! $receipt_details->customer_name !!}<br>
                            @endif
                            {{-- {{ $receipt_details->customer_name }}  --}}
                            @if (!empty($receipt_details->customer_supplier_business_name))
                                <br />{!! $receipt_details->customer_supplier_business_name !!}
                            @endif
                        </td>
                        <td>Client</td>
                    </tr>

                    {{-- <tr>
                        <td>اسم النشاط</td>
                        <td></td>
                        <td>Business Name</td>
                    </tr> --}}

                    <tr>
                        <td>الهاتف</td>
                        <td>
                            @if (!empty($receipt_details->customer_mobile))
                                <br />{!! $receipt_details->customer_mobile !!}
                            @endif
                        </td>
                        <td>Mobile</td>
                    </tr>

                    <tr>
                        <td>عنوان</td>
                        <td>{!! $receipt_details->customer_info_address ?? '' !!}</td>
                        <td>Address</td>
                    </tr>
                </table>
                <table class="w-50">
                    {{-- <tr> --}}
                    {{-- <td>طريقة الدفع</td>
                        <td><b>{{ $receipt_details->payments[0]['method'] ?? '' }}</b></td>
                        <td>Pay Method</td>
                    </tr>
                    <tr>
                        <td>المندوب</td>
                        <td>{{ $receipt_details->sales_person }}</td>
                        <td>Sales Man</td>
                    </tr> --}}
                    <tr>
                        <td>الرقم الضريبي للعميل</td>
                        <td>{{ $receipt_details->customer_tax_number }}</td>
                        <td>Customr VAT Number</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="items">
            <table class="table table-border w-100" lang="en" dir="ltr">
                <thead>
                    <tr>
                        <th>م</th>
                        <th colspan="3" style="word-break: break-all;">
                            الصنف
                            <br />
                            Item
                        </th>
                        <th>
                            الوحده
                            <br />

                            Unit
                        </th>
                        <th>
                            الكميه
                            <br />

                            Quantity
                        </th>
                        <th>
                            السعر
                            <br />
                            Price
                        </th>
                        {{-- <th>
                            الخصم
                            <br />
                            Discount
                        </th>
                        <th>
                            السعر بعد الخصم
                        </th> --}}
                        <th>
                            اجمالي
                            <br />
                            Total
                        </th>
                        <th>
                            الضريبه
                            <br />
                            Vat
                        </th>
                        <th>
                            الصافي
                            <br />
                            Net
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($receipt_details->lines as $line)
                        <tr scope="row">
                            <td>{{ $loop->iteration }}</td>
                            <td colspan="3" style="word-break: break-all;">
                                @if (!empty($line['image']))
                                    <img src="{{ $line['image'] }}" alt="Image" width="50"
                                        style="float: left; margin-right: 8px;">
                                @endif
                                {{ $line['name'] }} {{ $line['product_variation'] ?? '' }} {{ $line['variation'] }}
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
                                    <br> {{ $line['product_expiry_label'] }}: {{ $line['product_expiry'] }}
                                    @endif
                                    @if (!empty($line['patch_number']))
                                        <br /> {{ $line['patch_number_label'] }}: {{ $line['patch_number'] }}
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
                            <td>
                                {{ $line['units'] }}
                            </td>
                            <td>
                                {{ $line['quantity'] }}
                            </td>
                            <td>
                                {{ $line['unit_price'] }}
                            </td>
                            {{-- <td>
                                {{ $line['line_discount'] }}
                            </td> --}}
                            <td>
                                {{ $line['line_total_exc_tax'] }}
                            </td>
                            <td>
                                {{ $line['line_total_tax'] }}
                            </td>
                            <td class="text-right">
                                {{ $line['line_total'] }}
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row payments_table">

            <div class="col-md-12">
                <hr class="mt-0" />

            </div>
            <div class="col-xs-6">

                <table class="table table-slim">
                    @if (!empty($receipt_details->payments))
                        @foreach ($receipt_details->payments as $payment)
                            <tr>
                                <td> <strong>{{ $payment['method'] }}</strong> </td>
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
                            <tr>
                                <th>ضريبة القيمة المضافة :</th>
                                <td class="text-right">
                                    {{ $receipt_details->total_taxes }}
                                </td>
                            </tr>
                            

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
                                    الإجمالي مرتجع شامل الضريبة :
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

        </div>
        <div class="col-xs-12 mb-3">
            <p>ملاحظات : </p>
            <p>{!! nl2br($receipt_details->additional_notes) !!}</p>
        </div>
        <footer>
            @if (!empty($receipt_details->footer_text))
                <p class="centered">
                    {!! $receipt_details->footer_text !!}
                </p>
            @endif
            {{-- <tr style="background-color: #fff;">
                <td colspan="3" style="color:black">
                </td>
            </tr> --}}
        </footer>
    </div>
</body>

</html>
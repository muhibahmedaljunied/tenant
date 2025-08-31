<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt-{{ $receipt_details->invoice_no }}</title>

    <style>
        /* #private_invoice {
            font-family: 'Cairo', 'sans-serif';
            background: #ccc; 
        } */

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
            border-right: 1px solid;
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

        #private_invoice .w-25 {
            width: 25%;
        }

        #private_invoice .px-3 {
            padding: 0 1rem;
        }

        #private_invoice .w-75 {
            width: 75%;
        }

        .col-xs-1,
        .col-xs-10,
        .col-xs-11,
        .col-xs-12,
        .col-xs-2,
        .col-xs-3,
        .col-xs-4,
        .col-xs-5,
        .col-xs-6,
        .col-xs-7,
        .col-xs-8,
        .col-xs-9 {
            float: right;
        }
    </style>
</head>

<body dir="rtl" class="invoice">
    <div id="private_invoice" dir="rtl">
        <div class="m-auto text-center">
            <h4 class="text-center invoice-title">{{ session('business.name') }}</h4>
        </div>

        <div class="col-sm-12">
            @if (!empty($receipt_details->header_text))
            <div class="col-xs-12">
                {!! $receipt_details->header_text !!}
            </div>
            @endif
        </div>
        <div class="user_info">
            <div class="row">
                <div class="col-sm-12">
                    @if(!empty($receipt_details->logo))
                    <div class="text-box centered">
                        <img style="max-height: 100px; width: auto;" src="{{$receipt_details->logo}}" alt="Logo">
                    </div>
                @endif
                    {{-- <img style="max-height:120px;width:100%;object-fit:cover;" src="{{ $receipt_details->logo }}"
                        class="img img-responsive center-block"> --}}
                </div>
                <div class="col-sm-12">
                    <div class="d-flex table-items table-border text-center">
                        <table class="table-border w-50 border-left">
                            <tr>
                                <td>جوال العميل</td>
                                <td style="padding-bottom:11px;">
                                    @if (!empty($receipt_details->customer_mobile))
                            {!! $receipt_details->customer_mobile !!}
                            @endif
                                </td>
                            </tr>
                            <tr>
                                <td>عنوان العميل</td>
                                <td>{!! trim($receipt_details->customer_full_address) ?? '' !!}</td>
                            </tr>
                            <tr>
                                <td>المندوب</td>
                                <td>{{ $receipt_details->commission_agent }}</td>
                            </tr>

                            <tr>
                                <td>تاريخ الفاتوره</td>
                                <td>{{ $receipt_details->invoice_date }}</td>
                            </tr>
                            {{-- <tr>
                                <td>جوال</td>
                                <td>
                                    @if (!empty($receipt_details->customer_mobile))
                                    {!! $receipt_details->customer_mobile !!}
                                    @endif
                                </td>
                            </tr> --}}


                        </table>
                        <table class="w-50">
                            <tr>
                                <td>اسم العميل</td>
                                <td style="padding-bottom:11px;">
                                    @if (!empty($receipt_details->customer_name))
                                    {!! $receipt_details->customer_name !!}<br>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>رقم الفاتوره</td>
                                <td>{{ $receipt_details->invoice_no }}</td>
                            </tr>
                            <tr>
                                <td>من مخزن</td>
                                <td>{{ $receipt_details->location_name }}</td>
                            </tr>

                            <tr>
                                <td>كود المندوب</td>
                                <td>{{ $receipt_details->commission_agent_id ?? '' }}</td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="items">
            <table class="table table-border mb-10 w-100" lang="en" dir="rtl">
                <thead>
                    <tr>
                        <th>م</th>
                        <th colspan="3" style="word-break: break-all;">
                            الصنف
                        </th>
                        <th>
                            الوحده
                        </th>
                        <th>
                            عدد
                        </th>
                        <th>
                            السعر
                        </th>
                        <th>
                            مجموع
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
                            {{ $line['name'] }} {{ isset($line['product_variation']) ? $line['product_variation'] : ''
                            }} {{ $line['variation'] }}
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
                        <td>
                            {{ $line['units'] }}
                        </td>
                        <td>
                            {{ $line['quantity'] }}
                        </td>

                        <td>
                            {{ $line['unit_price'] }}
                        </td>

                        <td class="text-right">
                            {{ $line['line_total'] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="table table-border w-100">
                <tr>
                    <td>اجمالي الفاتوره</td>
                    <td style="padding-bottom:11px;">
                        @if (!empty($receipt_details->subtotal))
                        {!! $receipt_details->subtotal !!}
                        @endif
                    </td>
                    <td>خصم</td>
                    <td style="padding-bottom:11px;">
                        @if (!empty($receipt_details->total_discount))
                        {!! $receipt_details->total_discount !!}
                        @endif
                    </td>
                    <td>صافي الفاتوره</td>
                    <td style="padding-bottom:11px;">
                        @if (!empty($receipt_details->total))
                        {!! $receipt_details->total !!}
                        @endif
                    </td>
                </tr>

            </table>
        </div>
        <hr>
        <div class="items">
            <table class="table table-border w-100">
                <tr>
                    <td>الاجمالي</td>
                    <td style="padding-bottom:11px;">
                        {{ $receipt_details->balance_sheet['before']['formatted'] }}
                    </td>
                    <td>المدفوع من الفاتوره</td>
                    <td style="padding-bottom:11px;">
                        {{ $receipt_details->total_paid }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-xs-12 mb-3">
            <p>ملاحظات : </p>
            <p>{!! nl2br($receipt_details->additional_notes) !!}</p>
        </div>
        <div class="col-xs-12 mb-3">
            @if(!empty($receipt_details->payments))
            @foreach($receipt_details->payments as $payment)
            <div class="flex-box">
                <p class="width-50 text-right">{{$payment['method']}} ({{$payment['date']}}) </p>
                <p class="width-50 text-right">{{$payment['amount']}}</p>
            </div>
            @if (!empty($payment['left_balance']))
            <div class="flex-box">
                <p class="width-50 text-right">{{ trans('saasusers.left_subscription_amount') }}</p>
                <p class="width-50 text-right">{{ $payment['left_balance'] }}</p>
            </div>
            @endif
            @endforeach
            @endif
        </div>
        <footer>
            @if (!empty($receipt_details->footer_text))
            <p class="centered">
                {!! $receipt_details->footer_text !!}
            </p>
            @endif
        </footer>
    </div>
</body>

</html>
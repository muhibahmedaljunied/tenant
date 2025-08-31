<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt-{{ $receipt_details->invoice_no }}</title>
    {{--
    <link rel="stylesheet" href="{{ asset('css/rtl.css') }}"> --}}

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap");


        :root {
            --main-color: #287DCB;
            --main-color-light: rgb(40 125 203 / 25%);
            --main-color-dark: #2D2268;
            /* --main-font: "Cairo", sans-serif; */
        }
        #private_invoice .bg-invoice-primary{
            background: #287DCB !important;

        }
        #private_invoice .col-xs-1,
        #private_invoice .col-xs-10,
        #private_invoice .col-xs-11,
        #private_invoice .col-xs-12,
        #private_invoice .col-xs-2,
        #private_invoice .col-xs-3,
        #private_invoice .col-xs-4,
        #private_invoice .col-xs-5,
        #private_invoice .col-xs-6,
        #private_invoice .col-xs-7,
        #private_invoice .col-xs-8,
        #private_invoice .col-xs-9 {
            float: right;
        }


        #private_invoice .d-flex {
            display: flex;
        }

        #private_invoice .company_tax_num p {
            font-weight: 800;
            color: #2D2268;
        }

        #private_invoice .justify-content-between {
            justify-content: space-between;
        }

        #private_invoice .align-items-center {
            align-items: center !important;
        }

        #private_invoice .invoice_main_content--content {
            padding: 0 5px;
        }

        #private_invoice .w-25 {
            width: 25% !important;
        }

        #private_invoice .w-50 {
            width: 50% !important;
        }

        #private_invoice .w-75 {
            width: 75% !important;
        }

        #private_invoice .w-100 {
            width: 100% !important;
        }

        #private_invoice {
            font-family: "Cairo", sans-serif;
            font-weight: 600;
        }

        #private_invoice .h-100 {
            height: 100% !important;
        }

        #private_invoice .rounded-1 {
            border-radius: 0.25rem !important;
        }

        #private_invoice .border {
            border: 1px solid #dee2e6 !important;
        }

        #private_invoice .border-primary {
            border-color: #0d6efd !important;
        }

        #private_invoice .no-gutters [class*="col-"] {
            padding-right: 0 !important;
            padding-left: 0 !important;
        }

        #private_invoice .item_summary {
            background: #287DCB !important;
            padding: 0.25rem;
        }

        #private_invoice .mb-1 {
            margin-bottom: 0.25rem !important;
        }

        #private_invoice .item_summary_content .title {
            color: #fff;
            font-size: 1.25rem;
        }

        #private_invoice .invoice_main_content .title {
            background: #287DCB !important;
            color: #fff;
            text-align: center;
            font-size: 10px;
            padding: 6px 0;
        }

        #private_invoice .invoice_content {
            border: 1px solid #287DCB !important;
            background-color: rgb(40 125 203 / 25%);
            border-radius: 5px;
            margin-bottom: 5px;
        }

        #private_invoice .invoice_customer_info {
            display: flex;
            justify-content: space-between;
            border: 1px solid #2D2268 !important;
            border-right: 0;
            padding: 5px;
            align-items: center;
            height: 100%;
        }

        #private_invoice .invoice_products tbody tr:last-child {
            border: 1px solid #2D2268 !important;
            border-radius: 14px;
            border-top: 0;
        }

        #private_invoice .invoice_products tbody tr:not(:last-child) {
            border-bottom: 1px solid #999;
        }

        #private_invoice .invoice_customer_info .invoice_customer_info--title {
            width: 33.333%;
            font-size: 11px;
        }

        #private_invoice .invoice_customer_info:nth-child(odd) {
            border-bottom: 0;
        }

        #private_invoice .invoice_customer_info .invoice_customer_info--content {
            width: 33.333%;
        }

        #private_invoice .invoice_products thead th {
            background: #287DCB !important;
            color: #fff;
            text-align: center;
            border-left: 1px solid #fff;
        }

        #private_invoice .invoice_products .table tbody .total {
            background: rgb(40 125 203 / 25%);
        }

        #private_invoice .signature {
            overflow: hidden;
        }

        #private_invoice .signature span {
            position: relative;
            display: block;
            overflow: hidden;
            font-weight: 700;
            color: #2D2268;
        }

        #private_invoice .signature span::after {
            content: "";
            width: 90%;
            height: 0;
            position: absolute;
            top: 50%;
            border: 1px dashed #00000069;
            margin-right: 5px;
            transform: translateY(50%);
        }

        #private_invoice .invoice_products .table tbody tr td {
            border: 1px solid #2D2268 !important;
            border-bottom: 0;
            border-top: 0;
        }

        #private_invoice .invoice_products .table tbody tr:last-child td:first-child {
            border-radius: 0 0 0 10px;
        }

        #private_invoice .invoice_products .table tbody tr:last-child td:last-child {
            border-radius: 0 0 10px;
        }

        #private_invoice .invoice_products .table {
            min-height: 33.333vh;
        }
    </style>
</head>

<body class="invoice">
    <div id="private_invoice" dir="rtl">
        <header>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        @if (!empty($receipt_details->header_text))
                        <div class="col-xs-12">
                            {!! $receipt_details->header_text !!}
                        </div>
                        @endif
                    </div>
                    <div class="col-xs-12">
                        <div class="row invoice_content no-gutters">
                            <div class="col-xs-4">
                                <div class="border-black border-bottom bg-invoice-primary invoice_main_content">
                                    <div class="d-flex">
                                        <span class="title w-25">التاريخ</span>
                                        <span class="invoice_main_content--content w-75">{{
                                            now()->parse($receipt_details->invoice_date)->format('Y-m-d') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="border-black border-bottom bg-invoice-primary invoice_main_content">
                                    <div class="d-flex">
                                        <span class="title w-50">فاتورة مبيعات</span>
                                        <span class="invoice_main_content--content w-75"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="border-black border-bottom bg-invoice-primary invoice_main_content">
                                    <div class="d-flex">
                                        <span class="title w-50">رقم الفاتورة</span>
                                        <span class="invoice_main_content--content w-75">{{ $receipt_details->invoice_no
                                            }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="border-black border-bottom bg-invoice-primary invoice_main_content">
                                    <div class="d-flex">
                                        <span class="title w-25">الوقت</span>
                                        <span class="invoice_main_content--content w-75">{{
                                            now()->parse($receipt_details->invoice_date)->format('H:i') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <div class="">
                                    <div class="col-xs-7">
                                        <div class="invoice_customer_info">
                                            <span class="invoice_customer_info--title">العميل</span>
                                            <span class="invoice_customer_info--content">{{
                                                $receipt_details->customer_name ?? '' }}</span>
                                            <span class="invoice_customer_info--title text-right">:Customer</span>
                                        </div>
                                    </div>
                                    <div class="col-xs-5">
                                        <div class="invoice_customer_info">
                                            <span class="invoice_customer_info--title">العنوان</span>
                                            <span class="invoice_customer_info--content"></span>
                                            <span class="invoice_customer_info--title text-right">:Address</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="">
                                    <div class="col-xs-7">
                                        <div class="invoice_customer_info">
                                            <span class="invoice_customer_info--title">الرقم الضريبي للعميل</span>
                                            <span class="invoice_customer_info--content">{{
                                                $receipt_details->customer_tax_number ?? '' }}</span>
                                            <span class="invoice_customer_info--title text-right">ID VAT</span>
                                        </div>
                                    </div>

                                    <div class="col-xs-5">
                                        <div class="invoice_customer_info">
                                            <span class="invoice_customer_info--title">رقم الهاتف</span>
                                            <span class="invoice_customer_info--content"></span>
                                            <span class="invoice_customer_info--title text-right">Tel</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="invoice_products">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-bordered" dir="ltr">
                            <thead>
                                <tr class="text-center">
                                    <th class="rounded-2 rounded-end-0 rounded-bottom-0">
                                        رمز الصنف
                                    </th>
                                    <th width="30%">البيان</th>
                                    <th>الكميه</th>
                                    <th>السعر</th>
                                    <th>الضريبه</th>
                                    <th class="rounded-2 rounded-start-0 rounded-bottom-0">الاجمالي</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($receipt_details->lines as $line)
                                <tr scope="row">
                                    <td>{{ $line['sub_sku'] ?? '' }}</td>
                                    <td style="word-break: break-all;">
                                        @if (!empty($line['image']))
                                        <img src="{{ $line['image'] }}" alt="Image" width="50"
                                            style="float: left; margin-right: 8px;" />
                                        @endif
                                        {{ $line['name'] }} {{ $line['product_variation'] }}
                                        {{ $line['variation'] }}
                                        @if (!empty($line['brand']))
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
                                        <br> {{ $line['product_expiry_label'] }}:
                                        {{ $line['product_expiry'] }}
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
                                        {{ $line['quantity'] }}
                                    </td>

                                    <td>
                                        {{ $line['unit_price'] }}
                                    </td>

                                    <td>
                                        {{ $line['line_total_tax'] }}
                                    </td>

                                    <td class="total">
                                        {{ $line['line_total'] }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="invoice_summary_content mb-2">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-5">
                        <div class="item_summary_content mb-1">
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="text-center border-primary rounded-1 border h-100 p-10">{{
                                        number_format($receipt_details->subtotal_unformatted,2) }}</div>
                                </div>
                                <div class="col-xs-8">
                                    <div class="item_summary rounded-1 px-2 d-flex justify-content-between">
                                        <span class="title">المجموع</span>
                                        <span class="item_summary--content"></span>
                                        <span class="title text-right">Total</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item_summary_content mb-1">
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="text-center border-primary rounded-1 border h-100 p-10">
                                        {{ $receipt_details->total_discount_uf ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-xs-8">
                                    <div class="item_summary rounded-1 px-2 d-flex justify-content-between">
                                        <span class="title">الخصم</span>
                                        <span class="item_summary--content"></span>
                                        <span class="title text-right">Disc.</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="item_summary_content mb-1">
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="text-center border-primary rounded-1 border h-100 p-10">
                                        {{ number_format($receipt_details->subtotal_unformatted -
                                        $receipt_details->total_discount_uf,2)}}
                                    </div>
                                </div>
                                <div class="col-xs-8">
                                    <div class="item_summary rounded-1 px-2 d-flex justify-content-between">
                                        <span class="title">المجموع بعد الخصم</span>
                                        <span class="item_summary--content"></span>
                                        <span class="title text-right">Total After disc.</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="item_summary_content mb-1">
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="text-center border-primary rounded-1 border h-100 p-10">
                                        {{ $receipt_details->total_lines_tax_uf }}
                                    </div>
                                </div>
                                <div class="col-xs-8">
                                    <div class="item_summary rounded-1 px-2 d-flex justify-content-between">
                                        <span class="title">ضريبة القيمة المضافة</span>
                                        <span class="item_summary--content"></span>
                                        <span class="title text-right">VAT</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="item_summary_content mb-1">
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="text-center border-primary rounded-1 border h-100 p-10">
                                        {{ number_format($receipt_details->total_unformatted,2) }}
                                    </div>
                                </div>
                                <div class="col-xs-8">
                                    <div class="item_summary rounded-1 px-2 d-flex justify-content-between">
                                        <span class="title">الاجمالي شامل الضريبة</span>
                                        <span class="item_summary--content"></span>
                                        <span class="title text-right">Total With VAT</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-5 company_tax_num">
                                    @if (!empty($receipt_details->logo))
                                    <img width="80" src="{{ $receipt_details->logo }}"
                                        class="img img-responsive center-block">
                                    @endif
                                    <p class="mb-0">{{ session('business.tax_number_1') }}</p>
                                    <p class="mb-0">{{ session('business.tax_number_2') }}</p>
                                </div>
                                <div class="col-xs-7">
                                    <div
                                        class="border border-2 border-primary margin-bottom mb-2 px-2 rounded-1 text-center">
                                        {{-- <p class="mb-0">Lorem ipsum dolor sit amet consectetur adipisicing
                                            elit. Lorem ipsum dolor sit amet consectetur.</p> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="border-primary border border-2 rounded-1 p-4 d-flex align-items-center justify-content-between">
                            <span class="title">فقط</span>
                            <span class="summary-content">{{ $receipt_details->total_in_words ??
                                $receipt_details->total_unformatted }}</span>
                            <span class="title text-right">Only</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-6">
                        <p class="signature mb-0"><span>توقيع البائع : </span></p>
                    </div>
                    <div class="col-xs-6">
                        <p class="signature mb-0"><span>توقيع المستلم : </span></p>
                    </div>
                    {{-- <div class="col-xs-12">
                        <img src="" class="footer_img" alt="">
                    </div> --}}
                </div>
            </div>
        </footer>
    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt-{{ $receipt_details->invoice_no }}</title>
    <link rel="stylesheet" href="{{ asset('css/rtl.css') }}">

    <style>
        #private_invoice .invoice_content .invoice_main_content {
            text-align: center;
        }

        .customer-info p {
            margin-bottom: 0;
        }
    </style>
</head>
<body class="invoice">
    <div id="private_invoice">
        <div style="padding:0px 2.5rem;">
            <div style="min-height: 65px;"></div>

            <section class="invoice-details" style="display: flex;height: 20px;">
                <p style="width:34%;display: flex;">
                    <span class="placeholder" style="width: 60%; "></span>
                    <span class="placeholder" style="width: 40%;">{{
                        now()->parse($receipt_details->invoice_date)->format('Y-m-d') }}</span>
                </p>
                <p style="width:26%;display: flex;">
                    <span class="placeholder" style="width: 60%; "></span>
                    <span class="placeholder" style="width: 40%;"></span>
                </p>
                <p style="width:22%;display: flex;">
                    <span class="placeholder" style="width: 60%; "></span>
                    <span class="placeholder" style="width: 40%;">{{ $receipt_details->invoice_no
                        }}</span>
                </p>

                <p style="width:18%;display: flex;">
                    <span class="placeholder" style="width: 60%; "></span>
                    <span class="placeholder" style="width: 40%;">{{
                        now()->parse($receipt_details->invoice_date)->format('H:i') }}</span>
                </p>
            </section>

            <section class="customer-info" style="display: flex;height: 50px;width:100%;flex-direction: column;">

                <div style="display: flex">
                    <p style="width:60%;display: flex;justify-content: center;">
                        <span class="placeholder" style="width: 73%;">{{
                            $receipt_details->customer_name ?? '' }}</span>
                    </p>
                    <p style="width:40%;display: flex;justify-content: center;">
                        <span class="placeholder" style="width: 73%;">{{ $receipt_details->invoice_no }}</span>
                    </p>
                </div>

                <div style="display: flex">
                    <p
                        style="width:60%;display: flex;justify-content: center;text-align: center;align-items: center;padding: 7px;">
                        <span class="placeholder" style="width: 73%;text-align: center;">{{
                            $receipt_details->customer_tax_number ?? '' }}</span>
                    </p>
                    <p style="width:40%;display: flex;justify-content: center;    align-items: center;">
                        <span class="placeholder" style="width: 73%;text-align: center;">{{ $receipt_details->invoice_no
                            }}</span>
                    </p>
                </div>

            </section>

            <table class="invoice-items" dir="ltr" style="min-height: 45.5rem;width: 100%;">
                <thead>
                    <tr style="
                    background: transparent !important;
                ">
                        <th style="
                        height: 24px;
                    "></th>
                    </tr>
                </thead>
                <tbody style="
                vertical-align: text-top;
                text-align:center;
            ">
                    @foreach ($receipt_details->lines as $line)
                    <tr scope="row">
                        <td style="width:20%">{{ $line['sub_sku'] ?? '' }}</td>
                        <td style="word-break: break-all; width:40%;">
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

                        <td style="width:9%">
                            {{ $line['quantity'] }}
                        </td>

                        <td style="width: 7%;">
                            {{ $line['unit_price'] }}
                        </td>

                        <td style="width: 9%;">
                            {{ $line['line_total_tax'] }}
                        </td>

                        <td class="total">
                            {{ $line['line_total'] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>


            </table>
            <section class="invoice-totals" style="height: 13rem;">
                <div style="width: 16%">
                    <div style="height:24px;text-align: center;">
                        {{
                        number_format($receipt_details->subtotal_unformatted,2) }}
                    </div>
                    <div style="height:24px;padding-top:3px;text-align: center;">
                        {{ $receipt_details->total_discount_uf ?? 0 }}
                    </div>
                    <div style="height:24px;padding-top:3px;text-align: center;">
                        {{ number_format($receipt_details->subtotal_unformatted -
                        $receipt_details->total_discount_uf,2)}}
                    </div>
                    <div style="height:24px;padding-top:3px;text-align: center;">
                        {{ $receipt_details->total_lines_tax_uf }}
                    </div>
                    <div style="height:24px;padding-top:3px;text-align: center;">
                        {{ number_format($receipt_details->total_unformatted,2) }}
                    </div>
                </div>


            </section>

        </div>
    </div>
</body>

</html>
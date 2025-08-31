<!-- business information here -->
<!DOCTYPE html>
<html lang="auto" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <title>Receipt-{{$receipt_details->invoice_no}}</title>
</head>
<!-- @include('sale_pos.receipts.invoice_style_a4') -->
<body>
<style>
    tfoot>tr {
        background-color: #a9a9a9
    }

    table {
        table-layout: fixed;
        margin: auto;
    }

    tr td:nth-child(1) {
        font-weight: 800;
    }

    tr td:nth-child(2) {
        text-align: center;
    }

    tr td:nth-child(3) {
        font-weight: 800;
        text-align: right;
    }

    .col-sm-3 {
        width: 25%;
        float: left;
    }

    .col-sm-4 {
        width: 33.33333333%;
    }

    .col-sm-6 {
        width: 50%;
        float: left;
    }

    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    td {
        padding: 5px;
        word-wrap: break-word;
        /* border:1px solid black;
  border-collapse: collapse; */
        /* max-width: 30%; */
    }
	.table-details tfoot>tr {
    background-color: #a9a9a9;
}
    .address {
        vertical-align: top;
        height: 80px;
    }

    .flex-evenly {
        display: flex;
        justify-content: space-evenly;
    }

    .products thead tr td {
        text-align: center;

    }

    .products thead tr {
        background-color: #ccc;
    }

    .align-center {
        align-items: center !important;
    }

    .justify-center {
        justify-content: center !important;
    }
</style>
<div class="content">
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
                        <br />{{ $receipt_details->contact }}
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
        <table style="width: 45%;text-align: center" class="table-details">
            <thead></thead>
			
		

            <tbody>
                <tr>
                    <td>Customr Number</td>
                    <td>

                        @if (!empty($receipt_details->client_id_label))
                            <br />
                            <strong>{{ $receipt_details->client_id_label }}</strong> {{ $receipt_details->client_id }}
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
                @if (!empty($receipt_details->customer_tax_label))
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
                        <td colspan="2">{!!$receipt_details->shipping_custom_field_1_label!!}
						</td>
                        <td>
							{!! $receipt_details->shipping_custom_field_1_value !!}
                        </td>
                    </tr>
                @endif
                @if (!empty($receipt_details->shipping_custom_field_2_label))
                    <tr class="">
                        <td colspan="2">{!!$receipt_details->shipping_custom_field_2_label!!}
						</td>
                        <td>
							{!! $receipt_details->shipping_custom_field_2_value !!}
                        </td>
                    </tr>
                @endif
                @if (!empty($receipt_details->shipping_custom_field_3_label))
                    <tr class="">
                        <td colspan="2">{!!$receipt_details->shipping_custom_field_3_label!!}
						</td>
                        <td>
							{!! $receipt_details->shipping_custom_field_3_value !!}
                        </td>
                    </tr>
                @endif
                @if (!empty($receipt_details->shipping_custom_field_4_label))
                    <tr class="">
                        <td colspan="2">{!!$receipt_details->shipping_custom_field_4_label!!}
						</td>
                        <td>
							{!! $receipt_details->shipping_custom_field_4_value !!}
                        </td>
                    </tr>
                @endif
                @if (!empty($receipt_details->shipping_custom_field_5_label))
                    <tr class="">
                        <td colspan="2">{!!$receipt_details->shipping_custom_field_5_label!!}
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
        <table style="width: 45%;text-align: center" class="table-details">
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
                        <td>{{ $receipt_details->sales_person }}</td>
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
        <table class="products" style="width: 90%; margin-top: 25px;">
            <thead>
                <tr>
                    <th>التسلسل</th>
                    <th>الوصف</th>
                    @if ($receipt_details->show_cat_code == 1)
					<td style="">{{ $receipt_details->cat_code_label }}</td>
                    @endif
                    <th>الكميه</th>
                    <th>الوحده</th>
                    <th>سعر الوحده</th>
                    <th>الاجمالي</th>
                    {{-- <th>رقم الصنف</th> --}}
                    {{-- <th>خصم الصحه</th> --}}
                    {{-- @if (!empty($line['modifiers']))
					  <th>الخصم</th>
					  @endif --}}
					  {{-- <th>القيمة المضافه</th> --}}
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
                        <td style="word-break: break-all;">
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

                        @if ($receipt_details->show_cat_code == 1)
                            <td>
                                @if (!empty($line['cat_code']))
                                    {{ $line['cat_code'] }}
                                @endif
                            </td>
                        @endif

                        <td class="text-right">
                            {{ $line['quantity'] }}
                        </td>
                        <td class="text-right">
                            {{ $line['units'] }}
                        </td>


                        <td class="text-right">
                            {{ $line['unit_price_inc_tax'] }}
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
                {{-- <tr>
					  <td scope="row">1</td>
					  <td>sdfsafasf</td>
					  <td></td>
					  <td></td>
					  <td></td>
					  <td></td>
					  <td></td>
					  <td></td>
					  <td></td>
					  <td></td>
					  <td></td>
					  <td></td>
				  </tr> --}}


            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <span class="flex-evenly"><span>الاجمالي</span> <span>55</span></span>
                    </td>
                    <td colspan="3">
                        <span>الاجمالي</span> <span>55</span>
                    </td>
                    <td colspan="1">
                        <span class="flex-evenly"><span>الاجمالي</span> <span>55</span></span>

                    </td>
                    <td>
                        <span>23</span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div>
        <table>
            <thead>

            </thead>
            <tbody>
                <tr>
                    <td>

                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


</body>
</html>

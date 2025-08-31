@extends('layouts.app')
@section('title', __( 'lang_v1.shipments'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>@lang( 'lang_v1.shipments')
    </h1>
</section>

<!-- Main content -->
<section class="content no-print">
    @component('components.filters', ['title' => __('report.filters')])
        <div class="col-md-3">
            <div class="form-group">
                <label for="sell_list_filter_location_id">@lang('purchase.business_location'):</label>
                <select name="sell_list_filter_location_id" id="sell_list_filter_location_id" class="form-control select2" style="width:100%">
                    <option value="" disabled selected>@lang('lang_v1.all')</option>
                    @foreach($business_locations as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="sell_list_filter_customer_id">@lang('contact.customer'):</label>
                <select name="sell_list_filter_customer_id" id="sell_list_filter_customer_id" class="form-control select2" style="width:100%">
                    <option value="" disabled selected>@lang('lang_v1.all')</option>
                    @foreach($customers as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="sell_list_filter_date_range">@lang('report.date_range'):</label>
                <input type="text" name="sell_list_filter_date_range" id="sell_list_filter_date_range" class="form-control" placeholder="@lang('lang_v1.select_a_date_range')" readonly>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="created_by">@lang('report.user'):</label>
                <select name="created_by" id="created_by" class="form-control select2" style="width:100%">
                    <option value="" disabled selected>@lang('messages.please_select')</option>
                    @foreach($sales_representative as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endcomponent

    @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.sell_return')])
        @include('sell_return.partials.sell_return_list')
    @endcomponent

    <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
</section>

<!-- /.content -->
<div class="modal fade payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

<!-- This will be printed -->
<!-- <section class="invoice print_section" id="receipt_section">
</section> -->

@stop

@section('javascript')
<script type="text/javascript">
$(document).ready( function(){
    //Date range as a button
    $('#sell_list_filter_date_range').daterangepicker(
        dateRangeSettings,
        function (start, end) {
            $('#sell_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
            sell_table.ajax.reload();
        }
    );
    $('#sell_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
        $('#sell_list_filter_date_range').val('');
        sell_table.ajax.reload();
    });

    sell_table = $('#sell_table').DataTable({
        processing: true,
        serverSide: true,
        aaSorting: [[1, 'desc']],
        scrollY:        "75vh",
        scrollX:        true,
        scrollCollapse: true,
        "ajax": {
            "url": "/sells",
            "data": function ( d ) {
                if($('#sell_list_filter_date_range').val()) {
                    var start = $('#sell_list_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    var end = $('#sell_list_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    d.start_date = start;
                    d.end_date = end;
                }
                if($('#sell_list_filter_location_id').length) {
                    d.location_id = $('#sell_list_filter_location_id').val();
                }
                d.customer_id = $('#sell_list_filter_customer_id').val();

                if($('#sell_list_filter_payment_status').length) {
                    d.payment_status = $('#sell_list_filter_payment_status').val();
                }
                if($('#created_by').length) {
                    d.created_by = $('#created_by').val();
                }
                if($('#service_staffs').length) {
                    d.service_staffs = $('#service_staffs').val();
                }
                d.only_shipments = true;
                d.shipping_status = $('#shipping_status').val();
            }
        },
        columns: [
            { data: 'action', name: 'action', searchable: false, orderable: false},
            { data: 'transaction_date', name: 'transaction_date'  },
            { data: 'invoice_no', name: 'invoice_no'},
            { data: 'conatct_name', name: 'conatct_name'},
            { data: 'mobile', name: 'contacts.mobile'},
            { data: 'business_location', name: 'bl.name'},
            { data: 'shipping_status', name: 'shipping_status'},
            @if(!empty($custom_labels['shipping']['custom_field_1']))
                { data: 'shipping_custom_field_1', name: 'shipping_custom_field_1'},
            @endif
            @if(!empty($custom_labels['shipping']['custom_field_2']))
                { data: 'shipping_custom_field_2', name: 'shipping_custom_field_2'},
            @endif
            @if(!empty($custom_labels['shipping']['custom_field_3']))
                { data: 'shipping_custom_field_3', name: 'shipping_custom_field_3'},
            @endif
            @if(!empty($custom_labels['shipping']['custom_field_4']))
                { data: 'shipping_custom_field_4', name: 'shipping_custom_field_4'},
            @endif
            @if(!empty($custom_labels['shipping']['custom_field_5']))
                { data: 'shipping_custom_field_5', name: 'shipping_custom_field_5'},
            @endif
            { data: 'payment_status', name: 'payment_status'},
            { data: 'waiter', name: 'ss.first_name', @if(empty($is_service_staff_enabled)) visible: false @endif }
        ],
        "fnDrawCallback": function (oSettings) {
            __currency_convert_recursively($('#sell_table'));
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(4)').attr('class', 'clickable_td');
        }
    });

    $(document).on('change', '#sell_list_filter_location_id, #sell_list_filter_customer_id, #sell_list_filter_payment_status, #created_by, #shipping_status, #service_staffs',  function() {
        sell_table.ajax.reload();
    });
});
</script>
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection
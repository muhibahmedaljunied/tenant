@extends('layouts.app')
@section('title', __('lang_v1.purchase_return'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header no-print">
        <h1>@lang('lang_v1.purchase_return')
        </h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
        @component('components.filters', ['title' => __('report.filters')])
            <div class="col-md-3">
                <div class="form-group">
                    <label for="purchase_list_filter_location_id">@lang('purchase.business_location'):</label>
                    <select name="purchase_list_filter_location_id" id="purchase_list_filter_location_id"
                        class="form-control select2" style="width:100%">
                        <option value="" disabled selected>@lang('lang_v1.all')</option>
                        @foreach ($business_locations as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="purchase_list_filter_store_id">@lang('store.store'):</label>
                    <select name="purchase_list_filter_store_id" id="purchase_list_filter_store_id" class="form-control select2"
                        style="width:100%">

                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="purchase_list_filter_date_range">@lang('report.date_range'):</label>
                    <input type="text" name="purchase_list_filter_date_range" id="purchase_list_filter_date_range"
                        class="form-control" placeholder="@lang('lang_v1.select_a_date_range')" readonly>
                </div>
            </div>
        @endcomponent

        @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.all_purchase_returns')])
            @can('purchase.update')
                @slot('tool')
                    <div class="box-tools">
                        <a class="btn btn-block btn-primary" href="{{ action('CombinedPurchaseReturnController@create') }}">
                            <i class="fa fa-plus"></i> @lang('messages.add')
                        </a>
                    </div>
                @endslot
            @endcan

            @can('purchase.view')
                @include('purchase_return.partials.purchase_return_list')
            @endcan
        @endcomponent

        <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
        <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>
    </section>


    <!-- /.content -->
@stop
@section('javascript')
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
    <script>
        $(document).ready(function() {


            // --------------------------------------------muhib add this---------------------------------------------------
            $('#purchase_list_filter_location_id').on('change', function() {

                console.log('hebo_change');
                var locationIds = $(this).val();
                if (!locationIds) {
                    $('#store_id').html(
                        '<option value="">{{ __('messages.please_select') }}</option>');
                    $('#store_id').trigger('change');
                    return;
                }
                $.ajax({
                    url: '{{ route('getStoresByLocationsPurchaseReturn') }}',
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
                        $('#purchase_list_filter_store_id').html(options).trigger('change');
                    }
                });
            });

            // -----------------------------------------------------------------------------------------------
            $('#purchase_list_filter_date_range').daterangepicker(
                dateRangeSettings,
                function(start, end) {
                    $('#purchase_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end
                        .format(moment_date_format));
                    purchase_return_table.ajax.reload();
                }
            );
            $('#purchase_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
                $('#purchase_list_filter_date_range').val('');
                purchase_return_table.ajax.reload();
            });

            //Purchase table
            purchase_return_table = $('#purchase_return_datatable').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [
                    [0, 'desc']
                ],
                ajax: {
                    url: '/purchase-return',
                    data: function(d) {
                        if ($('#purchase_list_filter_location_id').length) {
                            d.location_id = $('#purchase_list_filter_location_id').val();
                        }

                        var start = '';
                        var end = '';
                        if ($('#purchase_list_filter_date_range').val()) {
                            start = $('input#purchase_list_filter_date_range')
                                .data('daterangepicker')
                                .startDate.format('YYYY-MM-DD');
                            end = $('input#purchase_list_filter_date_range')
                                .data('daterangepicker')
                                .endDate.format('YYYY-MM-DD');
                        }
                        d.start_date = start;
                        d.end_date = end;
                    },
                },
                columnDefs: [{
                    "targets": [7, 8],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        data: 'transaction_date',
                        name: 'transaction_date'
                    },
                    {
                        data: 'ref_no',
                        name: 'ref_no'
                    },
               
                    {
                        data: 'parent_purchase',
                        name: 'T.ref_no'
                    },
                    {
                        data: 'location_name',
                        name: 'BS.name'
                    },
                         {
                        data: 'store_name',
                        name: 'stores.name'
                    },
                    {
                        data: 'name',
                        name: 'contacts.name'
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status'
                    },
                    {
                        data: 'final_total',
                        name: 'final_total'
                    },
                    {
                        data: 'payment_due',
                        name: 'payment_due'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                "fnDrawCallback": function(oSettings) {
                    var total_purchase = sum_table_col($('#purchase_return_datatable'), 'final_total');
                    $('#footer_purchase_return_total').text(total_purchase);

                    $('#footer_payment_status_count').html(__sum_status_html($(
                        '#purchase_return_datatable'), 'payment-status-label'));

                    var total_due = sum_table_col($('#purchase_return_datatable'), 'payment_due');
                    $('#footer_total_due').text(total_due);

                    __currency_convert_recursively($('#purchase_return_datatable'));
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td:eq(5)').attr('class', 'clickable_td');
                }
            });

            $(document).on(
                'change',
                '#purchase_list_filter_location_id',
                function() {
                    purchase_return_table.ajax.reload();
                }
            );
        });
    </script>

@endsection

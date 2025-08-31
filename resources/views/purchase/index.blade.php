@extends('layouts.app')
@section('title', __('purchase.purchases'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header no-print">
        <h1>@lang('purchase.purchases')
            <small></small>
        </h1>
        <!-- <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                <li class="active">Here</li>
            </ol> -->
    </section>

    <!-- Main content -->
    <section class="content no-print">
        <form method="GET" action="{{ url()->current() }}">
            <div class="row">
                <div class="col-md-12">
                    @component('components.filters', ['title' => __('report.filters')])
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="purchase_list_filter_location_id">@lang('purchase.business_location'):</label>
                                <select name="purchase_list_filter_location_id" id="purchase_list_filter_location_id"
                                    class="form-control select2" style="width:100%;">
                                    <option value="">@lang('lang_v1.all')</option>
                                    @foreach ($business_locations as $key => $location)
                                        <option value="{{ $key }}">{{ $location }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="purchase_list_filter_store_id">@lang('store.store'):</label>
                                <select name="purchase_list_filter_store_id" id="purchase_list_filter_store_id"
                                    class="form-control select2" style="width:100%;">

                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="purchase_list_filter_supplier_id">@lang('purchase.supplier'):</label>
                                <select name="purchase_list_filter_supplier_id" id="purchase_list_filter_supplier_id"
                                    class="form-control select2" style="width:100%;">
                                    <option value="">@lang('lang_v1.all')</option>
                                    @foreach ($suppliers as $key => $supplier)
                                        <option value="{{ $key }}">{{ $supplier }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="purchase_list_filter_status">@lang('purchase.purchase_status'):</label>
                                <select name="purchase_list_filter_status" id="purchase_list_filter_status"
                                    class="form-control select2" style="width:100%;">
                                    <option value="">@lang('lang_v1.all')</option>
                                    @foreach ($orderStatuses as $key => $status)
                                        <option value="{{ $key }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="purchase_list_filter_payment_status">@lang('purchase.payment_status'):</label>
                                <select name="purchase_list_filter_payment_status" id="purchase_list_filter_payment_status"
                                    class="form-control select2" style="width:100%;">
                                    <option value="">@lang('lang_v1.all')</option>
                                    <option value="paid">@lang('lang_v1.paid')</option>
                                    <option value="due">@lang('lang_v1.due')</option>
                                    <option value="partial">@lang('lang_v1.partial')</option>
                                    <option value="overdue">@lang('lang_v1.overdue')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="purchase_list_filter_date_range">@lang('report.date_range'):</label>
                                <input type="text" name="purchase_list_filter_date_range"
                                    id="purchase_list_filter_date_range" class="form-control" placeholder="@lang('lang_v1.select_a_date_range')"
                                    readonly>
                            </div>
                        </div>
                    @endcomponent
                </div>
            </div>
        </form>

        @component('components.widget', ['class' => 'box-primary', 'title' => __('purchase.all_purchases')])
            @can('purchase.create')
                @slot('tool')
                    <div class="box-tools">
                        <a class="btn btn-block btn-primary" href="{{ action('PurchaseController@create') }}">
                            <i class="fa fa-plus"></i> @lang('messages.add')
                        </a>
                    </div>
                @endslot
            @endcan
            @include('purchase.partials.purchase_table')
        @endcomponent

        <!-- Modal Dialogs -->
        <div class="modal fade product_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
        <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
        <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

        @include('purchase.partials.update_purchase_status_modal')

    </section>


    <section id="receipt_section" class="print_section"></section>

    <!-- /.content -->
@stop
@section('javascript')
    <script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
    <script>
        $('#purchase_list_filter_location_id').on('change', function() {

            console.log('hebo_change');
            var locationIds = $(this).val();
            if (!locationIds) {
                $('#purchase_list_filter_store_id').html(
                    '<option value="">{{ __('messages.please_select') }}</option>');
                $('#store_id').trigger('change');
                return;
            }
            $.ajax({
                url: '{{ route('getStoresByLocationsPurchase') }}',
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


        //Date range as a button
        $('#purchase_list_filter_date_range').daterangepicker(
            dateRangeSettings,
            function(start, end) {
                $('#purchase_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(
                    moment_date_format));
                purchase_table.ajax.reload();
            }
        );
        $('#purchase_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
            $('#purchase_list_filter_date_range').val('');
            purchase_table.ajax.reload();
        });

        $(document).on('click', '.update_status', function(e) {
            e.preventDefault();
            $('#update_purchase_status_form').find('#status').val($(this).data('status'));
            $('#update_purchase_status_form').find('#purchase_id').val($(this).data('purchase_id'));
            $('#update_purchase_status_modal').modal('show');
        });

        $(document).on('submit', '#update_purchase_status_form', function(e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();

            $.ajax({
                method: 'POST',
                url: $(this).attr('action'),
                dataType: 'json',
                data: data,
                beforeSend: function(xhr) {
                    __disable_submit_button(form.find('button[type="submit"]'));
                },
                success: function(result) {
                    if (result.success == true) {
                        $('#update_purchase_status_modal').modal('hide');
                        toastr.success(result.msg);
                        purchase_table.ajax.reload();
                        $('#update_purchase_status_form')
                            .find('button[type="submit"]')
                            .attr('disabled', false);
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        });
    </script>

@endsection

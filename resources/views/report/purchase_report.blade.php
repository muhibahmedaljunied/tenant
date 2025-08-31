@extends('layouts.app')
@section('title', 'Report 606 (' . __('lang_v1.purchase') . ')')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header no-print">
        <h1>Report 606 (@lang('lang_v1.purchase'))
        </h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
                <form method="GET" action="#">
                    @csrf
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="purchase_list_filter_location_id">@lang('purchase.business_location'):</label>
                            <select name="purchase_list_filter_location_id" id="purchase_list_filter_location_id" class="form-control select2" style="width:100%;">
                                <option value="">@lang('lang_v1.all')</option>
                                @foreach ($business_locations as $key => $location)
                                    <option value="{{ $key }}">{{ $location }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="purchase_list_filter_supplier_id">@lang('purchase.supplier'):</label>
                            <select name="purchase_list_filter_supplier_id" id="purchase_list_filter_supplier_id" class="form-control select2" style="width:100%;">
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
                            <select name="purchase_list_filter_status" id="purchase_list_filter_status" class="form-control select2" style="width:100%;">
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
                            <select name="purchase_list_filter_payment_status" id="purchase_list_filter_payment_status" class="form-control select2" style="width:100%;">
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
                            <input type="text" name="purchase_list_filter_date_range" id="purchase_list_filter_date_range" class="form-control" placeholder="@lang('lang_v1.select_a_date_range')" readonly>
                        </div>
                    </div>
                </form>
            @endcomponent
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-primary'])
                <div class="table-responsive">
                    <table class="table table-bordered table-striped ajax_view" id="purchase_report_table">
                        <thead>
                            <tr>
                                <th>@lang('lang_v1.contact_id')</th>
                                <th>@lang('purchase.supplier')</th>
                                <th>@lang('purchase.ref_no')</th>
                                <th>@lang('purchase.purchase_date') (@lang('lang_v1.year_month'))</th>
                                <th>@lang('purchase.purchase_date') (@lang('lang_v1.day'))</th>
                                <th>@lang('lang_v1.payment_date') (@lang('lang_v1.year_month'))</th>
                                <th>@lang('lang_v1.payment_date') (@lang('lang_v1.day'))</th>
                                <th>@lang('sale.total') (@lang('product.exc_of_tax'))</th>
                                <th>@lang('sale.discount')</th>
                                <th>@lang('sale.tax')</th>
                                <th>@lang('sale.total') (@lang('product.inc_of_tax'))</th>
                                <th>@lang('lang_v1.payment_method')</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            @endcomponent
        </div>
    </div>
</section>


    <section id="receipt_section" class="print_section"></section>

    <!-- /.content -->
@stop
@section('javascript')

    <script type="text/javascript">
        $(document).ready(function() {
            //Purchase report table
            purchase_report_table = $('#purchase_report_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/reports/purchase-report',
                    data: function(d) {
                        if ($('#purchase_list_filter_location_id').length) {
                            d.location_id = $('#purchase_list_filter_location_id').val();
                        }
                        if ($('#purchase_list_filter_supplier_id').length) {
                            d.supplier_id = $('#purchase_list_filter_supplier_id').val();
                        }
                        if ($('#purchase_list_filter_payment_status').length) {
                            d.payment_status = $('#purchase_list_filter_payment_status').val();
                        }
                        if ($('#purchase_list_filter_status').length) {
                            d.status = $('#purchase_list_filter_status').val();
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

                        d = __datatable_ajax_callback(d);
                    },
                },
                columns: [{
                        data: 'contact_id',
                        name: 'contacts.contact_id'
                    },
                    {
                        data: 'name',
                        name: 'contacts.name'
                    },
                    {
                        data: 'ref_no',
                        name: 'ref_no'
                    },
                    {
                        data: 'purchase_year_month',
                        name: 'transaction_date'
                    },
                    {
                        data: 'purchase_day',
                        name: 'transaction_date'
                    },
                    {
                        data: 'payment_year_month',
                        searching: false
                    },
                    {
                        data: 'payment_day',
                        searching: false
                    },
                    {
                        data: 'total_before_tax',
                        name: 'total_before_tax'
                    },
                    {
                        data: 'discount_amount',
                        name: 'discount_amount'
                    },
                    {
                        data: 'tax_amount',
                        name: 'tax_amount'
                    },
                    {
                        data: 'final_total',
                        name: 'final_total'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                ],
                fnDrawCallback: function(oSettings) {
                    __currency_convert_recursively($('#purchase_report_table'));
                }
            });

            $(document).on(
                'change',
                '#purchase_list_filter_location_id, \
                            #purchase_list_filter_supplier_id, #purchase_list_filter_payment_status,\
                             #purchase_list_filter_status',
                function() {
                    purchase_report_table.ajax.reload();
                }
            );
            $('#purchase_list_filter_date_range').daterangepicker(
                dateRangeSettings,
                function(start, end) {
                    $('#purchase_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end
                        .format(moment_date_format));
                    purchase_report_table.ajax.reload();
                }
            );
            $('#purchase_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
                $('#purchase_list_filter_date_range').val('');
                purchase_report_table.ajax.reload();
            });
        });
    </script>

@endsection

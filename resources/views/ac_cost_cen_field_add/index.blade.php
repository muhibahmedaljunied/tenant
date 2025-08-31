@extends('layouts.app')
@section('title', __( 'chart_of_accounts.cost_cen_field_adds'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header no-print">
        <h1>@lang( 'chart_of_accounts.cost_cen_field_adds')
        </h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
        @component('components.widget', ['class' => 'box-primary', 'title' => ''])
            @can('extra_cost_center.create')
                @slot('tool')
                    <div class="box-tools" style="margin-left: 0.5em;">
                        <a class="btn btn-block btn-primary" href="{{action('AcCostCenFieldAddController@create')}}">
                            <i class="fa fa-plus"></i> @lang('chart_of_accounts.add_new_cost_center_field_add')</a>
                    </div>

                @endslot
            @endcan
            <div class="table-responsive">
                <table class="table table-bordered table-striped ajax_view" id="cost_cen_field_add_table">
                    <thead>
                    <tr>
                        <th>@lang('chart_of_accounts.row_no')</th>
                        <th>@lang('chart_of_accounts.cost_description_field_add')</th>
                        <th>@lang('chart_of_accounts.parent_cost_no')</th>
                        @canany(['extra_cost_center.edit', 'extra_cost_center.delete'])
                            <th>@lang('messages.action')</th>
                        @endcanany
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        @endcomponent
    </section>
    <!-- /.content -->
    <div class="modal fade payment_modal" tabindex="-1" role="dialog"
         aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog"
         aria-labelledby="gridSystemModalLabel">
    </div>


@stop

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            //Date range as a button
            $('#sell_list_filter_date_range').daterangepicker(
                dateRangeSettings,
                function(start, end) {
                    $('#sell_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                    cost_cen_field_add_table.ajax.reload();
                },
            );
            $('#sell_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
                $('#sell_list_filter_date_range').val('');
                cost_cen_field_add_table.ajax.reload();
            });

            cost_cen_field_add_table = $('#cost_cen_field_add_table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [[0, 'desc']],
                'ajax': {
                    'url': '/ac/cost_cen_field_add',
                    'data': function(d) {

                    },
                },
                scrollY: true,

                columns: [

                    { data: 'row_no', name: 'row_no' },
                    { data: 'cost_description', name: 'cost_description' },
                    { data: 'parent_cost_no', name: 'parent_cost_no' },
                        @canany(['extra_cost_center.edit', 'extra_cost_center.delete'])
                    {
                        data: 'action', name: 'action', orderable: false, 'searchable': false,
                    },
                    @endcanany
                ],
                // "fnDrawCallback": function (oSettings) {
                //     __currency_convert_recursively($('#cost_cen_field_add_table'));
                // },

                // createdRow: function( row, data, dataIndex ) {
                //     $( row ).find('td:eq(6)').attr('class', 'clickable_td');
                // }
            });

            $(document).on('change', '#sell_list_filter_location_id, #sell_list_filter_customer_id, #sell_list_filter_payment_status, #created_by, #sales_cmsn_agnt, #service_staffs, #shipping_status', function() {
                cost_cen_field_add_table.ajax.reload();
            });


            $('#only_subscriptions').on('ifChanged', function(event) {
                cost_cen_field_add_table.ajax.reload();
            });

            @can('extra_cost_center.delete')
            $(document).on('click', 'button.delete_ac_journal_entry_button', function() {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_cost_center,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then(willDelete => {
                    if (willDelete) {
                        var href = $(this).data('href');
                        var data = $(this).serialize();

                        $.ajax({
                            method: 'DELETE',
                            url: href,
                            dataType: 'json',
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    cost_cen_field_add_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    }
                });
            });
            @endcan
        });
    </script>
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection
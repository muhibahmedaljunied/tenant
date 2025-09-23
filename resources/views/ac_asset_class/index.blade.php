@extends('layouts.app')
@section('title', __( 'assets.asset_classes'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>@lang( 'assets.asset_classes')
    </h1>
</section>

<!-- Main content -->
<section class="content no-print">
    {{-- @component('components.filters', ['title' => __('report.filters')]) --}}
        {{-- @include('sell.partials.sell_list_filters') --}}
        
    {{-- @endcomponent --}}
    @component('components.widget', ['class' => 'box-primary', 'title' => ''])
        @can('sell.create')
            @slot('tool')
                <div class="box-tools" style="margin-left: 0.5em;">
                    <a class="btn btn-block btn-primary" href="{{action('AcAssetClassController@create')}}">
                    <i class="fa fa-plus"></i> @lang('assets.add_asset_classes')</a>
                </div>
               
            @endslot
        @endcan
        @if(auth()->user()->can('direct_sell.access') ||  auth()->user()->can('view_own_sell_only') ||  auth()->user()->can('view_commission_agent_sell'))
        @php
            $custom_labels = json_decode(session('business.custom_labels'), true);
         @endphp
         <div class="table-responsive">
            <table class="table table-bordered table-striped ajax_view" id="cost_cen_branche_table">
                <thead>
                    <tr>
                        <th>@lang('assets.row_no')</th>
                        <th>@lang('assets.asset_class_name_ar')</th>
                        <th>@lang('assets.is_depreciable')</th>
                        <th>@lang('assets.asset_account')</th>
                        <th>@lang('assets.asset_expense_account')</th>
                        <th>@lang('assets.accumulated_consumption_account')</th>
                        <th>@lang('assets.useful_life_type')</th>

                        <th>@lang('messages.action')</th>

                    
                    </tr>
                </thead>
                <tbody></tbody>
                
            </table>
        </div>
        @endif
    @endcomponent
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
            cost_cen_branche_table.ajax.reload();
        }
    );
    $('#sell_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
        $('#sell_list_filter_date_range').val('');
        cost_cen_branche_table.ajax.reload();
    });

    cost_cen_branche_table = $('#cost_cen_branche_table').DataTable({
        processing: true,
        serverSide: true,
        aaSorting: [[0, 'desc']],
        "ajax": {
            "url": "/ac/class_asset",
            "data": function ( d ) {
                
            }
        },
        scrollY:        true,
       
        columns: [
            
            { data: 'row_no', name: 'row_no'},
            { data: 'asset_class_name', name: 'asset_class_name'},
            { data: 'is_depreciable', name: 'is_depreciable'},
            { data: 'asset_account', name: 'asset_account'},
            { data: 'asset_expense_account', name: 'asset_expense_account'},
            { data: 'accumulated_consumption_account', name: 'accumulated_consumption_account'},
            { data: 'useful_life_type', name: 'useful_life_type'},
            
            { data: 'action', name: 'action', orderable: false, "searchable": false},
        ],
        // "fnDrawCallback": function (oSettings) {
        //     __currency_convert_recursively($('#cost_cen_branche_table'));
        // },
        
        // createdRow: function( row, data, dataIndex ) {
        //     $( row ).find('td:eq(6)').attr('class', 'clickable_td');
        // }
    });

    $(document).on('change', '#sell_list_filter_location_id, #sell_list_filter_customer_id, #sell_list_filter_payment_status, #created_by, #sales_cmsn_agnt, #service_staffs, #shipping_status',  function() {
        cost_cen_branche_table.ajax.reload();
    });
    

    $('#only_subscriptions').on('ifChanged', function(event){
        cost_cen_branche_table.ajax.reload();
    });


    $(document).on('click', 'button.delete_ac_asset_class_button', function() {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_record,
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
                            cost_cen_branche_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });
});
</script>
<script src="{{ url('js/payment.js?v=' . $asset_v) }}"></script>
@endsection
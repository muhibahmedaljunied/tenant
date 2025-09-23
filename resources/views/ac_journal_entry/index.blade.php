@extends('layouts.app')
@section('title', __('chart_of_accounts.journal_entries'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header no-print">
        <h1>
            @if ($entryType === 'opening')
                @lang('chart_of_accounts.opening_entries')
            @else
                @lang('chart_of_accounts.journal_entries')
            @endif
        </h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
        @if ($entryType === 'opening')
            <ul class="nav nav-tabs">
                <li class="nav-item @if ($type === 'account') active @endif">
                    <a class="nav-link" @if ($type === 'account') aria-current="page" @endif
                        href="{{ action('OpeningEntryController@index', ['type' => 'account']) }}">الحسابات</a>
                </li>
                <li class="nav-item @if ($type === 'product') active @endif">
                    <a class="nav-link" href="{{ action('OpeningEntryController@index', ['type' => 'product']) }}">المنتجات
                        وتكاليف</a>
                </li>
                <li class="nav-item @if ($type === 'customer') active @endif">
                    <a class="nav-link"
                        href="{{ action('OpeningEntryController@index', ['type' => 'customer']) }}">العملاء</a>
                </li>
                <li class="nav-item @if ($type === 'vendor') active @endif">
                    <a class="nav-link"
                        href='{{ action('OpeningEntryController@index', ['type' => 'vendor']) }}'>الموردين</a>
                </li>
            </ul>
        @endif
        {{-- @component('components.filters', ['title' => __('report.filters')]) --}}
        {{-- @include('sell.partials.sell_list_filters') --}}

        {{-- @endcomponent --}}
        @component('components.widget', [
            'class' => 'box-primary',
            'title' =>
                $entryType === 'opening' ? __('chart_of_accounts.opening_entry') : __('chart_of_accounts.all_journal_entry'),
        ])
            @can('sell.create')
                @slot('tool')
                    @if ($entryType === 'opening')
                        <div class="box-tools" style="margin-left: 0.5em;">
                            <a class="btn btn-block btn-primary" href="{{ action('AcJournalEntryController@openingBalance') }}">
                                <i class="fa fa-plus"></i> @lang('chart_of_accounts.add_open_account')</a>
                        </div>
                    @else
                        <div style="margin-left: 0.5em;display: flex;justify-content: end;">
                            <a class="btn  btn-primary margin-left-10" href="{{ action('AcJournalEntryController@create') }}">
                                <i class="fa fa-plus"></i> @lang('chart_of_accounts.add_journal_entry')
                            </a>
                            <a class="btn  btn-warning " href="{{ action('AcJournalEntryController@importView') }}">
                                <i class="fa fa-upload"></i> @lang('chart_of_accounts.import_journal_entry')
                            </a>
                        </div>
                    @endif
                @endslot
            @endcan
            @if (auth()->user()->can('direct_sell.access') ||
                    auth()->user()->can('view_own_sell_only') ||
                    auth()->user()->can('view_commission_agent_sell'))
                @php
                    $custom_labels = json_decode(session('business.custom_labels'), true);
                @endphp
                <div class="table-responsive">
                    <table class="table table-bordered table-striped ajax_view" id="ac_journal_entry_table">
                        <thead>
                            <tr>
                                <th>@lang('chart_of_accounts.entry_no')</th>
                                <th>@lang('chart_of_accounts.entry_desc')</th>
                                <th>@lang('chart_of_accounts.entry_date')</th>
                                <th>@lang('chart_of_accounts.entry_lines_no')</th>
                                <th>@lang('chart_of_accounts.entry_total_term')</th>
                                <th>@lang('chart_of_accounts.entry_type')</th>

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
    <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <!-- This will be printed -->
    <!-- <section class="invoice print_section" id="receipt_section">
                </section> -->

@stop

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            //Date range as a button
            $('#sell_list_filter_date_range').daterangepicker(
                dateRangeSettings,
                function(start, end) {
                    $('#sell_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(
                        moment_date_format));
                    ac_journal_entry_table.ajax.reload();
                },
            );
            $('#sell_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
                $('#sell_list_filter_date_range').val('');
                ac_journal_entry_table.ajax.reload();
            });

            ac_journal_entry_table = $('#ac_journal_entry_table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [
                    [0, 'desc']
                ],
                'ajax': {
                    'url': @if ($entryType === 'opening')
                        '/ac/opening_entries'
                    @else
                        '/ac/journal_entry'
                    @endif ,
                    'data': {
                        @if ($entryType === 'opening' && isset($type))
                            'type': "{{ $type }}",
                        @endif
                    },
                },
                scrollY: true,

                columns: [

                    {
                        data: 'entry_no',
                        name: 'entry_no'
                    },
                    {
                        data: 'entry_desc',
                        name: 'entry_desc'
                    },
                    {
                        data: 'entry_date',
                        name: 'entry_date'
                    },
                    {
                        data: 'entry_lines_no',
                        name: 'entry_lines_no'
                    },
                    {
                        data: 'entry_total_term',
                        name: 'entry_total_term'
                    },
                    {
                        data: 'entry_type',
                        name: 'entry_type'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        'searchable': false
                    },
                ],
                // "fnDrawCallback": function (oSettings) {
                //     __currency_convert_recursively($('#ac_journal_entry_table'));
                // },

                // createdRow: function( row, data, dataIndex ) {
                //     $( row ).find('td:eq(6)').attr('class', 'clickable_td');
                // }
            });

            $(document).on('change',
                '#sell_list_filter_location_id, #sell_list_filter_customer_id, #sell_list_filter_payment_status, #created_by, #sales_cmsn_agnt, #service_staffs, #shipping_status',
                function() {
                    ac_journal_entry_table.ajax.reload();
                });


            $('#only_subscriptions').on('ifChanged', function(event) {
                ac_journal_entry_table.ajax.reload();
            });


            $(document).on('click', 'button.delete_ac_journal_entry_button', function() {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_journal_entry,
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
                                    ac_journal_entry_table.ajax.reload();
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

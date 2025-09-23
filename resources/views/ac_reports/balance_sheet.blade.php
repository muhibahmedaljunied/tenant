@extends('layouts.app')
@section('title', __('chart_of_accounts.balance_sheet'))

@section('css')
    <style type="text/css">
        a {
            text-decoration: none;
            color: #850062;


        }

        a:hover {
            color: #9A1551;
        }


        .mr-auto {
            margin-right: auto;
        }

        .d-block {
            display: block !important;
        }
    </style>
    @if (app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ url('customs/ac/custom_ar.css') }}">
    @else
        <link rel="stylesheet" href="{{ url('customs/ac/custom.css') }}">
    @endif
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('lang_v1.chart_of_accounts')
            <small>@lang('chart_of_accounts.balance_sheet')</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <form action="{{ route('ac_reports_balance-sheet-report') }}" method="GET">
                        <div class="row">
                            <div class="col-md-12">
                         <div class="col-md-3">
    <div class="form-group">
        <label for="ar_date_to">{{ __('report.ar_date_to') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </span>
            <input type="text" id="ar_date_to" name="ar_date_to" class="form-control date-picker" value="{{ $date_to }}" readonly>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="branch_cost_center">{{ __('chart_of_accounts.cost_center') }}:</label>
        <select id="branch_cost_center" name="branch_cost_center" class="form-control select2 cost_center" style="width: 100%;">
            <option value="">{{ __('chart_of_accounts.please_select_cost_center') }}</option>
            @foreach($ac_cost_cen_branches as $key => $value)
                <option value="{{ $key }}" {{ $branch_cost_center == $key ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="field_cost_center">{{ __('chart_of_accounts.feild_add_cost_center') }}:</label>
        <select id="field_cost_center" name="field_cost_center" class="form-control select2 cost_center" style="width: 100%;">
            <option value="">{{ __('chart_of_accounts.please_select_field_add_cost_center') }}</option>
            @foreach($ac_cost_cen_field_adds as $key => $value)
                <option value="{{ $key }}" {{ $field_cost_center == $key ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>
</div>

                                <div class="col-md-3">
                                    <div class="form-group">

                                        <button type="submit" class="btn btn-primary" style="margin-top:24px; ">تصفيه
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endcomponent
            </div>
        </div>

        {{-- @can('account.access') --}}
        {{-- <div class="row"> --}}
        {{-- <div class="col-sm-12"> --}}
        @component('components.widget')
            {{-- <div class="parent-container"> --}}
            <button class='btn btn-primary mr-auto d-block' id='print-btn'>Print</button>
            {{-- <div class="child-container"> --}}

            <div class="inner-block-reports col-md-12  col-sm-12 col-xs-12 clearfix">
                <div class="reports-header text-center">
                    <h3>@lang('chart_of_accounts.balance_sheet')</h3>
                    <h6>***</h6>
                    <h5>
                        @lang('chart_of_accounts.from_date') <span>{{ $date_from }}</span>
                        @lang('chart_of_accounts.to_date') <span>{{ $date_to }}</span>
                    </h5>
                </div>
                <div class="table-responsive manage-currency-table reports-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="3"></th>
                                <th colspan="1" class="text-center">{{ $date_to }} </th>

                            </tr>

                        </thead>
                        <tbody>
                            {{-- assets_1 --}}
                            <tr style="font-weight:bold">
                                <td colspan="3" class="">
                                    {{ $assets_1->account_number }} - {{ $assets_1->account_name_ar }}
                                </td>
                                <td colspan="1" class="">
                                    &nbsp;
                                </td>
                            </tr>
                            @php
                                $array_of_cost_center = [];

                                $array_of_acc = $accountStatementService->getParents($assets_1->account_number, true);
                                $total_assets_1 = 0;

                                $closedEntries = $accountStatementService->closedEntriesData($date_from, $date_to, $branch_cost_centers, $field_cost_centers);

                                $openedEntries = $accountStatementService->openedEntriesData($previous_date, $field_cost_centers, $branch_cost_centers);
                            @endphp
                            @foreach ($array_of_acc as $account)
                                @php
                                    $debtor_main = 0;
                                    $creditor_main = 0;
                                    $debtor_open = 0;
                                    $creditor_open = 0;
                                    $net_open = 0;
                                    $net_main = 0;
                                    $net_close = 0;
                                    $main_acc_type = '';
                                    $net_debtor_close = 0;
                                    $net_creditor_close = 0;
                                    $net_acc_type = '';
                                    $open_acc_type = '';
                                    $account_details = $accountsMasters->where('account_number', $account)->first();
                                    $positive_main = $closedEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '>', 0)
                                        ->sum('amount');
                                    $negative_main = $closedEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '<', 0)
                                        ->sum('amount');
                                    //for before start period search (open)
                                    $positive_open = $openedEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '>', 0)
                                        ->sum('amount');
                                    $negative_open = $openedEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '<', 0)
                                        ->sum('amount');
                                    //debtor
                                    if ($account_details->account_type == 'debtor') {
                                        $debtor_main = $positive_main;
                                        $creditor_main = $negative_main * -1;

                                        if ($debtor_main >= $creditor_main) {
                                            $net_main = $debtor_main - $creditor_main;
                                            $main_acc_type = 'debtor';
                                        } else {
                                            $net_main = $creditor_main - $debtor_main;
                                            $main_acc_type = 'creditor';
                                        }

                                        $debtor_open = $positive_open;
                                        $creditor_open = $negative_open * -1;
                                        if ($debtor_open >= $creditor_open) {
                                            $net_open = $debtor_open - $creditor_open;
                                            $open_acc_type = 'debtor';
                                        } else {
                                            $net_open = $creditor_open - $debtor_open;
                                            $open_acc_type = 'creditor';
                                        }

                                        $net_debtor_close = $debtor_open + $debtor_main;
                                        $net_creditor_close = $creditor_open + $creditor_main;
                                        if ($net_debtor_close >= $net_creditor_close) {
                                            $net_close = $net_debtor_close - $net_creditor_close;
                                            $net_acc_type = 'debtor';
                                        } else {
                                            $net_close = $net_creditor_close - $net_debtor_close;
                                            $net_acc_type = 'creditor';
                                        }
                                    } else {
                                        //creditor
                                        $creditor_main = $positive_main;
                                        $debtor_main = $negative_main * -1;

                                        if ($creditor_main >= $debtor_main) {
                                            $net_main = $creditor_main - $debtor_main;
                                            $main_acc_type = 'creditor';
                                        } else {
                                            $net_main = $debtor_main - $creditor_main;
                                            $main_acc_type = 'debtor';
                                        }

                                        $creditor_open = $positive_open;
                                        $debtor_open = $negative_open * -1;
                                        if ($creditor_open >= $debtor_open) {
                                            $net_open = $creditor_open - $debtor_open;
                                            $open_acc_type = 'creditor';
                                        } else {
                                            $net_open = $debtor_open - $creditor_open;
                                            $open_acc_type = 'debtor';
                                        }

                                        $net_debtor_close = $debtor_open + $debtor_main;
                                        $net_creditor_close = $creditor_open + $creditor_main;
                                        if ($net_creditor_close >= $net_debtor_close) {
                                            $net_close = $net_creditor_close - $net_debtor_close;
                                            $net_acc_type = 'creditor';
                                        } else {
                                            $net_close = $net_debtor_close - $net_creditor_close;
                                            $net_acc_type = 'debtor';
                                        }
                                    }
                                    if ($net_acc_type == $account_details->account_type) {
                                        $total_assets_1 += $net_close;
                                    } else {
                                        $total_assets_1 -= $net_close;
                                    }

                                @endphp
                            @endforeach
                            @include('ac_reports.includes.balance_sheet_table', [
                                'parents' => $assets_1,
                                'date_from' => $date_from,
                                'date_to' => $date_to,
                                'branch_cost_centers' => $branch_cost_centers,
                                'field_cost_centers' => $field_cost_centers,
                            ])


                            <tr style="border:1pt solid black;font-weight:bold">
                                <td colspan="3" class="">
                                    مجموع {{ $assets_1->account_number }} -
                                    {{ $assets_1->account_name_ar }}
                                </td>
                                <td colspan="1" class="text-center">
                                    @if ($total_assets_1 >= 0)
                                        {{ DisplayDouble($total_assets_1) }}
                                    @else
                                        ({{ DisplayDouble($total_assets_1 * -1) }})
                                    @endif

                                </td>
                            </tr>


                            {{-- liabilities_2 --}}
                            <tr style="font-weight:bold">
                                <td colspan="3" class="">
                                    {{ $liabilities_2->account_number }} - {{ $liabilities_2->account_name_ar }}
                                </td>
                                <td colspan="1" class="">
                                    &nbsp;
                                </td>
                            </tr>


                            @php
                                $array_of_acc = $accountStatementService->getParents($liabilities_2->account_number, true);
                                $total_liabilities_2 = 0;
                            @endphp
                            @foreach ($array_of_acc as $account)
                                @php
                                    $debtor_main = 0;
                                    $creditor_main = 0;
                                    $debtor_open = 0;
                                    $creditor_open = 0;
                                    $net_open = 0;
                                    $net_main = 0;
                                    $net_close = 0;
                                    $main_acc_type = '';
                                    $net_debtor_close = 0;
                                    $net_creditor_close = 0;
                                    $net_acc_type = '';
                                    $open_acc_type = '';
                                    $account_details = $accountsMasters->where('account_number', $account)->first();
                                    $positive_main = $closedEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '>', 0)
                                        ->sum('amount');
                                    $negative_main = $closedEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '<', 0)
                                        ->sum('amount');
                                    //for before start period search (open)
                                    $positive_open = $openedEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '>', 0)
                                        ->sum('amount');
                                    $negative_open = $openedEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '<', 0)
                                        ->sum('amount');
                                    //debtor
                                    if ($account_details->account_type == 'debtor') {
                                        $debtor_main = $positive_main;
                                        $creditor_main = $negative_main * -1;

                                        if ($debtor_main >= $creditor_main) {
                                            $net_main = $debtor_main - $creditor_main;
                                            $main_acc_type = 'debtor';
                                        } else {
                                            $net_main = $creditor_main - $debtor_main;
                                            $main_acc_type = 'creditor';
                                        }

                                        $debtor_open = $positive_open;
                                        $creditor_open = $negative_open * -1;
                                        if ($debtor_open >= $creditor_open) {
                                            $net_open = $debtor_open - $creditor_open;
                                            $open_acc_type = 'debtor';
                                        } else {
                                            $net_open = $creditor_open - $debtor_open;
                                            $open_acc_type = 'creditor';
                                        }

                                        $net_debtor_close = $debtor_open + $debtor_main;
                                        $net_creditor_close = $creditor_open + $creditor_main;
                                        if ($net_debtor_close >= $net_creditor_close) {
                                            $net_close = $net_debtor_close - $net_creditor_close;
                                            $net_acc_type = 'debtor';
                                        } else {
                                            $net_close = $net_creditor_close - $net_debtor_close;
                                            $net_acc_type = 'creditor';
                                        }
                                    } else {
                                        //creditor
                                        $creditor_main = $positive_main;
                                        $debtor_main = $negative_main * -1;

                                        if ($creditor_main >= $debtor_main) {
                                            $net_main = $creditor_main - $debtor_main;
                                            $main_acc_type = 'creditor';
                                        } else {
                                            $net_main = $debtor_main - $creditor_main;
                                            $main_acc_type = 'debtor';
                                        }

                                        $creditor_open = $positive_open;
                                        $debtor_open = $negative_open * -1;
                                        if ($creditor_open >= $debtor_open) {
                                            $net_open = $creditor_open - $debtor_open;
                                            $open_acc_type = 'creditor';
                                        } else {
                                            $net_open = $debtor_open - $creditor_open;
                                            $open_acc_type = 'debtor';
                                        }

                                        $net_debtor_close = $debtor_open + $debtor_main;
                                        $net_creditor_close = $creditor_open + $creditor_main;
                                        if ($net_creditor_close >= $net_debtor_close) {
                                            $net_close = $net_creditor_close - $net_debtor_close;
                                            $net_acc_type = 'creditor';
                                        } else {
                                            $net_close = $net_debtor_close - $net_creditor_close;
                                            $net_acc_type = 'debtor';
                                        }
                                    }
                                    if ($net_acc_type == $account_details->account_type) {
                                        $total_liabilities_2 += $net_close;
                                    } else {
                                        $total_liabilities_2 -= $net_close;
                                    }

                                @endphp
                            @endforeach

                            @include('ac_reports.includes.balance_sheet_table', [
                                'parents' => $liabilities_2,
                                'date_from' => $date_from,
                                'date_to' => $date_to,
                                'branch_cost_centers' => $branch_cost_centers,
                                'field_cost_centers' => $field_cost_centers,
                            ])


                            <tr style="border-top:1pt solid black;font-weight:bold">
                                <td colspan="3" class="">
                                    مجموع {{ $liabilities_2->account_number }} -
                                    {{ $liabilities_2->account_name_ar }}
                                </td>
                                <td colspan="1" class="text-center">
                                    @if ($total_liabilities_2 >= 0)
                                        {{ DisplayDouble($total_liabilities_2) }}
                                    @else
                                        ({{ DisplayDouble($total_liabilities_2) }})
                                    @endif
                                </td>
                            </tr>


                            {{-- equity_3 --}}
                            <tr style="font-weight:bold">
                                <td colspan="3" class="">
                                    {{ $equity_3->account_number }} - {{ $equity_3->account_name_ar }}
                                </td>
                                <td colspan="1" class="">
                                    &nbsp;
                                </td>
                            </tr>

                            @php
                                $array_of_acc = $accountStatementService->getParents($equity_3->account_number, true);
                                $total_equity_3 = 0;
                            @endphp
                            @foreach ($array_of_acc as $account)
                                @php
                                    $debtor_main = 0;
                                    $creditor_main = 0;
                                    $debtor_open = 0;
                                    $creditor_open = 0;
                                    $net_open = 0;
                                    $net_main = 0;
                                    $net_close = 0;
                                    $main_acc_type = '';
                                    $net_debtor_close = 0;
                                    $net_creditor_close = 0;
                                    $net_acc_type = '';
                                    $open_acc_type = '';
                                    $account_details = $accountsMasters->where('account_number', $account)->first();
                                @endphp
                                @if ($account == $profits_losses_current_period_acc)
                                    @php $total_equity_3 += $profits_losses_current_period; @endphp
                                @else
                                    @php

                                        $positive_main = $closedEntries
                                            ->where('account_number', $account)
                                            ->where('amount', '>', 0)
                                            ->sum('amount');
                                        $negative_main = $closedEntries
                                            ->where('account_number', $account)
                                            ->where('amount', '<', 0)
                                            ->sum('amount');
                                        //for before start period search (open)
                                        $positive_open = $openedEntries
                                            ->where('account_number', $account)
                                            ->where('amount', '>', 0)
                                            ->sum('amount');
                                        $negative_open = $openedEntries
                                            ->where('account_number', $account)
                                            ->where('amount', '<', 0)
                                            ->sum('amount');
                                        //debtor
                                        if ($account_details->account_type == 'debtor') {
                                            $debtor_main = $positive_main;
                                            $creditor_main = $negative_main * -1;

                                            if ($debtor_main >= $creditor_main) {
                                                $net_main = $debtor_main - $creditor_main;
                                                $main_acc_type = 'debtor';
                                            } else {
                                                $net_main = $creditor_main - $debtor_main;
                                                $main_acc_type = 'creditor';
                                            }

                                            $debtor_open = $positive_open;
                                            $creditor_open = $negative_open * -1;
                                            if ($debtor_open >= $creditor_open) {
                                                $net_open = $debtor_open - $creditor_open;
                                                $open_acc_type = 'debtor';
                                            } else {
                                                $net_open = $creditor_open - $debtor_open;
                                                $open_acc_type = 'creditor';
                                            }

                                            $net_debtor_close = $debtor_open + $debtor_main;
                                            $net_creditor_close = $creditor_open + $creditor_main;
                                            if ($net_debtor_close >= $net_creditor_close) {
                                                $net_close = $net_debtor_close - $net_creditor_close;
                                                $net_acc_type = 'debtor';
                                            } else {
                                                $net_close = $net_creditor_close - $net_debtor_close;
                                                $net_acc_type = 'creditor';
                                            }
                                        } else {
                                            //creditor
                                            $creditor_main = $positive_main;
                                            $debtor_main = $negative_main * -1;

                                            if ($creditor_main >= $debtor_main) {
                                                $net_main = $creditor_main - $debtor_main;
                                                $main_acc_type = 'creditor';
                                            } else {
                                                $net_main = $debtor_main - $creditor_main;
                                                $main_acc_type = 'debtor';
                                            }

                                            $creditor_open = $positive_open;
                                            $debtor_open = $negative_open * -1;
                                            if ($creditor_open >= $debtor_open) {
                                                $net_open = $creditor_open - $debtor_open;
                                                $open_acc_type = 'creditor';
                                            } else {
                                                $net_open = $debtor_open - $creditor_open;
                                                $open_acc_type = 'debtor';
                                            }

                                            $net_debtor_close = $debtor_open + $debtor_main;
                                            $net_creditor_close = $creditor_open + $creditor_main;
                                            if ($net_creditor_close >= $net_debtor_close) {
                                                $net_close = $net_creditor_close - $net_debtor_close;
                                                $net_acc_type = 'creditor';
                                            } else {
                                                $net_close = $net_debtor_close - $net_creditor_close;
                                                $net_acc_type = 'debtor';
                                            }
                                        }
                                        if ($net_acc_type == $account_details->account_type) {
                                            $total_equity_3 += $net_close;
                                        } else {
                                            $total_equity_3 -= $net_close;
                                        }

                                    @endphp
                                @endif
                            @endforeach

                            @include('ac_reports.includes.balance_sheet_table', [
                                'parents' => $equity_3,
                                'date_from' => $date_from,
                                'date_to' => $date_to,
                                'branch_cost_centers' => $branch_cost_centers,
                                'field_cost_centers' => $field_cost_centers,
                                'profits_losses_current_period_acc' => $profits_losses_current_period_acc,
                                'profits_losses_current_period' => $profits_losses_current_period,
                            ])


                            <tr style="border-top:1pt solid black;font-weight:bold">
                                <td colspan="3" class="">
                                    مجموع {{ $equity_3->account_number }} -
                                    {{ $equity_3->account_name_ar }}
                                </td>
                                <td colspan="1" class="text-center">
                                    @if ($total_equity_3 >= 0)
                                        {{ DisplayDouble($total_equity_3) }}
                                    @else
                                        ({{ DisplayDouble($total_equity_3) }})
                                    @endif
                                </td>
                            </tr>


                            <tr style="border:1pt solid black;font-weight:bold">
                                <td colspan="3" class="">
                                    مجموع (
                                    {{ $equity_3->account_name_ar }}) + (
                                    {{ $liabilities_2->account_name_ar }})
                                </td>
                                <td colspan="1" class="text-center">
                                    @if (($sumTotalLE = $total_liabilities_2 + $total_equity_3) >= 0)
                                        {{ DisplayDouble($sumTotalLE) }}
                                    @else
                                        ({{ DisplayDouble($sumTotalLE) }})
                                    @endif
                                </td>
                            </tr>

                        </tbody>

                    </table>
                    <div>
                        <a href="#" class="btn btn-sm btn-info export_btn ">
                            <i class="fas fa-file-excel"></i>
                            {{ trans('lang_v1.export') }}
                        </a>
                    </div>
    
                </div>

                <div class="report-bottom">
                </div>
            </div>

            {{-- </div> --}}
            {{-- </div> --}}
        @endcomponent
        {{-- </div> --}}
        {{-- </div> --}}
        {{-- @endcan --}}


    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script src="{{ url('js/set_right_left_xslx.js') }}"></script>
    <script src="{{ url('js/excelexport.js') }}"></script>

    <script>
        $('.export_btn').click(function(e) {
            e.preventDefault();
            ExportToExcel($('.reports-table'));
        });

        function printPageArea(areaID) {
            var printContent = document.querySelector(areaID).innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
        }
        $(document).ready(function() {
            document.querySelector('#print-btn').addEventListener('click', function() {
                printPageArea('.inner-block-reports');
                // $('.inner-block-reports').print();
            });
            $('#al_date_filter').daterangepicker(dateRangeSettings, function(start, end) {
                $('#al_date_filter').val(
                    start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format),
                );
                $('.ar_date_from').val(start.format(moment_date_format));
                $('.ar_date_to').val(end.format(moment_date_format));
                console.log(start.format(moment_date_format));
                console.log(end.format(moment_date_format));

            });
            $('#al_date_filter').on('cancel.daterangepicker', function(ev, picker) {
                $('#al_date_filter').val('');
                // activity_log_table.ajax.reload();
            });

            var treeFolder = $('.folder');
            treeFolder.on('click', function() {
                $(this).parent().find('ul:first').slideToggle();
            });

            $(document).on('change', '#filter_type', function() {
                var filter_type = $(this).val();
                // console.log(filter_type);
                $('#cost_cen_list_div').hide();
                if (filter_type != 'NULL') {
                    if (filter_type == 'branch_cost_center' || filter_type == 'feild_add_cost_center') {

                        $('#cost_cen_list').html('');
                        $.ajax({
                            method: 'POST',
                            url: '/ac/reports/get_filter_type_data_list',
                            dataType: 'json',
                            data: {
                                type: filter_type,
                            },
                            success: function(result) {
                                // console.log(result.msg);
                                // $("#cost_cen_list").html(result.html_content);
                                if (result.success) {
                                    $('#cost_cen_list').html(result.html_content);
                                    $('#cost_cen_list_div').show();
                                    // toastr.success('Done');
                                }
                            },
                        });
                    }
                } else {
                    // $('#cost_cen_list_div').hide();
                    // toastr.success('null');
                }
            });
        });

        // Just for centering elements:

        // Centring function:
        jQuery.fn.center = function() {
            this.css('position', 'absolute');
            this.css('top', Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
                $(window).scrollTop()) + 'px');
            this.css('left', Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                $(window).scrollLeft()) + 'px');
            return this;
        };


        $('.date-picker').datepicker({
            autoclose: true,
            endDate: 'today',
            format: 'yyyy-m-d',
        });


        // Centring element:
        // $('.parent-container').center();
    </script>
@endsection

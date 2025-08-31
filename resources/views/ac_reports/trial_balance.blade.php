@extends('layouts.app')
@section('title', __('chart_of_accounts.trial_balance'))

@section('css')
    <style type="text/css">
        /* body {
                                                direction: rtl;
                                            } */

        a {
            text-decoration: none;
            color: #850062;

            &:hover {
                color: #9A1551;
            }
        }
    </style>
    @if (app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('customs/ac/custom_ar.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('customs/ac/custom.css') }}">
    @endif
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('lang_v1.chart_of_accounts')
            <small>@lang('chart_of_accounts.trial_balance')</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <form action="{{ route('ac_reports_trial-balance-report') }}" method="GET">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="col-md-3">
                                    <div class="form-group">
                            <label for="al_date_filter">{{ __('report.date_range') }}:</label>

                                        <input type="hidden" name="ar_date_from" class="ar_date_from"
                                            value="{{ $date_from }}" />
                                        <input type="hidden" name="ar_date_to" class="ar_date_to"
                                            value="{{ $date_to }}" />
                                        <input type="text" readonly id="al_date_filter" class="form-control"
                                            placeholder="{{ __('lang_v1.select_a_date_range') }}"
                                            value="{{ $date_from }} - {{ $date_to }}" />

                                    </div>
                                </div>


                       <div class="col-md-3 form-group">
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

<div class="col-md-3 form-group">
    <label for="field_cost_center">{{ __('chart_of_accounts.feild_add_cost_center') }}:</label>
    <select id="field_cost_center" name="field_cost_center" class="form-control select2 feild_add_cost_center" style="width: 100%;">
        <option value="">{{ __('chart_of_accounts.please_select_field_add_cost_center') }}</option>
        @foreach($ac_cost_cen_field_adds as $key => $value)
            <option value="{{ $key }}" {{ $field_cost_center == $key ? 'selected' : '' }}>
                {{ $value }}
            </option>
        @endforeach
    </select>
</div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" style="margin-top:24px;">تصفيه</button>
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
            {{-- <div class="child-container"> --}}

            <div class="inner-block-reports col-md-12  col-sm-12 col-xs-12 clearfix">
                <div class="reports-header text-center">
                    <h3>@lang('chart_of_accounts.trial_balance')</h3>
                    <h6>***</h6>
                    <h5>
                        @lang('chart_of_accounts.from_date') <span>{{ $date_from }}</span>
                        @lang('chart_of_accounts.to_date') <span>{{ $date_to }}</span>
                    </h5>
                </div>
                <div class="table-responsive manage-currency-table reports-table" id="report_table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="3"></th>
                                <th colspan="2" class="text-center">الرصيد الافتتاحي</th>
                                <th colspan="2" class="text-center">الحركة</th>
                                <th colspan="2" class="text-center">صافي الحركة</th>
                                <th colspan="2" class="text-center">الرصيد الختامي</th>
                            </tr>
                            <tr>
                                <th colspan="3">الحساب</th>
                                <th>مدين</th>
                                <th>دائن</th>

                                <th>مدين</th>
                                <th>دائن</th>

                                <th>مدين</th>
                                <th>دائن</th>

                                <th>مدين</th>
                                <th>دائن</th>


                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sum_open_debtor = 0;
                                $sum_open_creditor = 0;

                                $sum_main_debtor = 0;
                                $sum_main_creditor = 0;

                                $sum_net_main_debtor = 0;
                                $sum_net_main_creditor = 0;

                                $sum_close_debtor = 0;
                                $sum_close_creditor = 0;

                                $array_of_cost_center = [];
                                $branch_cost_centers = [];
                                $field_cost_centers = [];
                                if ($branch_cost_center) {
                                    $branch_cost_centers = $accountStatementService->getSelfCostCenterBranchParents(
                                        $branch_cost_center,
                                    );
                                }
                                if ($field_cost_center) {
                                    $field_cost_centers = $accountStatementService->getSelfCostCenterFieldsParents(
                                        $field_cost_center,
                                    );
                                }
                                $closedEntries = $accountStatementService->closedEntriesData(
                                    $date_from,
                                    $date_to,
                                    $branch_cost_centers,
                                    $field_cost_centers,
                                );

                                $openedEntries = $accountStatementService->openedEntriesData(
                                    $previous_date,
                                    $field_cost_centers,
                                    $branch_cost_centers,
                                );

                            @endphp
                            @foreach ($all_masters as $account)
                                <tr>
                                    <td data-title="Account" colspan="3">
                                        <a>
                                            <tab{{ $account->account_level }}>
                                                {{ $account->account_number }} - {{ $account->account_name_ar }}
                                                {{-- - {{ $account->account_level }} --}}
                                                </tab{{ $account->account_level }}>
                                        </a>
                                    </td>
                                    @php

                                        $array_of_acc = $accountStatementService->getParents($account->account_number);
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

                                        //for in period search (main)
                                        $positive_main = $closedEntries
                                            ->whereIn('account_number', $array_of_acc)
                                            ->where('amount', '>', 0)
                                            ->sum('amount');

                                        $negative_main = $closedEntries
                                            ->whereIn('account_number', $array_of_acc)
                                            ->where('amount', '<', 0)
                                            ->sum('amount');

                                        //for before start period search (open)
                                        $positive_open = $openedEntries
                                            ->whereIn('account_number', $array_of_acc)
                                            ->where('amount', '>', 0)
                                            ->sum('amount');
                                        $negative_open = $openedEntries
                                            ->whereIn('account_number', $array_of_acc)
                                            ->where('amount', '<', 0)
                                            ->sum('amount');

                                        //debtor
                                        if ($account->account_type == 'debtor') {
                                            $debtor_main = $positive_main;
                                            $creditor_main = $negative_main * -1;

                                            $sum_main_debtor += $debtor_main;
                                            $sum_main_creditor += $creditor_main;

                                            if ($debtor_main >= $creditor_main) {
                                                $net_main = $debtor_main - $creditor_main;
                                                $main_acc_type = 'debtor';
                                                $sum_net_main_debtor += $net_main;
                                            } else {
                                                $net_main = $creditor_main - $debtor_main;
                                                $main_acc_type = 'creditor';
                                                $sum_net_main_creditor += $net_main;
                                            }

                                            $debtor_open = $positive_open;
                                            $creditor_open = $negative_open * -1;
                                            if ($debtor_open >= $creditor_open) {
                                                $net_open = $debtor_open - $creditor_open;
                                                $open_acc_type = 'debtor';
                                                $sum_open_debtor += $net_open;
                                            } else {
                                                $net_open = $creditor_open - $debtor_open;
                                                $open_acc_type = 'creditor';
                                                $sum_open_creditor += $net_open;
                                            }

                                            $net_debtor_close = $debtor_open + $debtor_main;
                                            $net_creditor_close = $creditor_open + $creditor_main;
                                            if ($net_debtor_close >= $net_creditor_close) {
                                                $net_close = $net_debtor_close - $net_creditor_close;
                                                $net_acc_type = 'debtor';
                                                $sum_close_debtor += $net_close;
                                            } else {
                                                $net_close = $net_creditor_close - $net_debtor_close;
                                                $net_acc_type = 'creditor';
                                                $sum_close_creditor += $net_close;
                                            }
                                        } else {
                                            //creditor
                                            $creditor_main = $positive_main;
                                            $debtor_main = $negative_main * -1;

                                            $sum_main_debtor += $debtor_main;
                                            $sum_main_creditor += $creditor_main;

                                            if ($creditor_main >= $debtor_main) {
                                                $net_main = $creditor_main - $debtor_main;
                                                $main_acc_type = 'creditor';
                                                $sum_net_main_creditor += $net_main;
                                            } else {
                                                $net_main = $debtor_main - $creditor_main;
                                                $main_acc_type = 'debtor';
                                                $sum_net_main_debtor += $net_main;
                                            }

                                            $creditor_open = $positive_open;
                                            $debtor_open = $negative_open * -1;
                                            if ($creditor_open >= $debtor_open) {
                                                $net_open = $creditor_open - $debtor_open;
                                                $open_acc_type = 'creditor';
                                                $sum_open_creditor += $net_open;
                                            } else {
                                                $net_open = $debtor_open - $creditor_open;
                                                $open_acc_type = 'debtor';
                                                $sum_open_debtor += $net_open;
                                            }

                                            $net_debtor_close = $debtor_open + $debtor_main;
                                            $net_creditor_close = $creditor_open + $creditor_main;
                                            if ($net_creditor_close >= $net_debtor_close) {
                                                $net_close = $net_creditor_close - $net_debtor_close;
                                                $net_acc_type = 'creditor';
                                                $sum_close_creditor += $net_close;
                                            } else {
                                                $net_close = $net_debtor_close - $net_creditor_close;
                                                $net_acc_type = 'debtor';
                                                $sum_close_debtor += $net_close;
                                            }
                                        }

                                    @endphp
                                    <td class="center">
                                        {{ $open_acc_type == 'debtor' ? DisplayDouble($net_open) : 0 }}
                                    </td>
                                    <td class="center">
                                        {{ $open_acc_type == 'creditor' ? DisplayDouble($net_open) : 0 }}
                                    </td>


                                    <td class="center">
                                        {{ DisplayDouble($debtor_main) }}
                                    </td>
                                    <td class="center">
                                        {{ DisplayDouble($creditor_main) }}
                                    </td>

                                    <td class="center">
                                        {{ $main_acc_type == 'debtor' ? DisplayDouble($net_main) : 0 }}
                                    </td>
                                    <td class="center">
                                        {{ $main_acc_type == 'creditor' ? DisplayDouble($net_main) : 0 }}
                                    </td>

                                    <td class="center">
                                        {{ $net_acc_type == 'debtor' ? DisplayDouble($net_close) : 0 }}
                                    </td>
                                    <td class="center">
                                        {{ $net_acc_type == 'creditor' ? DisplayDouble($net_close) : 0 }}
                                    </td>


                                </tr>
                                @include('ac_reports.includes.trial_balance_table', [
                                    'parents' => $account,
                                    'date_from' => $date_from,
                                    'date_to' => $date_to,
                                    'branch_cost_centers' => $branch_cost_centers,
                                    'field_cost_centers' => $field_cost_centers,
                                ])
                            @endforeach


                            <tr style="font-weight:bold; background-color: #403e3d;color: #FFFFFF;">
                                <td data-title="Account" colspan="3" class="">
                                    المجموع
                                </td>
                                <td class="center">
                                    {{ DisplayDouble($sum_open_debtor) }}
                                </td>
                                <td class="center">
                                    {{ DisplayDouble($sum_open_creditor) }}
                                </td>


                                <td class="center">
                                    {{ DisplayDouble($sum_main_debtor) }}
                                </td>
                                <td class="center">
                                    {{ DisplayDouble($sum_main_creditor) }}
                                </td>

                                <td class="center">
                                    {{ DisplayDouble($sum_net_main_debtor) }}
                                </td>
                                <td class="center">
                                    {{ DisplayDouble($sum_net_main_creditor) }}
                                </td>

                                <td class="center">
                                    {{ DisplayDouble($sum_close_debtor) }}
                                </td>
                                <td class="center">
                                    {{ DisplayDouble($sum_close_creditor) }}
                                </td>
                            </tr>
                        </tbody>


                    </table>
                </div>
                <div>
                    <a href="#" class="btn btn-sm btn-info export_btn ">
                        <i class="fas fa-file-excel"></i>
                        {{ trans('lang_v1.export') }}
                    </a>
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

        <div class="modal fade master_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

        <div class="modal fade add_masterModal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <form action="{{ action('AcMasterController@store') }}" method="POST" id="master_add_form">
                        @csrf




                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">@lang('chart_of_accounts.add_mastert')</h4>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" id="account_level" name="account_level">
                            <input type="hidden" id="account_number" name="account_number">
                            <input type="hidden" id="parent_acct_no" name="parent_acct_no">
                            <input type="hidden" id="account_type" name="account_type">

                            <div class="form-group">
                                <label for="account_name_ar">{{ __('chart_of_accounts.account_name_ar') }}:*</label>
                                <input type="text" id="add-account_name_ar" name="account_name_ar"
                                    class="form-control" required
                                    placeholder="{{ __('chart_of_accounts.account_name_ar') }}">
                            </div>

                            <div class="form-group">
                                <label for="account_name_en">{{ __('chart_of_accounts.account_name_en') }}</label>
                                <input type="text" id="add-account_name_en" name="account_name_en"
                                    class="form-control" placeholder="{{ __('chart_of_accounts.account_name_en') }}">
                            </div>

                            <div class="form-group">
                                <label for="account_number">{{ __('chart_of_accounts.account_number') }}:</label>
                                <input type="text" id="add-account_number" name="account_number" class="form-control"
                                    required readonly>
                            </div>

                            <div class="form-group">
                                <label for="account_level">{{ __('chart_of_accounts.account_level') }}:</label>
                                <input type="text" id="add-account_level" name="account_level" class="form-control"
                                    required readonly>
                            </div>

                            <div class="form-group">
                                <label for="add-parent_acct_name">{{ __('chart_of_accounts.parent_acct_no') }}:</label>
                                <input type="text" id="add-parent_acct_name" name="add-parent_acct_name"
                                    class="form-control" required readonly>
                            </div>



                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="pay_collect" name="pay_collect" value="1">
                                        {{ __('chart_of_accounts.pay_collect') }}
                                    </label>
                                </div>

                            </div>


                        </div><!-- /.modal-body -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">@lang('messages.add')</button>
                            <button type="button" class="btn btn-default"
                                data-dismiss="modal">@lang('messages.close')</button>
                        </div>

                    </form>


                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->

        </div>
    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script src="{{ asset('js/set_right_left_xslx.js') }}"></script>
    <script src="{{ asset('js/excelexport.js') }}"></script>

    <script>
        $('.export_btn').click(function(e) {
            e.preventDefault();
            ExportToExcel($('.reports-table'));
        });
        $(document).ready(function() {
            $('#al_date_filter').daterangepicker(dateRangeSettings, function(start, end) {
                $('#al_date_filter').val(
                    `${start.format(moment_date_format)} ~ ${end.format(moment_date_format)}`);
                $('.ar_date_from').val(start.format(moment_date_format));
                $('.ar_date_to').val(end.format(moment_date_format));
            });
            $('#al_date_filter').on('cancel.daterangepicker', function(ev, picker) {
                $('#al_date_filter').val('');
            });
        });
        // Just for centering elements:
    </script>
@endsection

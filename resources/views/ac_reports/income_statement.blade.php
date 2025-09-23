@extends('layouts.app')
@section('title', __('chart_of_accounts.income_statement'))

@section('css')
    <style type="text/css">
        a {
            text-decoration: none;
            color: #850062;

            &:hover {
                color: #9A1551;
            }
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
            <small>@lang('chart_of_accounts.income_statement')</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <form action="{{ route('ac_reports_income-statement-report') }}" method="GET">
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

                                        <button type="submit" class="btn btn-primary" style="margin-top:24px; ">تصفيه</button>
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
                    <h3>@lang('chart_of_accounts.income_statement')</h3>
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
                            {{-- operating_income_41 --}}
                            <tr style="font-weight:bold">
                                <td colspan="3" class="">
                                    {{ $operating_income_41->account_number }} - {{ $operating_income_41->account_name_ar }}
                                </td>
                                <td colspan="1" class="">
                                    &nbsp;
                                </td>
                            </tr>
                            @php
                                $array_of_cost_center = [];
                                $branch_cost_centers = [];
                                $field_cost_centers = [];

                                if ($branch_cost_center) {
                                    $branch_cost_centers = $accountStatementService->getSelfCostCenterBranchParents($branch_cost_center);
                                }

                                if ($field_cost_center) {
                                    $field_cost_centers = $accountStatementService->getSelfCostCenterFieldsParents($field_cost_center);
                                }

                                $total_operating_income_41 = 0;
                                $closedJournalEntries = $accountStatementService->closedEntriesData($date_from, $date_to, $branch_cost_centers, $field_cost_centers);
                                $openedJournalEntries = $accountStatementService->openedEntriesData($previous_date, $field_cost_centers, $branch_cost_centers);
                            @endphp 
                            @foreach ($accountStatementService->getParents($operating_income_41->account_number, true) as $account)
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
                                    $account_details = $accountStatementService
                                        ->getAccountMaster()
                                        ->where('account_number', $account)
                                        ->first();
                                    $positive_main = $closedJournalEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '>', 0)
                                        ->sum('amount');
                                    $negative_main = $closedJournalEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '<', 0)
                                        ->sum('amount');
                                    //for before start period search (open)
                                    $positive_open = $openedJournalEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '>', 0)
                                        ->sum('amount');
                                    $negative_open = $openedJournalEntries
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
                                @endphp
                                @if ($net_close != 0)
                                    <tr>
                                        <td colspan="3" class="">
                                            <tab2>
                                                {{ $account_details->account_number }} -
                                                {{ $account_details->account_name_ar }}
                                            </tab2>
                                        </td>
                                        <td colspan="1" class="text-center">
                                            @if ($net_acc_type == $account_details->account_type)
                                                {{ DisplayDouble($net_close) }}
                                                @php $total_operating_income_41 += $net_close; @endphp
                                            @else
                                                ({{ DisplayDouble($net_close) }})
                                                @php $total_operating_income_41 -= $net_close; @endphp
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach



                            <tr style="border-top:1pt solid black;font-weight:bold">
                                <td colspan="3" class="">
                                    مجموع {{ $operating_income_41->account_number }} -
                                    {{ $operating_income_41->account_name_ar }}
                                </td>
                                <td colspan="1" class="text-center">
                                    @if ($total_operating_income_41 >= 0)
                                        {{ DisplayDouble($total_operating_income_41) }}
                                    @else
                                        ({{ DisplayDouble($total_operating_income_41) }})
                                    @endif
                                </td>
                            </tr>
                            @include('ac_reports.partials.sales_return')

                            {{-- direct_expenses_51 --}}
                            <tr style="font-weight:bold">
                                <td colspan="3" class="">
                                    {{ $direct_expenses_51->account_number }} - {{ $direct_expenses_51->account_name_ar }}
                                </td>
                                <td colspan="1" class="">
                                    &nbsp;
                                </td>
                            </tr>
                            @php
                                $total_direct_expenses_51 = 0;
                            @endphp
                            @foreach ($accountStatementService->getParents($direct_expenses_51->account_number, true) as $account)
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
                                    $account_details = $accountStatementService
                                        ->getAccountMaster()
                                        ->where('account_number', $account)
                                        ->first();
                                    $positive_main = $closedJournalEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '>', 0)
                                        ->sum('amount');
                                    $negative_main = $closedJournalEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '<', 0)
                                        ->sum('amount');
                                    //for before start period search (open)
                                    $positive_open = $openedJournalEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '>', 0)
                                        ->sum('amount');
                                    $negative_open = $openedJournalEntries
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
                                @endphp
                                @if ($net_close != 0)
                                    <tr>
                                        <td colspan="3" class="">
                                            <tab2>
                                                {{ $account_details->account_number }} -
                                                {{ $account_details->account_name_ar }}
                                            </tab2>
                                        </td>
                                        <td colspan="1" class="text-center">
                                            @if ($net_acc_type == $account_details->account_type)
                                                {{ DisplayDouble($net_close) }}
                                                @php $total_direct_expenses_51 += $net_close; @endphp
                                            @else
                                                ({{ DisplayDouble($net_close) }})
                                                @php $total_direct_expenses_51 -= $net_close; @endphp
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach



                            <tr style="border-top:1pt solid black;font-weight:bold">
                                <td colspan="3" class="">
                                    مجموع {{ $direct_expenses_51->account_number }} -
                                    {{ $direct_expenses_51->account_name_ar }}
                                </td>
                                <td colspan="1" class="text-center">
                                    @if ($total_direct_expenses_51 >= 0)
                                        {{ DisplayDouble($total_direct_expenses_51) }}
                                    @else
                                        ({{ DisplayDouble($total_direct_expenses_51) }})
                                    @endif
                                </td>
                            </tr>

                            <tr style="border:1pt solid black;font-weight:bold">
                                <td colspan="3" class="">
                                    إجمالي الربح
                                </td>
                                <td colspan="1" class="text-center">
                                    {{ DisplayDouble($total_operating_income_41 - $total_direct_expenses_51) }}

                                </td>
                            </tr>

                            {{-- non_operating_income_42 --}}
                            <tr style="font-weight:bold">
                                <td colspan="3" class="">
                                    {{ $non_operating_income_42->account_number }} -
                                    {{ $non_operating_income_42->account_name_ar }}
                                </td>
                                <td colspan="1" class="">
                                    &nbsp;
                                </td>
                            </tr>
                            @php
                                $total_non_operating_income_42 = 0;
                            @endphp
                            @foreach ($accountStatementService->getParents($non_operating_income_42->account_number, true) as $account)
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
                                    $account_details = $accountStatementService
                                        ->getAccountMaster()
                                        ->where('account_number', $account)
                                        ->first();
                                    $positive_main = $closedJournalEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '>', 0)
                                        ->sum('amount');
                                    $negative_main = $closedJournalEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '<', 0)
                                        ->sum('amount');
                                    //for before start period search (open)
                                    $positive_open = $openedJournalEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '>', 0)
                                        ->sum('amount');
                                    $negative_open = $openedJournalEntries
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
                                @endphp
                                @if ($net_close != 0)
                                    <tr>
                                        <td colspan="3" class="">
                                            <tab2>
                                                {{ $account_details->account_number }} -
                                                {{ $account_details->account_name_ar }}
                                            </tab2>
                                        </td>
                                        <td colspan="1" class="text-center">
                                            @if ($net_acc_type == $account_details->account_type)
                                                {{ DisplayDouble($net_close) }}
                                                @php $total_non_operating_income_42 += $net_close; @endphp
                                            @else
                                                ({{ DisplayDouble($net_close) }})
                                                @php $total_non_operating_income_42 -= $net_close; @endphp
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach



                            <tr style="border-top:1pt solid black;font-weight:bold">
                                <td colspan="3" class="">
                                    مجموع {{ $non_operating_income_42->account_number }} -
                                    {{ $non_operating_income_42->account_name_ar }}
                                </td>
                                <td colspan="1" class="text-center">
                                    @if ($total_non_operating_income_42 >= 0)
                                        {{ DisplayDouble($total_non_operating_income_42) }}
                                    @else
                                        ({{ DisplayDouble($total_non_operating_income_42) }})
                                    @endif
                                </td>
                            </tr>




                            {{-- operating_expenses_52 --}}
                            <tr style="font-weight:bold">
                                <td colspan="3">
                                    {{ $operating_expenses_52->account_number }} -
                                    {{ $operating_expenses_52->account_name_ar }}
                                </td>
                                <td colspan="1">
                                    &nbsp;
                                </td>
                            </tr>
                            @php
                                $total_operating_expenses_52 = 0;
                            @endphp
                            @foreach ($accountStatementService->getParents($operating_expenses_52->account_number, true) as $account)
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
                                    $account_details = $accountStatementService
                                        ->getAccountMaster()
                                        ->where('account_number', $account)
                                        ->first();
                                    $positive_main = $closedJournalEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '>', 0)
                                        ->sum('amount');
                                    $negative_main = $closedJournalEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '<', 0)
                                        ->sum('amount');
                                    //for before start period search (open)
                                    $positive_open = $openedJournalEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '>', 0)
                                        ->sum('amount');
                                    $negative_open = $openedJournalEntries
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
                                @endphp
                                @if ($net_close != 0)
                                    <tr>
                                        <td colspan="3" class="">
                                            <tab2>
                                                {{ $account_details->account_number }} -
                                                {{ $account_details->account_name_ar }}
                                            </tab2>
                                        </td>
                                        <td colspan="1" class="text-center">
                                            @if ($net_acc_type == $account_details->account_type)
                                                {{ DisplayDouble($net_close) }}
                                                @php $total_operating_expenses_52 += $net_close; @endphp
                                            @else
                                                ({{ DisplayDouble($net_close) }})
                                                @php $total_operating_expenses_52 -= $net_close; @endphp
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach



                            <tr style="border-top:1pt solid black;font-weight:bold">
                                <td colspan="3" class="">
                                    مجموع {{ $operating_expenses_52->account_number }} -
                                    {{ $operating_expenses_52->account_name_ar }}
                                </td>
                                <td colspan="1" class="text-center">
                                    @if ($total_operating_expenses_52 >= 0)
                                        {{ DisplayDouble($total_operating_expenses_52) }}
                                    @else
                                        ({{ DisplayDouble($total_operating_expenses_52) }})
                                    @endif
                                </td>
                            </tr>

                            <tr style="border:1pt solid black;font-weight:bold">
                                <td colspan="3" class="">
                                    صافي الدخل قبل الفوائد، الضريبة والزكاة
                                </td>
                                <td colspan="1" class="text-center">
                                    {{ DisplayDouble($total_operating_income_41 - $total_direct_expenses_51 + $total_non_operating_income_42 - $total_operating_expenses_52) }}

                                </td>
                            </tr>



                            {{-- non_operating_expenses_53 --}}
                            <tr style="font-weight:bold">
                                <td colspan="3" class="">
                                    {{ $non_operating_expenses_53->account_number }} -
                                    {{ $non_operating_expenses_53->account_name_ar }}
                                </td>
                                <td colspan="1" class="">
                                    &nbsp;
                                </td>
                            </tr>
                            @php
                                $total_non_operating_expenses_53 = 0;
                            @endphp
                            @foreach ($accountStatementService->getParents($non_operating_expenses_53->account_number, true) as $account)
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
                                    $account_details = $accountStatementService
                                        ->getAccountMaster()
                                        ->where('account_number', $account)
                                        ->first();
                                    $positive_main = $closedJournalEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '>', 0)
                                        ->sum('amount');
                                    $negative_main = $closedJournalEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '<', 0)
                                        ->sum('amount');
                                    //for before start period search (open)
                                    $positive_open = $openedJournalEntries
                                        ->where('account_number', $account)
                                        ->where('amount', '>', 0)
                                        ->sum('amount');
                                    $negative_open = $openedJournalEntries
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
                                @endphp
                                @if ($net_close != 0)
                                    <tr>
                                        <td colspan="3" class="">
                                            <tab2>
                                                {{ $account_details->account_number }} -
                                                {{ $account_details->account_name_ar }}
                                            </tab2>
                                        </td>
                                        <td colspan="1" class="text-center">
                                            @if ($net_acc_type == $account_details->account_type)
                                                {{ DisplayDouble($net_close) }}
                                                @php $total_non_operating_expenses_53 += $net_close; @endphp
                                            @else
                                                ({{ DisplayDouble($net_close) }})
                                                @php $total_non_operating_expenses_53 -= $net_close; @endphp
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach



                            <tr style="border-top:1pt solid black;font-weight:bold">
                                <td colspan="3" class="">
                                    مجموع {{ $non_operating_expenses_53->account_number }} -
                                    {{ $non_operating_expenses_53->account_name_ar }}
                                </td>
                                <td colspan="1" class="text-center">
                                    @if ($total_non_operating_expenses_53 >= 0)
                                        {{ DisplayDouble($total_non_operating_expenses_53) }}
                                    @else
                                        ({{ DisplayDouble($total_non_operating_expenses_53) }})
                                    @endif
                                </td>
                            </tr>

                            <tr style="border:1pt solid black;font-weight:bold">
                                <td colspan="3" class="">
                                    صافي الربح
                                </td>
                                <td colspan="1" class="text-center">
                                    @php
                                        $total = $total_operating_income_41 - $total_direct_expenses_51 + $total_non_operating_income_42 - $total_operating_expenses_52 - $total_non_operating_expenses_53;
                                    @endphp

                                    @if ($total >= 0)
                                        {{ DisplayDouble($total) }}
                                    @else
                                        ({{ DisplayDouble($total) }})
                                    @endif


                                </td>
                            </tr>


                        </tbody>



                    </table>
                    <div>
                        <a href="#" class="btn btn-sm btn-info export_btn">
                            <i class="fas fa-file-excel"></i>
                            {{ trans('lang_v1.export') }}
                        </a>
                    </div>
    
                </div>

                <div class="report-bottom">
                    {{-- <a href="">
                            تصدير دفتر الأستاذ بالتفصيل إلى Excel
                        </a> --}}
                    {{-- <div class="dropdown pull-right">
                            <button class="btn btn-primary dropdown-toggle open" type="button" data-toggle="dropdown"
                                aria-expanded="false">
                                تصدير
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="/tenant/reports/general_ledgers.xlsx?detail=false">
                                    </a>
                                </li>
                                <li>
                                    <a href="/tenant/reports/general_ledgers.pdf">
                                    </a>
                                </li>
                            </ul>
                        </div> --}}
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

        $(document).ready(function() {
            $('#al_date_filter').daterangepicker(dateRangeSettings, function(start, end) {
                $('#al_date_filter').val(
                    start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
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

            var treeFolder = $(".folder");
            treeFolder.on("click", function() {
                $(this).parent().find("ul:first").slideToggle();
            });

            $(document).on('change', '#filter_type', function() {
                var filter_type = $(this).val();
                // console.log(filter_type);
                $('#cost_cen_list_div').hide();
                if (filter_type != 'NULL') {
                    if (filter_type == 'branch_cost_center' || filter_type == 'feild_add_cost_center') {

                        $("#cost_cen_list").html('');
                        $.ajax({
                            method: 'POST',
                            url: '/ac/reports/get_filter_type_data_list',
                            dataType: 'json',
                            data: {
                                type: filter_type
                            },
                            success: function(result) {
                                if (result.success) {
                                    $("#cost_cen_list").html(result.html_content);
                                    $('#cost_cen_list_div').show();
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
            this.css("position", "absolute");
            this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
                $(window).scrollTop()) + "px");
            this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                $(window).scrollLeft()) + "px");
            return this;
        }
    </script>
@endsection

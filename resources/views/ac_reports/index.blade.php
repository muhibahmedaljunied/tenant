@php
    use App\Models\AcCostCenBranche;
    use App\Models\AcCostCenFieldAdd;
    use App\Models\AcJournalEntryDetail;
@endphp
@extends('layouts.app')
@section('title', __('chart_of_accounts.journal_ledger'))

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
            <small>@lang('chart_of_accounts.journal_ledger')</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row no-print">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <form action="{{ route('ac_reports_journal-ledger-report') }}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="al_date_filter">{{ __('report.date_range') }}:</label>

                                        <input type="hidden" name="ar_date_from" class="ar_date_from"
                                            value="{{ $date_from }}">
                                        <input type="hidden" name="ar_date_to" class="ar_date_to" value="{{ $date_to }}">
                                        <input type="text" readonly id="al_date_filter" class="form-control"
                                            placeholder="{{ __('lang_v1.select_a_date_range') }}"
                                            value="{{ $date_from }} - {{ $date_to }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label
                                            for="branch_cost_center">{{ __('chart_of_accounts.branch_cost_center') }}:</label>
                                        <select id="branch_cost_center" name="branch_cost_center"
                                            class="form-control select2 cost_center" style="width: 100%;">
                                            <option value="">{{ __('chart_of_accounts.please_select_cost_center') }}
                                            </option>
                                            @foreach ($ac_cost_cen_branches as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ $branch_cost_center == $key ? 'selected' : '' }}>{{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label
                                            for="field_cost_center">{{ __('chart_of_accounts.feild_add_cost_center') }}:</label>
                                        <select id="field_cost_center" name="field_cost_center"
                                            class="form-control select2 cost_center" style="width: 100%;">
                                            <option value="">
                                                {{ __('chart_of_accounts.please_select_field_add_cost_center') }}</option>
                                            @foreach ($ac_cost_cen_field_adds as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ $field_cost_center == $key ? 'selected' : '' }}>{{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3" id="cost_cen_list_div" style="display: none;">
                                    <div class="form-group" id="cost_cen_list">

                                    </div>
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
            <div class="treeview">
                <div class="inner-block-reports col-md-12  col-sm-12 col-xs-12 clearfix">
                    <div class="reports-header text-center">
                        <h3>@lang('chart_of_accounts.journal_ledger_summary')</h3>
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
                                    <th colspan="3">@lang('chart_of_accounts.account_number_name')</th>
                                    {{-- <th class="balance-ytd hide">
                                    الرصيد الافتتاحي
                                </th> --}}
                                    <th>@lang('chart_of_accounts.debtor')</th>
                                    <th>@lang('chart_of_accounts.creditor')</th>
                                    <th>@lang('chart_of_accounts.net_movement')</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($all_masters as $account)
                                    <tr>
                                        <td data-title="Account" colspan="3" class="">
                                            <tab{{ $account->account_level }}>
                                                {{ $account->account_number }} - {{ $account->account_name_ar }}
                                                </tab{{ $account->account_level }}>
                                        </td>

                                        {{-- <td class="hide balance-ytd"></td> --}}
                                        @php
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
                                            $array_of_acc = $accountStatementService->getParents(
                                                $account->account_number,
                                            );
                                            $debtor_main = 0;
                                            $creditor_main = 0;
                                            $closedEntries = $accountStatementService->closedEntriesData(
                                                $date_from,
                                                $date_to,
                                                $branch_cost_centers,
                                                $field_cost_centers,
                                            );
                                            $positive_main = $closedEntries
                                                ->whereIn('account_number', $array_of_acc)
                                                ->where('amount', '>', 0)
                                                ->sum('amount');
                                            $negative_main = $closedEntries
                                                ->whereIn('account_number', $array_of_acc)
                                                ->where('amount', '<', 0)
                                                ->sum('amount');
                                            //debtor
                                            if ($account->account_type == 'debtor') {
                                                $debtor_main = $positive_main;
                                                $creditor_main = $negative_main * -1;
                                            } else {
                                                //creditor
                                                $creditor_main = $positive_main;
                                                $debtor_main = $negative_main * -1;
                                            }

                                        @endphp

                                        <td data-title="Debit">
                                            {{ DisplayDouble($debtor_main) }}
                                        </td>
                                        <td data-title="Credit">
                                            {{ DisplayDouble($creditor_main) }}
                                        </td>
                                        <td data-title="Net Movement">
                                            {{ DisplayDouble($positive_main + $negative_main) }}
                                        </td>
                                        {{-- <td class="balance-ytd hide"></td> --}}
                                    </tr>
                                    @include('ac_reports.includes.tree_table', [
                                        'parents' => $account,
                                        'date_from' => $date_from,
                                        'date_to' => $date_to,
                                        'branch_cost_center' => $branch_cost_center,
                                        'field_cost_center' => $field_cost_center,
                                    ])
                                @endforeach


                                <tr class="reports-total" style="font-weight:bold; background-color: #403e3d;color: #FFFFFF;">
                                    <td data-title="Account" colspan=" 3">
                                        المجموع
                                    </td>
                                    {{-- <td data-title="Opening Balance" class="balance-ytd hide">
                                    0.00 ر.س
                                </td> --}}
                                    <td data-title="Debit">
                                        {{ DisplayDouble($debtor) }} ر.س
                                    </td>
                                    <td data-title="Credit">
                                        {{ DisplayDouble($creditor) }} ر.س
                                    </td>
                                    <td data-title="Net Movement">
                                        {{ DisplayDouble($creditor - $debtor) }} ر.س
                                    </td>
                                    {{-- <td data-title="YTD Balance" class="balance-ytd hide">
                                    0.00 ر.س
                                </td> --}}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="report-bottom no-print">
                        <button type="button" class="btn btn-primary pull-right" aria-label="Print"
                            onclick="window.print();"><i class="fa fa-print"></i> @lang('messages.print')</button>
                        <div class="dropdown pull-right">
                        </div>
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


                        <form action="{{ action('AcMasterController@store') }}" method="post" id="master_add_form">
                            @csrf
                            <!-- Your form fields go here -->


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
                                    <label
                                        for="add-account_name_ar">{{ __('chart_of_accounts.account_name_ar') }}:*</label>
                                    <input type="text" id="add-account_name_ar" name="account_name_ar"
                                        class="form-control" required
                                        placeholder="{{ __('chart_of_accounts.account_name_ar') }}">
                                </div>

                                <div class="form-group">
                                    <label for="add-account_name_en">{{ __('chart_of_accounts.account_name_en') }}</label>
                                    <input type="text" id="add-account_name_en" name="account_name_en"
                                        class="form-control" placeholder="{{ __('chart_of_accounts.account_name_en') }}">
                                </div>

                                <div class="form-group">
                                    <label for="add-account_number">{{ __('chart_of_accounts.account_number') }}:</label>
                                    <input type="text" id="add-account_number" name="account_number"
                                        class="form-control" required readonly>
                                </div>

                                <div class="form-group">
                                    <label for="add-account_level">{{ __('chart_of_accounts.account_level') }}:</label>
                                    <input type="text" id="add-account_level" name="account_level"
                                        class="form-control" required readonly>
                                </div>

                                <div class="form-group">
                                    <label
                                        for="add-parent_acct_name">{{ __('chart_of_accounts.parent_acct_no') }}:</label>
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

    <script>
        $(document).ready(function() {
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
                console.log(filter_type);
                if (filter_type != 'NULL') {
                    if (filter_type == 'branch_cost_center' || filter_type == 'feild_add_cost_center') {
                        $('#cost_cen_list_div').hide();
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
                    $('#cost_cen_list_div').hide();
                    // toastr.success('null');
                }
            });


        });


        // Centring element:
        // $('.parent-container').center();
    </script>
@endsection

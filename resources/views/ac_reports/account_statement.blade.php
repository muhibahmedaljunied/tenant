@extends('layouts.app')
@section('title', __('chart_of_accounts.account_statement'))

@section('css')
    <style type="text/css">
        a {
            text-decoration: none;
            color: #850062;
            &:hover {
                color: #9A1551;
            }
        }

        table {
            table-layout: fixed;
        }

        table td {
            width: 30px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('lang_v1.chart_of_accounts')
            <small>@lang('chart_of_accounts.account_statement')</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <form action="{{ route('ac_reports_account-statement-report') }}" method="GET">
                        <div class="row">
                            <div class="col-md-12">
                           <div class="col-md-3">
    <div class="form-group">
        <label for="account_number">{{ __('report.select_account') }}:</label>
        <select id="account_number" name="account_number" class="form-control select2 account_number" style="width: 100%;" required>
            <option value="">{{ __('chart_of_accounts.please_select_account') }}</option>
            @foreach($lastChildrenBranch as $key => $value)
                <option value="{{ $key }}" {{ $filter_type == 'accounts_group' ? '' : ($account_number == $key ? 'selected' : '') }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="al_date_filter">{{ __('report.date_range') }}:</label>
        <input type="hidden" name="ar_date_from" class="ar_date_from" value="{{ $date_from }}">
        <input type="hidden" name="ar_date_to" class="ar_date_to" value="{{ $date_to }}">
        <input type="text" id="al_date_filter" class="form-control" readonly 
               placeholder="{{ __('lang_v1.select_a_date_range') }}" value="{{ $date_from }} - {{ $date_to }}">
    </div>
</div>

                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-12">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::checkbox('is_int_sec_acc_number', 1, $is_int_sec_acc_number, ['id' => 'is_int_sec_acc_number']) !!}
                                        {!! Form::label('al_date_filter', __('report.filter_type') . ':') !!}
                                        {!! Form::select('int_sec_acc_number', $lastChildrenBranch, $int_sec_acc_number, [
                                            'class' => 'form-control select2 int_sec_acc_number',
                                            'style' => 'width: 100%;',
                                            'required',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        <div class="row">
                            <div class="col-md-12">

                          <div class="col-md-3">
    <div class="form-group">
        <label for="filter_type">{{ __('chart_of_accounts.select_filter_no_type') }}:</label>
        <select id="filter_type" name="filter_type" class="form-control select2">
            <option value="NULL" {{ $filter_type == 'NULL' ? 'selected' : '' }}>
                {{ __('chart_of_accounts.select_filter_no_type') }}
            </option>
            <option value="accounts_group" {{ $filter_type == 'accounts_group' ? 'selected' : '' }}>
                {{ __('chart_of_accounts.accounts_group') }}
            </option>
        </select>
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


                                @if ($filter_type == 'branch_cost_center' || $filter_type == 'feild_add_cost_center')
                              <div class="col-md-3" id="cost_cen_list_div">
    <div class="form-group" id="cost_cen_list">
        <label for="cost_center">{{ __('chart_of_accounts.cost_center') }}:</label>
        
        @if ($filter_type == 'branch_cost_center')
            <select id="cost_center" name="cost_center" class="form-control select2 cost_center" style="width: 100%;">
                <option value="">{{ __('chart_of_accounts.please_select_cost_center') }}</option>
                @foreach($ac_cost_cen_branches as $key => $value)
                    <option value="{{ $key }}" {{ $cost_center == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        @elseif ($filter_type == 'feild_add_cost_center')
            <select id="cost_center" name="cost_center" class="form-control select2 cost_center" style="width: 100%;">
                <option value="">{{ __('chart_of_accounts.please_select_cost_center') }}</option>
                @foreach($ac_cost_cen_field_adds as $key => $value)
                    <option value="{{ $key }}" {{ $cost_center == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        @endif
    </div>
</div>

                                @else
                                    <div class="col-md-3" id="cost_cen_list_div" style="display: none;">
                                        <div class="form-group" id="cost_cen_list">

                                        </div>
                                    </div>
                                @endif
                                <div id="accounts_group_div" style="display: none;">
                           <div class="col-md-3">
    <div class="form-group">
        <label for="filter_from_acc">{{ __('report.filter_from_acc') }}:</label>
        <select id="filter_from_acc" name="filter_from_acc" class="form-control select2 filter_from_acc" style="width: 100%;">
            <option value="">{{ __('chart_of_accounts.please_select_account_start') }}</option>
            @foreach($lastChildrenBranch as $key => $value)
                <option value="{{ $key }}" {{ $filter_from_acc == $key ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="filter_to_acc">{{ __('report.filter_to_acc') }}:</label>
        <select id="filter_to_acc" name="filter_to_acc" class="form-control select2 filter_to_acc" style="width: 100%;">
            <option value="">{{ __('chart_of_accounts.please_select_account_end') }}</option>
            @foreach($lastChildrenBranch as $key => $value)
                <option value="{{ $key }}" {{ $filter_to_acc == $key ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>
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

        @component('components.widget')
            @if (count($accounts_range))
                <a href="#" class="btn btn-sm btn-info export_btn">
                    <i class="fas fa-file-excel"></i>
                </a>
                <a href="#" class="btn btn-sm btn-github print_btn">
                    <i class="fa fa-print" aria-hidden="true"></i>
                </a>
                <div class="treeview">
                    <div class="inner-block-reports col-12 clearfix">
                        <div class="reports-header text-center">
                            <h3>@lang('chart_of_accounts.account_statement')</h3>
                            <h6>*********</h6>
                            <h5>
                                @lang('chart_of_accounts.from_date') <span>{{ $date_from }}</span>
                                @lang('chart_of_accounts.to_date') <span>{{ $date_to }}</span>
                            </h5>
                        </div>

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
                        @endphp
                        @foreach ($accounts_range as $key => $value)
                            @php
                                $journal_entry = '';
                                $found_journal_entry = false;
                            @endphp
                            @foreach ($journal_entries as $key2 => $journal_entry_temp)
                                @if ($key2 == $value)
                                    @php
                                        $journal_entry = $journal_entry_temp;
                                        $found_journal_entry = true;
                                    @endphp
                                @endif
                            @endforeach
                            @php
                                ////////////hereeeeeeeeeeeeeeeeeeeee///////////////
                                $opening_debtor = 0;
                                $opening_creditor = 0;
                                $opening_total_abs = 0;
                                $opening_total = 0;
                                $master_details = $accountStatementService->getAccountMaster()->where('account_number', $value)->first();
                                $closedEntries = $accountStatementService->closedEntriesData($previous_date,null, $branch_cost_centers, $field_cost_centers);
                                // $positive = \App\Models\AcJournalEntryDetail::where([['account_number', $master_details->account_number], ['amount', '>', 0]])
                                //     ->forEntryDate($previous_date, $branch_cost_centers)
                                //     ->forAccountType($field_cost_centers)
                                //     ->sum('amount');
                                $positive = $closedEntries->where('account_number', $master_details->account_number)->where('amount', '>',0)->sum('amount');
                                $negative = $closedEntries->where('account_number', $master_details->account_number)->where('amount', '<',0)->sum('amount');
                                // $negative = \App\Models\AcJournalEntryDetail::where([['account_number', $master_details->account_number], ['amount', '<', 0]])
                                //     ->forEntryDate($previous_date, $branch_cost_centers)
                                //     ->forAccountType($field_cost_centers)
                                //     ->sum('amount');
                                //opening_debtor
                                if ($master_details->account_type == 'debtor') {
                                    $opening_debtor = $positive;
                                    $opening_creditor = $negative * -1;
                                } else {
                                    //opening_creditor
                                    $opening_creditor = $positive;
                                    $opening_debtor = $negative * -1;
                                }

                                $opening_total = $positive + $negative;
                                $opening_total_abs = abs($positive + $negative);

                                $total_debtor = 0;
                                $total_debtor = $opening_debtor;
                                $total_creditor = 0;
                                $total_creditor = $opening_creditor;
                                $total_net = 0;
                                $total_net = $opening_total;
                                $sun_net = 0;
                                $sun_net = $opening_total;
                                /////////hereeeeeeeeeeeeeeeeeeeee//////////
                            @endphp
                            <h6 class="text-center">************************</h6>
                            <h3 class="text-center">{{ $master_details->account_name_ar }} (
                                {{ $master_details->account_number }} ) </h3>
                            <div class="table-responsive manage-currency-table reports-table" id="report_table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="3">
                                                @lang('chart_of_accounts.entry_date', [], 'ar')
                                                <br>
                                                @lang('chart_of_accounts.entry_date', [], 'en')
                                            </th>

                                            <th colspan="2">
                                                @lang('chart_of_accounts.account_number_name', [], 'ar')
                                                <br>
                                                @lang('chart_of_accounts.account_number_name', [], 'en')
                                            </th>

                                            <th colspan="7">
                                                @lang('chart_of_accounts.entry_desc', [], 'ar')
                                                <br>
                                                @lang('chart_of_accounts.entry_desc', [], 'en')
                                            </th>

                                            <th colspan="2">
                                                @lang('chart_of_accounts.entry_ref', [], 'ar')
                                                <br>
                                                @lang('chart_of_accounts.entry_ref', [], 'en')
                                            </th>

                                            <th colspan="2">
                                                @lang('chart_of_accounts.debtor', [], 'ar')
                                                <br>
                                                @lang('chart_of_accounts.debtor', [], 'en')
                                            </th>

                                            <th colspan="2">
                                                @lang('chart_of_accounts.creditor', [], 'ar')
                                                <br>
                                                @lang('chart_of_accounts.creditor', [], 'en')
                                            </th>

                                            <th colspan="2">
                                                @lang('chart_of_accounts.balance', [], 'ar')
                                                <br>
                                                @lang('chart_of_accounts.balance', [], 'en')
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr class="reports-total">
                                            <td data-title="Account" colspan="3">
                                                {{ $previous_date }}
                                            </td>

                                            <td style="text-align:center;" data-title="Debit" colspan="11">
                                                الرصيد الافتتاحي

                                            </td>
                                            <td data-title="Credit" colspan="2">
                                                {{-- {{ $opening_debtor }} {{session('business.currency.code')}} --}}

                                            </td>
                                            <td data-title="Net Movement" colspan="2">
                                                {{-- {{ $opening_creditor }} {{session('business.currency.code')}} --}}
                                            </td>
                                            <td data-title="Net Movement" colspan="2">
                                                {{ DisplayDouble($opening_total_abs) }}
                                                {{ session('business.currency.code') }}
                                            </td>
                                            {{-- <td data-title="YTD Balance" class="balance-ytd hide">
                                        0.00 {{session('business.currency.code')}}
                                    </td> --}}
                                        </tr>

                                        @if ($found_journal_entry)
                                            @foreach ($journal_entry->sortBy('ac_journal_entry.entry_date') as $entry)
                                                <tr>
                                                    <td colspan="3">
                                                        {{ $entry->ac_journal_entry->entry_date }}

                                                    </td>
                                                    <td colspan="2">
                                                        {{ $entry->account_type->account_name_ar }} -
                                                        {{ $entry->account_number }}

                                                    </td>
                                                    <td colspan="2">
                                                        @if ($entry->ac_journal_entry->entry_type == 'daily')
                                                            @lang('chart_of_accounts.entry_type_daily')
                                                        @else
                                                            @lang('chart_of_accounts.entry_type_opening')
                                                        @endif

                                                    </td>

                                                    <td colspan="5">
                                                        {{ $entry->ac_journal_entry->entry_desc }}

                                                    </td>
                                                    <td colspan="2">
                                                        {{ $entry->ac_journal_entry->sequence }}

                                                    </td>

                                                    @php
                                                        $debtor = 0;
                                                        $creditor = 0;
                                                        $positive = 0;
                                                        $negative = 0;

                                                        if ($entry->amount > 0) {
                                                            $positive = (float) $entry->amount;
                                                        } else {
                                                            $negative = (float) $entry->amount;
                                                        }

                                                        if ($entry->account_type->account_type == 'debtor') {
                                                            $debtor = $positive;
                                                            $creditor = $negative * -1;
                                                        } else {
                                                            //creditor
                                                            $creditor = $positive;
                                                            $debtor = $negative * -1;
                                                        }

                                                        $sun_net += $positive + $negative;
                                                        $total_debtor += $debtor;
                                                        $total_creditor += $creditor;
                                                        $total_net += $debtor + $creditor;

                                                    @endphp

                                                    <td colspan="2">
                                                        {{ DisplayDouble($debtor) }} {{ session('business.currency.code') }}

                                                    </td>
                                                    <td colspan="2">
                                                        {{ DisplayDouble($creditor) }} {{ session('business.currency.code') }}

                                                    </td>
                                                    <td colspan="2">
                                                        {{ DisplayDouble($sun_net) }} {{ session('business.currency.code') }}

                                                    </td>

                                                </tr>
                                            @endforeach
                                        @endif

                                        <tr class="reports-total">
                                            <td data-title="Account" colspan="14">
                                                المجموع
                                            </td>
                                            {{-- <td data-title="Opening Balance" class="balance-ytd hide">
                                                    0.00 {{session('business.currency.code')}}
                                                </td> --}}
                                            <td data-title="Debit" colspan="2">
                                                {{ DisplayDouble($total_debtor - $opening_debtor) }}
                                                {{ session('business.currency.code') }}
                                            </td>
                                            <td data-title="Credit" colspan="2">
                                                {{ DisplayDouble($total_creditor - $opening_creditor) }}
                                                {{ session('business.currency.code') }}
                                            </td>
                                            <td data-title="Net Movement" colspan="2">

                                            </td>
                                            {{-- <td data-title="YTD Balance" class="balance-ytd hide">
                                                    0.00 {{session('business.currency.code')}}
                                                </td> --}}
                                        </tr>
                                        <tr class="reports-total">
                                            <td data-title="Account" colspan="3">
                                                {{ $date_to }}
                                            </td>
                                            {{-- <td data-title="Opening Balance" class="balance-ytd hide">
                                                    0.00 {{session('business.currency.code')}}
                                                </td> --}}
                                            <td data-title="Debit" colspan="11">
                                                صافي الحركة

                                            </td>
                                            <td data-title="Credit" colspan="2">
                                                @if ($total_debtor > $total_creditor)
                                                    {{ DisplayDouble($total_debtor - $total_creditor - $opening_total_abs) }}
                                                    {{ session('business.currency.code') }}
                                                @else
                                                    0 {{ session('business.currency.code') }}
                                                @endif
                                            </td>
                                            <td data-title="Net Movement" colspan="2">
                                                @if ($total_debtor < $total_creditor)
                                                    {{ DisplayDouble($total_creditor - $total_debtor - $opening_total_abs) }}
                                                    {{ session('business.currency.code') }}
                                                @else
                                                    0 {{ session('business.currency.code') }}
                                                @endif
                                            </td>
                                            <td data-title="Net Movement" colspan="2">

                                            </td>
                                            {{-- <td data-title="YTD Balance" class="balance-ytd hide">
                                                    0.00 {{session('business.currency.code')}}
                                                </td> --}}
                                        </tr>
                                        <tr class="reports-total">
                                            <td data-title="Account" colspan="3">
                                                {{ $date_to }}
                                            </td>
                                            {{-- <td data-title="Opening Balance" class="balance-ytd hide">
                                                    0.00 {{session('business.currency.code')}}
                                                </td> --}}
                                            <td data-title="Debit" colspan="11">
                                                رصيد ختامي

                                            </td>
                                            <td data-title="Credit" colspan="2">

                                            </td>
                                            <td data-title="Net Movement" colspan="2">

                                            </td>
                                            <td data-title="Net Movement" colspan="2">
                                                @if ($total_debtor > $total_creditor)
                                                    {{ DisplayDouble($total_debtor - $total_creditor) }}
                                                    {{ session('business.currency.code') }}
                                                @else
                                                    {{ DisplayDouble($total_creditor - $total_debtor) }}
                                                    {{ session('business.currency.code') }}
                                                @endif

                                            </td>
                                            {{-- <td data-title="YTD Balance" class="balance-ytd hide">
                                                    0.00 {{session('business.currency.code')}}
                                                </td> --}}
                                        </tr>
                                    </tbody>
                                </table>


                            </div>
                        @endforeach

                        <div class="report-bottom">

                        </div>
                    </div>
                </div>
            @endif
        @endcomponent
        {{-- @endcan --}}


    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

    <script>
        let company_logo = "{{ session('business.logo') }}";
        let company_name = "{{ session('business.name') }}";

        $(document).ready(function() {
            function set_right_to_left(wb) {
                if (!wb.Workbook) wb.Workbook = {};
                if (!wb.Workbook.Views) wb.Workbook.Views = [];
                if (!wb.Workbook.Views[0]) wb.Workbook.Views[0] = {};
                wb.Workbook.Views[0].RTL = true;
            }

            function ExportToExcel(type, fn, dl) {
                var elt = document.getElementById('report_table');
                var wb = XLSX.utils.table_to_book(elt, {
                    sheet: "sheet1"
                });
                set_right_to_left(wb);
                return dl ?
                    XLSX.write(wb, {
                        bookType: type,
                        bookSST: true,
                        type: 'base64'
                    }) :
                    XLSX.writeFile(wb, fn || (
                        `كشف حساب - {{ !empty($master_details) ? $master_details->account_name_ar : '' }} .` +
                        (type || 'xlsx')));
            }

            $('.export_btn').click(function(e) {
                e.preventDefault();
                ExportToExcel();
            });
            $('.print_btn').click(function(e) {
                e.preventDefault();
                $('.inner-block-reports').printThis({
                    header: `<div class="col-12" style="margin-top:3px;text-align:center">
                                    <img style="max-height: 120px; width: 100%;object-fit:cover;" src="{{ url('uploads/business_logos/' . Session::get('business.logo')) }}"
                                        class="img img-responsive center-block">
                                </div>`

                });
            });
            $('#al_date_filter').daterangepicker(dateRangeSettings, function(start, end) {
                $('#al_date_filter').val(
                    start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format),
                );
                $('.ar_date_from').val(start.format(moment_date_format));
                $('.ar_date_to').val(end.format(moment_date_format));

            });
            $('#al_date_filter').on('cancel.daterangepicker', function(ev, picker) {
                $('#al_date_filter').val('');
                // activity_log_table.ajax.reload();
            });

            $(document).on('change', '#filter_type', function() {
                var filter_type = $(this).val();
                // console.log(filter_type);
                $('#accounts_group_div').hide();
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
                    } else if (filter_type == 'accounts_group') {

                        $('#cost_cen_list').html('');
                        $('#accounts_group_div').show();
                    }
                } else {
                    // $('#cost_cen_list_div').hide();
                    // toastr.success('null');
                }
            });

        });

        // Just for centering elements:

        // Centring function:
        // jQuery.fn.center = function() {
        //     this.css('position', 'absolute');
        //     this.css('top', Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
        //         $(window).scrollTop()) + 'px');
        //     this.css('left', Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
        //         $(window).scrollLeft()) + 'px');
        //     return this;
        // };


        // Centring element:
        // $('.parent-container').center();
    </script>
@endsection

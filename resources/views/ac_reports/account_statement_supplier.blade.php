@extends('layouts.app')
@section('title', __('chart_of_accounts.account_statement_supplier'))

@section('css')
    <style media="all">
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

        .invoice-report tbody>tr>td {
            vertical-align: middle !important;
        }

        table td {
            width: 30px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #user_tree_table .fs6 {
            font-size: 1.05rem !important;
        }
    </style>
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('chart_of_accounts.account_statement_supplier')
            <small>@lang('chart_of_accounts.account_statement')</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <form action="{{ route('ac_reports_account-statement-report_supplier') }}" method="GET">

                        <div class="row">
                            <div class="col-md-12">
                       <div class="col-md-3">
    <div class="form-group">
        <label for="account_number">{{ __('report.select_account') }}:</label>
        <select id="account_number" name="account_number[]" class="form-control select2 account_number" style="width: 100%;" multiple>
            <option value="">{{ __('chart_of_accounts.please_select_account') }}</option>
            @foreach($lastChildrenBranch as $key => $value)
                <option value="{{ $key }}" {{ in_array($key, (array)$account_number) ? 'selected' : '' }}>
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
                <div class="treeview">
                    <div class="inner-block-reports col-sm-12 col-xs-12 clearfix">
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

                            $accountDaysDetails = \App\Models\AcJournalEntryDetail::whereIn('account_number', $accountsMaster->pluck('account_number')->toArray())
                                ->forEntryDate(now()->toDateString(), $branch_cost_centers)
                                ->with('ac_journal_entry.journal_entry_debt_ages')
                                ->forAccountType($field_cost_centers)
                                ->get();
                            $accountAmountDetails = \App\Models\AcJournalEntryDetail::whereIn('account_number', $accountsMaster->pluck('account_number')->toArray())
                                ->forEntryDate($previous_date, $branch_cost_centers)
                                ->forAccountType($field_cost_centers)
                                ->select('amount')
                                ->get();
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
                                $master_details = $accountsMaster->where('account_number', $value)->first();
                                $positive = $accountAmountDetails->where('account_number', $value)->sum(function ($value) {
                                    if ($value['amount'] > 0) {
                                        return $value['amount'];
                                    }
                                    return 0;
                                });
                                $negative = $accountAmountDetails->where('account_number', $value)->sum(function ($value) {
                                    if ($value['amount'] < 0) {
                                        return $value['amount'];
                                    }
                                    return 0;
                                });

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
                            <div class="account_parent_content">
                                <a href="#" class="btn btn-sm btn-github print_btn ">
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-info export_btn ">
                                    <i class="fas fa-file-excel"></i>
                                </a>
                                <div class="content">
                                    <div class="inner-block-reports">
                                        <h6 class="text-center">************************</h6>
                                        <h3 class="text-center">{{ $master_details->account_name_ar }} (
                                            {{ $master_details->account_number }} ) </h3>
                                        <div class="table-responsive manage-currency-table reports-table">
                                            <table class="table" id="report_table">
                                                <thead>
                                                    <tr>
                                                        <th colspan="3">
                                                            @lang('chart_of_accounts.entry_date', [], 'ar')
                                                            <br>
                                                            @lang('chart_of_accounts.entry_date', [], 'en')
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
                                                        <td style="text-align:center;" data-title="Debit" colspan="9">
                                                            الرصيد الافتتاحي
                                                        </td>
                                                        <td data-title="Credit" colspan="2"></td>
                                                        <td data-title="Net Movement" colspan="2"></td>
                                                        <td data-title="Net Movement" colspan="2">
                                                            {{ DisplayDouble($opening_total_abs) }}
                                                        </td>
                                                    </tr>
    
    
                                                    @if ($found_journal_entry)
                                                        @foreach ($journal_entry->sortBy('ac_journal_entry.entry_date') as $entry)
                                                            <tr>
                                                                <td colspan="3">
                                                                    {{ $entry->ac_journal_entry->entry_date }}
                                                                </td>
                                                                <td colspan="7">
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
                                                                    {{ DisplayDouble($debtor) }}
                                                                </td>
                                                                <td colspan="2">
                                                                    {{ DisplayDouble($creditor) }}
                                                                </td>
                                                                <td colspan="2">
                                                                    {{ DisplayDouble($sun_net) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
    
                                                    <tr class="reports-total">
                                                        <td data-title="Account" colspan="12">
                                                            المجموع
                                                        </td>
    
                                                        <td data-title="Debit" colspan="2">
                                                            {{ DisplayDouble($total_debtor - $opening_debtor) }}
                                                        </td>
                                                        <td data-title="Credit" colspan="2">
                                                            {{ DisplayDouble($total_creditor - $opening_creditor) }}
                                                        </td>
                                                        <td data-title="Net Movement" colspan="2">
    
                                                        </td>
    
                                                    </tr>
                                                    <tr class="reports-total">
                                                        <td data-title="Account" colspan="3">
                                                            {{ $date_to }}
                                                        </td>
    
                                                        <td data-title="Debit" colspan="9">
                                                            صافي الحركة
    
                                                        </td>
                                                        <td data-title="Credit" colspan="2">
                                                            @if ($total_debtor > $total_creditor)
                                                                {{ DisplayDouble($total_debtor - $total_creditor - $opening_total_abs) }}
                                                            @else
                                                                0
                                                            @endif
                                                        </td>
                                                        <td data-title="Net Movement" colspan="2">
                                                            @if ($total_debtor < $total_creditor)
                                                                {{ DisplayDouble($total_creditor - $total_debtor - $opening_total_abs) }}
                                                            @else
                                                                0
                                                            @endif
                                                        </td>
                                                        <td data-title="Net Movement" colspan="2">
    
                                                        </td>
                                                    </tr>
                                                    <tr class="reports-total">
                                                        <td data-title="Account" colspan="3">
                                                            {{ $date_to }}
                                                        </td>
                                                        <td data-title="Debit" colspan="9">
                                                            رصيد ختامي
    
                                                        </td>
                                                        <td data-title="Credit" colspan="2">
    
                                                        </td>
                                                        <td data-title="Net Movement" colspan="2">
    
                                                        </td>
                                                        <td data-title="Net Movement" colspan="2">
                                                            @if ($total_debtor > $total_creditor)
                                                                {{ DisplayDouble($total_debtor - $total_creditor) }}
                                                            @else
                                                                {{ DisplayDouble($total_creditor - $total_debtor) }}
                                                            @endif
    
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
    
                                        </div>
                                    </div>
    
                                    <hr>
                                    @php
                                        $daysDetails = $accountDaysDetails->where('account_number', $master_details->account_number);
    
                                        $newDaysDetails = collect();
                                        $daysDetails = $daysDetails->each(function ($item) use ($newDaysDetails) {
                                            if ($item->ac_journal_entry->journal_entry_debt_ages->count()) {
                                                return $item->ac_journal_entry->journal_entry_debt_ages->each(function ($content) use ($newDaysDetails) {
                                                    return $newDaysDetails->push(['amount' => $content->amount, 'entry_date' => $content->debtages_date]);
                                                });
                                            }
                                            return $newDaysDetails->push(['amount' => $item->amount, 'entry_date' => $item->ac_journal_entry->entry_date]);
                                        });
                                    @endphp
    
                                    <div class="client-report">
                                        <x-report.debt-statement-report-component :combinedData="$newDaysDetails" :totalDebtor="$total_debtor"
                                            :totalCreditor="$total_creditor" :dateRanges="getDebtDatesRanges()" />
                                    </div>
                                </div>
                                <hr>
                            </div>
                        @endforeach
                        <div class="report-bottom">
                        </div>
                    </div>
                </div>
            @endif

        @endcomponent


    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script src="{{ asset('js/set_right_left_xslx.js') }}"></script>
    <script src="{{ asset('js/excel_debt_export.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.js"></script>
    <script>
        $(document).ready(function() {
            let company_logo = "{{ url('uploads/business_logos/' . Session::get('business.logo')) }}";
            let company_name = "{{ session('business.name') }}";

            $('.export_btn').click(function(e) {
                e.preventDefault();
                ExportDebtAgesToExcel($(this).parents('.account_parent_content').find('.content'));
            });
            $('.print_btn').click(function(e) {
                e.preventDefault();
                $(this).parents('.account_parent_content').find('.content').printThis({
                    importStyle: true,
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
                console.log(start.format(moment_date_format));
                console.log(end.format(moment_date_format));

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
        jQuery.fn.center = function() {
            this.css('position', 'absolute');
            this.css('top', Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
                $(window).scrollTop()) + 'px');
            this.css('left', Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                $(window).scrollLeft()) + 'px');
            return this;
        };


    </script>
@endsection

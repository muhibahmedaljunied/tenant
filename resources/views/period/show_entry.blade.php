@extends('layouts.app')
@if ($ac_journal->entry_type == 'opening')
    @section('title', __('chart_of_accounts.show_openning_journal_entry'))
@else
    @section('title', __('chart_of_accounts.show_journal_entry'))
@endif
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        @if ($ac_journal->entry_type == 'opening')
            <h1>@lang('chart_of_accounts.show_openning_journal_entry')</h1>
        @else
            <h1>@lang('chart_of_accounts.show_journal_entry')</h1>
        @endif

    </section>

    <!-- Main content -->
    <section class="content">

        @component('components.widget', ['class' => 'box-primary'])
            <div class="row">
                <div class="clearfix"></div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="entry_desc">{{ __('chart_of_accounts.entry_desc') }}</label>
                        <input type="text" name="entry_desc" id="entry_desc" class="form-control"
                               value="{{ $ac_journal->entry_desc }}" readonly>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="entry_date">{{ __('chart_of_accounts.entry_date') }}:*</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <input type="text" name="entry_date" id="entry_date" class="form-control"
                                   value="{{ $ac_journal->entry_date }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="cost_cen_branche_id">{{ __('chart_of_accounts.cost_cen_branch') }}</label>
                        <input type="text" name="cost_cen_branche_id" id="cost_cen_branche_id" class="form-control"
                               value="{{ $ac_journal->cost_cen_branche_details ? $ac_journal->cost_cen_branche_details->cost_description : '' }}"
                               readonly>
                    </div>
                </div>


            </div>

            <div class="row">
                <div class="clearfix"></div>
                @if ($ac_journal->entry_type == 'opening')
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="opening_balance_calculation">{{ __('chart_of_accounts.opening_balance_calculation') }}</label>
                            <input type="text" name="opening_balance_calculation" id="opening_balance_calculation" class="form-control"
                                   value="{{ $ac_journal->opening_account_details->account_name_ar . '-' . $ac_journal->opening_account_details->account_number }}"
                                   readonly>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-striped table-responsive"
                           id="journal_entry_table">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 20vw">
                                @lang('chart_of_accounts.account_number')
                            </th>
                            <th class="text-center">
                                @lang('chart_of_accounts.debtor')
                            </th>

                            <th class="text-center">
                                @lang('chart_of_accounts.creditor')
                            </th>

                            <th class="text-center">
                                @lang('chart_of_accounts.comments')
                            </th>
                            <th class="text-center">
                                @lang('chart_of_accounts.cost_cen_field')
                            </th>

                        </tr>
                        </thead>
                        <tbody class="journal_entry_rows" id="journal_entry_rows">
                        @php
                            $total_debtor = 0;
                            $total_creditor = 0;
                        @endphp
                        @foreach ($ac_journal->ac_journal_entries as $ac_journal_entry)
                            @break(!$ac_journal_entry->account_type)

                            <tr class="journal_entry_row">

                                <td>
                                    <input type="text" class="form-control"
                                           value="{{ $ac_journal_entry->account_type->account_name_ar . '-' . $ac_journal_entry->account_number }}"
                                           readonly>
                                </td>
                                <td>
                                    @if (($ac_journal_entry->account_type->account_type == 'debtor' && $ac_journal_entry->amount > 0) ||
                                        ($ac_journal_entry->account_type->account_type == 'creditor' && $ac_journal_entry->amount < 0))
                                        <input type="text" class="form-control"
                                               value="{{ DisplayDouble(abs($ac_journal_entry->amount)) }}" readonly>
                                        @php
                                            $total_debtor += abs($ac_journal_entry->amount);
                                        @endphp
                                    @endif

                                </td>
                                <td>
                                    @if (($ac_journal_entry->account_type->account_type == 'creditor' && $ac_journal_entry->amount > 0) ||
                                        ($ac_journal_entry->account_type->account_type == 'debtor' && $ac_journal_entry->amount < 0))
                                        <input type="text" class="form-control"
                                               value="{{ DisplayDouble(abs($ac_journal_entry->amount)) }}" readonly>
                                        @php
                                            $total_creditor += abs($ac_journal_entry->amount);
                                        @endphp
                                    @endif

                                </td>
                                <td>
                                    <input type="text" class="form-control" value="{{ $ac_journal_entry->entry_desc }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control"
                                           value="{{ $ac_journal_entry->cost_cen_field ? $ac_journal_entry->cost_cen_field->cost_description : '' }}"
                                           readonly>
                                </td>

                            <tr>
                        @endforeach

                        </tbody>

                    </table>
                    <table class="table table-condensed table-bordered table-striped table-responsive">
                        <tbody>

                        <tr style="background-color: greenyellow">
                            <td colspan="4" class="text-center"><span>الاجمالى</span></td>
                        </tr>
                        <tr style="background-color: greenyellow">
                            <td class="text-center"><span>المجموع :</span> <span
                                        id="total_debtor">{{ DisplayDouble($total_debtor) }}</span></td>
                            <td><span>المجموع :</span> <span id="total_creditor"> {{ DisplayDouble($total_creditor) }}</span></td>

                        </tr>
                        <tr>

                            <td colspan="4" id="unbalanced_entry" style="color: red;display: none;">القيد غير متوازن
                                بمقدار <span id="unbalanced_entry_value_dif"></span></td>


                        </tr>
                        </tbody>
                    </table>

                </div>


            </div>
        @endcomponent


    </section>
    <!-- /.content -->

@endsection

@section('javascript')

    <script type="text/javascript">
        $(document).ready(function() {
        });
    </script>
@endsection

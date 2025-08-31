@extends('layouts.app')
@section('title', __('chart_of_accounts.edit_opening_journal_entry'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('chart_of_accounts.edit_opening_journal_entry')</h1>
    <!-- <ol class="breadcrumb">
                                                                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                                                                <li class="active">Here</li>
                                                            </ol> -->
</section>

<!-- Main content -->
<section class="content">
    <form action="{{ action('AcJournalEntryController@updateOpenAccountEntry', [$ac_journal_entry_record->id]) }}" method="POST" id="journal_entry_add_form" class="journal_entry_form{{ empty($duplicate_product) ? 'create' : '' }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @component('components.widget', ['class' => 'box-primary'])
        <div class="row">
            <div class="clearfix"></div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="opening_balance_calculation">{{ __('chart_of_accounts.opening_balance_calculation') }}:*</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <select name="opening_balance_calculation" class="form-control select2 opening_balance_calculation" style="width: 100%;" required>
                            @foreach($lastChildrenBranch as $key => $label)
                            <option value="{{ $key }}" {{ $ac_journal_entry_record->opening_account == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="entry_date">{{ __('chart_of_accounts.entry_date') }}:*</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" name="entry_date" id="entry_date" class="form-control" required value="{{ \Carbon\Carbon::parse($ac_journal_entry_record->entry_date)->format('d/m/Y') }}">
                    </div>
                </div>

            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="cost_cen_branche_id">{{ __('chart_of_accounts.cost_cen_branch') }}:</label>
                    <select name="cost_cen_branche_id" class="form-control select2 cost_cen_branche_id" style="width: 100%;">
                        <option value="">{{ __('Select Cost Center') }}</option>
                        @foreach($ac_cost_cen_branches as $key => $label)
                        <option value="{{ $key }}" {{ $ac_journal_entry_record->cost_cen_branche_id == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>

            </div>




        </div>

        <div class="row">
            <div class="clearfix"></div>

            <div class="col-sm-8">
                <div class="form-group">
                    <label for="entry_desc">{{ __('chart_of_accounts.entry_desc') }}</label>
                    <input type="text" name="entry_desc" id="entry_desc" value="{{ $ac_journal_entry_record->entry_desc }}" class="form-control">
                </div>

            </div>

        </div>
        <input type="hidden" id="journal_entry_row_count" value="0">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped table-responsive" id="journal_entry_table">
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

                            <th class="text-center"><i class="fas fa-times" aria-hidden="true"></i></th>
                        </tr>
                    </thead>
                    <tbody class="journal_entry_rows" id="journal_entry_rows">
                        @php
                        $counter = 0;
                        $total_debtor = 0.0;
                        $total_creditor = 0.0;
                        @endphp

                        @foreach ($ac_journal_entry_record->ac_journal_entries as $entry)
                        @if ($entry->account_number != $ac_journal_entry_record->opening_account)
                        <tr class="journal_entry_row">

                            <td>
                                <select name="journal_entries[{{ $counter }}][account_number]" class="form-control select2 account_number" style="width: 100%;" required>
                                    @foreach($lastChildrenBranch as $key => $label)
                                    <option value="{{ $key }}" {{ $entry->account_number == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>

                            </td>
                            <td>
                                @php
                                $debtor = 0.0;
                                $creditor = 0.0;
                                if ($entry->account_type->account_type == 'debtor') {
                                if ($entry->amount > 0) {
                                $debtor = $entry->amount;
                                } else {
                                $creditor = $entry->amount * -1;
                                }
                                } else {
                                if ($entry->amount > 0) {
                                $creditor = $entry->amount;
                                } else {
                                $debtor = $entry->amount * -1;
                                }
                                }
                                $total_debtor += $debtor;
                                $total_creditor += $creditor;

                                @endphp
                                <input type="text" name="journal_entries[{{ $counter }}][debtor]" value="{{ @num_format($debtor) }}" class="form-control input_number debtor" required>

                            </td>
                            <td>
                                <input type="text" name="journal_entries[{{ $counter }}][creditor]" value="{{ @num_format($creditor) }}" class="form-control input_number creditor" required>
                            </td>

                            <td>
                                <input type="text" name="journal_entries[{{ $counter }}][entry_desc]" value="{{ $entry->entry_desc }}" class="form-control entry_desc">
                            </td>

                            <td>
                                <select name="journal_entries[{{ $counter }}][cost_cen_field_id]" class="form-control select2 cost_cen_field_id" style="width: 100%;" placeholder="Select Addtional Cost Center">
                                    @foreach($ac_cost_cen_field_adds as $id => $label)
                                    <option value="{{ $id }}" {{ $entry->cost_cen_field_id == $id ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </td>


                            <td class="text-center " style="padding-top: 10px;">
                                <i class="fa fa-times  journal_entry_remove_row cursor-pointer btn btn-danger"
                                    aria-hidden="true"></i>
                            </td>
                        <tr>
                            @php $counter++; @endphp
                            @endif
                            @endforeach
                            <input type="hidden" id="journal_entry_row_count" value="{{ $counter }}">

                    </tbody>

                </table>
                <table class="table table-condensed table-bordered table-striped table-responsive">
                    <tbody>

                        <tr style="background-color: greenyellow">
                            <td colspan="4" class="text-center"><span>الاجمالى</span></td>
                        </tr>
                        <tr style="background-color: greenyellow">
                            <td class="text-center"><span>المجموع :</span> <span
                                    id="total_debtor">{{ $total_debtor }}</span>
                                <input type="hidden" class="total_debtor" name="total_debtor" value="{{ $total_debtor }}">
                            </td>
                            <td> <span>المجموع :</span> <span id="total_creditor"> {{ $total_creditor }}</span>
                                <input type="hidden" class="total_creditor" name="total_creditor"
                                    value="{{ $total_creditor }}">
                            </td>

                        </tr>

                    </tbody>
                </table>

            </div>


        </div>

        <div class="row">

            <i class="fa fa-plus btn btn-info add_new_journal_entry_row cursor-pointer">@lang('chart_of_accounts.add_more')</i>



        </div>
        @endcomponent




        <div class="row">

            <div class="col-sm-12">
                <input type="hidden" name="submit_type" id="submit_type">
                <div class="text-center">
                    <div class="btn-group">
                        <button type="submit" value="submit"
                            class="btn btn-primary submit_product_form">@lang('messages.save')</button>
                    </div>

                </div>
            </div>
        </div>



    </form>

</section>
<!-- /.content -->

@endsection

@section('javascript')


<script type="text/javascript">
    $(document).ready(function() {

        //Add new row on click on add row
        $('.add_new_journal_entry_row').on('click', function() {
            var journal_entry_row = $('input#journal_entry_row_count').val();
            $.ajax({
                method: 'GET',
                url: '/ac/journal_entry/journal_entry_row_open_account',
                async: false,
                data: {
                    journal_entry_row: journal_entry_row,
                },
                dataType: 'json',
                success: function(result) {
                    if (result.success) {
                        $('table#journal_entry_table tbody')
                            .append(result.html_content)
                            .find('input.pos_quantity');
                        //increment row count
                        $('input#journal_entry_row_count').val(parseInt(journal_entry_row) +
                            1);
                        // var this_row = $('table#pos_table tbody')
                        //     .find('tr')
                        //     .last();
                        // pos_each_row(this_row);




                    } else {
                        toastr.error(result.msg);
                        $('input#search_product')
                            .focus()
                            .select();
                    }
                },
            });

        });
        //Remove row on click on remove row
        $('table#journal_entry_table tbody').on('click', 'i.journal_entry_remove_row', function() {
            $(this)
                .parents('tr')
                .remove();
            calculateDebtorCreditorTotalMain();
            // pos_total_row();
        });

        $(".creditor, .debtor").on('keyup', function() {
            var journal_entry_row = parseInt($('input#journal_entry_row_count').val());
            console.log("journal_entry_row: " + journal_entry_row)
            if (journal_entry_row == 0) {
                calculateDebtorCreditorTotalMain();
            }
        });


        function calculateDebtorCreditorTotalMain() {
            var total_creditor = 0;
            var total_debtor = 0;
            // alert(total_creditor)
            //total creditor
            $(".creditor").each(function(i, v) {
                total_creditor += parseFloat($(this).val());
            });
            console.log("total_creditor: " + total_creditor)
            //total debtor
            $(".debtor").each(function(i, v) {
                total_debtor += parseFloat($(this).val());
            });
            console.log("total_debtor: " + total_debtor)
            //set total_debtor & total_creditor
            $("#total_creditor").text(total_creditor);
            $("#total_debtor").text(total_debtor);
            $('.total_creditor').val(total_creditor);
            $('.total_debtor').val(total_debtor);



        }


        __page_leave_confirmation('#journal_entry_add_form');
        onScan.attachTo(document, {
            suffixKeyCodes: [13], // enter-key expected at the end of a scan
            reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
            onScan: function(sCode, iQty) {
                $('input#sku').val(sCode);
            },
            onScanError: function(oDebug) {
                console.log(oDebug);
            },
            minLength: 2,
            ignoreIfFocusOn: ['input', '.form-control']
            // onKeyDetect: function(iKeyCode){ // output all potentially relevant key events - great for debugging!
            //     console.log('Pressed: ' + iKeyCode);
            // }
        });




        //Datetime picker
        $('#entry_date').datetimepicker({
            format: moment_date_format + ' ' + moment_time_format,
            ignoreReadonly: true,
        });

    });
</script>
@endsection
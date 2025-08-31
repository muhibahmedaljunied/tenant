@extends('layouts.app')
@section('title', __('chart_of_accounts.add_new_journal_entry'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('chart_of_accounts.add_new_journal_entry')</h1>
</section>
<!-- Main content -->
<section class="content">
    <form action="{{ action('AcJournalEntryController@store') }}" method="POST" id="journal_entry_add_form" class="journal_entry_form" enctype="multipart/form-data">
        @csrf

        @component('components.widget', ['class' => 'box-primary'])
        <div class="row">
            <div class="clearfix"></div>
            @if (session()->has('error'))
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible  show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{ session('error') }}</strong>
                </div>
            </div>
            @endif
            <div class="col-sm-8">
                <div class="form-group">
                    <label for="entry_desc">{{ __('chart_of_accounts.entry_desc') }}</label>
                    <input type="text" name="entry_desc" value="{{ old('entry_desc') }}" class="form-control">
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="entry_date">{{ __('chart_of_accounts.entry_date') }}:*</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" name="entry_date" id="entry_date" class="form-control" required value="{{ old('entry_date', now()->format('d/m/Y')) }}">
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="cost_cen_branche_id">{{ __('chart_of_accounts.cost_cen_branch') }}:</label>
                    <select name="cost_cen_branche_id" class="form-control select2 cost_cen_branche_id" style="width: 100%;" placeholder="Select Cost Center">
                        <option value="">{{ __('messages.please_select') }}</option>
                        @foreach($ac_cost_cen_branches as $key => $val)
                        <option value="{{ $key }}" {{ old('cost_cen_branche_id') == $key ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
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
                        @if(count(old('journal_entries', [])))
                        @foreach(old('journal_entries', []) as $key => $entry)
                        <tr class="journal_entry_row">
                            <td>
                                <select name="journal_entries[{{ $key }}][account_number]" class="form-control select2 account_number" style="width: 100%;" required>
                                    @foreach($lastChildrenBranch as $val => $label)
                                    <option value="{{ $val }}" {{ ($entry['account_number'] ?? null) == $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="journal_entries[{{ $key }}][debtor]" value="{{ @num_format($entry['debtor'] ?? 0) }}" class="form-control input_number debtor" required>
                            </td>
                            <td>
                                <input type="text" name="journal_entries[{{ $key }}][creditor]" value="{{ @num_format($entry['creditor'] ?? 0) }}" class="form-control input_number creditor" required>
                            </td>
                            <td>
                                <input type="text" name="journal_entries[{{ $key }}][entry_desc]" value="{{ $entry['entry_desc'] ?? '' }}" class="form-control entry_desc">
                            </td>
                            <td>
                                <select name="journal_entries[{{ $key }}][cost_cen_field_id]" class="form-control select2 cost_cen_field_id" style="width: 100%;" placeholder="Select Additional Cost Center">
                                    @foreach($ac_cost_cen_field_adds as $id => $name)
                                    <option value="{{ $id }}" {{ ($entry['cost_cen_field_id'] ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center" style="padding-top: 10px;">
                                <i class="fa fa-times journal_entry_remove_row cursor-pointer btn btn-danger" aria-hidden="true"></i>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr class="journal_entry_row">
                            <td>
                                <select name="journal_entries[0][account_number]" class="form-control select2 account_number" style="width: 100%;" required>
                                    @foreach($lastChildrenBranch as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="journal_entries[0][debtor]" value="{{ @num_format(0.0) }}" class="form-control input_number debtor" required>
                            </td>
                            <td>
                                <input type="text" name="journal_entries[0][creditor]" value="{{ @num_format(0.0) }}" class="form-control input_number creditor" required>
                            </td>
                            <td>
                                <input type="text" name="journal_entries[0][entry_desc]" class="form-control entry_desc">
                            </td>
                            <td>
                                <select name="journal_entries[0][cost_cen_field_id]" class="form-control select2 cost_cen_field_id" style="width: 100%;" placeholder="Select Additional Cost Center">
                                    @foreach($ac_cost_cen_field_adds as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center" style="padding-top: 10px;">
                                <i class="fa fa-times journal_entry_remove_row cursor-pointer btn btn-danger" aria-hidden="true"></i>
                            </td>
                        </tr>
                        @endif
                    </tbody>

                </table>
                <table class="table table-condensed table-bordered table-striped table-responsive">
                    <tbody>

                        <tr style="background-color: greenyellow">
                            <td colspan="4" class="text-center"><span>الاجمالى</span></td>
                        </tr>
                        <tr style="background-color: greenyellow">
                            <td class="text-center"><span>المجموع :</span> <span id="total_debtor">0</span> </td>
                            <td> <span>المجموع :</span> <span id="total_creditor"> 0</span></td>
                        </tr>
                        <tr>
                            <td colspan="4" id="unbalanced_entry" style="color: red;display: none;">القيد غير متوازن
                                بمقدار <span id="unbalanced_entry_value_dif"></span></td>
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
                            class="btn btn-primary submit_journal_entry_form">@lang('messages.save')</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</section>
@endsection
@section('javascript')
<script>
    $(".alert").alert();
</script>

<script type="text/javascript">
    function calculateDebtorCreditorTotalMain() {
        var total_creditor = 0;
        var total_debtor = 0;
        //total creditor
        $(".creditor").each(function(i, v) {
            total_creditor += parseFloat($(this).val());
        });
        //total debtor
        $(".debtor").each(function(i, v) {
            total_debtor += parseFloat($(this).val());
        });
        //set total_debtor & total_creditor
        $("#total_creditor").text(total_creditor);
        $("#total_debtor").text(total_debtor);

        //check diff
        if (total_creditor != total_debtor) {
            $(".submit_journal_entry_form").prop('disabled', true);

            $("#unbalanced_entry_value_dif").text(
                Math.abs(total_creditor - total_debtor).toFixed($("#__precision").val())
            );
            $("#unbalanced_entry").show();
        } else {
            $(".submit_journal_entry_form").prop('disabled', false);
            $("#unbalanced_entry").hide();
        }
    }
    $(document).ready(function() {
        calculateDebtorCreditorTotalMain();
        //Add new row on click on add row
        $('.add_new_journal_entry_row').on('click', function() {
            var journal_entry_row = $('input#journal_entry_row_count').val();
            $.ajax({
                method: 'GET',
                url: '/ac/journal_entry/journal_entry_row',
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
        });

        $(".creditor, .debtor").on('keyup', function() {
            var journal_entry_row = parseInt($('input#journal_entry_row_count').val());
            if (journal_entry_row == 0) {
                calculateDebtorCreditorTotalMain();
            }
        });


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
        });


        //Datetime picker
        $('#entry_date').datetimepicker({
            format: moment_date_format + ' ' + moment_time_format,
            ignoreReadonly: true,
        });

    });
</script>
@endsection
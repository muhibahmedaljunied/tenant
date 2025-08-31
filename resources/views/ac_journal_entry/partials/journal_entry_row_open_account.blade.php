<tr class="journal_entry_row">
    <td>
        <select name="journal_entries[{{ $row_count }}][account_number]" class="form-control select2 account_number" style="width: 100%;" required>
            @foreach($lastChildrenBranch as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="text" name="journal_entries[{{ $row_count }}][debtor]" class="form-control input_number debtor" required value="{{ @num_format(0.0) }}">
    </td>
    <td>
        <input type="text" name="journal_entries[{{ $row_count }}][creditor]" class="form-control input_number creditor" required value="{{ @num_format(0.0) }}">
    </td>
    <td>
        <input type="text" name="journal_entries[{{ $row_count }}][entry_desc]" class="form-control input_number entry_desc">
    </td>
    <td>
        <select name="journal_entries[{{ $row_count }}][cost_cen_field_id]" class="form-control select2 cost_cen_field_id" style="width: 100%;">
            <option value="">Select Addtional Cost Center</option>
            @foreach($ac_cost_cen_field_adds as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </td>
    <td class="text-center " style="padding-top: 10px;">
        <i class="fa fa-times  journal_entry_remove_row cursor-pointer btn btn-danger" aria-hidden="true"></i>
    </td>
</tr>

<script type="text/javascript">
    $(document).ready(function() {
        $(".creditor, .debtor").on('keyup', calculateDebtorCreditorTotal);
        __select2($('.select2'));
    });
</script>

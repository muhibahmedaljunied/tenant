@php
    if($type == 'allowance') {
        $name_col = 'allowance_names';
        $val_col = 'allowance_amounts';
        $val_class = 'allowance';
        $type_col = 'allowance_types';
        $percent_col = 'allowance_percent';
    } elseif($type == 'deduction') {
        $name_col = 'deduction_names';
        $val_col = 'deduction_amounts';
        $val_class = 'deduction';
        $type_col = 'deduction_types';
        $percent_col = 'deduction_percent';
    }

    $amount_type = !empty($amount_type) ? $amount_type : 'fixed';
    $percent = $amount_type == 'percent' && !empty($percent) ?  $percent : 0;
@endphp
<tr>
    <td>
        <input type="text" name="{{ $name_col }}[]" class="form-control input-sm" value="{{ !empty($name) ? $name : '' }}">
    </td>
    <td>
        <select name="{{ $type_col }}[]" class="form-control input-sm amount_type">
            <option value="fixed" {{ $amount_type == 'fixed' ? 'selected' : '' }}>{{ __('lang_v1.fixed') }}</option>
            <option value="percent" {{ $amount_type == 'percent' ? 'selected' : '' }}>{{ __('lang_v1.percentage') }}</option>
        </select>
        <div class="input-group percent_field @if($amount_type != 'percent') hide @endif">
            <input type="text" name="{{ $percent_col }}[]" class="form-control input-sm input_number percent" value="{{ @num_format($percent) }}">
            <span class="input-group-addon"><i class="fa fa-percent"></i></span>
        </div>
    </td>
    <td>
        @php
            $readonly = $amount_type == 'percent' ? 'readonly' : '';
        @endphp
        <input type="text" name="{{ $val_col }}[]" class="form-control input-sm value_field input_number {{ $val_class }}" value="{{ !empty($value) ? @num_format((float) $value) : 0 }}" {{ $readonly }}>
    </td>
    <td>
        @if(!empty($add_button))
            <button type="button" class="btn btn-primary btn-xs" @if($type == 'allowance') id="add_allowance" @elseif($type == 'deduction') id="add_deduction" @endif>
            <i class="fa fa-plus"></i>
        @else
            <button type="button" class="btn btn-danger btn-xs remove_tr"><i class="fa fa-minus"></i></button>
        @endif
    </button></td>
</tr>
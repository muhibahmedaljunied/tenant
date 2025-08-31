<label for="cost_center">{{ __('chart_of_accounts.cost_center') }}:</label>
@if (count($ac_cost_cen_list))
    <select name="cost_center" id="cost_center" class="form-control select2 cost_center" style="width: 100%;">
        <option value="">{{ __('chart_of_accounts.please_select_cost_center') }}</option>
        @foreach($ac_cost_cen_list as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
@else
    <select name="cost_center" id="cost_center" class="form-control select2 cost_center" style="width: 100%;">
        <option value="">{{ __('chart_of_accounts.please_select_cost_center') }}</option>
    </select>
@endif



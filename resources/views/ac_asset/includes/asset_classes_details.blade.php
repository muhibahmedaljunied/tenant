<div class="col-md-6">
    <div class="form-group">
        <label for="asset_account">{{ __('assets.asset_account') }}</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select name="asset_account" id="asset_account" class="form-control select2" disabled>
                <option value="">Select Acc</option>
                @foreach($lastChildrenBranch as $key => $value)
                    <option value="{{ $key }}" {{ $asset_class_record->asset_account == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="clearfix"></div>

<div class="col-md-6">
    <div class="form-group">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="is_depreciable" id="is_depreciable" value="1" {{ $asset_class_record->is_depreciable ? 'checked' : '' }} disabled>
                @lang('assets.is_depreciable')
            </label>
        </div>
    </div>
</div>
@if ($asset_class_record->is_depreciable)
    <div class="clearfix"></div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="asset_expense_account">{{ __('assets.asset_expense_account') }}</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fas fa-money-bill-alt"></i>
                </span>
                <select name="asset_expense_account" id="asset_expense_account" class="form-control select2" disabled>
                    <option value="">Select Acc</option>
                    @foreach($lastChildrenBranch as $key => $value)
                        <option value="{{ $key }}" {{ $asset_class_record->asset_expense_account == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="accumulated_consumption_account">{{ __('assets.accumulated_consumption_account') }}</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fas fa-money-bill-alt"></i>
                </span>
                <select name="accumulated_consumption_account" id="accumulated_consumption_account" class="form-control select2" disabled>
                    <option value="">Select Acc</option>
                    @foreach($lastChildrenBranch as $key => $value)
                        <option value="{{ $key }}" {{ $asset_class_record->accumulated_consumption_account == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    {{-- <div class="col-md-6">
        <div class="form-group">
            <label for="useful_life_type">{{ __('assets.useful_life_type') }}:</label>
            <select name="useful_life_type" id="useful_life_type" class="form-control select2" disabled>
                <option value="years" @if ($asset_class_record->useful_life == 'years') selected @endif>
                    @lang('assets.useful_life_years')</option>
                <option value="percent" @if ($asset_class_record->useful_life == 'percent') selected @endif>
                    @lang('assets.useful_life_percent')</option>

            </select>

        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label id="years_lable_div">{{ __('assets.years') }}</label>
            <label id="percent_lable_div">{{ __('assets.percent') }}</label>
            <input type="text" name="useful_life" class="form-control" value="{{ $asset_class_record->useful_life }}" disabled>
        </div>
    </div> --}}


    <div class="col-md-6">
        <div class="form-group">
            <label for="asset_value">{{ __('assets.asset_value') }}:*</label>
            <input type="text" name="asset_value" id="asset_value" class="form-control">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="scrap_value">{{ __('assets.scrap_value') }}:*</label>
            <input type="text" name="scrap_value" id="scrap_value" class="form-control">
        </div>
    </div>
@endif

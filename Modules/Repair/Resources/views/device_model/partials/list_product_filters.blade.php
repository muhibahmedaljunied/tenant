<div class="col-md-3">
    <div class="form-group">
        <label for="repair_model_id">@lang('repair::lang.device_model'):</label>
        <select name="repair_model_id" id="repair_model_id" class="form-control select2" placeholder="@lang('messages.all')">
            @foreach ($view_data['device_models'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>

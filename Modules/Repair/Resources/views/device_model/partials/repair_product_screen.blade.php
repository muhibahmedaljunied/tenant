<input type="hidden" name="has_module_data" value="true">

<div class="col-sm-4">
    <div class="form-group">
        <label for="repair_model_id">{{ __('repair::lang.device_model') }}:</label>
        <select name="repair_model_id" id="repair_model_id" class="form-control select2">
            <option value="">{{ __('messages.please_select') }}</option>
            @foreach ($view_data['device_models'] as $key => $value)
                <option value="{{ $key }}" {{ !empty($product->repair_model_id) && $product->repair_model_id == $key ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>

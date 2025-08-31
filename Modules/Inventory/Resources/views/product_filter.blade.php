<div class="row">
    <div class="col-md-12">
    @component('components.filters', ['title' => __('report.filters')])
       
        <div class="col-md-3">
            <div class="form-group">
                <label for="category_id">{{ __('product.category') . ':' }}</label>
                <select name="category_id" id="product_list_filter_category_id" class="form-control select2" style="width:100%">
                    <option value="">{{ __('lang_v1.all') }}</option>
                    @foreach($categories as $key => $value)
                        <option value="{{ $key }}" {{ old('category_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="brand_id">{{ __('product.brand') . ':' }}</label>
                <select name="brand_id" id="product_list_filter_brand_id" class="form-control select2" style="width:100%">
                    <option value="">{{ __('lang_v1.all') }}</option>
                    @foreach($brands as $key => $value)
                        <option value="{{ $key }}" {{ old('brand_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="product_list_filter_current_stock">{{ __('report.current_stock') . ':' }}</label>
                <select name="current_stock" id="product_list_filter_current_stock" class="form-control select2" style="width:100%">
                    <option value="">{{ __('lang_v1.all') }}</option>
                    <option value="zero" {{ old('current_stock') == 'zero' ? 'selected' : '' }}>Zero</option>
                    <option value="gtzero" {{ old('current_stock') == 'gtzero' ? 'selected' : '' }}>اكبر من الصفر</option>
                    <option value="lszero" {{ old('current_stock') == 'lszero' ? 'selected' : '' }}>اقل من الصفر</option>
                </select>
            </div>
        </div>

    @endcomponent
    </div>
</div>
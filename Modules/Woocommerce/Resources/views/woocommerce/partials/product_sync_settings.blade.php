<div class="pos-tab-content">
    <div class="row">
        <div class="col-xs-4">
            <div class="form-group">
                <label for="default_tax_class">
                    {{ __('woocommerce::lang.default_tax_class') }}:
                </label>
                @show_tooltip(__('woocommerce::lang.default_tax_class_help'))
                <input type="text" name="default_tax_class" value="{{ $default_settings['default_tax_class'] }}" class="form-control">
            </div>
        </div>

        <div class="col-xs-4">
            <div class="form-group">
                <label for="product_tax_type">
                    {{ __('woocommerce::lang.sync_product_price') }}:
                </label>
                <select name="product_tax_type" class="form-control">
                    <option value="inc" {{ $default_settings['product_tax_type'] == 'inc' ? 'selected' : '' }}>
                        {{ __('woocommerce::lang.inc_tax') }}
                    </option>
                    <option value="exc" {{ $default_settings['product_tax_type'] == 'exc' ? 'selected' : '' }}>
                        {{ __('woocommerce::lang.exc_tax') }}
                    </option>
                </select>
            </div>
        </div>

        <div class="col-xs-4">
            <div class="form-group">
                <label for="default_selling_price_group">
                    {{ __('woocommerce::lang.default_selling_price_group') }}:
                </label>
                <select name="default_selling_price_group" class="form-control select2" style="width: 100%;">
                    @foreach($price_groups as $key => $label)
                    <option value="{{ $key }}" {{ $default_settings['default_selling_price_group'] == $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="sync_description_as">{{ __('woocommerce::lang.sync_description_as') }}:</label>
                <select name="sync_description_as" class="form-control select2" style="width: 100%;">
                    <option value="short" {{ (!empty($default_settings['sync_description_as']) && $default_settings['sync_description_as'] == 'short') ? 'selected' : '' }}>
                        {{ __('woocommerce::lang.short_description') }}
                    </option>
                    <option value="long" {{ (empty($default_settings['sync_description_as']) || $default_settings['sync_description_as'] == 'long') ? 'selected' : '' }}>
                        {{ __('woocommerce::lang.long_description') }}
                    </option>
                    <option value="both" {{ (!empty($default_settings['sync_description_as']) && $default_settings['sync_description_as'] == 'both') ? 'selected' : '' }}>
                        {{ __('woocommerce::lang.both') }}
                    </option>
                </select>
            </div>
        </div>

        <div class="col-xs-12">
            <hr>
        </div>

        <div class="col-xs-12">
            <div class="form-group">
                <label>@lang('woocommerce::lang.product_fields_to_be_synced_for_create'):</label><br>

                <label class="checkbox-inline">
                    <input type="checkbox" name="product_fields_for_create[]" value="name" checked disabled class="input-icheck">
                    @lang('product.product_name'),
                </label>

                <label class="checkbox-inline">
                    <input type="checkbox" name="product_fields_for_create[]" value="price" checked disabled class="input-icheck">
                    @lang('woocommerce::lang.price'),
                </label>

                @foreach(['category', 'quantity', 'weight', 'image', 'description'] as $field)
                <label class="checkbox-inline">
                    <input type="checkbox"
                        name="product_fields_for_create[]"
                        value="{{ $field }}"
                        class="input-icheck"
                        {{ in_array($field, $default_settings['product_fields_for_create']) ? 'checked' : '' }}>
                    @lang($field === 'description' ? 'lang_v1.description' : ($field === 'image' ? 'woocommerce::lang.images' : ($field === 'weight' ? 'lang_v1.weight' : ($field === 'quantity' ? 'sale.qty' : 'product.' . $field))))
                </label>
                @endforeach
            </div>
        </div>

        <div class="col-xs-12">
            <br>
            <div class="form-group">
                <label>@lang('woocommerce::lang.product_fields_to_be_synced_for_update'):</label><br>

                @foreach(['name', 'price', 'category', 'quantity', 'weight', 'image', 'description'] as $field)
                <label class="checkbox-inline">
                    <input type="checkbox"
                        name="product_fields_for_update[]"
                        value="{{ $field }}"
                        class="input-icheck"
                        {{ in_array($field, $default_settings['product_fields_for_update']) ? 'checked' : '' }}>
                    @lang($field === 'description' ? 'lang_v1.description' : ($field === 'image' ? 'woocommerce::lang.images' : ($field === 'weight' ? 'lang_v1.weight' : ($field === 'quantity' ? 'sale.qty' : 'product.' . $field))))
                </label>
                @endforeach
            </div>
        </div>

    </div>
</div>
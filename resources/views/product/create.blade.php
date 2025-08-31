@extends('layouts.app')
@section('title', __('product.add_new_product'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('product.add_new_product')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @php
            $form_class = empty($duplicate_product) ? 'create' : '';
        @endphp
        <form action="{{ action('ProductController@store') }}" method="post" id="product_add_form"
            class="product_form {{ $form_class }}" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">



            @component('components.widget', ['class' => 'box-primary'])
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name">{{ __('product.product_name') }}:*</label>
                            <input type="text" name="name" id="name" class="form-control" required
                                placeholder="{{ __('product.product_name') }}"
                                value="{{ !empty($duplicate_product->name) ? $duplicate_product->name : '' }}">
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="sku">{{ __('product.sku') }}:</label>
                            <input type="text" name="sku" id="sku" class="form-control unique_sku"
                                placeholder="{{ __('product.sku') }}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="sku2">{{ __('product.sku2') }}:</label>
                            <input type="text" name="sku2" id="sku2" class="form-control"
                                placeholder="{{ __('product.sku') }}">
                        </div>
                    </div>



                    <div class="clearfix"></div>




                    <div class="clearfix"></div>

                    <div class="col-sm-4 @if (!session('business.enable_brand')) hide @endif">

                        <div class="form-group">
                            <label for="brand_id">{{ __('product.brand') }}:</label>
                            <div class="input-group">
                                <select name="brand_id" id="brand_id" class="form-control select2">
                                    <option value="">{{ __('messages.please_select') }}</option>
                                    @foreach ($brands as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ !empty($duplicate_product->brand_id) && $duplicate_product->brand_id == $key ? 'selected' : '' }}>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default bg-white btn-flat btn-modal"
                                        {{ !auth()->user()->can('brand.create') ? 'disabled' : '' }}
                                        data-href="{{ action('BrandController@create', ['quick_add' => true]) }}"
                                        title="{{ __('brand.add_brand') }}" data-container=".view_modal">
                                        <i class="fa fa-plus-circle text-primary fa-lg"></i>
                                    </button>
                                </span>
                            </div>
                        </div>

                    </div>


                    <div class="col-sm-4 {{ !session('business.enable_category') ? 'hide' : '' }}">
                        <div class="form-group">
                            <label for="category_id">{{ __('product.category') }}:</label>
                            <select name="category_id" id="category_id" class="form-control select2">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach ($categories as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ !empty($duplicate_product->category_id) && $duplicate_product->category_id == $key ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div
                        class="col-sm-4 {{ !(session('business.enable_category') && session('business.enable_sub_category')) ? 'hide' : '' }}">
                        <div class="form-group">
                            <label for="sub_category_id">{{ __('product.sub_category') }}:</label>
                            <select name="sub_category_id" id="sub_category_id" class="form-control select2">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach ($sub_categories as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ !empty($duplicate_product->sub_category_id) && $duplicate_product->sub_category_id == $key ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    @php
                        $default_location = null;
                        if (count($business_locations) == 1) {
                            $default_location = array_key_first($business_locations->toArray());
                        }
                    @endphp
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="product_locations">{{ __('business.business_locations') }}:</label>
                            <select name="product_locations[]" id="product_locations" class="form-control select2" multiple>
                                @foreach ($business_locations as $key => $value)
                                    <option value="{{ $key }}" {{ $default_location == $key ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="store_id">{{ __('store.store') }}:</label>
                            <select name="store_id" id="store_id" class="form-control select2">

                         


                            </select>
                        </div>
                    </div>



                    <div class="col-sm-4">
                        <div class="form-group">
                            <br>
                            <label>
                                <input type="checkbox" name="enable_stock" id="enable_stock" class="input-icheck" value="1"
                                    {{ !empty($duplicate_product) && $duplicate_product->enable_stock ? 'checked' : '' }}>
                                <strong>{{ __('product.manage_stock') }}</strong>
                            </label>
                            <p class="help-block"><i>{{ __('product.enable_stock_help') }}</i></p>
                        </div>
                    </div>

                    <div class="col-sm-4 {{ !empty($duplicate_product) && $duplicate_product->enable_stock == 0 ? 'hide' : '' }}"
                        id="alert_quantity_div">
                        <div class="form-group">
                            <label for="alert_quantity">{{ __('product.alert_quantity') }}:</label>
                            <input type="number" name="alert_quantity" id="alert_quantity" class="form-control"
                                placeholder="{{ __('product.alert_quantity') }}" min="0"
                                value="{{ !empty($duplicate_product->alert_quantity) ? $duplicate_product->alert_quantity : '' }}">
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="unit_id">{{ __('product.unit') }}:*</label>
                            <div class="input-group">
                                <select name="unit_id" id="unit_id" class="form-control select2 unique_unit" required>
                                    @foreach ($units as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ !empty($duplicate_product->unit_id) && $duplicate_product->unit_id == $key ? 'selected' : '' }}>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default bg-white btn-flat btn-modal"
                                        {{ !auth()->user()->can('unit.create') ? 'disabled' : '' }}
                                        data-href="{{ action('UnitController@create', ['quick_add' => true]) }}"
                                        title="{{ __('unit.add_unit') }}" data-container=".view_modal">
                                        <i class="fa fa-plus-circle text-primary fa-lg"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <label>
                            <input type="checkbox" name="enable_multi_unit" id="enable_multi_unit" class="input-icheck"
                                value="0">
                            <strong>{{ __('product.enable_multi_unit') }}</strong>
                        </label>
                    </div>

                    <div class="col-sm-4 {{ !session('business.enable_sub_units') ? 'hide' : '' }}">
                        <div class="form-group">
                            <label for="sub_unit_ids">{{ __('lang_v1.related_sub_units') }}:</label>
                            <select name="sub_unit_ids[]" id="sub_unit_ids" class="form-control select2" multiple>
                                @foreach ($sub_unit_ids ?? [] as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ !empty($duplicate_product->sub_unit_ids) && in_array($key, $duplicate_product->sub_unit_ids) ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if (!empty($common_settings['enable_product_warranty']))
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="warranty_id">{{ __('lang_v1.warranty') }}:</label>
                                <select name="warranty_id" id="warranty_id" class="form-control select2">
                                    <option value="">{{ __('messages.please_select') }}</option>
                                    @foreach ($warranties as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <!-- include module fields -->
                    @if (!empty($pos_module_data))
                        @foreach ($pos_module_data as $key => $value)
                            @if (!empty($value['view_path']))
                                @includeIf($value['view_path'], ['view_data' => $value['view_data']])
                            @endif
                        @endforeach
                    @endif
                    <div class="clearfix"></div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="upload_image">{{ __('lang_v1.product_image') }}:</label>
                            <input type="file" name="image" id="upload_image" accept="image/*">
                            <small>
                                <p class="help-block">
                                    {{ __('purchase.max_file_size', ['size' => config('constants.document_size_limit') / 1000000]) }}
                                    <br>
                                    {{ __('lang_v1.aspect_ratio_should_be_1_1') }}
                                </p>
                            </small>
                        </div>
                    </div>

                </div>
            @endcomponent

            @component('components.widget', ['class' => 'box-primary'])
                <div class="row">
                    @if (session('business.enable_product_expiry'))
                        @if (session('business.expiry_type') == 'add_expiry')
                            @php
                                $expiry_period = 12;
                                $hide = true;
                            @endphp
                        @else
                            @php
                                $expiry_period = null;
                                $hide = false;
                            @endphp
                        @endif
                        <div class="col-sm-4 @if ($hide) hide @endif">
                            <div class="form-group">
                                <div class="multi-input">
                                    <label for="expiry_period">{{ __('product.expires_in') }}:</label><br>
                                    <input type="text" name="expiry_period" id="expiry_period"
                                        class="form-control pull-left input_number"
                                        placeholder="{{ __('product.expiry_period') }}" style="width:60%;"
                                        value="{{ !empty($duplicate_product->expiry_period) ? num_format($duplicate_product->expiry_period) : $expiry_period }}">
                                    <select name="expiry_period_type" id="expiry_period_type"
                                        class="form-control select2 pull-left" style="width:40%;">
                                        <option value="months"
                                            {{ !empty($duplicate_product->expiry_period_type) && $duplicate_product->expiry_period_type == 'months' ? 'selected' : '' }}>
                                            {{ __('product.months') }}</option>
                                        <option value="days"
                                            {{ !empty($duplicate_product->expiry_period_type) && $duplicate_product->expiry_period_type == 'days' ? 'selected' : '' }}>
                                            {{ __('product.days') }}</option>
                                        <option value=""
                                            {{ !empty($duplicate_product->expiry_period_type) && $duplicate_product->expiry_period_type == '' ? 'selected' : '' }}>
                                            {{ __('product.not_applicable') }}</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    @endif

                    <div class="col-sm-4">
                        <div class="form-group">
                            <br>
                            <label>
                                <input type="checkbox" name="enable_sr_no" class="input-icheck" value="1"
                                    {{ !empty($duplicate_product) && $duplicate_product->enable_sr_no ? 'checked' : '' }}>
                                <strong>{{ __('lang_v1.enable_imei_or_sr_no') }}</strong>
                            </label>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <br>
                            <label>
                                <input type="checkbox" name="not_for_selling" class="input-icheck" value="1"
                                    {{ !empty($duplicate_product) && $duplicate_product->not_for_selling ? 'checked' : '' }}>
                                <strong>{{ __('lang_v1.not_for_selling') }}</strong>
                            </label>
                        </div>
                    </div>


                    <div class="clearfix"></div>

                    <!-- Rack, Row & position number -->
                    @if (session('business.enable_racks') || session('business.enable_row') || session('business.enable_position'))
                        <div class="col-md-12">
                            <h4>@lang('lang_v1.rack_details'):
                                @show_tooltip(__('lang_v1.tooltip_rack_details'))
                            </h4>
                        </div>
                        @foreach ($business_locations as $id => $location)
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="rack_{{ $id }}">{{ $location }}:</label>

                                    @if (session('business.enable_racks'))
                                        <input type="text" name="product_racks[{{ $id }}][rack]"
                                            id="rack_{{ $id }}" class="form-control"
                                            placeholder="{{ __('lang_v1.rack') }}"
                                            value="{{ !empty($rack_details[$id]['rack']) ? $rack_details[$id]['rack'] : '' }}">
                                    @endif

                                    @if (session('business.enable_row'))
                                        <input type="text" name="product_racks[{{ $id }}][row]"
                                            class="form-control" placeholder="{{ __('lang_v1.row') }}"
                                            value="{{ !empty($rack_details[$id]['row']) ? $rack_details[$id]['row'] : '' }}">
                                    @endif

                                    @if (session('business.enable_position'))
                                        <input type="text" name="product_racks[{{ $id }}][position]"
                                            class="form-control" placeholder="{{ __('lang_v1.position') }}"
                                            value="{{ !empty($rack_details[$id]['position']) ? $rack_details[$id]['position'] : '' }}">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="weight">{{ __('lang_v1.weight') }}:</label>
                            <input type="text" name="weight" id="weight" class="form-control"
                                placeholder="{{ __('lang_v1.weight') }}"
                                value="{{ !empty($duplicate_product->weight) ? $duplicate_product->weight : '' }}">
                        </div>
                    </div>


                    @php
                        $custom_labels = json_decode(session('business.custom_labels'), true);
                        $product_custom_field1 = !empty($custom_labels['product']['custom_field_1'])
                            ? $custom_labels['product']['custom_field_1']
                            : __('lang_v1.product_custom_field1');
                        $product_custom_field2 = !empty($custom_labels['product']['custom_field_2'])
                            ? $custom_labels['product']['custom_field_2']
                            : __('lang_v1.product_custom_field2');
                        $product_custom_field3 = !empty($custom_labels['product']['custom_field_3'])
                            ? $custom_labels['product']['custom_field_3']
                            : __('lang_v1.product_custom_field3');
                        $product_custom_field4 = !empty($custom_labels['product']['custom_field_4'])
                            ? $custom_labels['product']['custom_field_4']
                            : __('lang_v1.product_custom_field4');
                    @endphp
                    <!--custom fields-->
                    <div class="clearfix"></div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="product_custom_field1">{{ $product_custom_field1 }}:</label>
                            <input type="text" name="product_custom_field1" id="product_custom_field1"
                                class="form-control" placeholder="{{ $product_custom_field1 }}"
                                value="{{ !empty($duplicate_product->product_custom_field1) ? $duplicate_product->product_custom_field1 : '' }}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="product_custom_field2">{{ $product_custom_field2 }}:</label>
                            <input type="text" name="product_custom_field2" id="product_custom_field2"
                                class="form-control" placeholder="{{ $product_custom_field2 }}"
                                value="{{ !empty($duplicate_product->product_custom_field2) ? $duplicate_product->product_custom_field2 : '' }}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="product_custom_field3">{{ $product_custom_field3 }}:</label>
                            <input type="text" name="product_custom_field3" id="product_custom_field3"
                                class="form-control" placeholder="{{ $product_custom_field3 }}"
                                value="{{ !empty($duplicate_product->product_custom_field3) ? $duplicate_product->product_custom_field3 : '' }}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="product_custom_field4">{{ $product_custom_field4 }}:</label>
                            <input type="text" name="product_custom_field4" id="product_custom_field4"
                                class="form-control" placeholder="{{ $product_custom_field4 }}"
                                value="{{ !empty($duplicate_product->product_custom_field4) ? $duplicate_product->product_custom_field4 : '' }}">
                        </div>
                    </div>

                    <!--custom fields-->
                    <div class="clearfix"></div>
                    @include('layouts.partials.module_form_part')
                </div>
            @endcomponent


            @component('components.widget', ['class' => 'box-primary'])
                <div class="row">
                    <div class="col-sm-4 {{ !session('business.enable_price_tax') ? 'hide' : '' }}">
                        <div class="form-group">
                            <label for="tax">{{ __('product.applicable_tax') }}:</label>
                            <select name="tax" id="tax" class="form-control select2">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach ($taxes as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ !empty($duplicate_product->tax) && $duplicate_product->tax == $key ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4 hide">
                        <div class="form-group">
                            <label for="tax_type">{{ __('product.selling_price_tax_type') }}:*</label>
                            <select name="tax_type" id="tax_type" class="form-control select2" required>
                                <option value="inclusive"
                                    {{ !empty($duplicate_product->tax_type) && $duplicate_product->tax_type == 'inclusive' ? 'selected' : '' }}>
                                    {{ __('product.inclusive') }}</option>
                                <option value="exclusive"
                                    {{ !empty($duplicate_product->tax_type) && $duplicate_product->tax_type == 'exclusive' ? 'selected' : '' }}>
                                    {{ __('product.exclusive') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="type">{{ __('product.product_type') }}:*</label>
                            <select name="type" id="type" class="form-control select2" required
                                data-action="{{ !empty($duplicate_product) ? 'duplicate' : 'add' }}"
                                data-product_id="{{ !empty($duplicate_product) ? $duplicate_product->id : '0' }}">
                                @foreach ($product_types as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ !empty($duplicate_product->type) && $duplicate_product->type == $key ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-12" id="product_form_part">
                        @include('product.partials.single_product_form_part', [
                            'profit_percent' => $default_profit_percent,
                        ])
                    </div>


                    <input type="hidden" id="variation_counter" value="1">
                    <input type="hidden" id="default_profit_percent" value="{{ $default_profit_percent }}">

                </div>
            @endcomponent
            @component('components.widget', ['class' => 'box-primary hidden multi_unit_box'])
                <div class="row">
                    <div class="col-sm-12">
                        <table
                            class="table table-condensed table-bordered table-th-green text-center table-striped add_another_units_table">
                            <thead>
                                <tr>
                                    <th>{{ __('product.unit') }}</th>
                                    <th>{{ __('lang_v1.quantity') }}</th>
                                    <th>{{ __('product.sku') }}</th>
                                    <th>{{ __('product.default_purchase_price') }}</th>
                                    <th>{{ __('product.profit_percent') }}</th>
                                    <th>{{ __('product.default_selling_price') }}</th>
                                    <th>
                                        <button type="button" class="btn btn-primary btn-xs add_unit_row" data-sub-key="1"
                                            data-row-html="">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamic rows will be added here -->
                            </tbody>
                        </table>
                    </div>

                </div>
            @endcomponent
            <div class="row">
                <div class="col-sm-12">
                    <input type="hidden" name="submit_type" id="submit_type">
                    <div class="text-center">
                        <div class="btn-group">
                            @if ($selling_price_group_count)
                                <button type="submit" value="submit_n_add_selling_prices"
                                    class="btn btn-warning submit_product_form">@lang('lang_v1.save_n_add_selling_price_group_prices')</button>
                            @endif

                            @can('product.opening_stock')
                                <button id="opening_stock_button" @if (!empty($duplicate_product) && $duplicate_product->enable_stock == 0) disabled @endif
                                    type="submit" value="submit_n_add_opening_stock"
                                    class="btn bg-purple submit_product_form">@lang('lang_v1.save_n_add_opening_stock')</button>
                            @endcan

                            <button type="submit" value="save_n_add_another"
                                class="btn bg-maroon submit_product_form">@lang('lang_v1.save_n_add_another')</button>

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
    @php $asset_v = env('APP_VERSION'); @endphp
    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {





            $('#product_locations').on('change', function() {

                console.log('hebo_change');
                var locationIds = $(this).val();
                if (!locationIds) {
                    $('#store_id').html(
                        '<option value="">{{ __('messages.please_select') }}</option>');
                    $('#store_id').trigger('change');
                    return;
                }
                $.ajax({
                    url: '{{ route('getStoresByLocations') }}',
                    type: 'POST',
                    data: {
                        location_ids: locationIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        var options =
                            '<option value="">{{ __('messages.please_select') }}</option>';
                        $.each(data, function(key, value) {
                            options += '<option value="' + key + '">' +
                                value + '</option>';
                        });
                        $('#store_id').html(options).trigger('change');
                    }
                });
            });







            __page_leave_confirmation('#product_add_form');
            onScan.attachTo(document, {
                suffixKeyCodes: [13], // enter-key expected at the end of a scan
                reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
                onScan: function(sCode, iQty) {
                    $('input#sku,.bar_code').val(sCode);
                },
                onScanError: function(oDebug) {
                    console.log(oDebug);
                },
                minLength: 2,
                ignoreIfFocusOn: ['input', '.form-control']

            });

            $(document).on('change', 'select#type', function(e) {
                console.log(e);
            })
            var unitIncreament = 0;

            function getTemplate(number) {
                return `<tr>
    <td>
        <br>
        <div class="input-group width-100">
            <select name="another_unit[${number}][unit_id]" class="form-control select2 width-100 unique_unit unit_id" required>
                @foreach ($units as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </td>
    <td>
        <br>
        <input type="text" class="form-control unit_qty input-sm input_number" name="another_unit[${number}][qty]" required>
    </td>
    <td>
        <br>
        <input type="text" class="form-control input-sm unit_barcode bar_code unique_sku" name="another_unit[${number}][sku]" required>
    </td>
    <td>
        <div class="col-sm-6">
            <label for="single_dpp">{{ __('product.exc_of_tax') }}:*</label>
            <input type="text" name="another_unit[${number}][single_dpp]" class="form-control input-sm unit_single_dpp input_number" placeholder="{{ __('product.exc_of_tax') }}" required>
        </div>
        <div class="col-sm-6">
            <br>
            <label for="single_dpp_inc_tax">{{ __('product.inc_of_tax') }}:*</label>
            <input type="text" name="another_unit[${number}][single_dpp_inc_tax]" class="form-control input-sm unit_single_dp_inc_tax input_number" placeholder="{{ __('product.inc_of_tax') }}" required>
        </div>
    </td>
    <td>
        <br>
        <input type="text" name="another_unit[${number}][profit_percent]" class="form-control input-sm input_number profit_unit_percent" id="profit_unit_percent"  required>
    </td>
    <td>
        <div class="col-sm-6">
            <label for="single_dsp">{{ __('product.exc_of_tax') }}:*</label>
            <input type="text" name="another_unit[${number}][single_dsp]" class="form-control input-sm unit_single_dsp input_number" placeholder="{{ __('product.exc_of_tax') }}" required>
        </div>
        <div class="col-sm-6">
            <br>
            <label for="single_dsp_inc_tax">{{ __('product.inc_of_tax') }}:*</label>
            <input type="text" name="another_unit[${number}][single_dsp_inc_tax]" class="form-control input-sm unit_single_dsp_inc_tax input_number" placeholder="{{ __('product.inc_of_tax') }}" required>
        </div>
    </td>
    <td>
        <button type="button" class="btn btn-danger btn-xs remove_unit_value_row">-</button>
        <input type="hidden" class="unit_row_index" value="${$('.add_another_units_table tbody tr').length +1 }">
    </td>
</tr>
`

            }
            $('.add_unit_row').on('click', function() {
                let unitRow = getTemplate(++unitIncreament);
                let $selector = $(unitRow).appendTo(`.multi_unit_box .add_another_units_table tbody`);
                $selector.find('.select2').select2({
                    dropdownParent: $selector.find('td:first-child')
                });
                $.validator.addClassRules('unit_barcode', {
                    remote: {
                        url: '/products/check_product_sku',
                        type: 'post',
                        data: {
                            sku: function() {
                                return $selector.find('.unit_barcode').val();
                            },
                            product_id: function() {
                                if ($('#product_id').length) {
                                    return $('#product_id').val();
                                } else {
                                    return '';
                                }
                            },
                        },
                    },
                });

            });
        });
    </script>
    <script src="{{ asset('js/multiunits.js?v=' . $asset_v) }}"></script>

@endsection

@extends('layouts.app')
@section('title', __('product.edit_product'))

@section('content')
    <script src="http://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('product.edit_product')</h1>
        <!-- <ol class="breadcrumb">
                                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                                <li class="active">Here</li>
                            </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        <form action="{{ action('ProductController@update', [$product->id]) }}" method="POST" id="product_add_form"
            class="product_form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" id="product_id" value="{{ $product->id }}">

            @component('components.widget', ['class' => 'box-primary'])
                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name">{{ __('product.product_name') }}:*</label>
                            <input type="text" name="name" id="name" class="form-control" required
                                placeholder="{{ __('product.product_name') }}" value="{{ old('name', $product->name) }}">
                        </div>
                    </div>

                    <div class="col-sm-3 @if (!(session('business.enable_category') && session('business.enable_sub_category'))) hide @endif">
                        <div class="form-group">
                            <label for="sku">{{ __('product.sku') }}:*</label>
                            @show_tooltip(__('tooltip.sku'))
                            <input type="text" name="sku" id="sku" class="form-control" required
                                placeholder="{{ __('product.sku') }}" value="{{ old('sku', $product->sku) }}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="sku2">{{ __('product.sku2') }}:</label>
                            <input type="text" name="sku2" id="sku2" class="form-control"
                                placeholder="{{ __('product.sku2') }}" value="{{ old('sku2', $product->sku2) }}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="barcode_type">{{ __('product.barcode_type') }}:*</label>
                            <select name="barcode_type" id="barcode_type" class="form-control select2" required>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach ($barcode_types as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('barcode_type', $product->barcode_type) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="clearfix"></div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="unit_id">{{ __('product.unit') }}:*</label>
                            <div class="input-group">
                                <select name="unit_id" id="unit_id" class="form-control select2" required>
                                    <option value="">{{ __('messages.please_select') }}</option>
                                    @foreach ($units as $id => $name)
                                        <option value="{{ $id }}"
                                            {{ old('unit_id', $product->unit_id) == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="input-group-btn">
                                    <button type="button" @if (!auth()->user()->can('unit.create')) disabled @endif
                                        class="btn btn-default bg-white btn-flat quick_add_unit btn-modal"
                                        data-href="{{ action('UnitController@create', ['quick_add' => true]) }}"
                                        title="@lang('unit.add_unit')" data-container=".view_modal">
                                        <i class="fa fa-plus-circle text-primary fa-lg"></i>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <label for="enable_multi_unit">
                            <input type="checkbox" name="enable_multi_unit" id="enable_multi_unit" class="input-icheck"
                                value="0" {{ old('enable_multi_unit', $product->multi_unit_active) ? 'checked' : '' }}>
                            <strong>@lang('product.enable_multi_unit')</strong>
                        </label>
                    </div>

                    <div class="col-sm-4 @if (!session('business.enable_sub_units')) hide @endif">
                        <div class="form-group">
                            <label for="sub_unit_ids">{{ __('lang_v1.related_sub_units') }}:</label>
                            @show_tooltip(__('lang_v1.sub_units_tooltip'))

                            <select name="sub_unit_ids[]" id="sub_unit_ids" class="form-control select2" multiple>
                                @foreach ($sub_units as $sub_unit_id => $sub_unit_value)
                                    <option value="{{ $sub_unit_id }}" @if (is_array($product->sub_unit_ids) && in_array($sub_unit_id, $product->sub_unit_ids)) selected @endif>
                                        {{ $sub_unit_value['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4 @if (!session('business.enable_brand')) hide @endif">
                        <div class="form-group">
                            <label for="brand_id">{{ __('product.brand') }}:</label>
                            <div class="input-group">
                                <select name="brand_id" id="brand_id" class="form-control select2">
                                    <option value="">{{ __('messages.please_select') }}</option>
                                    @foreach ($brands as $id => $name)
                                        <option value="{{ $id }}"
                                            {{ old('brand_id', $product->brand_id) == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="input-group-btn">
                                    <button type="button" @if (!auth()->user()->can('brand.create')) disabled @endif
                                        class="btn btn-default bg-white btn-flat btn-modal"
                                        data-href="{{ action('BrandController@create', ['quick_add' => true]) }}"
                                        title="@lang('brand.add_brand')" data-container=".view_modal">
                                        <i class="fa fa-plus-circle text-primary fa-lg"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>




                    <div class="clearfix"></div>
                    <div class="col-sm-4 @if (!session('business.enable_category')) hide @endif">
                        <div class="form-group">
                            <label for="category_id">{{ __('product.category') }}:</label>
                            <select name="category_id" id="category_id" class="form-control select2">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ old('category_id', $product->category_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4 @if (!(session('business.enable_category') && session('business.enable_sub_category'))) hide @endif">
                        <div class="form-group">
                            <label for="sub_category_id">{{ __('product.sub_category') }}:</label>
                            <select name="sub_category_id" id="sub_category_id" class="form-control select2">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach ($sub_categories as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ old('sub_category_id', $product->sub_category_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="product_locations">{{ __('business.business_locations') }}:</label>
                            @show_tooltip(__('lang_v1.product_location_help'))
                            <select name="product_locations[]" id="product_locations" class="form-control select2" multiple>
                                @foreach ($business_locations as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ $product->product_locations->pluck('id')->contains($id) ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="clearfix"></div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <br>
                            <label for="enable_stock">
                                <input type="checkbox" name="enable_stock" id="enable_stock" class="input-icheck"
                                    value="1" {{ old('enable_stock', $product->enable_stock) ? 'checked' : '' }}>
                                <strong>@lang('product.manage_stock')</strong>
                            </label>
                            @show_tooltip(__('tooltip.enable_stock'))
                            <p class="help-block"><i>@lang('product.enable_stock_help')</i></p>
                        </div>
                    </div>

                    <div class="col-sm-4" id="alert_quantity_div"
                        @if (!$product->enable_stock) style="display:none" @endif>
                        <div class="form-group">
                            <label for="alert_quantity">{{ __('product.alert_quantity') }}:</label>
                            @show_tooltip(__('tooltip.alert_quantity'))
                            <input type="number" name="alert_quantity" id="alert_quantity" class="form-control"
                                placeholder="{{ __('product.alert_quantity') }}" min="0"
                                value="{{ old('alert_quantity', $product->alert_quantity) }}">
                        </div>
                    </div>

                    @if (!empty($common_settings['enable_product_warranty']))
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="warranty_id">{{ __('lang_v1.warranty') }}:</label>
                                <select name="warranty_id" id="warranty_id" class="form-control select2">
                                    <option value="">{{ __('messages.please_select') }}</option>
                                    @foreach ($warranties as $id => $label)
                                        <option value="{{ $id }}"
                                            {{ old('warranty_id', $product->warranty_id) == $id ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
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

                    <div class="col-sm-8">
                        <div class="form-group">
                            <label for="product_description">{{ __('lang_v1.product_description') }}:</label>
                            <textarea name="product_description" id="product_description" class="form-control">{{ old('product_description', $product->product_description) }}</textarea>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="upload_image">{{ __('lang_v1.product_image') }}:</label>
                            <input type="file" name="image" id="upload_image" accept="image/*" class="form-control">

                            <small>
                                <p class="help-block">
                                    @lang('purchase.max_file_size', ['size' => config('constants.document_size_limit') / 1000000]).
                                    @lang('lang_v1.aspect_ratio_should_be_1_1')
                                    @if (!empty($product->image))
                                        <br> @lang('lang_v1.previous_image_will_be_replaced')
                                    @endif
                                </p>
                            </small>

                            @if (!empty($product->image))
                                <img width="100" src="/uploads/img/{{ $product->image }}">
                            @endif
                        </div>
                    </div>

                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="product_brochure">{{ __('lang_v1.product_brochure') }}:</label>
                        <input type="file" name="product_brochure" id="product_brochure" class="form-control"
                            accept="{{ implode(',', array_keys(config('constants.document_upload_mimes_types'))) }}">

                        <small>
                            <p class="help-block">
                                @lang('lang_v1.previous_file_will_be_replaced')<br>
                                @lang('purchase.max_file_size', ['size' => config('constants.document_size_limit') / 1000000])
                                @includeIf('components.document_help_text')
                            </p>
                        </small>
                    </div>
                </div>

            @endcomponent
            @php
                $multiUnitClassName = $product->multi_unit_active ? '' : 'hidden';
            @endphp
            @component('components.widget', ['class' => "box-primary $multiUnitClassName multi_unit_box"])
                <div class="row">
                    <div class="col-sm-12">
                        <table
                            class="table table-condensed table-bordered table-th-green text-center table-striped add_another_units_table">
                            <thead>
                                <tr>
                                    <th>@lang('product.unit')</th>
                                    <th>@lang('lang_v1.quantity')</th>
                                    <th>@lang('product.default_purchase_price')</th>

                                    <th>@lang('product.sku')</th>
                                    <th>@lang('product.default_selling_price')</th>

                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @forelse ($product->unitPrices as $productUnit)
                      <tr>
                        <td>
                          <input type="hidden" name="another_unit[{{$loop->iteration}}][product_unit_var_id]" value="{{ $productUnit->id }}">
                            <br>
                          <div class="input-group width-100">
                            {!! Form::select(
                                'another_unit[{{$loop->iteration}}][unit_id]',
                                $units,
                                $productUnit->unit_id,
                                ['class' => 'form-control select2 width-100 unit_id', 'required'],
                            ) !!}
                        </div>
                        </td>
                        <td>
                            <br>

                            <input type="text" class="form-control input-sm input_number" name="another_unit[{{$loop->iteration}}][qty]" required value="{{$productUnit->equal_qty}}" >
                        </td>
                        <td>
                            <div class="col-sm-6">
                              {!! Form::label('single_dpp', trans('product.exc_of_tax') . ':*') !!}
                
                              {!! Form::text('another_unit[{{$loop->iteration}}][single_dpp]', $productUnit->purchase_price, ['class' => 'form-control input-sm dpp input_number', 'placeholder' => __('product.exc_of_tax'), 'required']); !!}
                            </div>
                
                            <div class="col-sm-6">
                            <br>

                              {!! Form::label('single_dpp_inc_tax', trans('product.inc_of_tax') . ':*') !!}
                            
                              {!! Form::text('another_unit[{{$loop->iteration}}][single_dpp_inc_tax]', $productUnit->purchase_price_inc_tax, ['class' => 'form-control input-sm dpp_inc_tax input_number', 'placeholder' => __('product.inc_of_tax'), 'required']); !!}
                            </div>
                          </td>
                    
                    
                        <td>
                            <br>

                            <input 
                                class="form-control input-sm  bar_code" 
                                required
                                name="another_unit[{{$loop->iteration}}][sku]" 
                                type="text" 
                                value="{{ $productUnit->bar_code }}"
                                >
                        </td>
                        <td>
                            <div class="col-sm-6">
                                {!! Form::label('single_dpp', trans('product.exc_of_tax') . ':*') !!}
                                {!! Form::text('another_unit[{{$loop->iteration}}][single_dsp]', $productUnit->default_sell_price, ['class' => 'form-control input-sm  input_number', 'placeholder' => __('product.exc_of_tax'), 'id' => 'another_unit[{{$loop->iteration}}][single_dsp]', 'required']); !!}
                            </div>
                            <div class="col-sm-6">
                            <br>

                            {!! Form::label('single_dpp_inc_tax', trans('product.inc_of_tax') . ':*') !!}
                            {!! Form::text('another_unit[{{$loop->iteration}}][single_dsp_inc_tax]', $productUnit->sell_price_inc_tax, ['class' => 'form-control input-sm  input_number', 'placeholder' => __('product.inc_of_tax'), 'id' => 'another_unit[{{$loop->iteration}}][single_dsp_inc_tax]', 'required']); !!}
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-xs add_unit_row" data-sub-key="1"
                                data-row-html=""><i class="fa fa-plus"></i></button>
                        </td>
                      </tr>  
                    @empty
                      <tr>
                          <td>
                              <br>
                            <div class="input-group width-100">
                              {!! Form::select(
                                  'another_unit[0][unit_id]',
                                  $units,
                                  '',
                                  ['class' => 'form-control select2 width-100 unit_id', 'required'],
                              ) !!}
                          </div>
                          </td>
                          <td>
                              <br>

                              <input type="text" class="form-control input-sm input_number" name="another_unit[0][qty]" required value="0" >
                          </td>
                          <td>
                              <div class="col-sm-6">
                                {!! Form::label('single_dpp', trans('product.exc_of_tax') . ':*') !!}
                  
                                {!! Form::text('another_unit[0][single_dpp]', 0, ['class' => 'form-control input-sm dpp input_number', 'placeholder' => __('product.exc_of_tax'), 'required']); !!}
                              </div>
                  
                              <div class="col-sm-6">
                              <br>

                                {!! Form::label('single_dpp_inc_tax', trans('product.inc_of_tax') . ':*') !!}
                              
                                {!! Form::text('another_unit[0][single_dpp_inc_tax]', 0, ['class' => 'form-control input-sm dpp_inc_tax input_number', 'placeholder' => __('product.inc_of_tax'), 'required']); !!}
                              </div>
                            </td>
                      
                      
                          <td>
                              <br>

                              <input 
                                  class="form-control input-sm  bar_code" 
                                  required
                                  name="another_unit[0][sku]" 
                                  type="text" 
                                  >
                          </td>
                          <td>
                              <div class="col-sm-6">
                                  {!! Form::label('single_dpp', trans('product.exc_of_tax') . ':*') !!}
                                  {!! Form::text('another_unit[0][single_dsp]', 0, ['class' => 'form-control input-sm  input_number', 'placeholder' => __('product.exc_of_tax'), 'id' => 'another_unit[0][single_dsp]', 'required']); !!}
                              </div>
                              <div class="col-sm-6">
                              <br>

                              {!! Form::label('single_dpp_inc_tax', trans('product.inc_of_tax') . ':*') !!}
                              {!! Form::text('another_unit[0][single_dsp_inc_tax]', 0, ['class' => 'form-control input-sm  input_number', 'placeholder' => __('product.inc_of_tax'), 'id' => 'another_unit[0][single_dsp_inc_tax]', 'required']); !!}
                              </div>
                          </td>
                          <td>
                              <button type="button" class="btn btn-primary btn-xs add_unit_row" data-sub-key="1"
                                  data-row-html=""><i class="fa fa-plus"></i></button>
                          </td>
                      </tr>
                    @endforelse --}}

                            </tbody>

                        </table>

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
                                    @php
                                        $disabled =
                                            empty($product->expiry_period_type) || empty($product->enable_stock);
                                        $disabled_period = empty($product->enable_stock);
                                    @endphp

                                    <label for="expiry_period">{{ __('product.expires_in') }}:</label><br>

                                    <input type="text" name="expiry_period" id="expiry_period"
                                        class="form-control pull-left input_number"
                                        placeholder="{{ __('product.expiry_period') }}" style="width:60%;"
                                        value="{{ old('expiry_period', @num_format($product->expiry_period)) }}"
                                        @if ($disabled) disabled @endif>

                                    <select name="expiry_period_type" id="expiry_period_type"
                                        class="form-control select2 pull-left" style="width:40%;"
                                        @if ($disabled_period) disabled @endif>
                                        <option value="">{{ __('product.not_applicable') }}</option>
                                        <option value="months"
                                            {{ old('expiry_period_type', $product->expiry_period_type) == 'months' ? 'selected' : '' }}>
                                            {{ __('product.months') }}
                                        </option>
                                        <option value="days"
                                            {{ old('expiry_period_type', $product->expiry_period_type) == 'days' ? 'selected' : '' }}>
                                            {{ __('product.days') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif


                    <div class="clearfix"></div>

                    <!-- Rack, Row & position number -->
                    @if (session('business.enable_racks') || session('business.enable_row') || session('business.enable_position'))
                        <div class="col-md-12">
                            <h4>
                                {{ __('lang_v1.rack_details') }}:
                                @show_tooltip(__('lang_v1.tooltip_rack_details'))
                            </h4>
                        </div>

                        @foreach ($business_locations as $id => $location)
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="rack_{{ $id }}">{{ $location }}:</label>

                                    @if (!empty($rack_details[$id]))
                                        @if (session('business.enable_racks'))
                                            <input type="text" name="product_racks_update[{{ $id }}][rack]"
                                                id="rack_{{ $id }}" class="form-control"
                                                value="{{ old('product_racks_update.' . $id . '.rack', $rack_details[$id]['rack']) }}">
                                        @endif

                                        @if (session('business.enable_row'))
                                            <input type="text" name="product_racks_update[{{ $id }}][row]"
                                                class="form-control"
                                                value="{{ old('product_racks_update.' . $id . '.row', $rack_details[$id]['row']) }}">
                                        @endif

                                        @if (session('business.enable_position'))
                                            <input type="text" name="product_racks_update[{{ $id }}][position]"
                                                class="form-control"
                                                value="{{ old('product_racks_update.' . $id . '.position', $rack_details[$id]['position']) }}">
                                        @endif
                                    @else
                                        <input type="text" name="product_racks[{{ $id }}][rack]"
                                            id="rack_{{ $id }}" class="form-control"
                                            placeholder="{{ __('lang_v1.rack') }}"
                                            value="{{ old('product_racks.' . $id . '.rack') }}">

                                        <input type="text" name="product_racks[{{ $id }}][row]"
                                            class="form-control" placeholder="{{ __('lang_v1.row') }}"
                                            value="{{ old('product_racks.' . $id . '.row') }}">

                                        <input type="text" name="product_racks[{{ $id }}][position]"
                                            class="form-control" placeholder="{{ __('lang_v1.position') }}"
                                            value="{{ old('product_racks.' . $id . '.position') }}">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif



                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="weight">{{ __('lang_v1.weight') }}:</label>
                            <input type="text" name="weight" id="weight" class="form-control"
                                placeholder="{{ __('lang_v1.weight') }}" value="{{ old('weight', $product->weight) }}">
                        </div>
                    </div>

                    <div class="clearfix"></div>
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
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="product_custom_field1">{{ $product_custom_field1 }}:</label>
                            <input type="text" name="product_custom_field1" id="product_custom_field1"
                                class="form-control" placeholder="{{ $product_custom_field1 }}"
                                value="{{ old('product_custom_field1', $product->product_custom_field1) }}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="product_custom_field2">{{ $product_custom_field2 }}:</label>
                            <input type="text" name="product_custom_field2" id="product_custom_field2"
                                class="form-control" placeholder="{{ $product_custom_field2 }}"
                                value="{{ old('product_custom_field2', $product->product_custom_field2) }}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="product_custom_field3">{{ $product_custom_field3 }}:</label>
                            <input type="text" name="product_custom_field3" id="product_custom_field3"
                                class="form-control" placeholder="{{ $product_custom_field3 }}"
                                value="{{ old('product_custom_field3', $product->product_custom_field3) }}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="product_custom_field4">{{ $product_custom_field4 }}:</label>
                            <input type="text" name="product_custom_field4" id="product_custom_field4"
                                class="form-control" placeholder="{{ $product_custom_field4 }}"
                                value="{{ old('product_custom_field4', $product->product_custom_field4) }}">
                        </div>
                    </div>

                    <!--custom fields-->
                    @include('layouts.partials.module_form_part')
                </div>
            @endcomponent

            @component('components.widget', ['class' => 'box-primary'])
                <div class="row">

                    <div class="col-sm-4 @if (!session('business.enable_price_tax')) hide @endif">
                        <div class="form-group">
                            <label for="tax">{{ __('product.applicable_tax') }}:</label>
                            <select name="tax" id="tax" class="form-control select2"
                                @foreach ($tax_attributes ?? [] as $attr => $val)
                        @if (is_scalar($val))
                            {{ $attr }}="{{ $val }}"
                        @endif @endforeach>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach ($taxes as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ old('tax', $product->tax) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4 hide">
                        <div class="form-group">
                            <label for="tax_type">{{ __('product.selling_price_tax_type') }}:*</label>
                            <select name="tax_type" id="tax_type" class="form-control select2" required>
                                <option value="inclusive"
                                    {{ old('tax_type', $product->tax_type) == 'inclusive' ? 'selected' : '' }}>
                                    {{ __('product.inclusive') }}
                                </option>
                                <option value="exclusive"
                                    {{ old('tax_type', $product->tax_type) == 'exclusive' ? 'selected' : '' }}>
                                    {{ __('product.exclusive') }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="type">{{ __('product.product_type') }}:*</label>
                            @show_tooltip(__('tooltip.product_type'))
                            <select name="type" id="type" class="form-control select2" required disabled
                                data-action="edit" data-product_id="{{ $product->id }}">
                                @foreach ($product_types as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('type', $product->type) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="form-group col-sm-12" id="product_form_part"></div>
                    <input type="hidden" id="variation_counter" value="0">
                    <input type="hidden" id="default_profit_percent" value="{{ $default_profit_percent }}">
                </div>
            @endcomponent

            <div class="row">
                <input type="hidden" name="submit_type" id="submit_type">
                <div class="col-sm-12">
                    <div class="text-center">
                        <div class="btn-group">
                            @if ($selling_price_group_count)
                                <button type="submit" value="submit_n_add_selling_prices"
                                    class="btn btn-warning submit_product_form">@lang('lang_v1.save_n_add_selling_price_group_prices')</button>
                            @endif

                            @can('product.opening_stock')
                                <button type="submit" @if (empty($product->enable_stock)) disabled="true" @endif
                                    id="opening_stock_button" value="update_n_edit_opening_stock"
                                    class="btn bg-purple submit_product_form">@lang('lang_v1.update_n_edit_opening_stock')</button>
                            @endcan

                            <button type="submit" value="save_n_add_another"
                                class="btn bg-maroon submit_product_form">@lang('lang_v1.update_n_add_another')</button>

                            <button type="submit" value="submit"
                                class="btn btn-primary submit_product_form">@lang('messages.update')</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script src="{{ url('js/product.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            __page_leave_confirmation('#product_add_form');
        });
    </script>
    <script>
        var unitIncreament = {{ optional($product->unitPrices)->count() }};

        function getTemplate(number) {
            return ` <tr>
                @php $number = 0; @endphp
                   <td>
    <br>
    <div class="input-group width-100">
        <select name="another_unit[{{ $number }}][unit_id]" class="form-control select2 width-100 unit_id" required>
            @foreach ($units as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
    </div>
</td>

<td>
    <br>
    <input type="text"
           name="another_unit[{{ $number }}][qty]"
           class="form-control input-sm input_number"
           required
           value="0">
</td>

<td>
    <div class="col-sm-6">
        <label for="another_unit[{{ $number }}][single_dpp]">{{ __('product.exc_of_tax') }}:*</label>
        <input type="text"
               name="another_unit[{{ $number }}][single_dpp]"
               class="form-control input-sm dpp input_number"
               placeholder="{{ __('product.exc_of_tax') }}"
               required
               value="0">
    </div>

    <div class="col-sm-6">
        <br>
        <label for="another_unit[{{ $number }}][single_dpp_inc_tax]">{{ __('product.inc_of_tax') }}:*</label>
        <input type="text"
               name="another_unit[{{ $number }}][single_dpp_inc_tax]"
               class="form-control input-sm dpp_inc_tax input_number"
               placeholder="{{ __('product.inc_of_tax') }}"
               required
               value="0">
    </div>
</td>

<td>
    <br>
    <input type="text"
           name="another_unit[{{ $number }}][sku]"
           class="form-control input-sm bar_code"
           required>
</td>

<td>
    <div class="col-sm-6">
        <label for="another_unit[{{ $number }}][single_dsp]">{{ __('product.exc_of_tax') }}:*</label>
        <input type="text"
               name="another_unit[{{ $number }}][single_dsp]"
               id="another_unit[{{ $number }}][single_dsp]"
               class="form-control input-sm input_number"
               placeholder="{{ __('product.exc_of_tax') }}"
               required
               value="0">
    </div>

    <div class="col-sm-6">
        <br>
        <label for="another_unit[{{ $number }}][single_dsp_inc_tax]">{{ __('product.inc_of_tax') }}:*</label>
        <input type="text"
               name="another_unit[{{ $number }}][single_dsp_inc_tax]"
               id="another_unit[{{ $number }}][single_dsp_inc_tax]"
               class="form-control input-sm input_number"
               placeholder="{{ __('product.inc_of_tax') }}"
               required
               value="0">
    </div>
</td>

                      <td>&nbsp;</td>
                  </tr>`

        }
        $('.add_unit_row').on('click', function() {
            // unitIncreament++;
            let unitRow = getTemplate(++unitIncreament);
            let $selector = $(unitRow).appendTo(`.multi_unit_box .add_another_units_table tbody`);
            $selector.find('.select2').select2({
                dropdownParent: $selector.find('td:first-child')
            })
            // $('.multi_unit_box .add_another_units_table tbody').append(unitTemplateRow);

        });
        $('#enable_multi_unit').on('ifChanged', function() {
            let $this = $(this);
            console.log('changed');
            if ($this.is(':checked')) {
                $('.multi_unit_box').removeClass('hidden');
                console.log('true');
            } else {
                $('.multi_unit_box').addClass('hidden');
                console.log('notchecked');

            }
        });
    </script>
@endsection

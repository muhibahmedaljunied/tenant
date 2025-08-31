<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <form action="{{ action('ProductController@saveQuickProduct') }}" method="post" id="quick_add_product_form">
      @csrf

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="modalTitle">@lang( 'product.add_new_product' )</h4>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="name">{{ __('product.product_name') . ':*' }}</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $product_name }}" required placeholder="{{ __('product.product_name') }}">
            <select name="type" id="type" class="hide">
              <option value="single" selected>Single</option>
              <option value="variable">Variable</option>
            </select>
          </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group">
            <label for="sku">{{ __('product.sku') . ':' }}</label> @show_tooltip(__('tooltip.sku'))
            <input type="text" name="sku" id="sku" class="form-control" placeholder="{{ __('product.sku') }}">
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <label for="barcode_type">{{ __('product.barcode_type') . ':*' }}</label>
            <select name="barcode_type" id="barcode_type" class="form-control select2" required>
              @foreach($barcode_types as $key => $label)
                <option value="{{$key}}" @if('C128' == $key) selected @endif>{{$label}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="clearfix"></div>

        <div class="col-sm-4">
          <div class="form-group">
            <label for="unit_id">{{ __('product.unit') . ':*' }}</label>
            <select name="unit_id" id="unit_id" class="form-control select2" required>
              @foreach($units as $key => $label)
                <option value="{{$key}}">{{$label}}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="col-sm-4 @if(!session('business.enable_sub_units')) hide @endif">
          <div class="form-group">
            <label for="sub_unit_ids">{{ __('lang_v1.related_sub_units') . ':' }}</label> @show_tooltip(__('lang_v1.sub_units_tooltip'))
            <select name="sub_unit_ids[]" id="sub_unit_ids" class="form-control select2" multiple></select>
          </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group">
            <label for="brand_id">{{ __('product.brand') . ':' }}</label>
            <select name="brand_id" id="brand_id" class="form-control select2">
              <option value="">{{ __('messages.please_select') }}</option>
              @foreach($brands as $key => $label)
                <option value="{{$key}}">{{$label}}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="clearfix"></div>
        <div class="col-sm-4">
          <div class="form-group">
            <label for="category_id">{{ __('product.category') . ':' }}</label>
            <select name="category_id" id="category_id" class="form-control select2">
              <option value="">{{ __('messages.please_select') }}</option>
              @foreach($categories as $key => $label)
                <option value="{{$key}}">{{$label}}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="col-sm-4 @if(!(session('business.enable_category') && session('business.enable_sub_category'))) hide @endif">
          <div class="form-group">
            <label for="sub_category_id">{{ __('product.sub_category') . ':' }}</label>
            <select name="sub_category_id" id="sub_category_id" class="form-control select2">
              <option value="">{{ __('messages.please_select') }}</option>
            </select>
          </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group">
            <br>
            <label>
              <input type="checkbox" name="enable_stock" value="1" class="input-icheck" id="enable_stock" checked> <strong>@lang('product.manage_stock')</strong>
            </label>@show_tooltip(__('tooltip.enable_stock')) <p class="help-block"><i>@lang('product.enable_stock_help')</i></p>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-4" id="alert_quantity_div">
          <div class="form-group">
            <label for="alert_quantity">{{ __('product.alert_quantity') . ':' }}</label>
            <input type="number" name="alert_quantity" id="alert_quantity" class="form-control" placeholder="{{ __('product.alert_quantity') }}" min="0">
          </div>
        </div>
        @if(!empty($common_settings['enable_product_warranty']))
        <div class="col-sm-4">
          <div class="form-group">
            <label for="warranty_id">{{ __('lang_v1.warranty') . ':' }}</label>
            <select name="warranty_id" id="warranty_id" class="form-control select2">
              <option value="">{{ __('messages.please_select') }}</option>
              @foreach($warranties as $key => $label)
                <option value="{{$key}}">{{$label}}</option>
              @endforeach
            </select>
          </div>
        </div>
        @endif
        @if(session('business.enable_product_expiry'))
        @if(session('business.expiry_type') == 'add_expiry')
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
        <div class="col-sm-4 @if($hide) hide @endif">
          <div class="form-group">
            <div class="multi-input">
              <label for="expiry_period">{{ __('product.expires_in') . ':' }}</label><br>
              <input type="text" name="expiry_period" id="expiry_period" class="form-control pull-left input_number" value="{{ $expiry_period }}" placeholder="{{ __('product.expiry_period') }}" style="width:60%;">
              <select name="expiry_period_type" id="expiry_period_type" class="form-control select2 pull-left" style="width:40%;">
                <option value="months" @if('months' == 'months') selected @endif>{{ __('product.months') }}</option>
                <option value="days">{{ __('product.days') }}</option>
                <option value="">{{ __('product.not_applicable') }}</option>
              </select>
            </div>
          </div>
        </div>
        @endif
        @php
        $default_location = null;
        if(count($business_locations) == 1){
        $default_location = array_key_first($business_locations->toArray());
        }
        @endphp
        <div class="col-sm-4">
          <div class="form-group">
            <label for="product_locations">{{ __('business.business_locations') . ':' }}</label> @show_tooltip(__('lang_v1.product_location_help'))
            <select name="product_locations[]" id="product_locations" class="form-control select2" multiple>
              @foreach($business_locations as $key => $label)
                <option value="{{$key}}" @if(isset($default_location) && $default_location == $key) selected @endif>{{$label}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <label for="weight">{{ __('lang_v1.weight') . ':' }}</label>
            <input type="text" name="weight" id="weight" class="form-control" placeholder="{{ __('lang_v1.weight') }}">
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-8">
          <div class="form-group">
            <label for="product_description">{{ __('lang_v1.product_description') . ':' }}</label>
            <textarea name="product_description" id="product_description" class="form-control"></textarea>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-4">
          <div class="form-group">
            <label for="tax">{{ __('product.applicable_tax') . ':' }}</label>
            <select name="tax" id="tax" class="form-control select2">
              <option value="">{{ __('messages.please_select') }}</option>
              @foreach($taxes as $key => $label)
                <option value="{{$key}}" @if(isset($tax_attributes) && isset($tax_attributes['selected']) && $tax_attributes['selected'] == $key) selected @endif>{{$label}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <label for="tax_type">{{ __('product.selling_price_tax_type') . ':*' }}</label>
            <select name="tax_type" id="tax_type" class="form-control select2" required>
              <option value="inclusive">{{ __('product.inclusive') }}</option>
              <option value="exclusive" selected>{{ __('product.exclusive') }}</option>
            </select>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="checkbox">
            <br>
            <label>
              <input type="checkbox" name="enable_sr_no" value="1" class="input-icheck"> <strong>@lang('lang_v1.enable_imei_or_sr_no')</strong>
            </label>@show_tooltip(__('lang_v1.tooltip_sr_no'))
          </div>
        </div>
        <div class="clearfix"></div>
        @php
        $custom_labels = json_decode(session('business.custom_labels'), true);
        $product_custom_field1 = !empty($custom_labels['product']['custom_field_1']) ? $custom_labels['product']['custom_field_1'] : __('lang_v1.product_custom_field1');
        $product_custom_field2 = !empty($custom_labels['product']['custom_field_2']) ? $custom_labels['product']['custom_field_2'] : __('lang_v1.product_custom_field2');
        $product_custom_field3 = !empty($custom_labels['product']['custom_field_3']) ? $custom_labels['product']['custom_field_3'] : __('lang_v1.product_custom_field3');
        $product_custom_field4 = !empty($custom_labels['product']['custom_field_4']) ? $custom_labels['product']['custom_field_4'] : __('lang_v1.product_custom_field4');
        @endphp
        <div class="col-sm-4">
          <div class="form-group">
            <br>
            <label>
              <input type="checkbox" name="not_for_selling" value="1" class="input-icheck"> <strong>@lang('lang_v1.not_for_selling')</strong>
            </label> @show_tooltip(__('lang_v1.tooltip_not_for_selling'))
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-3">
          <div class="form-group">
            <label for="product_custom_field1">{{ $product_custom_field1 . ':' }}</label>
            <input type="text" name="product_custom_field1" id="product_custom_field1" class="form-control" placeholder="{{ $product_custom_field1 }}">
          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">
            <label for="product_custom_field2">{{ $product_custom_field2 . ':' }}</label>
            <input type="text" name="product_custom_field2" id="product_custom_field2" class="form-control" placeholder="{{ $product_custom_field2 }}">
          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">
            <label for="product_custom_field3">{{ $product_custom_field3 . ':' }}</label>
            <input type="text" name="product_custom_field3" id="product_custom_field3" class="form-control" placeholder="{{ $product_custom_field3 }}">
          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">
            <label for="product_custom_field4">{{ $product_custom_field4 . ':' }}</label>
            <input type="text" name="product_custom_field4" id="product_custom_field4" class="form-control" placeholder="{{ $product_custom_field4 }}">
          </div>
        </div>
        <div class="clearfix"></div>
        @if(!empty($module_form_parts))
        @foreach($module_form_parts as $key => $value)
        @if(!empty($value['template_path']))
        @php
        $template_data = $value['template_data'] ?: [];
        @endphp
        @include($value['template_path'], $template_data)
        @endif
        @endforeach
        @endif
      </div>
      <div class="row">
        <div class="form-group col-sm-11 col-sm-offset-1">
          @include('product.partials.single_product_form_part', ['profit_percent' => $default_profit_percent, 'quick_add' => true ])
        </div>
      </div>
      @if(!empty($product_for) && $product_for == 'pos')
      @include('product.partials.quick_product_opening_stock', ['locations' => $locations])
      @endif
    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary" id="submit_quick_product">@lang( 'messages.save' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script type="text/javascript">
  $(document).ready(function() {
    $("form#quick_add_product_form").validate({
      rules: {
        sku: {
          remote: {
            url: "/products/check_product_sku",
            type: "post",
            data: {
              sku: function() {
                return $("#sku").val();
              },
              product_id: function() {
                if ($('#product_id').length > 0) {
                  return $('#product_id').val();
                } else {
                  return '';
                }
              },
            }
          }
        },
        expiry_period: {
          required: {
            depends: function(element) {
              return ($('#expiry_period_type').val().trim() != '');
            }
          }
        }
      },
      messages: {
        sku: {
          remote: LANG.sku_already_exists
        }
      },
      submitHandler: function(form) {

        var form = $("form#quick_add_product_form");
        var url = form.attr('action');
        form.find('button[type="submit"]').attr('disabled', true);
        $.ajax({
          method: "POST",
          url: url,
          dataType: 'json',
          data: $(form).serialize(),
          success: function(data) {
            $('.quick_add_product_modal').modal('hide');
            if (data.success) {
              toastr.success(data.msg);
              if (typeof get_purchase_entry_row !== 'undefined') {
                var selected_location = $('#location_id').val();
                var location_check = true;
                if (data.locations && selected_location && data.locations.indexOf(selected_location) == -1) {
                  location_check = false;
                }
                if (location_check) {
                  get_purchase_entry_row(data.product.id, 0);
                }

              }
              $(document).trigger({
                type: "quickProductAdded",
                'product': data.product,
                'variation': data.variation
              });
            } else {
              toastr.error(data.msg);
            }
          }
        });
        return false;
      }
    });
  });
</script>
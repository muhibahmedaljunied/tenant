<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">

    <form action="{{ action('BusinessLocationController@update', [$location->id]) }}" method="POST" id="business_location_add_form">
      @csrf
      @method('PUT')
      <input type="hidden" name="hidden_id" value="{{ $location->id }}" id="hidden_id">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'business.edit_business_location' )</h4>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="name">{{ __('invoice.name') }}:*</label>
              <input type="text" name="name" value="{{ $location->name }}" class="form-control" required placeholder="{{ __('invoice.name') }}">
            </div>
          </div>

          <div class="col-sm-6" style="display: none;">
            <div class="form-group">
              <label for="location_id">{{ __('lang_v1.location_id') }}:</label>
              <input type="text" name="location_id" value="{{ $location->location_id }}" class="form-control" placeholder="{{ __('lang_v1.location_id') }}">
            </div>
          </div>

          <div class="col-sm-6" style="display: none;">
            <div class="form-group">
              <label for="landmark">{{ __('business.landmark') }}:</label>
              <input type="text" name="landmark" value="{{ $location->landmark }}" class="form-control" placeholder="{{ __('business.landmark') }}">
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="city">{{ __('business.city') }}:*</label>
              <input type="text" name="city" value="{{ $location->city }}" class="form-control" placeholder="{{ __('business.city') }}" required>
            </div>
          </div>

          <div class="col-sm-6" style="display: none;">
            <div class="form-group">
              <label for="zip_code">{{ __('business.zip_code') }}:</label>
              <input type="text" name="zip_code" value="{{ $location->zip_code }}" class="form-control" placeholder="{{ __('business.zip_code') }}">
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="state">{{ __('business.state') }}:*</label>
              <input type="text" name="state" value="{{ $location->state }}" class="form-control" placeholder="{{ __('business.state') }}" required>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="country">{{ __('business.country') }}:*</label>
              <input type="text" name="country" value="{{ $location->country }}" class="form-control" placeholder="{{ __('business.country') }}" required>
            </div>
          </div>

          <!-- Additional fields for mobile, alternate number, email, website would follow similar structure -->

          <div class="col-sm-6">
            <div class="form-group">
              <label for="invoice_scheme_id">{{ __('invoice.invoice_scheme') }}:*</label>
              <select name="invoice_scheme_id" class="form-control" required>
                <option value="">{{ __('messages.please_select') }}</option>
                @foreach($invoice_schemes as $key => $value)
                <option value="{{ $key }}" {{ $location->invoice_scheme_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Repeat this pattern for invoice_layout_id, sale_invoice_layout_id, and selling_price_group_id -->

          <!-- Custom Fields -->
          <div class="clearfix"></div>
          @php
          $custom_labels = json_decode(session('business.custom_labels'), true);
          $location_custom_field1 = !empty($custom_labels['location']['custom_field_1']) ? $custom_labels['location']['custom_field_1'] : __('lang_v1.location_custom_field1');
          $location_custom_field2 = !empty($custom_labels['location']['custom_field_2']) ? $custom_labels['location']['custom_field_2'] : __('lang_v1.location_custom_field2');
          $location_custom_field3 = !empty($custom_labels['location']['custom_field_3']) ? $custom_labels['location']['custom_field_3'] : __('lang_v1.location_custom_field3');
          $location_custom_field4 = !empty($custom_labels['location']['custom_field_4']) ? $custom_labels['location']['custom_field_4'] : __('lang_v1.location_custom_field4');
          @endphp

          <div class="col-sm-3">
            <div class="form-group">
              <label for="custom_field1">{{ $location_custom_field1 }}:</label>
              <input type="text" name="custom_field1" value="{{ $location->custom_field1 }}" class="form-control" placeholder="{{ $location_custom_field1 }}">
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <label for="custom_field2">{{ $location_custom_field2 }}:</label>
              <input type="text" name="custom_field2" value="{{ $location->custom_field2 }}" class="form-control" placeholder="{{ $location_custom_field2 }}">
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <label for="custom_field3">{{ $location_custom_field3 }}:</label>
              <input type="text" name="custom_field3" value="{{ $location->custom_field3 }}" class="form-control" placeholder="{{ $location_custom_field3 }}">
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <label for="custom_field4">{{ $location_custom_field4 }}:</label>
              <input type="text" name="custom_field4" value="{{ $location->custom_field4 }}" class="form-control" placeholder="{{ $location_custom_field4 }}">
            </div>
          </div>
          <div class="clearfix"></div>

<hr>
          <!-- Featured Products -->
          <div class="col-sm-12">
            <div class="form-group">
              <label for="featured_products">{{ __('lang_v1.pos_screen_featured_products') }}:</label>
              <select name="featured_products[]" id="featured_products" class="form-control" multiple>
                @foreach($featured_products as $key => $value)
                <option value="{{ $key }}" {{ in_array($key, $location->featured_products ?? []) ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Payment Options table would follow next, with checkboxes and select inputs for accounts -->
        </div>
      </div>


      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ action('BusinessLocationController@update', [$location->id]) }}" method="POST" id="business_location_add_form">
      @csrf
      @method('PUT')

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'business.edit_business_location' )</h4>
    </div>

    <div class="modal-body">
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label for="name">{{ __( 'invoice.name' ) }}:*</label>
            <input type="text" name="name" id="name" class="form-control" required placeholder="{{ __( 'invoice.name' ) }}" value="{{ old('name', $location->name) }}">
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
            <label for="landmark">{{ __( 'business.landmark' ) }}:</label>
            <input type="text" name="landmark" id="landmark" class="form-control" placeholder="{{ __( 'business.landmark' ) }}" value="{{ old('landmark', $location->landmark) }}">
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
            <label for="city">{{ __( 'business.city' ) }}:*</label>
            <input type="text" name="city" id="city" class="form-control" required placeholder="{{ __( 'business.city') }}" value="{{ old('city', $location->city) }}">
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
            <label for="zip_code">{{ __( 'business.zip_code' ) }}:*</label>
            <input type="text" name="zip_code" id="zip_code" class="form-control" required placeholder="{{ __( 'business.zip_code') }}" value="{{ old('zip_code', $location->zip_code) }}">
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
            <label for="state">{{ __( 'business.state' ) }}:*</label>
            <input type="text" name="state" id="state" class="form-control" required placeholder="{{ __( 'business.state') }}" value="{{ old('state', $location->state) }}">
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
            <label for="country">{{ __( 'business.country' ) }}:*</label>
            <input type="text" name="country" id="country" class="form-control" required placeholder="{{ __( 'business.country') }}" value="{{ old('country', $location->country) }}">
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
            <label for="invoice_scheme_id">{{ __('invoice.invoice_scheme') }}:*</label> @show_tooltip(__('tooltip.invoice_scheme'))
            <select name="invoice_scheme_id" id="invoice_scheme_id" class="form-control" required>
              <option value="">{{ __('messages.please_select') }}</option>
              @foreach($invoice_schemes as $id => $name)
                <option value="{{ $id }}" {{ old('invoice_scheme_id', $location->invoice_scheme_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
            <label for="invoice_layout_id">{{ __('invoice.invoice_layout') }}:*</label> @show_tooltip(__('tooltip.invoice_layout'))
            <select name="invoice_layout_id" id="invoice_layout_id" class="form-control" required>
              <option value="">{{ __('messages.please_select') }}</option>
              @foreach($invoice_layouts as $id => $name)
                <option value="{{ $id }}" {{ old('invoice_layout_id', $location->invoice_layout_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
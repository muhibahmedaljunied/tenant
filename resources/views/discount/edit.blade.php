<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ action('DiscountController@update', [$discount->id]) }}" method="POST" id="discount_form">
      @csrf
      @method('PUT')

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'sale.edit_discount' )</h4>
    </div>

    <div class="modal-body">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label for="name">{{ __( 'unit.name' ) }}:*</label>
            <input type="text" name="name" id="name" class="form-control" required placeholder="{{ __( 'unit.name' ) }}" value="{{ old('name', $discount->name) }}">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="variation_ids">{{ __('report.products') }}:</label>
            <select name="variation_ids[]" id="variation_ids" class="form-control" multiple>
              @foreach($variations as $id => $name)
                <option value="{{ $id }}" {{ in_array($id, old('variation_ids', array_keys($variations))) ? 'selected' : '' }}>{{ $name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-6 @if(!empty($variations)) hide @endif" id="brand_input">
          <div class="form-group">
            <label for="brand_id">{{ __('product.brand') }}:</label>
            <select name="brand_id" id="brand_id" class="form-control select2" style="width: 100%;">
              <option value="">{{ __('messages.please_select') }}</option>
              @foreach($brands as $id => $name)
                <option value="{{ $id }}" {{ old('brand_id', $discount->brand_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-sm-6 @if(!empty($variations)) hide @endif" id="category_input">
          <div class="form-group">
            <label for="category_id">{{ __('product.category') }}:</label>
            <select name="category_id" id="category_id" class="form-control select2" style="width: 100%;">
              <option value="">{{ __('messages.please_select') }}</option>
              @foreach($categories as $id => $name)
                <option value="{{ $id }}" {{ old('category_id', $discount->category_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="location_id">{{ __('sale.location') }}:*</label>
            <select name="location_id" id="location_id" class="form-control select2" required>
              <option value="">{{ __('messages.please_select') }}</option>
              @foreach($locations as $id => $name)
                <option value="{{ $id }}" {{ old('location_id', $discount->location_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="priority">{{ __( 'lang_v1.priority' ) }}:</label>
            <input type="text" name="priority" id="priority" class="form-control input_number" required placeholder="{{ __( 'lang_v1.priority' ) }}" value="{{ old('priority', $discount->priority) }}">
          </div>
        </div>
         <div class="col-sm-6">
          <div class="form-group">
            <label for="discount_type">{{ __('sale.discount_type') }}:*</label>
            <select name="discount_type" id="discount_type" class="form-control select2" required>
              <option value="">{{ __('messages.please_select') }}</option>
              <option value="fixed" {{ old('discount_type', $discount->discount_type) == 'fixed' ? 'selected' : '' }}>{{ __('lang_v1.fixed') }}</option>
              <option value="percentage" {{ old('discount_type', $discount->discount_type) == 'percentage' ? 'selected' : '' }}>{{ __('lang_v1.percentage') }}</option>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="discount_amount">{{ __( 'sale.discount_amount' ) }}:*</label>
            <input type="text" name="discount_amount" id="discount_amount" class="form-control input_number" required placeholder="{{ __( 'sale.discount_amount' ) }}" value="{{ old('discount_amount', $discount->discount_amount) }}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="starts_at">{{ __( 'lang_v1.starts_at' ) }}:</label>
            <input type="text" name="starts_at" id="starts_at" class="form-control discount_date" required placeholder="{{ __( 'lang_v1.starts_at' ) }}" value="{{ old('starts_at', $starts_at) }}" readonly>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="ends_at">{{ __( 'lang_v1.ends_at' ) }}:</label>
            <input type="text" name="ends_at" id="ends_at" class="form-control discount_date" required placeholder="{{ __( 'lang_v1.ends_at' ) }}" value="{{ old('ends_at', $ends_at) }}" readonly>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <br>
            <label>
              <input type="checkbox" name="applicable_in_spg" value="1" class="input-icheck" {{ !empty(old('applicable_in_spg', $discount->applicable_in_spg)) ? 'checked' : '' }}> <strong>@lang('lang_v1.applicable_in_cpg')</strong>
            </label>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <br>
            <label>
              <input type="checkbox" name="applicable_in_cg" value="1" class="input-icheck" {{ !empty(old('applicable_in_cg', $discount->applicable_in_cg)) ? 'checked' : '' }}> <strong>@lang('lang_v1.applicable_in_cg')</strong>
            </label>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label>
              <input type="checkbox" name="is_active" value="1" class="input-icheck" {{ !empty(old('is_active', $discount->is_active)) ? 'checked' : '' }}> <strong>@lang('lang_v1.is_active')</strong>
            </label>
          </div>
        </div>
      </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
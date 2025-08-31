<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ action('CustomerGroupController@update', [$customer_group->id]) }}" method="POST" id="customer_group_edit_form">
      @csrf
      @method('PUT')

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'lang_v1.edit_customer_group' )</h4>
    </div>

    <div class="modal-body">
      <div class="form-group">
        <label for="name">{{ __( 'lang_v1.customer_group_name' ) }}:*</label>
        <input type="text" name="name" id="name" class="form-control" required placeholder="{{ __( 'lang_v1.customer_group_name' ) }}" value="{{ old('name', $customer_group->name) }}">
      </div>
      <div class="form-group">
        <label for="price_calculation_type">{{ __( 'lang_v1.price_calculation_type' ) }}:</label>
        <select name="price_calculation_type" id="price_calculation_type" class="form-control">
          <option value="percentage" {{ old('price_calculation_type', $customer_group->price_calculation_type) == 'percentage' ? 'selected' : '' }}>{{ __('lang_v1.percentage') }}</option>
          <option value="selling_price_group" {{ old('price_calculation_type', $customer_group->price_calculation_type) == 'selling_price_group' ? 'selected' : '' }}>{{ __('lang_v1.selling_price_group') }}</option>
        </select>
      </div>
      <div class="form-group percentage-field @if($customer_group->price_calculation_type != 'percentage') hide @endif">
        <label for="amount">{{ __( 'lang_v1.calculation_percentage' ) }}:</label>
        @show_tooltip(__('lang_v1.tooltip_calculation_percentage'))
        <input type="text" name="amount" id="amount" class="form-control input_number" placeholder="{{ __( 'lang_v1.calculation_percentage') }}" value="{{ old('amount', @num_format($customer_group->amount)) }}">
      </div>
      <div class="form-group selling_price_group-field @if($customer_group->price_calculation_type != 'selling_price_group') hide @endif">
        <label for="selling_price_group_id">{{ __( 'lang_v1.selling_price_group' ) }}:</label>
        <select name="selling_price_group_id" id="selling_price_group_id" class="form-control">
          <option value="">{{ __('messages.please_select') }}</option>
          @foreach($price_groups as $id => $name)
            <option value="{{ $id }}" {{ old('selling_price_group_id', $customer_group->selling_price_group_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
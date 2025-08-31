<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ action('CustomerGroupController@store') }}" method="POST" id="customer_group_add_form">
      @csrf

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'lang_v1.add_customer_group' )</h4>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <label for="name">{{ __('lang_v1.customer_group_name') }}:*</label>
          <input type="text" name="name" class="form-control" required placeholder="{{ __('lang_v1.customer_group_name') }}">
        </div>

        <div class="form-group">
          <label for="price_calculation_type">{{ __('lang_v1.price_calculation_type') }}:</label>
          <select name="price_calculation_type" class="form-control">
            <option value="percentage" selected>{{ __('lang_v1.percentage') }}</option>
            <option value="selling_price_group">{{ __('lang_v1.selling_price_group') }}</option>
          </select>
        </div>

        <div class="form-group percentage-field">
          <label for="amount">{{ __('lang_v1.calculation_percentage') }}:</label>
          <input type="text" name="amount" class="form-control input_number" placeholder="{{ __('lang_v1.calculation_percentage') }}">
        </div>

        <div class="form-group selling_price_group-field hide">
          <label for="selling_price_group_id">{{ __('lang_v1.selling_price_group') }}:</label>
          <select name="selling_price_group_id" class="form-control">
            @foreach($price_groups as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
          </select>
        </div>


      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
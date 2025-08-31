<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ action('\\Modules\\Superadmin\\Http\\Controllers\\SuperadminSubscriptionsController@store') }}" method="POST" id="superadmin_add_subscription">
      @csrf
      <input type="hidden" name="business_id" value="{{ $business_id }}">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'superadmin::lang.add_subscription' )</h4>
    </div>

    <div class="modal-body">
      <div class="form-group">
        <label for="package_id">{{ __( 'superadmin::lang.subscription_packages' ) }}:*</label>
        <select name="package_id" id="package_id" class="form-control" required>
          <option value="">{{ __( 'messages.please_select' ) }}</option>
          @foreach($packages as $key => $value)
            <option value="{{ $key }}" {{ old('package_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label for="paid_via">{{ __( 'superadmin::lang.paid_via' ) }}:*</label>
        <select name="paid_via" id="paid_via" class="form-control" required>
          <option value="">{{ __( 'messages.please_select' ) }}</option>
          @foreach($gateways as $key => $value)
            <option value="{{ $key }}" {{ old('paid_via') == $key ? 'selected' : '' }}>{{ $value }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label for="payment_transaction_id">{{ __( 'superadmin::lang.payment_transaction_id' ) }}:</label>
        <input type="text" name="payment_transaction_id" id="payment_transaction_id" class="form-control" placeholder="{{ __( 'superadmin::lang.payment_transaction_id' ) }}" value="{{ old('payment_transaction_id') }}">
      </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
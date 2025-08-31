<!-- Modal -->
<div class="modal-dialog" role="document">
    <div class="modal-content">
     <form action="{{ action('\\Modules\\Superadmin\\Http\\Controllers\\SuperadminSubscriptionsController@update', $subscription->id) }}" method="POST" id="status_change_form">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">@lang( "superadmin::lang.subscription_status")</h4>
      </div>

      <div class="modal-body">
             <div class="form-group">
                <label for="status">{{ __( "superadmin::lang.status") }}</label>
                <select name="status" id="status" class="form-control">
                  @foreach($status as $key => $value)
                    <option value="{{ $key }}" {{ old('status', $subscription->status) == $key ? 'selected' : '' }}>{{ $value }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label for="payment_transaction_id">{{ __("superadmin::lang.payment_transaction_id") }}</label>
                <input type="text" name="payment_transaction_id" id="payment_transaction_id" class="form-control" value="{{ old('payment_transaction_id', $subscription->payment_transaction_id) }}">
              </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( "superadmin::lang.update")</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( "superadmin::lang.close")</button>
      </div>
      </form>
    </div>
</div>

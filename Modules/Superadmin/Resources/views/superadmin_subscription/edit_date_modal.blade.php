<!-- Modal -->
<div class="modal-dialog" role="document">
    <div class="modal-content edit-subscription-modal">
     <form action="{{ action('\\Modules\\Superadmin\\Http\\Controllers\\SuperadminSubscriptionsController@updateSubscription') }}" method="POST" id="edit_subscription_form">
      @csrf
      <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">@lang( "superadmin::lang.edit_subscription")</h4>
      </div>
      <div class="modal-body">
             <div class="form-group">
                <label for="start_date">{{ __( "superadmin::lang.start_date") }}</label>
                <input type="text" name="start_date" id="start_date" class="form-control datepicker" value="{{ !empty($subscription->start_date) ? @format_date($subscription->start_date) : '' }}" readonly>
              </div>

              <div class="form-group">
                <label for="end_date">{{ __("superadmin::lang.end_date") }}</label>
                <input type="text" name="end_date" id="end_date" class="form-control datepicker" value="{{ !empty($subscription->end_date) ? @format_date($subscription->end_date) : '' }}" readonly>
              </div>

              <div class="form-group">
                <label for="trial_end_date">{{ __("superadmin::lang.trial_end_date") }}</label>
                <input type="text" name="trial_end_date" id="trial_end_date" class="form-control datepicker" value="{{ !empty($subscription->trial_end_date) ? @format_date($subscription->trial_end_date) : '' }}" readonly>
              </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( "superadmin::lang.update")</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( "superadmin::lang.close")</button>
      </div>
      </form>
    </div>
</div>

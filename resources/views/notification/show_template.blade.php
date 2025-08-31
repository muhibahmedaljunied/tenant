<!-- Fix for scroll issue in new booking -->
<style type="text/css">
  .modal {
    overflow-y:auto; 
  }
</style>
<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ $notification_template['template_for'] == 'send_ledger' ? action('ContactController@sendLedger') : action('NotificationController@send') }}" method="post" id="send_notification_form">
      @csrf
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'lang_v1.send_notification' ) - {{$template_name}}</h4>
      </div>

      <div class="modal-body">
        <div class="form-group @if($notification_template['template_for'] == 'send_ledger') hide @endif">
          <label class="checkbox-inline">
            <input type="checkbox" name="notification_type[]" value="email" class="input-icheck notification_type" checked> @lang('lang_v1.send_email')
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="notification_type[]" value="sms" class="input-icheck notification_type"> @lang('lang_v1.send_sms')
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="notification_type[]" value="whatsapp" class="input-icheck notification_type"> @lang('lang_v1.send_whatsapp')
          </label>
        </div>
        <div id="email_div">
          <div class="form-group">
            <label for="to_email">{{ __('lang_v1.to') . ':' }}</label> @show_tooltip(__('lang_v1.notification_email_tooltip'))
            <input type="text" name="to_email" id="to_email" class="form-control" value="{{ $contact->email }}" placeholder="{{ __('lang_v1.to') }}">
          </div>
          <div class="form-group">
            <label for="subject">{{ __('lang_v1.email_subject') . ':' }}</label>
            <input type="text" name="subject" id="subject" class="form-control" value="{{ $notification_template['subject'] }}" placeholder="{{ __('lang_v1.email_subject') }}">
          </div>
          <div class="form-group">
            <label for="cc">CC:</label>
            <input type="email" name="cc" id="cc" class="form-control" value="{{ $notification_template['cc'] }}" placeholder="CC">
          </div>
          <div class="form-group">
            <label for="bcc">BCC:</label>
            <input type="email" name="bcc" id="bcc" class="form-control" value="{{ $notification_template['bcc'] }}" placeholder="BCC">
          </div>
          <div class="form-group">
            <label for="email_body">{{ __('lang_v1.email_body') . ':' }}</label>
            <textarea name="email_body" id="email_body" class="form-control" placeholder="{{ __('lang_v1.email_body') }}" rows="6">{{ $notification_template['email_body'] }}</textarea>
          </div>
          @if($notification_template['template_for'] == 'send_ledger')
            <p class="help-block">*@lang('lang_v1.ledger_attacment_help')</p>
          @endif
        </div>
        <div class="form-group">
            <label for="mobile_number">{{ __('lang_v1.mobile_number') . ':' }}</label>
            <input type="text" name="mobile_number" id="mobile_number" class="form-control" value="{{ $contact->mobile }}" placeholder="{{ __('lang_v1.mobile_number') }}">
        </div>
        <div id="sms_div" class="hide">
          <div class="form-group">
            <label for="sms_body">{{ __('lang_v1.sms_body') . ':' }}</label>
            <textarea name="sms_body" id="sms_body" class="form-control" placeholder="{{ __('lang_v1.sms_body') }}" rows="6">{{ $notification_template['sms_body'] }}</textarea>
          </div>
        </div>
        <div id="whatsapp_div" class="hide">
            <label for="whatsapp_text">{{ __('lang_v1.whatsapp_text') . ':' }}</label>
            <textarea name="whatsapp_text" id="whatsapp_text" class="form-control" placeholder="{{ __('lang_v1.whatsapp_text') }}" rows="6">{{ $notification_template['whatsapp_text'] }}</textarea>
        </div>
        <strong>@lang('lang_v1.available_tags'):</strong> <p class="help-block">{{implode(', ', $tags)}}</p>

        @if(!empty($transaction))
          <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
        @endif

        @if($notification_template['template_for'] == 'send_ledger')
          <input type="hidden" name="contact_id" value="{{ $contact->id }}">
          <input type="hidden" name="start_date" value="{{ $start_date }}">
          <input type="hidden" name="end_date" value="{{ $end_date }}">
        @endif

        <input type="hidden" name="template_for" value="{{ $notification_template['template_for'] }}">

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="send_notification_btn">@lang('lang_v1.send')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
      </div>
    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script type="text/javascript">
// Fix for not updating textarea value on modal
  // CKEDITOR.on('instanceReady', function(){
  //    $.each( CKEDITOR.instances, function(instance) {
  //     CKEDITOR.instances[instance].on("change", function(e) {
  //         for ( instance in CKEDITOR.instances )
  //         CKEDITOR.instances[instance].updateElement();
  //     });
  //    });
  // });

  if (_.isNull(tinyMCE.activeEditor)) {
        tinymce.init({
            selector: 'textarea#email_body',
        });
    }
    
  $(document).ready(function(){
    //initialize iCheck
    $('input[type="checkbox"].input-icheck, input[type="radio"].input-icheck').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue'
    });
  });

  $(document).on('ifChanged', '.notification_type', function(){
    var notification_type = $(this).val();
    console.log(notification_type);
    if (notification_type == 'email') {
      if ($(this).is(':checked')) {
        $('div#email_div').removeClass('hide');
      } else {
        $('div#email_div').addClass('hide');
      }
    } else if(notification_type == 'sms'){
      if ($(this).is(':checked')) {
        $('div#sms_div').removeClass('hide');
      } else {
        $('div#sms_div').addClass('hide');
      }
    } else if(notification_type == 'whatsapp'){
      if ($(this).is(':checked')) {
        $('div#whatsapp_div').removeClass('hide');
      } else {
        $('div#whatsapp_div').addClass('hide');
      }
    }
  });
  $('#send_notification_form').submit(function(e){
    e.preventDefault();
    tinyMCE.triggerSave();
    var data = $(this).serialize();
    var btn = $('#send_notification_btn');
    btn.text("@lang('lang_v1.sending')...");
    btn.attr('disabled', 'disabled');
    $.ajax({
      method: "POST",
      url: $(this).attr("action"),
      dataType: "json",
      data: $(this).serialize(),
      beforeSend: function(xhr) {
          __disable_submit_button(btn);
      },
      success: function(result){
        if(result.success == true){
          if (result.whatsapp_link) {
            window.open(result.whatsapp_link);
          }
          $('div.view_modal').modal('hide');
          toastr.success(result.msg);
        } else {
          toastr.error(result.msg);
        }
        $('#send_notification_btn').text("@lang('lang_v1.send')");
        $('#send_notification_btn').removeAttr('disabled');
      }
    });
  });
</script>
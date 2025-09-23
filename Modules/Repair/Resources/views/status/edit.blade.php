<div class="modal-dialog" role="document">
  <div class="modal-content">

    {{-- <form action="{{ action('\\Modules\\Repair\\Http\\Controllers\\RepairStatusController@update', [$status->id]) }}" method="post" id="status_form"> --}}
        <form action="{{ route('repairStatus-update', [$status->id]) }}" method="post" id="status_form">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'repair::lang.edit_status' )</h4>
      </div>

      <div class="modal-body">
          <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      <label for="name">{{ __( 'repair::lang.status_name' ) . ':*' }}</label>
                      <input type="text" name="name" id="name" class="form-control" required placeholder="{{ __( 'repair::lang.status_name' ) }}" value="{{ old('name', $status->name) }}">
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label for="color">{{ __( 'repair::lang.color' ) . ':' }}</label>
                      <input type="text" name="color" id="color" class="form-control" placeholder="{{ __( 'repair::lang.color' ) }}" value="{{ old('color', $status->color) }}">
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      <label for="sort_order">{{ __( 'repair::lang.sort_order' ) . ':' }}</label>
                      <input type="number" name="sort_order" id="sort_order" class="form-control" placeholder="{{ __( 'repair::lang.sort_order' ) }}" value="{{ old('sort_order', $status->sort_order) }}">
                  </div>
              </div>
              <div class="col-md-6 mt-15">
                  <div class="form-group">
                      <div class="checkbox">
                          <label>
                              <input type="checkbox" name="is_completed_status" value="1" id="is_completed_status" {{ old('is_completed_status', $status->is_completed_status) ? 'checked' : '' }}> @lang('repair::lang.mark_this_status_as_complete')
                          </label>
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="checkbox">
                          <label>
                              <input type="checkbox" name="return_product" value="1" id="return_product" {{ old('return_product', $status->return_product) ? 'checked' : '' }}> عمل مرتجع للمنتج 
                          </label>
                      </div>
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      <label for="sms_template">{{ __( 'repair::lang.sms_template' ) . ':' }}</label>
                      <textarea name="sms_template" id="sms_template" class="form-control" placeholder="{{ __( 'repair::lang.sms_template' ) }}" rows="4">{{ old('sms_template', $status->sms_template) }}</textarea>
                  </div>
              </div>
              <div class="col-md-12">
                  <div class="form-group">
                      <label for="email_subject">{{ __( 'lang_v1.email_subject' ) . ':' }}</label>
                      <input type="text" name="email_subject" id="email_subject" class="form-control" placeholder="{{ __( 'lang_v1.email_subject' ) }}" value="{{ old('email_subject', $status->email_subject) }}">
                  </div>
              </div>
              <div class="col-md-12">
                  <div class="form-group">
                      <label for="email_body">{{ __( 'lang_v1.email_body' ) . ':' }}</label>
                      <textarea name="email_body" id="email_body" class="form-control" placeholder="{{ __( 'lang_v1.email_body' ) }}" rows="5">{{ old('email_body', $status->email_body) }}</textarea>
                      <p class="help-block">
                          <label>{{$status_template_tags['help_text']}}:</label><br>
                          {{implode(', ', $status_template_tags['tags'])}}
                      </p>
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
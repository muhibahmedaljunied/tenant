<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ action('SalesCommissionAgentController@update', [$user->id]) }}" method="POST" id="sale_commission_agent_form">
      @csrf
      @method('PUT')

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'lang_v1.edit_sales_commission_agent' )</h4>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-2">
          <div class="form-group">
            <label for="surname">{{ __( 'business.prefix' ) . ':' }}</label>
            <input type="text" name="surname" id="surname" class="form-control" placeholder="{{ __( 'business.prefix_placeholder' ) }}" value="{{ $user->surname }}">
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            <label for="first_name">{{ __( 'business.first_name' ) . ':*' }}</label>
            <input type="text" name="first_name" id="first_name" class="form-control" required placeholder="{{ __( 'business.first_name' ) }}" value="{{ $user->first_name }}">
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            <label for="last_name">{{ __( 'business.last_name' ) . ':' }}</label>
            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="{{ __( 'business.last_name' ) }}" value="{{ $user->last_name }}">
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="email">{{ __( 'business.email' ) . ':' }}</label>
            <input type="text" name="email" id="email" class="form-control" placeholder="{{ __( 'business.email' ) }}" value="{{ $user->email }}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="contact_no">{{ __( 'lang_v1.contact_no' ) . ':' }}</label>
            <input type="text" name="contact_no" id="contact_no" class="form-control" placeholder="{{ __( 'lang_v1.contact_no' ) }}" value="{{ $user->contact_no }}">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="address">{{ __( 'business.address' ) . ':' }}</label>
            <textarea name="address" id="address" class="form-control" placeholder="{{ __( 'business.address') }}" rows="3">{{ $user->address }}</textarea>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="cmmsn_percent">{{ __( 'lang_v1.cmmsn_percent' ) . ':' }}</label>
            <input type="text" name="cmmsn_percent" id="cmmsn_percent" class="form-control input_number" placeholder="{{ __( 'lang_v1.cmmsn_percent' ) }}" required value="{{ @num_format($user->cmmsn_percent) }}">
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
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <form action="{{ action('\\Modules\\Essentials\\Http\\Controllers\\EssentialsHolidayController@store') }}" method="post" id="add_holiday_form">
      @csrf
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'essentials::lang.add_holiday' )</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="form-group col-md-12">
            <label for="name">{{ __( 'lang_v1.name' ) . ':*' }}</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="{{ __( 'lang_v1.name' ) }}" required>
          </div>
          <div class="form-group col-md-6">
            <label for="start_date">{{ __( 'essentials::lang.start_date' ) . ':*' }}</label>
            <div class="input-group data">
              <input type="text" name="start_date" id="start_date" class="form-control" placeholder="{{ __( 'essentials::lang.start_date' ) }}" readonly>
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
          <div class="form-group col-md-6">
            <label for="end_date">{{ __( 'essentials::lang.end_date' ) . ':*' }}</label>
            <div class="input-group data">
              <input type="text" name="end_date" id="end_date" class="form-control" placeholder="{{ __( 'essentials::lang.end_date' ) }}" readonly required>
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
          <div class="form-group col-md-12">
            <label for="location_id">{{ __( 'business.business_location' ) . ':' }}</label>
            <select name="location_id" id="location_id" class="form-control select2">
              <option value="">{{ __( 'lang_v1.all' ) }}</option>
              @foreach($locations as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-12">
            <label for="note">{{ __( 'brand.note' ) . ':' }}</label>
            <textarea name="note" id="note" class="form-control" placeholder="{{ __( 'brand.note' ) }}" rows="3"></textarea>
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
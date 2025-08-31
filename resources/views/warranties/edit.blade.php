<div class="modal-dialog" role="document">
  <div class="modal-content">
    <form action="{{ action('WarrantyController@update', [$warranty->id]) }}" method="post" id="warranty_form">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'lang_v1.edit_warranty' )</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="name">{{ __( 'lang_v1.name' ) }}:*</label>
          <input type="text" name="name" id="name" class="form-control" required placeholder="{{ __( 'lang_v1.name' ) }}" value="{{ $warranty->name }}">
        </div>
        <div class="form-group">
          <label for="description">{{ __( 'lang_v1.description' ) }}:</label>
          <textarea name="description" id="description" class="form-control" placeholder="{{ __( 'lang_v1.description' ) }}" rows="3">{{ $warranty->description }}</textarea>
        </div>
        <strong><label for="duration">{{ __( 'lang_v1.duration' ) }}:</label>*</strong>
        <div class="form-group">
          <input type="number" name="duration" id="duration" class="form-control width-40 pull-left" placeholder="{{ __( 'lang_v1.duration' ) }}" required value="{{ $warranty->duration }}">
          <select name="duration_type" id="duration_type" class="form-control width-60 pull-left" required>
            <option value="">{{ __('messages.please_select') }}</option>
            <option value="days" {{ $warranty->duration_type == 'days' ? 'selected' : '' }}>{{ __('lang_v1.days') }}</option>
            <option value="months" {{ $warranty->duration_type == 'months' ? 'selected' : '' }}>{{ __('lang_v1.months') }}</option>
            <option value="years" {{ $warranty->duration_type == 'years' ? 'selected' : '' }}>{{ __('lang_v1.years') }}</option>
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
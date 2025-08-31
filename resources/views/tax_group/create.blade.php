<div class="modal-dialog" role="document">
  <div class="modal-content">
    <form action="{{ action('GroupTaxController@store') }}" method="post" id="tax_group_add_form">
      @csrf
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'tax_rate.add_tax_group' )</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="name">{{ __( 'tax_rate.name' ) }}:*</label>
          <input type="text" name="name" id="name" class="form-control" required placeholder="{{ __( 'tax_rate.name' ) }}">
        </div>
        <div class="form-group">
          <label for="taxes">{{ __( 'tax_rate.sub_taxes' ) }}:*</label>
          <select name="taxes[]" id="taxes" class="form-control select2" required multiple>
            @foreach($taxes as $key => $value)
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
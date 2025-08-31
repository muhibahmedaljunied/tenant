<div class="modal-dialog" role="document">
  <div class="modal-content">
    <form action="{{ action('SellingPriceGroupController@update', [$spg->id]) }}" method="post" id="selling_price_group_form">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'lang_v1.edit_selling_price_group' )</h4>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <label for="name">@lang('lang_v1.name') :*</label>
          <input type="text" name="name" id="name" class="form-control" required placeholder="@lang('lang_v1.name')" value="{{ $spg->name }}">
        </div>

        <div class="form-group">
          <label for="description">@lang('lang_v1.description') :</label>
          <textarea name="description" id="description" class="form-control" placeholder="@lang('lang_v1.description')" rows="3">{{ $spg->description }}</textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>
    </form>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
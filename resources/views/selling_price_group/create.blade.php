<div class="modal-dialog" role="document">
  <div class="modal-content">
    <form action="{{ action('SellingPriceGroupController@store') }}" method="post" id="selling_price_group_form">
      @csrf
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'lang_v1.add_selling_price_group' )</h4>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <label for="name">@lang('lang_v1.name') :*</label>
          <input type="text" name="name" id="name" class="form-control" required placeholder="@lang('lang_v1.name')">
        </div>


        {{--<div class="form-group">
          <label for="app_currency_id">@lang('business.currency') :</label>
          <div class="input-group">
                <span class="input-group-addon">
                    <i class="fas fa-money-bill-alt"></i>
                </span>
            <select name="app_currency_id" id="app_currency_id" class="form-control select2" required placeholder="@lang('business.currency_placeholder')">
              @foreach($currencies as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
              @endforeach
            </select>
          </div>
        </div>--}} 

        <div class="form-group">
          <label for="description">@lang('lang_v1.description') :</label>
          <textarea name="description" id="description" class="form-control" placeholder="@lang('lang_v1.description')" rows="3"></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>
    </form>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
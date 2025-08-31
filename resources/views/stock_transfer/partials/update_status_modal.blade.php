<div class="modal fade" id="update_stock_transfer_status_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

      <form action="#" method="post" id="update_stock_transfer_status_form">
    @csrf
    <!-- Your form fields go here -->



        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">@lang( 'lang_v1.update_status' ) @show_tooltip(__('lang_v1.completed_status_help'))</h4> 
        </div>
<div class="modal-body">
    <div class="form-group">
        <label for="update_status">{{ __('sale.status') }}:*</label>
        <select id="update_status" name="status" class="form-control select2" style="width:100%" required>
            <option value="">{{ __('messages.please_select') }}</option>
            @foreach($statuses as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
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
</div>
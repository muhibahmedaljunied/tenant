<div class="modal fade" id="update_purchase_status_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form action="{{ action('PurchaseController@updateStatus') }}" method="POST" id="update_purchase_status_form">
                @csrf
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">@lang('lang_v1.update_status')</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="status">@lang('purchase.purchase_status'):</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="">@lang('messages.please_select')</option>
                            @foreach ($orderStatuses as $key => $status)
                                <option value="{{ $key }}">{{ $status }}</option>
                            @endforeach
                        </select>

                        <input type="hidden" name="purchase_id" id="purchase_id">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

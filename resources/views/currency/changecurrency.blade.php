<div class="modal-dialog" role="document">
<div class="modal-content">

    <form action="{{ action('SellingPriceGroupController@savecurrency') }}" method="POST" id="savecurrency">
        @csrf

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">تغيير العملة :</h4>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label for="app_currency_id">{{ __('business.currency') }}:</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fas fa-money-bill-alt"></i>
                </span>
                <select name="app_currency_id" id="app_currency_id" class="form-control select2" required>
                    <option value="">{{ __('business.currency_placeholder') }}</option>
                    @foreach($currencies as $id => $name)
                        <option value="{{ $id }}" {{ (old('app_currency_id', $settings["app_currency_id"]) == $id) ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" >@lang( 'messages.save' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    </form>

</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script>

</script>
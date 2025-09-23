<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form action="{{ action('StoreController@update', [$store->id]) }}" method="POST" id="store_edit_form">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang('store.edit_store')</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="actual_name">{{ __('store.name') }}:*</label>
                        <input type="text" name="actual_name" value="{{ $store->name }}" class="form-control"
                            required placeholder="{{ __('store.name') }}">
                    </div>

                    <div class="form-group col-sm-12">
                        <label for="short_name">{{ __('store.short_name') }}:*</label>
                        <input type="text" name="short_name" value="{{ $store->code }}" class="form-control"
                            required placeholder="{{ __('store.short_name') }}">
                    </div>

                    <div class="form-group col-sm-12">
                        <label for="allow_decimal">{{ __('store.business_locations') }}:*</label>
                        <select class="form-control">
                            @foreach ($locations as $key => $value)
                                <option value="{{ $key }}" {{ $store->location_id == $key ? 'selected' : '' }}>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>





                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
            </div>

        </form>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

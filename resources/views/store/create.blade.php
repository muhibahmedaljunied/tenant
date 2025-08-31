<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form action="{{ action('StoreController@store') }}" method="POST"
            id="{{ $quick_add ? 'quick_add_store_form' : 'store_add_form' }}">
            @csrf

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang('store.add_store')</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="actual_name">{{ __('store.name') }}:*</label>
                        <input type="text" name="actual_name" class="form-control" required
                            placeholder="{{ __('store.name') }}">
                    </div>

                    <div class="form-group col-sm-12">
                        <label for="short_name">{{ __('store.short_name') }}:*</label>
                        <input type="text" name="short_name" class="form-control" required
                            placeholder="{{ __('store.short_name') }}">
                    </div>


                    <div class="form-group col-sm-12">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-map-marker"></i>
                                </span>
                                <select name="select_location_id" id="select_location_id" class="form-control" required
                                    autofocus>
                                    <option value="">{{ __('lang_v1.select_location') }}</option>
                                    @foreach ($business_locations as $key => $value)
                                        <option value="{{ $key }}"
                                            @if (old('select_location_id') == $key) selected @endif>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                                <span class="input-group-addon">
                                    @show_tooltip(__('tooltip.sale_location'))
                                </span>
                            </div>
                        </div>
                    </div>
                


                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
            </div>

        </form>

    </div>
</div>



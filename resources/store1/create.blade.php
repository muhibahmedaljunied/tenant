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

             

                    <!-- Business Location -->

                    <div class="form-group col-sm-12">
           
                            <label for="location_id">@lang('purchase.business_location'):</label>
                            <select name="location_id" id="location_id" class="form-control select2"
                                style="width:100%;">
                       <option value="">{{ __('messages.please_select') }}</option>
                                @foreach ($business_locations as $key => $location)
                                    <option value="{{ $key }}">{{ $location }}</option>
                                @endforeach
                            </select>
                    
                    </div>
                    <!-- Store -->


                    <div class="form-group col-sm-12">

                        <label for="store_id">@lang('store.store'):</label>
                        <select name="store_id" id="store_id" class="form-control select2" style="width:100%;">
                   <option value="">{{ __('messages.please_select') }}</option>
                            @foreach ($stores as $key => $store)
                                <option value="{{ $key }}">{{ $store }}</option>
                            @endforeach
                        </select>

                    </div>


                  

                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
            </div>

        </form>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

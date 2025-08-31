<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form action="{{ action('\\Modules\\Assets\\Http\\Controllers\\AssetsController@update', $asset->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang( 'assets::lang.assets_edit' )</h4>
            </div>

            <div class="modal-body">

                <div class="row" style="margin-left: 0px;margin-right: 0px">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="assetcode">{{ __( 'assets::lang.asset_code' ) . ':*' }}</label>
                            <input type="text" name="assetcode" id="assetcode" class="form-control" required value="{{ old('assetcode', $asset->assetcode) }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="quantity">{{ __( 'assets::lang.asset_quantity' ) . ':*' }}</label>
                            <input type="text" name="quantity" id="quantity" class="form-control" required value="{{ old('quantity', $asset->quantity) }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">{{ __( 'assets::lang.asset_description' ) . ':' }}</label>
                    <input type="text" name="description" id="description" class="form-control" value="{{ old('description', $asset->description) }}">
                </div>

                <div class="form-group">
                    <label for="location_id">{{ __( 'assets::lang.asset_location' ) . ':' }}</label>
                    <select name="location_id" id="location_id" class="form-control">
                        @foreach($business_locations as $key => $value)
                            <option value="{{ $key }}" {{ old('location_id', $asset->location_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="purchasedate">{{ __('assets::lang.asset_purchasedate') . ':' }}</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" name="purchasedate" id="purchasedate" class="form-control date-picker" readonly value="{{ old('purchasedate', $asset->purchasedate) }}">
                    </div>
                </div>

                <div class="row" style="margin-right: 0px;margin-left: 0px">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="type">{{__('assets::lang.asset_type')}}</label>
                            <div class="input-group">
                                <select id="type" name="type" class="form-control">
                                    <option value="-1" {{ old('type', $asset->type) == -1 ? 'selected' : '' }}>@lang('assets::lang.asset_type_consumed')</option>
                                    <option value="1" {{ old('type', $asset->type) == 1 ? 'selected' : '' }}>@lang('assets::lang.asset_type_up')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="consume_rate">{{ __( 'assets::lang.asset_consume_rate' ) . ' : ' }}</label>
                            <input type="text" name="consume_rate" id="consume_rate" class="form-control" style="max-width:90%;" value="{{ old('consume_rate', $asset->consume_rate) }}">
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-right: 0px">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="price">{{ __( 'assets::lang.asset_price' ) . ':' }}</label>
                            <input type="text" name="price" id="price" class="form-control" value="{{ old('price', $asset->price) }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="curentprice">{{ __( 'assets::lang.asset_curentprice' ) . ':' }}</label>
                            <input type="text" name="curentprice" id="curentprice" class="form-control" value="{{ old('curentprice', $asset->curentprice) }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="changedate">{{ __('assets::lang.asset_changedate') . ':' }}</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" name="changedate" id="changedate" class="form-control date-picker" readonly value="{{ old('changedate', $asset->changedate) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">{{__('assets::lang.asset_status')}}</label>
                    <div class="form-group" style="max-width: 70%">
                        <select id="status" name="status" class="form-control" style="height: 40px">
                            <option value="0" {{ old('status', $asset->status) == 0 ? 'selected' : '' }}>@lang('assets::lang.asset_Existing')</option>
                            <option value="1" {{ old('status', $asset->status) == 1 ? 'selected' : '' }}>@lang('assets::lang.asset_consumed')</option>
                            <option value="2" {{ old('status', $asset->status) == 2 ? 'selected' : '' }}>@lang('assets::lang.asset_sold')</option>
                            <option value="3" {{ old('status', $asset->status) == 3 ? 'selected' : '' }}>@lang('assets::lang.asset_missing')</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">{{ __( 'assets::lang.asset_notes' ) . ':' }}</label>
                    <input type="text" name="notes" id="notes" class="form-control" value="{{ old('notes', $asset->notes) }}">
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
            </div>

        </form>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script>
    $('.date-picker').datepicker({
        autoclose: true,
        endDate: 'today',
        format:'yyyy-m-d',
    });
</script>
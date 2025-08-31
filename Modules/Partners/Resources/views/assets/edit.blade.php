<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ action('\\Modules\\Partners\\Http\\Controllers\\AssetsController@update', $asset->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang( 'partners::lang.asset_add' )</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-left: 0px;margin-right: 0px">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="assetcode">{{ __( 'partners::lang.asset_code' ) . ':*' }}</label>
                            <input type="text" name="assetcode" id="assetcode" value="{{ $asset->assetcode }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="quantity">{{ __( 'partners::lang.asset_quantity' ) . ':*' }}</label>
                            <input type="text" name="quantity" id="quantity" value="{{ $asset->quantity }}" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">{{ __( 'partners::lang.asset_description' ) . ':' }}</label>
                    <input type="text" name="description" id="description" value="{{ $asset->description }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="location_id">{{ __( 'partners::lang.asset_location' ) . ':' }}</label>
                    <select name="location_id" id="location_id" class="form-control">
                        @foreach($business_locations as $key => $value)
                            <option value="{{ $key }}" {{ $asset->location_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="purchasedate">{{ __('partners::lang.asset_purchasedate') . ':' }}</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" name="purchasedate" id="purchasedate" value="{{ $asset->purchasedate }}" class="form-control date-picker" readonly>
                    </div>
                </div>
                <div class="row" style="margin-right: 0px;margin-left: 0px">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="type">{{ __('partners::lang.asset_type') }}</label>
                            <div class="input-group">
                                <select id="type" name="type" class="form-control">
                                    <option value="-1" {{ $asset->type == -1 ? 'selected' : '' }}>@lang('partners::lang.asset_type_consumed')</option>
                                    <option value="1" {{ $asset->type == 1 ? 'selected' : '' }}>@lang('partners::lang.asset_type_up')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="consume_rate">{{ __( 'partners::lang.asset_consume_rate' ) . ' : ' }}</label>
                            <input type="text" name="consume_rate" id="consume_rate" value="{{ $asset->consume_rate }}" class="form-control" style="max-width:90%;">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-right: 0px">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="price">{{ __( 'partners::lang.asset_price' ) . ':' }}</label>
                            <input type="text" name="price" id="price" value="{{ $asset->price }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="curentprice">{{ __( 'partners::lang.asset_curentprice' ) . ':' }}</label>
                            <input type="text" name="curentprice" id="curentprice" value="{{ $asset->curentprice }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="changedate">{{ __('partners::lang.asset_changedate') . ':' }}</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" name="changedate" id="changedate" value="{{ $asset->changedate }}" class="form-control date-picker" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status">{{ __('partners::lang.asset_status') }}</label>
                    <div class="input-group">
                        <select id="status" name="status" class="form-control">
                            <option value="0" {{ $asset->status == 0 ? 'selected' : '' }}>@lang('partners::lang.asset_Existing')</option>
                            <option value="1" {{ $asset->status == 1 ? 'selected' : '' }}>@lang('partners::lang.asset_consumed')</option>
                            <option value="2" {{ $asset->status == 2 ? 'selected' : '' }}>@lang('partners::lang.asset_sold')</option>
                            <option value="3" {{ $asset->status == 3 ? 'selected' : '' }}>@lang('partners::lang.asset_missing')</option>
                        </select>
                    </div>
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
<div class="row">
	<div class="col-md-4">
	    <div class="form-group">
	        <label for="sr_location_id">{{ __('purchase.business_location') }}:</label>

	        <select name="sr_location_id" id="sr_location_id" class="form-control select2" style="width:100%">
	            @foreach($business_locations as $id => $name)
	                <option value="{{ $id }}" {{ old('sr_location_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
	            @endforeach
	        </select>
	    </div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
            <table class="table table-bordered table-striped" 
            id="supplier_stock_report_table" width="100%">
                <thead>
                    <tr>
                        <th>@lang('sale.product')</th>
                        <th>@lang('product.sku')</th>
                        <th>@lang('purchase.purchase_quantity')</th>
                        <th>@lang('lang_v1.total_sold')</th>
                        <th>@lang('lang_v1.total_returned')</th>
                        <th>@lang('report.current_stock')</th>
                        <th>@lang('lang_v1.total_stock_price')</th>
                    </tr>
                </thead>
            </table>
        </div>
	</div>
</div>
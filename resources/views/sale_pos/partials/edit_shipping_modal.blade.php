<!-- Edit Shipping Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="posShippingModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">@lang('sale.shipping')</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
				        <div class="form-group">
				            <label for="shipping_details_modal">{{ __('sale.shipping_details') . ':*' }}</label>
				            <textarea name="shipping_details_modal" id="shipping_details_modal" class="form-control" placeholder="{{ __('sale.shipping_details') }}" required rows="4">{{ !empty($transaction->shipping_details) ? $transaction->shipping_details : '' }}</textarea>
				        </div>
				    </div>

				    <div class="col-md-6">
				        <div class="form-group">
				            <label for="shipping_address_modal">{{ __('lang_v1.shipping_address') . ':' }}</label>
				            <textarea name="shipping_address_modal" id="shipping_address_modal" class="form-control" placeholder="{{ __('lang_v1.shipping_address') }}" rows="4">{{ !empty($transaction->shipping_address) ? $transaction->shipping_address : '' }}</textarea>
				        </div>
				    </div>

				    <div class="col-md-6">
				        <div class="form-group">
				            <label for="shipping_charges_modal">{{ __('sale.shipping_charges') . ':*' }}</label>
				            <div class="input-group">
				                <span class="input-group-addon">
				                    <i class="fa fa-info"></i>
				                </span>
				                <input type="text" name="shipping_charges_modal" id="shipping_charges_modal" class="form-control input_number" placeholder="{{ __('sale.shipping_charges') }}" value="{{ !empty($transaction->shipping_charges) ? @num_format($transaction->shipping_charges) : 0 }}">
				            </div>
				        </div>
				    </div>

				    <div class="col-md-6">
				        <div class="form-group">
				            <label for="shipping_status_modal">{{ __('lang_v1.shipping_status') . ':' }}</label>
				            <select name="shipping_status_modal" id="shipping_status_modal" class="form-control">
				                <option value="">{{ __('messages.please_select') }}</option>
				                @foreach($shipping_statuses as $key => $value)
				                    <option value="{{ $key }}" @if(!empty($transaction->shipping_status) && $transaction->shipping_status == $key) selected @endif>{{ $value }}</option>
				                @endforeach
				            </select>
				        </div>
				    </div>

				    <div class="col-md-6">
				        <div class="form-group">
				            <label for="delivered_to_modal">{{ __('lang_v1.delivered_to') . ':' }}</label>
				            <input type="text" name="delivered_to_modal" id="delivered_to_modal" class="form-control" placeholder="{{ __('lang_v1.delivered_to') }}" value="{{ !empty($transaction->delivered_to) ? $transaction->delivered_to : '' }}">
				        </div>
				    </div>
				    @php
				        $custom_labels = json_decode(session('business.custom_labels'), true);

				        $shipping_custom_label_1 = !empty($custom_labels['shipping']['custom_field_1']) ? $custom_labels['shipping']['custom_field_1'] : '';

				        $is_shipping_custom_field_1_required = !empty($custom_labels['shipping']['is_custom_field_1_required']) && $custom_labels['shipping']['is_custom_field_1_required'] == 1 ? true : false;

				        $shipping_custom_label_2 = !empty($custom_labels['shipping']['custom_field_2']) ? $custom_labels['shipping']['custom_field_2'] : '';

				        $is_shipping_custom_field_2_required = !empty($custom_labels['shipping']['is_custom_field_2_required']) && $custom_labels['shipping']['is_custom_field_2_required'] == 1 ? true : false;

				        $shipping_custom_label_3 = !empty($custom_labels['shipping']['custom_field_3']) ? $custom_labels['shipping']['custom_field_3'] : '';
				        $is_shipping_custom_field_3_required = !empty($custom_labels['shipping']['is_custom_field_3_required']) && $custom_labels['shipping']['is_custom_field_3_required'] == 1 ? true : false;

				        $shipping_custom_label_4 = !empty($custom_labels['shipping']['custom_field_4']) ? $custom_labels['shipping']['custom_field_4'] : '';
				        $is_shipping_custom_field_4_required = !empty($custom_labels['shipping']['is_custom_field_4_required']) && $custom_labels['shipping']['is_custom_field_4_required'] == 1 ? true : false;

				        $shipping_custom_label_5 = !empty($custom_labels['shipping']['custom_field_5']) ? $custom_labels['shipping']['custom_field_5'] : '';
				        $is_shipping_custom_field_5_required = !empty($custom_labels['shipping']['is_custom_field_5_required']) && $custom_labels['shipping']['is_custom_field_5_required'] == 1 ? true : false;
			        @endphp

			        @if(!empty($shipping_custom_label_1))
			        	@php
			        		$label_1 = $shipping_custom_label_1 . ':';
			        		if($is_shipping_custom_field_1_required) {
			        			$label_1 .= '*';
			        		}
			        	@endphp

			        	<div class="col-md-6">
				        <div class="form-group">
				            <label for="shipping_custom_field_1">{{ $label_1 }}</label>
				            <input type="text" name="shipping_custom_field_1" id="shipping_custom_field_1" class="form-control" placeholder="{{ $shipping_custom_label_1 }}" value="{{ !empty($transaction->shipping_custom_field_1) ? $transaction->shipping_custom_field_1 : '' }}" @if($is_shipping_custom_field_1_required) required @endif>
				        </div>
				    </div>
			        @endif
			        @if(!empty($shipping_custom_label_2))
			        	@php
			        		$label_2 = $shipping_custom_label_2 . ':';
			        		if($is_shipping_custom_field_2_required) {
			        			$label_2 .= '*';
			        		}
			        	@endphp

			        	<div class="col-md-6">
				        <div class="form-group">
				            <label for="shipping_custom_field_2">{{ $label_2 }}</label>
				            <input type="text" name="shipping_custom_field_2" id="shipping_custom_field_2" class="form-control" placeholder="{{ $shipping_custom_label_2 }}" value="{{ !empty($transaction->shipping_custom_field_2) ? $transaction->shipping_custom_field_2 : '' }}" @if($is_shipping_custom_field_2_required) required @endif>
				        </div>
				    </div>
			        @endif
			        @if(!empty($shipping_custom_label_3))
			        	@php
			        		$label_3 = $shipping_custom_label_3 . ':';
			        		if($is_shipping_custom_field_3_required) {
			        			$label_3 .= '*';
			        		}
			        	@endphp

			        	<div class="col-md-6">
				        <div class="form-group">
				            <label for="shipping_custom_field_3">{{ $label_3 }}</label>
				            <input type="text" name="shipping_custom_field_3" id="shipping_custom_field_3" class="form-control" placeholder="{{ $shipping_custom_label_3 }}" value="{{ !empty($transaction->shipping_custom_field_3) ? $transaction->shipping_custom_field_3 : '' }}" @if($is_shipping_custom_field_3_required) required @endif>
				        </div>
				    </div>
			        @endif
			        @if(!empty($shipping_custom_label_4))
			        	@php
			        		$label_4 = $shipping_custom_label_4 . ':';
			        		if($is_shipping_custom_field_4_required) {
			        			$label_4 .= '*';
			        		}
			        	@endphp

			        	<div class="col-md-6">
				        <div class="form-group">
				            <label for="shipping_custom_field_4">{{ $label_4 }}</label>
				            <input type="text" name="shipping_custom_field_4" id="shipping_custom_field_4" class="form-control" placeholder="{{ $shipping_custom_label_4 }}" value="{{ !empty($transaction->shipping_custom_field_4) ? $transaction->shipping_custom_field_4 : '' }}" @if($is_shipping_custom_field_4_required) required @endif>
				        </div>
				    </div>
			        @endif
			        @if(!empty($shipping_custom_label_5))
			        	@php
			        		$label_5 = $shipping_custom_label_5 . ':';
			        		if($is_shipping_custom_field_5_required) {
			        			$label_5 .= '*';
			        		}
			        	@endphp

			        	<div class="col-md-6">
				        <div class="form-group">
				            <label for="shipping_custom_field_5">{{ $label_5 }}</label>
				            <input type="text" name="shipping_custom_field_5" id="shipping_custom_field_5" class="form-control" placeholder="{{ $shipping_custom_label_5 }}" value="{{ !empty($transaction->shipping_custom_field_5) ? $transaction->shipping_custom_field_5 : '' }}" @if($is_shipping_custom_field_5_required) required @endif>
				        </div>
				    </div>
			        @endif
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="posShippingModalUpdate">@lang('messages.update')</button>
			    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.cancel')</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
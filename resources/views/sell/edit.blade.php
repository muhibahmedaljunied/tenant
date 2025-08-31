@extends('layouts.app')

@section('title', __('sale.edit_sale'))

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('sale.edit_sale') <small>(@lang('sale.invoice_no'): <span class="text-success">#{{$transaction->invoice_no}})</span></small></h1>
</section>
<!-- Main content -->
<section class="content">
<input type="hidden" id="amount_rounding_method" value="{{$pos_settings['amount_rounding_method'] ?? ''}}">
<input type="hidden" id="amount_rounding_method" value="{{$pos_settings['amount_rounding_method'] ?? 'none'}}">
@if(!empty($pos_settings['allow_overselling']))
	<input type="hidden" id="is_overselling_allowed">
@endif
@if(session('business.enable_rp') == 1)
    <input type="hidden" id="reward_point_enabled">
@endif
@php
	$custom_labels = json_decode(session('business.custom_labels'), true);
@endphp
<input type="hidden" id="item_addition_method" value="{{$business_details->item_addition_method}}">
<form action="{{ action('SellPosController@update', $transaction->id) }}" method="POST" id="edit_sell_form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="location_id" id="location_id" value="{{ $transaction->location_id }}" data-receipt_printer_type="{{ !empty($location_printer_type) ? $location_printer_type : 'browser' }}">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            @component('components.widget', ['class' => 'box-solid'])
                @if(!empty($transaction->selling_price_group_id))
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fas fa-money-bill-alt"></i>
                                </span>
                                <input type="hidden" name="price_group" id="price_group" value="{{ $transaction->selling_price_group_id }}">
                                <input type="text" name="price_group_text" value="{{ $transaction->price_group->name }}" class="form-control" readonly>
                                <span class="input-group-addon">
                                    @show_tooltip(__('lang_v1.price_group_help_text'))
                                </span> 
                            </div>
                        </div>
                    </div>
                @endif
                @if(in_array('types_of_service', $enabled_modules) && !empty($transaction->types_of_service))
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fas fa-external-link-square-alt text-primary service_modal_btn"></i>
                                </span>
                                <input type="text" name="types_of_service_text" value="{{ $transaction->types_of_service->name }}" class="form-control" readonly>
                                <input type="hidden" name="types_of_service_id" id="types_of_service_id" value="{{ $transaction->types_of_service_id }}">
                                <span class="input-group-addon">
                                    @show_tooltip(__('lang_v1.types_of_service_help'))
                                </span> 
                            </div>
                            <small><p class="help-block @if(empty($transaction->selling_price_group_id)) hide @endif" id="price_group_text">@lang('lang_v1.price_group'): <span>@if(!empty($transaction->selling_price_group_id)){{$transaction->price_group->name}}@endif</span></p></small>
                        </div>
                    </div>
                    <div class="modal fade types_of_service_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
                        @if(!empty($transaction->types_of_service))
                        @include('types_of_service.pos_form_modal', ['types_of_service' => $transaction->types_of_service])
                        @endif
                    </div>
                @endif
                @if(in_array('subscription', $enabled_modules))
                    <div class="col-md-4 pull-right col-sm-6">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" name="is_recurring" id="is_recurring" class="input-icheck" value="1" {{ $transaction->is_recurring ? 'checked' : '' }}> @lang('lang_v1.subscribe')?
                            </label><button type="button" data-toggle="modal" data-target="#recurringInvoiceModal" class="btn btn-link"><i class="fa fa-external-link"></i></button>@show_tooltip(__('lang_v1.recurring_invoice_help'))
                        </div>
                    </div>
                @endif
                <div class="clearfix"></div>
                <div class="@if(!empty($commission_agent)) col-sm-3 @else col-sm-4 @endif">
                    <div class="form-group">
                        <label for="contact_id">@lang('contact.customer') :*</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            <input type="hidden" id="default_customer_id" value="{{ $transaction->contact->id }}" >
                            <input type="hidden" id="default_customer_name" value="{{ $transaction->contact->name }}" >
                            <select name="contact_id" class="form-control mousetrap" id="customer_id" required placeholder="Enter Customer name / phone">
                                <option value="">Enter Customer name / phone</option>
                            </select>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default bg-white btn-flat add_new_customer" data-name=""><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                            </span>
                        </div>
                    </div>
                    <small>
                        <strong>
                            @lang('lang_v1.billing_address'):
                        </strong>
                        <div id="billing_address_div">
                            {!! $transaction->contact->contact_address ?? '' !!}
                        </div>
                        <br>
                        <strong>
                            @lang('lang_v1.shipping_address'):
                        </strong>
                        <div id="shipping_address_div">
                            {!! $transaction->contact->supplier_business_name ?? '' !!}, <br>
                            {!! $transaction->contact->name ?? '' !!}, <br>
                            {!!$transaction->contact->shipping_address ?? '' !!}
                        </div>                        
                    </small>
                </div>

                <div class="col-md-3">
		          <div class="form-group">
		            <div class="multi-input">
		              <label for="pay_term_number">@lang('contact.pay_term'):</label> @show_tooltip(__('tooltip.pay_term'))
		              <br/>
		              <input type="number" name="pay_term_number" value="{{ $transaction->pay_term_number }}" class="form-control width-40 pull-left" placeholder="@lang('contact.pay_term')">

		              <select name="pay_term_type" class="form-control width-60 pull-left" placeholder="@lang('messages.please_select')">
		              	<option value="">@lang('messages.please_select')</option>
		              	<option value="months" @if($transaction->pay_term_type == 'months') selected @endif>@lang('lang_v1.months')</option>
		              	<option value="days" @if($transaction->pay_term_type == 'days') selected @endif>@lang('lang_v1.days')</option>
		              </select>
		            </div>
		          </div>
		        </div>

				@if(!empty($commission_agent))
				<div class="col-sm-3">
					<div class="form-group">
					<label for="commission_agent">@lang('lang_v1.commission_agent'):</label>
					<select name="commission_agent" class="form-control select2">
						@foreach($commission_agent as $key => $value)
							<option value="{{ $key }}" @if($transaction->commission_agent == $key) selected @endif>{{ $value }}</option>
						@endforeach
					</select>
					</div>
				</div>
				@endif
				<div class="@if(!empty($commission_agent)) col-sm-3 @else col-sm-4 @endif">
					<div class="form-group">
						<label for="transaction_date">@lang('sale.sale_date') :*</label>
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</span>
							<input type="text" name="transaction_date" value="{{ $transaction->transaction_date }}" class="form-control" readonly required>
						</div>
					</div>
				</div>
				@php
					if($transaction->status == 'draft' && $transaction->is_quotation == 1){
						$status = 'quotation';
					} else if ($transaction->status == 'draft' && $transaction->sub_status == 'proforma') {
						$status = 'proforma';
					} else {
						$status = $transaction->status;
					}
				@endphp
				<div class="@if(!empty($commission_agent)) col-sm-3 @else col-sm-4 @endif">
					<div class="form-group">
						<label for="status">@lang('sale.status') :*</label>
						<select name="status" class="form-control select2" required>
							<option value="final" @if($status == 'final') selected @endif>@lang('sale.final')</option>
							<option value="draft" @if($status == 'draft') selected @endif>@lang('sale.draft')</option>
							<option value="quotation" @if($status == 'quotation') selected @endif>@lang('lang_v1.quotation')</option>
							<option value="proforma" @if($status == 'proforma') selected @endif>@lang('lang_v1.proforma')</option>
						</select>
					</div>
				</div>
				@if($transaction->status == 'draft')
				<div class="col-sm-3">
					<div class="form-group">
						<label for="invoice_scheme_id">@lang('invoice.invoice_scheme'):</label>
						<select name="invoice_scheme_id" class="form-control select2" placeholder="@lang('messages.please_select')">
							@foreach($invoice_schemes as $invoice_scheme)
								<option value="{{ $invoice_scheme->id }}" @if($default_invoice_schemes->id == $invoice_scheme->id) selected @endif>{{ $invoice_scheme->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				@endif
				@can('edit_invoice_number')
				<div class="col-sm-3">
					<div class="form-group">
						<label for="invoice_no">@lang('sale.invoice_no'):</label>
						<input type="text" name="invoice_no" value="{{ $transaction->invoice_no }}" class="form-control" placeholder="@lang('sale.invoice_no')">
					</div>
				</div>
				@endcan
				@php
			        $custom_field_1_label = !empty($custom_labels['sell']['custom_field_1']) ? $custom_labels['sell']['custom_field_1'] : '';

			        $is_custom_field_1_required = !empty($custom_labels['sell']['is_custom_field_1_required']) && $custom_labels['sell']['is_custom_field_1_required'] == 1 ? true : false;

			        $custom_field_2_label = !empty($custom_labels['sell']['custom_field_2']) ? $custom_labels['sell']['custom_field_2'] : '';

			        $is_custom_field_2_required = !empty($custom_labels['sell']['is_custom_field_2_required']) && $custom_labels['sell']['is_custom_field_2_required'] == 1 ? true : false;

			        $custom_field_3_label = !empty($custom_labels['sell']['custom_field_3']) ? $custom_labels['sell']['custom_field_3'] : '';

			        $is_custom_field_3_required = !empty($custom_labels['sell']['is_custom_field_3_required']) && $custom_labels['sell']['is_custom_field_3_required'] == 1 ? true : false;

			        $custom_field_4_label = !empty($custom_labels['sell']['custom_field_4']) ? $custom_labels['sell']['custom_field_4'] : '';

			        $is_custom_field_4_required = !empty($custom_labels['sell']['is_custom_field_4_required']) && $custom_labels['sell']['is_custom_field_4_required'] == 1 ? true : false;
		        @endphp
		        @if(!empty($custom_field_1_label))
		        	@php
		        		$label_1 = $custom_field_1_label . ':';
		        		if($is_custom_field_1_required) {
		        			$label_1 .= '*';
		        		}
		        	@endphp

		        	<div class="col-md-4">
				        <div class="form-group">
				            <label for="custom_field_1">{{ $label_1 }}</label>
				            <input type="text" name="custom_field_1" value="{{ $transaction->custom_field_1 }}" class="form-control" placeholder="{{ $custom_field_1_label }}" @if($is_custom_field_1_required) required @endif>
				        </div>
				    </div>
		        @endif
		        @if(!empty($custom_field_2_label))
		        	@php
		        		$label_2 = $custom_field_2_label . ':';
		        		if($is_custom_field_2_required) {
		        			$label_2 .= '*';
		        		}
		        	@endphp

		        	<div class="col-md-4">
				        <div class="form-group">
				            <label for="custom_field_2">{{ $label_2 }}</label>
				            <input type="text" name="custom_field_2" value="{{ $transaction->custom_field_2 }}" class="form-control" placeholder="{{ $custom_field_2_label }}" @if($is_custom_field_2_required) required @endif>
				        </div>
				    </div>
		        @endif
		        @if(!empty($custom_field_3_label))
		        	@php
		        		$label_3 = $custom_field_3_label . ':';
		        		if($is_custom_field_3_required) {
		        			$label_3 .= '*';
		        		}
		        	@endphp

		        	<div class="col-md-4">
				        <div class="form-group">
				            <label for="custom_field_3">{{ $label_3 }}</label>
				            <input type="text" name="custom_field_3" value="{{ $transaction->custom_field_3 }}" class="form-control" placeholder="{{ $custom_field_3_label }}" @if($is_custom_field_3_required) required @endif>
				        </div>
				    </div>
		        @endif
		        @if(!empty($custom_field_4_label))
		        	@php
		        		$label_4 = $custom_field_4_label . ':';
		        		if($is_custom_field_4_required) {
		        			$label_4 .= '*';
		        		}
		        	@endphp

		        	<div class="col-md-4">
				        <div class="form-group">
				            <label for="custom_field_4">{{ $label_4 }}</label>
				            <input type="text" name="custom_field_4" value="{{ $transaction->custom_field_4 }}" class="form-control" placeholder="{{ $custom_field_4_label }}" @if($is_custom_field_4_required) required @endif>
				        </div>
				    </div>
		        @endif
		        <div class="col-sm-3">
	                <div class="form-group">
	                    <label for="upload_document">@lang('purchase.attach_document'):</label>
	                    <input type="file" name="sell_document" id="upload_document" accept="{{ implode(',', array_keys(config('constants.document_upload_mimes_types'))) }}">
	                    <p class="help-block">@lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
	                    @includeIf('components.document_help_text')</p>
	                </div>
	            </div>
		        <div class="clearfix"></div>
				<!-- Call restaurant module if defined -->
		        @if(in_array('tables' ,$enabled_modules) || in_array('service_staff' ,$enabled_modules))
		        	<span id="restaurant_module_span" 
		        		data-transaction_id="{{$transaction->id}}">
		        	</span>
		        @endif
			@endcomponent
			
			@component('components.widget', ['class' => 'box-solid'])
				<div class="col-sm-10 col-sm-offset-1">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-btn">
								<button type="button" class="btn btn-default bg-white btn-flat" data-toggle="modal" data-target="#configure_search_modal" title="{{__('lang_v1.configure_product_search')}}"><i class="fa fa-barcode"></i></button>
							</div>
							<input type="text" name="search_product" id="search_product" class="form-control mousetrap" placeholder="{{ __('lang_v1.search_product_placeholder') }}" autofocus>
							<span class="input-group-btn">
								<button type="button" class="btn btn-default bg-white btn-flat pos_add_quick_product" data-href="{{action('ProductController@quickAdd')}}" data-container=".quick_add_product_modal"><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
							</span>
						</div>
					</div>
				</div>

				<div class="row col-sm-12 pos_product_div" style="min-height: 0">

					<input type="hidden" name="sell_price_tax" id="sell_price_tax" value="{{$business_details->sell_price_tax}}">

					<!-- Keeps count of product rows -->
					<input type="hidden" id="product_row_count" 
						value="{{count($sell_details)}}">
					@php
						$hide_tax = '';
						if( session()->get('business.enable_inline_tax') == 0){
							$hide_tax = 'hide';
						}
					@endphp
					<div class="table-responsive">
					<table class="table table-condensed table-bordered table-striped table-responsive" id="pos_table">
						<thead>
							<tr>
								<th class="text-center">	
									@lang('sale.product')
								</th>
								<th class="text-center">
									@lang('sale.qty')
								</th>
								@if(!empty($pos_settings['inline_service_staff']))
									<th class="text-center">
										@lang('restaurant.service_staff')
									</th>
								@endif
								<th @can('edit_product_price_from_sale_screen')) hide @endcan>
									@lang('sale.unit_price')
								</th>
								<th @can('edit_product_discount_from_sale_screen') hide @endcan>
									@lang('receipt.discount')
								</th>
								<th class="text-center {{$hide_tax}}">
									@lang('sale.tax')
								</th>
								<th class="text-center {{$hide_tax}}">
									@lang('sale.price_inc_tax')
								</th>
								<th class="text-center">
									@lang('sale.subtotal')
								</th>
								<th class="text-center"><i class="fa fa-close" aria-hidden="true"></i></th>
							</tr>
						</thead>
						<tbody>
							@foreach($sell_details as $sell_line)
								@include('sale_pos.product_row', ['product' => $sell_line, 'row_count' => $loop->index, 'tax_dropdown' => $taxes, 'sub_units' => !empty($sell_line->unit_details) ? $sell_line->unit_details : [], 'action' => 'edit', 'is_direct_sell' => true ])
							@endforeach
						</tbody>
					</table>
					</div>
					<div class="table-responsive">
					<table class="table table-condensed table-bordered table-striped table-responsive">
						<tr>
							<td>
								<div class="pull-right">
									<b>@lang('sale.item'):</b> 
									<span class="total_quantity">0</span>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<b>@lang('sale.total'): </b>
									<span class="price_total">0</span>
								</div>
							</td>
						</tr>
					</table>
					</div>
				</div>
			@endcomponent

			@component('components.widget', ['class' => 'box-solid'])
				<div class="col-md-4">
			        <div class="form-group">
			            <label for="discount_type">@lang('sale.discount_type') :*</label>
			            <div class="input-group">
			                <span class="input-group-addon">
			                    <i class="fa fa-info"></i>
			                </span>
			                <select name="discount_type" class="form-control" required data-default="percentage">
			                	<option value="fixed" @if($transaction->discount_type == 'fixed') selected @endif>@lang('lang_v1.fixed')</option>
			                	<option value="percentage" @if($transaction->discount_type == 'percentage') selected @endif>@lang('lang_v1.percentage')</option>
			                </select>
			            </div>
			        </div>
			    </div>
			    @php
			    	$max_discount = !is_null(auth()->user()->max_sales_discount_percent) ? auth()->user()->max_sales_discount_percent : '';
			    @endphp
			    <div class="col-md-4">
			        <div class="form-group">
			            <label for="discount_amount">@lang('sale.discount_amount') :*</label>
			            <div class="input-group">
			                <span class="input-group-addon">
			                    <i class="fa fa-info"></i>
			                </span>
			                <input type="text" name="discount_amount" value="{{ @num_format($transaction->discount_amount) }}" class="form-control input_number" data-default="{{ $business_details->default_sales_discount }}" data-max-discount="{{ $max_discount }}" data-max-discount-error_msg="{{ __('lang_v1.max_discount_error_msg', ['discount' => $max_discount != '' ? @num_format($max_discount) : '']) }}">
			            </div>
			        </div>
			    </div>
			    <div class="col-md-4"><br>
			    	<b>@lang( 'sale.discount_amount' ):</b>(-) 
					<span class="display_currency" id="total_discount">0</span>
			    </div>
			    <div class="clearfix"></div>
			    <div class="col-md-12 well well-sm bg-light-gray @if(session('business.enable_rp') != 1) hide @endif">
			    	<input type="hidden" name="rp_redeemed" id="rp_redeemed" value="{{$transaction->rp_redeemed}}">
			    	<input type="hidden" name="rp_redeemed_amount" id="rp_redeemed_amount" value="{{$transaction->rp_redeemed_amount}}">
			    	<div class="col-md-12"><h4>{{session('business.rp_name')}}</h4></div>
			    	<div class="col-md-4">
				        <div class="form-group">
				            <label for="rp_redeemed_modal">@lang('lang_v1.redeemed') :</label>
				            <div class="input-group">
				                <span class="input-group-addon">
				                    <i class="fa fa-gift"></i>
				                </span>
				                <input type="number" name="rp_redeemed_modal" value="{{ $transaction->rp_redeemed }}" class="form-control direct_sell_rp_input" data-amount_per_unit_point="{{ session('business.redeem_amount_per_unit_rp') }}" min="0" data-max_points="{{ !empty($redeem_details['points']) ? $redeem_details['points'] : 0 }}" data-min_order_total="{{ session('business.min_order_total_for_redeem') }}">
				                <input type="hidden" id="rp_name" value="{{session('business.rp_name')}}">
				            </div>
				        </div>
				    </div>
				    <div class="col-md-4">
				    	<p><strong>@lang('lang_v1.available'):</strong> <span id="available_rp">{{$redeem_details['points'] ?? 0}}</span></p>
				    </div>
				    <div class="col-md-4">
				    	<p><strong>@lang('lang_v1.redeemed_amount'):</strong> (-)<span id="rp_redeemed_amount_text">{{@num_format($transaction->rp_redeemed_amount)}}</span></p>
				    </div>
			    </div>
			    <div class="clearfix"></div>
			    <div class="col-md-4 hide">
			    	<div class="form-group">
			            <label for="tax_rate_id">@lang('sale.order_tax') :*</label>
			            <div class="input-group">
			                <span class="input-group-addon">
			                    <i class="fa fa-info"></i>
			                </span>
			                <select name="tax_rate_id" class="form-control" data-default="{{ $business_details->default_sales_tax }}">
			                	@foreach($taxes['tax_rates'] as $key => $value)
			                		<option value="{{ $key }}" @if($transaction->tax_id == $key) selected @endif>{{ $value }}</option>
			                	@endforeach
			                </select>

							<input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount" 
							value="{{@num_format(optional($transaction->tax)->amount)}}" data-default="{{$business_details->tax_calculation_amount}}">
			            </div>
			        </div>
			    </div>
			    <div class="col-md-4 col-md-offset-4">
			    	<b>@lang( 'sale.order_tax' ):</b>(+) 
					<span class="display_currency" id="order_tax">{{$transaction->tax_amount}}</span>
			    </div>
			    <div class="col-md-12">
			    	<div class="form-group">
						<label for="sell_note">@lang('sale.sell_note'):</label>
						<textarea name="sale_note" class="form-control" rows="3">{{ $transaction->additional_notes }}</textarea>
					</div>
			    </div>
			    <input type="hidden" name="is_direct_sale" value="1">
			@endcomponent

			@component('components.widget', ['class' => 'box-solid'])
			<div class="col-md-4">
				<div class="form-group">
		            <label for="shipping_details">@lang('sale.shipping_details')</label>
		            <textarea name="shipping_details" class="form-control" placeholder="@lang('sale.shipping_details')" rows="3" cols="30">{{ $transaction->shipping_details }}</textarea>
		        </div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
		            <label for="shipping_address">@lang('lang_v1.shipping_address')</label>
		            <textarea name="shipping_address" class="form-control" placeholder="@lang('lang_v1.shipping_address')" rows="3" cols="30">{{ $transaction->shipping_address }}</textarea>
		        </div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label for="shipping_charges">@lang('sale.shipping_charges')</label>
					<div class="input-group">
					<span class="input-group-addon">
					<i class="fa fa-info"></i>
					</span>
					<input type="text" name="shipping_charges" value="{{ @num_format($transaction->shipping_charges) }}" class="form-control input_number" placeholder="@lang('sale.shipping_charges')">
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="col-md-4">
				<div class="form-group">
		            <label for="shipping_status">@lang('lang_v1.shipping_status')</label>
		            <select name="shipping_status" class="form-control" placeholder="@lang('messages.please_select')">
		            	<option value="">@lang('messages.please_select')</option>
		            	@foreach($shipping_statuses as $key => $value)
		            		<option value="{{ $key }}" @if($transaction->shipping_status == $key) selected @endif>{{ $value }}</option>
		            	@endforeach
		            </select>
		        </div>
			</div>
			<div class="col-md-4">
		        <div class="form-group">
		            <label for="delivered_to">@lang('lang_v1.delivered_to') :</label>
		            <input type="text" name="delivered_to" value="{{ $transaction->delivered_to }}" class="form-control" placeholder="@lang('lang_v1.delivered_to')">
		        </div>
		    </div>
		    @php
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

	        	<div class="col-md-4">
			        <div class="form-group">
			            <label for="shipping_custom_field_1">{{ $label_1 }}</label>
			            <input type="text" name="shipping_custom_field_1" value="{{ !empty($transaction->shipping_custom_field_1) ? $transaction->shipping_custom_field_1 : null }}" class="form-control" placeholder="{{ $shipping_custom_label_1 }}" @if($is_shipping_custom_field_1_required) required @endif>
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

	        	<div class="col-md-4">
			        <div class="form-group">
			            <label for="shipping_custom_field_2">{{ $label_2 }}</label>
			            <input type="text" name="shipping_custom_field_2" value="{{ !empty($transaction->shipping_custom_field_2) ? $transaction->shipping_custom_field_2 : null }}" class="form-control" placeholder="{{ $shipping_custom_label_2 }}" @if($is_shipping_custom_field_2_required) required @endif>
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

	        	<div class="col-md-4">
			        <div class="form-group">
			            <label for="shipping_custom_field_3">{{ $label_3 }}</label>
			            <input type="text" name="shipping_custom_field_3" value="{{ !empty($transaction->shipping_custom_field_3) ? $transaction->shipping_custom_field_3 : null }}" class="form-control" placeholder="{{ $shipping_custom_label_3 }}" @if($is_shipping_custom_field_3_required) required @endif>
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

	        	<div class="col-md-4">
			        <div class="form-group">
			            <label for="shipping_custom_field_4">{{ $label_4 }}</label>
			            <input type="text" name="shipping_custom_field_4" value="{{ !empty($transaction->shipping_custom_field_4) ? $transaction->shipping_custom_field_4 : null }}" class="form-control" placeholder="{{ $shipping_custom_label_4 }}" @if($is_shipping_custom_field_4_required) required @endif>
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

	        	<div class="col-md-4">
			        <div class="form-group">
			            <label for="shipping_custom_field_5">{{ $label_5 }}</label>
			            <input type="text" name="shipping_custom_field_5" value="{{ !empty($transaction->shipping_custom_field_5) ? $transaction->shipping_custom_field_5 : null }}" class="form-control" placeholder="{{ $shipping_custom_label_5 }}" @if($is_shipping_custom_field_5_required) required @endif>
			        </div>
			    </div>
	        @endif
	        <div class="col-md-4">
                <div class="form-group">
                    <label for="shipping_documents">@lang('lang_v1.shipping_documents'):</label>
                    <input type="file" name="shipping_documents[]" id="shipping_documents" multiple accept="{{ implode(',', array_keys(config('constants.document_upload_mimes_types'))) }}">
                    <p class="help-block">
                    	@lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                    	@includeIf('components.document_help_text')
                    </p>
                    @php
                    	$medias = $transaction->media->where('model_media_type', 'shipping_document')->all();
                    @endphp
                    @include('sell.partials.media_table', ['medias' => $medias, 'delete' => true])
                </div>
            </div>
	        <div class="clearfix"></div>
		    <div class="col-md-4 col-md-offset-8">
		    	@if(!empty($pos_settings['amount_rounding_method']) && $pos_settings['amount_rounding_method'] > 0)
		    	<small id="round_off"><br>(@lang('lang_v1.round_off'): <span id="round_off_text">0</span>)</small>
				<br/>
				<input type="hidden" name="round_off_amount" 
					id="round_off_amount" value=0>
				@endif
		    	<div><b>@lang('sale.total_payable'): </b>
					<input type="hidden" name="final_total" id="final_total_input">
					<span id="total_payable">0</span>
				</div>
		    </div>
			@endcomponent
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-right">
	    	<input type="hidden" name="is_save_and_print" id="is_save_and_print" value="0">
	    	<button type="button" class="btn btn-primary" id="submit-sell">@lang('messages.update')</button>
	    	<button type="button" id="save-and-print" class="btn btn-primary btn-flat">@lang('lang_v1.update_and_print')</button>
	    </div>
	</div>
	@if(in_array('subscription', $enabled_modules))
		@include('sale_pos.partials.recurring_invoice_modal')
	@endif
	</form>
</section>

<div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
	@include('contact.create', ['quick_add' => true])
</div>
<!-- /.content -->
<div class="modal fade register_details_modal" tabindex="-1" role="dialog" 
	aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade close_register_modal" tabindex="-1" role="dialog" 
	aria-labelledby="gridSystemModalLabel">
</div>
<!-- quick product modal -->
<div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>

@include('sale_pos.partials.configure_search_modal')

@stop

@section('javascript')
	<script src="{{ asset('js/pos.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>
	<!-- Call restaurant module if defined -->
    @if(in_array('tables' ,$enabled_modules) || in_array('modifiers' ,$enabled_modules) || in_array('service_staff' ,$enabled_modules))
    	<script src="{{ asset('js/restaurant.js?v=' . $asset_v) }}"></script>
    @endif
    <script type="text/javascript">
    	$(document).ready( function(){
    		$('#shipping_documents').fileinput({
		        showUpload: false,
		        showPreview: false,
		        browseLabel: LANG.file_browse_label,
		        removeLabel: LANG.remove,
		    });
    	});
    </script>
@endsection

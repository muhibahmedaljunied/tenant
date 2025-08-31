@extends('layouts.app')
@section('title', __('manufacturing::lang.production'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('manufacturing::lang.production') </h1>
</section>

<!-- Main content -->
<section class="content">

<form action="{{ action('\\Modules\\Manufacturing\\Http\\Controllers\\ProductionController@update', [$production_purchase->id]) }}" method="POST" id="production_form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @component('components.widget', ['class' => 'box-primary'])
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="ref_no">{{ __('purchase.ref_no').':' }}</label>
                    <input type="text" name="ref_no" id="ref_no" value="{{ $production_purchase->ref_no }}" class="form-control">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="transaction_date">{{ __('manufacturing::lang.mfg_date') . ':*' }}</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" name="transaction_date" id="transaction_date" value="{{ @format_datetime($production_purchase->transaction_date) }}" class="form-control" readonly required>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="location_id">{{ __('purchase.business_location').':*' }}</label>
                    @show_tooltip(__('tooltip.purchase_location'))
                    <select name="location_id" id="location_id" class="form-control select2" required>
                        <option value="">{{ __('messages.please_select') }}</option>
                        @foreach($business_locations as $key => $value)
                            <option value="{{ $key }}" {{ $production_purchase->location_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @php
                $purchase_line = $production_purchase->purchase_lines[0];
            @endphp
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="variation_id_shown">{{ __('sale.product').':*' }}</label>
                    <select name="variation_id_shown" id="variation_id_shown" class="form-control" required disabled>
                        <option value="">{{ __('messages.please_select') }}</option>
                        @foreach($recipe_dropdown as $key => $value)
                            <option value="{{ $key }}" {{ $purchase_line->variation_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="variation_id" id="variation_id" value="{{ $purchase_line->variation_id }}">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="recipe_quantity">{{ __('lang_v1.quantity').':*' }}</label>
                    <div class="@if(!empty($sub_units)) input_inline @else input-group @endif" id="recipe_quantity_input">
                        <input type="text" name="quantity" id="recipe_quantity" value="{{ @num_format($quantity) }}" class="form-control input_number" required data-rule-notEmpty="true" data-rule-notEqualToWastedQuantity="true">
                        <span class="@if(empty($sub_units)) input-group-addon @endif" id="unit_html">
                            @if(!empty($sub_units))
                                <select name="sub_unit_id" class="form-control" id="sub_unit_id">
                                @foreach($sub_units as $key => $value)
                                    <option value="{{$key}}" data-multiplier="{{$value['multiplier']}}" data-unit_name="{{$value['name']}}" @if($key == $sub_unit_id) @php $unit_name = $value['name']; @endphp selected @endif>{{$value['name']}}</option>
                                @endforeach
                                </select>
                            @else
                                {{ $unit_name }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endcomponent

    @component('components.widget', ['class' => 'box-primary', 'title' => __('manufacturing::lang.ingredients')])
        <div class="row">
            <div class="col-md-12">
                <div id="enter_ingredients_table">
                    @include('manufacturing::recipe.ingredients_for_production')
                </div>
            </div>
        </div>
        <div class="row">
            @if(request()->session()->get('business.enable_lot_number') == 1)
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="lot_number">{{ __('lang_v1.lot_number').':' }}</label>
                        <input type="text" name="lot_number" id="lot_number" value="{{ $purchase_line->lot_number }}" class="form-control">
                    </div>
                </div>
            @endif
            @if(session('business.enable_product_expiry'))
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="exp_date">{{ __('product.exp_date').':*' }}</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <input type="text" name="exp_date" id="exp_date" value="{{ !empty($purchase_line->exp_date) ? @format_date($purchase_line->exp_date) : null }}" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-3">
                <div class="form-group">
                    <label for="mfg_wasted_units">{{ __('manufacturing::lang.waste_units').':' }}</label> @show_tooltip(__('manufacturing::lang.wastage_tooltip'))
                    <div class="input-group">
                        <input type="text" name="mfg_wasted_units" id="mfg_wasted_units" value="{{ @num_format($production_purchase->mfg_wasted_units) }}" class="form-control input_number">
                        <span class="input-group-addon" id="wasted_units_text">{{$unit_name}}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="production_cost">{{ __('manufacturing::lang.production_cost').':' }}</label> @show_tooltip(__('manufacturing::lang.production_cost_tooltip'))
                    <div class="input-group">
                        <input type="text" name="production_cost" id="production_cost" value="{{ @num_format($production_purchase->mfg_production_cost) }}" class="form-control input_number">
                        <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-md-offset-9">
                <input type="hidden" name="final_total" id="final_total" value="{{ @num_format($production_purchase->final_total) }}">
                <strong>
                    {{__('manufacturing::lang.total_production_cost')}}:
                </strong>
                <span id="total_production_cost" class="display_currency" data-currency_symbol="true">{{$total_production_cost}}</span><br>
                <strong>
                    {{__('manufacturing::lang.total_cost')}}:
                </strong>
                <span id="final_total_text" class="display_currency" data-currency_symbol="true">{{ $production_purchase->final_total }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-md-offset-9">
                <div class="form-group">
                    <br>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="finalize" id="finalize" value="1" class="input-icheck"> @lang('manufacturing::lang.finalize')
                        </label> @show_tooltip(__('manufacturing::lang.finalize_tooltip'))
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary pull-right">@lang('messages.submit')</button>
            </div>
        </div>
    @endcomponent
</form>
</section>
@endsection

@section('javascript')
    @include('manufacturing::production.production_script')

    <script type="text/javascript">
        $(document).ready( function () {
            calculateRecipeTotal();
        });
    </script>
@endsection

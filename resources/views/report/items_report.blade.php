@extends('layouts.app')
@section('title', __('lang_v1.items_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('lang_v1.items_report')}}</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
            <div class="col-md-3">
                <div class="form-group">
                    <label for="ir_supplier_id">{{ __('purchase.supplier') . ':' }}</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-user"></i>
                        </span>
                        <select name="ir_supplier_id" id="ir_supplier_id" class="form-control select2">
                            <option value="">{{ __('lang_v1.all') }}</option>
                            @foreach($suppliers as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="ir_purchase_date_filter">{{ __('purchase.purchase_date') . ':' }}</label>
                    <input type="text" name="ir_purchase_date_filter" id="ir_purchase_date_filter" class="form-control" placeholder="{{ __('lang_v1.select_a_date_range') }}" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="ir_customer_id">{{ __('contact.customer') . ':' }}</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-user"></i>
                        </span>
                        <select name="ir_customer_id" id="ir_customer_id" class="form-control select2">
                            <option value="">{{ __('lang_v1.all') }}</option>
                            @foreach($customers as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="ir_sale_date_filter">{{ __('lang_v1.sell_date') . ':' }}</label>
                    <input type="text" name="ir_sale_date_filter" id="ir_sale_date_filter" class="form-control" placeholder="{{ __('lang_v1.select_a_date_range') }}" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="ir_location_id">{{ __('purchase.business_location').':' }}</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-map-marker"></i>
                        </span>
                        <select name="ir_location_id" id="ir_location_id" class="form-control select2" required>
                            <option value="">{{ __('messages.please_select') }}</option>
                            @foreach($business_locations as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @if(Module::has('Manufacturing'))
                <div class="col-md-3">
                    <div class="form-group">
                        <br>
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" name="only_mfg" id="only_mfg_products" class="input-icheck" value="1"> {{ __('manufacturing::lang.only_mfg_products') }}
                            </label>
                        </div>
                    </div>
                </div>
            @endif
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-primary'])
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" 
                    id="items_report_table">
                        <thead>
                            <tr>
                                <th>@lang('sale.product')</th>
                                <th>@lang('product.sku')</th>
                                <th>@lang('purchase.purchase_date')</th>
                                <th>@lang('lang_v1.purchase')</th>
                                <th>@lang('purchase.supplier')</th>
                                <th>@lang('lang_v1.purchase_price')</th>
                                <th>@lang('lang_v1.sell_date')</th>
                                <th>@lang('business.sale')</th>
                                <th>@lang('contact.customer')</th>
                                <th>@lang('sale.location')</th>
                                <th>@lang('lang_v1.quantity')</th>
                                <th>@lang('lang_v1.selling_price')</th>
                                <th>@lang('sale.subtotal')</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="bg-gray font-17 text-center footer-total">
                                <td colspan="5"><strong>@lang('sale.total'):</strong></td>
                                <td id="footer_total_pp" 
                                    class="display_currency" data-currency_symbol="true"></td>
                                <td colspan="4"></td>
                                <td id="footer_total_qty"></td>
                                <td id="footer_total_sp"
                                    class="display_currency" data-currency_symbol="true"></td>
                                <td id="footer_total_subtotal"
                                    class="display_currency" data-currency_symbol="true"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endcomponent
        </div>
    </div>
</section>
<!-- /.content -->
<div class="modal fade view_register" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

@endsection

@section('javascript')
    <script src="{{ url('js/report.js?v=' . $asset_v) }}"></script>
@endsection
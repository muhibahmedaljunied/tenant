<div class="table-responsive">
    <table class="table table-bordered " id="stock_report_table">

        <thead>
            <tr>
                <th>SKU</th>
                <th>@lang('business.product')</th>
                <th>@lang('sale.location')</th>
                <th class="bg-blue">
                    @lang('lang_v1.purchase_price_exc_tax')
                </th>
                <th class="bg-blue">@lang('lang_v1.total_purchase_price')
                    <br><small>@lang('lang_v1.total_without_taxes')</small>
                </th>
                @can('view_product_stock_value')
                <th class="bg-blue stock_price">@lang('lang_v1.total_stock_price')
                    <br><small>(@lang('lang_v1.by_purchase_price'))</small>
                </th>
                {{-- <th class="bg-blue">سعر الشراء بالعملة المحل.ية <span>{{$currency->code ?? ''}}</span></th> --}}
                @endcan

                <th>@lang('report.current_stock')</th>
                <th class="bg-green-gradient">@lang('sale.unit_price')<br> <small>@lang('sale.price_inc_tax')</small> </th>
                <th class="bg-green-gradient">@lang('sale.unit_price')<br> <small>@lang('product.exc_of_tax')</small> </th>
                @can('view_product_stock_value')
                <th>
                    @lang('lang_v1.total_stock_price') 
                    {{-- <br><small>(@lang('lang_v1.by_sale_price'))</small> --}}
                    <br><small>(@lang('lang_v1.by_sale_price') @lang('lang_v1.total_without_taxes'))</small>
                
                </th>
                <th>@lang('lang_v1.total_stock_price') <br><small>(@lang('lang_v1.by_sale_price'))</small></th>
                {{-- <th>سعر البيع بالعملة المحلية <br><small>(@lang('lang_v1.by_sale_price'))</small></th> --}}
                <th>@lang('lang_v1.potential_profit')</th>
                @endcan
                <th>@lang('report.total_unit_sold')</th>
                <th>@lang('lang_v1.total_unit_transfered')</th>
                <th>@lang('lang_v1.total_unit_adjusted')</th>
               
                {{-- muhib remove condition from here --}}
            </tr>
        </thead>
        <tfoot>
            <tr class="bg-gray font-17 text-center footer-total">
                <td colspan="3"><strong>@lang('sale.total'):</strong></td>
                <td id="footer_total_pp_without_tax"></td>
                @can('view_product_stock_value')
                <td colspan="1" id="footer_total_stock_price"></td>
                @endcan
                <td id="footer_total_stock"></td>
                <td></td>
                <td></td>
                @can('view_product_stock_value')
                <td id="footer_total_sell_price_without_tax"></td>
                <td id="footer_stock_value_by_sale_price"></td>
                <td id="footer_potential_profit"></td>
                @endcan
                <td id="footer_total_sold"></td>
                <td id="footer_total_transfered"></td>
                <td id="footer_total_adjusted"></td>
                   {{-- muhib remove condition from here --}}
            </tr>
        </tfoot>
    </table>
</div>
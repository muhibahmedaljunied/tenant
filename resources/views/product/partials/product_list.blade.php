@php
    $colspan = 15;
    $custom_labels = json_decode(session('business.custom_labels'), true);
@endphp
<table class="table table-bordered table-striped ajax_view hide-footer" id="product_table">
    <thead>
        <tr>
            <th><input type="checkbox" id="select-all-row" data-table-id="product_table"></th>
            <th>&nbsp;</th>
            <th>@lang('messages.action')</th>
            <th>@lang('sale.product')</th>
            <th>@lang('purchase.business_location') @show_tooltip(__('lang_v1.product_business_location_tooltip'))</th>
            <th>@lang('store.store')</th>
            @can('view_purchase_price')
                @php
                    $colspan++;
                @endphp
                <th>@lang('lang_v1.unit_perchase_price')</th>
            @endcan
            @can('access_default_selling_price')
                @php
                    $colspan++;
                @endphp
                <th>@lang('lang_v1.selling_price')</th>
            @endcan
            <th>@lang('report.current_stock')</th>
            <th>@lang('product.product_type')</th>
            <th>@lang('product.category')</th>
            <th>@lang('product.brand')</th>
            <th>@lang('product.tax')</th>
            <th>@lang('product.sku')</th>
            <th>{{ $custom_labels['product']['custom_field_1'] ?? __('lang_v1.product_custom_field1') }}</th>
            <th>{{ $custom_labels['product']['custom_field_2'] ?? __('lang_v1.product_custom_field2') }}</th>
            <th>{{ $custom_labels['product']['custom_field_3'] ?? __('lang_v1.product_custom_field3') }}</th>
            <th>{{ $custom_labels['product']['custom_field_4'] ?? __('lang_v1.product_custom_field4') }}</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="{{ $colspan }}">
                <div style="display: flex; width: 100%;">

                    @can('product.delete')
                        <form action="{{ action('ProductController@massDestroy') }}" method="POST" id="mass_delete_form">
                            @csrf
                            <input type="hidden" name="selected_rows" id="selected_rows">
                            <button type="submit" class="btn btn-xs btn-danger"
                                id="delete-selected">@lang('lang_v1.delete_selected')</button>
                        </form>
                    @endcan

                    @if (config('constants.enable_product_bulk_edit'))
                        @can('product.update')
                            &nbsp;
                            <form action="{{ action('ProductController@bulkEdit') }}" method="POST" id="bulk_edit_form">
                                @csrf
                                <input type="hidden" name="selected_products" id="selected_products_for_edit">
                                <button type="submit" class="btn btn-xs btn-primary" id="edit-selected">
                                    <i class="fa fa-edit"></i> @lang('lang_v1.bulk_edit')
                                </button>
                            </form>
                            &nbsp;
                            <button type="button" class="btn btn-xs btn-success update_product_location" data-type="add">
                                @lang('lang_v1.add_to_location')
                            </button>
                            &nbsp;
                            <button type="button" class="btn btn-xs bg-navy update_product_location" data-type="remove">
                                @lang('lang_v1.remove_from_location')
                            </button>
                        @endcan
                    @endif

                    &nbsp;
                    <form action="{{ action('ProductController@massDeactivate') }}" method="POST"
                        id="mass_deactivate_form">
                        @csrf
                        <input type="hidden" name="selected_products" id="selected_products">
                        <button type="submit" class="btn btn-xs btn-warning" id="deactivate-selected">
                            @lang('lang_v1.deactivate_selected')
                        </button>
                    </form>
                    @show_tooltip(__('lang_v1.deactive_product_tooltip'))
                </div>
            </td>
        </tr>
    </tfoot>

</table>

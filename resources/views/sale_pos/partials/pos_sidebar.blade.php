<div class="row resizable" id="featured_products_box" style="display: none;">
@if(!empty($featured_products))
    @include('sale_pos.partials.featured_products')
@endif
</div>
<div class="row">
    @if(!empty($categories))
        <div class="col-md-4" id="product_category_div">
            <select class="select2" id="product_category" style="width:100% !important">

                <option value="all">@lang('lang_v1.all_category')</option>

                @foreach($categories as $category)
                    <option value="{{$category['id']}}">{{$category['name']}}</option>
                @endforeach

                @foreach($categories as $category)
                    @if(!empty($category['sub_categories']))
                        <optgroup label="{{$category['name']}}">
                            @foreach($category['sub_categories'] as $sc)
                                <i class="fa fa-minus"></i> <option value="{{$sc['id']}}">{{$sc['name']}}</option>
                            @endforeach
                        </optgroup>
                    @endif
                @endforeach
            </select>
        </div>
    @endif

    @if(!empty($brands))
        <div class="col-sm-4" id="product_brand_div">
            <select id="product_brand" class="select2" style="width:100% !important">
                <option value="">{{ __('messages.please_select') }}</option>
                @foreach($brands as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <!-- used in repair : filter for service/product -->
    <div class="col-md-6 hide" id="product_service_div">
        <select id="is_enabled_stock" class="select2" style="width:100% !important">
            <option value="">{{ __('messages.all') }}</option>
            <option value="product">{{ __('sale.product') }}</option>
            <option value="service">{{ __('lang_v1.service') }}</option>
        </select>
    </div>

    <div class="col-sm-4 @if(empty($featured_products)) hide @endif" id="feature_product_div">
        <button type="button" class="btn btn-primary btn-flat" id="show_featured_products">@lang('lang_v1.featured_products')</button>
    </div>
</div>
<br>
<div class="row">
    <input type="hidden" id="suggestion_page" value="1">
    <div class="col-md-12">
        <div class="eq-height-row" id="product_list_body"></div>
    </div>
    <div class="col-md-12 text-center" id="suggestion_page_loader" style="display: none;">
        <i class="fa fa-spinner fa-spin fa-2x"></i>
    </div>
</div>
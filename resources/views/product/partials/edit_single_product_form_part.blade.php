@if(!session('business.enable_price_tax')) 
  @php
    $default = 0;
    $class = 'hide';
  @endphp
@else
  @php
    $default = null;
    $class = '';
  @endphp
@endif

<div class="col-sm-12"><br>
    <div class="table-responsive">
    <table class="table table-bordered add-product-price-table table-condensed {{$class}}">
        <tr>
          <th>@lang('product.default_purchase_price')</th>
          <th>@lang('product.profit_percent') @show_tooltip(__('tooltip.profit_percent'))</th>
          <th>@lang('product.default_selling_price')</th>
          <th>@lang('lang_v1.product_image')</th>
        </tr>
        @foreach($product_deatails->variations as $variation )
            @if($loop->first)
                <tr>
                    <td>
                        <input type="hidden" name="single_variation_id" value="{{$variation->id}}">

                        <div class="col-sm-6">
                          <label for="single_dpp">{{ trans('product.exc_of_tax') . ':*' }}</label>
                          <input type="text" name="single_dpp" id="single_dpp" value="{{ @num_format($variation->default_purchase_price) }}" class="form-control input-sm dpp input_number" placeholder="{{ __('product.exc_of_tax') }}" required>
                        </div>

                        <div class="col-sm-6">
                          <label for="single_dpp_inc_tax">{{ trans('product.inc_of_tax') . ':*' }}</label>
                          <input type="text" name="single_dpp_inc_tax" id="single_dpp_inc_tax" value="{{ @num_format($variation->dpp_inc_tax) }}" class="form-control input-sm dpp_inc_tax input_number" placeholder="{{ __('product.inc_of_tax') }}" required>
                        </div>
                    </td>

                    <td>
                        <br/>
                        <input type="text" name="profit_percent" id="profit_percent" value="{{ @num_format($variation->profit_percent) }}" class="form-control input-sm input_number" required>
                    </td>

                    <td>
                        <div class="col-sm-6">
                            <label for="single_dsp">{{ trans('product.exc_of_tax') . ':*' }}</label>
                            <input type="text" name="single_dsp" id="single_dsp" value="{{ @num_format($variation->default_sell_price) }}" class="form-control input-sm input_number" placeholder="{{ __('product.exc_of_tax') }}" required>
                        </div>

                        <div class="col-sm-6">
                            <label for="single_dsp_inc_tax">{{ trans('product.inc_of_tax') . ':*' }}</label>
                            <input type="text" name="single_dsp_inc_tax" id="single_dsp_inc_tax" value="{{ @num_format($variation->sell_price_inc_tax) }}" class="form-control input-sm input_number" placeholder="{{ __('product.inc_of_tax') }}" required>
                        </div>
                    </td>
                    <td>
                        @php 
                            $action = !empty($action) ? $action : '';
                        @endphp
                        @if($action !== 'duplicate')
                            @foreach($variation->media as $media)
                                <div class="img-thumbnail">
                                    <span class="badge bg-red delete-media" data-href="{{ action('ProductController@deleteMedia', ['media_id' => $media->id])}}"><i class="fa fa-close"></i></span>
                                    {!! $media->thumbnail() !!}
                                </div>
                            @endforeach
                        @endif
                        <div class="form-group">
                            <label for="variation_images">{{ __('lang_v1.product_image') . ':' }}</label>
                            <input type="file" name="variation_images[]" class="variation_images" accept="image/*" multiple>
                            <small><p class="help-block">@lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)]) <br> @lang('lang_v1.aspect_ratio_should_be_1_1')</p></small>
                        </div>
                    </td>
                </tr>
            @endif
        @endforeach
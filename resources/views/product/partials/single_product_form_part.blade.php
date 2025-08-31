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
<div class="table-responsive">
    <table class="table table-bordered add-product-price-table table-condensed {{ $class }}">
        <tr>
            <th>@lang('product.default_purchase_price')</th>
            <th>@lang('product.profit_percent') @show_tooltip(__('tooltip.profit_percent'))</th>
            <th>@lang('product.default_selling_price')</th>
            @if(empty($quick_add))
                <th>@lang('lang_v1.product_image')</th>
            @endif
        </tr>
        <tr>
            <td>
                <div class="col-sm-6">
                    <label for="single_dpp">@lang('product.exc_of_tax'):</label>
                    <input type="text" name="single_dpp" id="single_dpp" class="form-control input-sm dpp input_number"
                        placeholder="@lang('product.exc_of_tax')" value="{{ $default }}" required>
                </div>
                <div class="col-sm-6">
                    <label for="single_dpp_inc_tax">@lang('product.inc_of_tax'):</label>
                    <input type="text" name="single_dpp_inc_tax" id="single_dpp_inc_tax" class="form-control input-sm dpp_inc_tax input_number"
                        placeholder="@lang('product.inc_of_tax')" value="{{ $default }}" required>
                </div>
            </td>

            <td>
                <br>
                <input type="text" name="profit_percent" id="profit_percent" class="form-control input-sm input_number"
                    value="{{ @num_format($profit_percent) }}" required>
            </td>

            <td>
                <div class="col-sm-6">
                    <label for="single_dsp">@lang('product.exc_of_tax'):</label>
                    <input type="text" name="single_dsp" id="single_dsp" class="form-control input-sm input_number"
                        placeholder="@lang('product.exc_of_tax')" value="{{ $default }}" required>
                </div>
                <div class="col-sm-6">
                    <label for="single_dsp_inc_tax">@lang('product.inc_of_tax'):</label>
                    <input type="text" name="single_dsp_inc_tax" id="single_dsp_inc_tax" class="form-control input-sm input_number"
                        placeholder="@lang('product.inc_of_tax')" value="{{ $default }}" required>
                </div>
            </td>

            @if(empty($quick_add))
                <td>
                    <div class="form-group">
                        <label for="variation_images">@lang('lang_v1.product_image'):</label>
                        <input type="file" name="variation_images[]" id="variation_images" class="variation_images"
                            accept="image/*" multiple>
                        <small>
                            <p class="help-block">
                                @lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])<br>
                                @lang('lang_v1.aspect_ratio_should_be_1_1')
                            </p>
                        </small>
                    </div>
                </td>
            @endif
        </tr>
    </table>
</div>

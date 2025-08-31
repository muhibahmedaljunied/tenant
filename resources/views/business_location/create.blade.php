<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        <form action="{{ action('BusinessLocationController@store') }}" method="post" id="business_location_add_form">
            @csrf

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang( 'business.add_business_location' )</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name">{{ __('invoice.name') }}:*</label>
                            <input type="text" name="name" class="form-control" required placeholder="{{ __('invoice.name') }}">
                        </div>
                    </div>

                    <div class="col-sm-6" style="display: none;">
                        <div class="form-group">
                            <label for="location_id">{{ __('lang_v1.location_id') }}:</label>
                            <input type="text" name="location_id" class="form-control" placeholder="{{ __('lang_v1.location_id') }}">
                        </div>
                    </div>

                    <div class="col-sm-6" style="display: none;">
                        <div class="form-group">
                            <label for="landmark">{{ __('business.landmark') }}:</label>
                            <input type="text" name="landmark" class="form-control" placeholder="{{ __('business.landmark') }}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="city">{{ __('business.city') }}:*</label>
                            <input type="text" name="city" class="form-control" placeholder="{{ __('business.city') }}" required>
                        </div>
                    </div>

                    <div class="col-sm-6" style="display: none;">
                        <div class="form-group">
                            <label for="zip_code">{{ __('business.zip_code') }}:</label>
                            <input type="text" name="zip_code" class="form-control" placeholder="{{ __('business.zip_code') }}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="state">{{ __('business.state') }}:*</label>
                            <input type="text" name="state" class="form-control" placeholder="{{ __('business.state') }}" required>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="country">{{ __('business.country') }}:*</label>
                            <input type="text" name="country" class="form-control" placeholder="{{ __('business.country') }}" required>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="mobile">{{ __('business.mobile') }}:</label>
                            <input type="text" name="mobile" class="form-control" placeholder="{{ __('business.mobile') }}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="alternate_number">{{ __('business.alternate_number') }}:</label>
                            <input type="text" name="alternate_number" class="form-control" placeholder="{{ __('business.alternate_number') }}">
                        </div>
                    </div>

                    <div class="col-sm-6" style="display: none;">
                        <div class="form-group">
                            <label for="email">{{ __('business.email') }}:</label>
                            <input type="email" name="email" class="form-control" placeholder="{{ __('business.email') }}">
                        </div>
                    </div>

                    <div class="col-sm-6" style="display: none;">
                        <div class="form-group">
                            <label for="website">{{ __('lang_v1.website') }}:</label>
                            <input type="text" name="website" class="form-control" placeholder="{{ __('lang_v1.website') }}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="invoice_scheme_id">{{ __('invoice.invoice_scheme') }}:*</label>
                            <select name="invoice_scheme_id" class="form-control" required>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($invoice_schemes as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="invoice_layout_id">{{ __('lang_v1.invoice_layout_for_pos') }}:*</label>
                            <select name="invoice_layout_id" class="form-control" required>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($invoice_layouts as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="sale_invoice_layout_id">{{ __('lang_v1.invoice_layout_for_sale') }}:*</label>
                            <select name="sale_invoice_layout_id" class="form-control" required>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($invoice_layouts as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="selling_price_group_id">{{ __('lang_v1.default_selling_price_group') }}:</label>
                            <select name="selling_price_group_id" class="form-control">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($price_groups as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
   <div class="clearfix"></div>
                    @php
                    $custom_labels = json_decode(session('business.custom_labels'), true);
                    $location_custom_field1 = !empty($custom_labels['location']['custom_field_1']) ? $custom_labels['location']['custom_field_1'] : __('lang_v1.location_custom_field1');
                    $location_custom_field2 = !empty($custom_labels['location']['custom_field_2']) ? $custom_labels['location']['custom_field_2'] : __('lang_v1.location_custom_field2');
                    $location_custom_field3 = !empty($custom_labels['location']['custom_field_3']) ? $custom_labels['location']['custom_field_3'] : __('lang_v1.location_custom_field3');
                    $location_custom_field4 = !empty($custom_labels['location']['custom_field_4']) ? $custom_labels['location']['custom_field_4'] : __('lang_v1.location_custom_field4');
                    @endphp

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="custom_field1">{{ $location_custom_field1 }}:</label>
                            <input type="text" name="custom_field1" class="form-control" placeholder="{{ $location_custom_field1 }}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="custom_field2">{{ $location_custom_field2 }}:</label>
                            <input type="text" name="custom_field2" class="form-control" placeholder="{{ $location_custom_field2 }}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="custom_field3">{{ $location_custom_field3 }}:</label>
                            <input type="text" name="custom_field3" class="form-control" placeholder="{{ $location_custom_field3 }}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="custom_field4">{{ $location_custom_field4 }}:</label>
                            <input type="text" name="custom_field4" class="form-control" placeholder="{{ $location_custom_field4 }}">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="featured_products">{{ __('lang_v1.pos_screen_featured_products') }}:</label>
                            <select name="featured_products[]" id="featured_products" class="form-control" multiple>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <strong>{{ __('lang_v1.payment_options') }}</strong>
                        <div class="form-group">
                            <table class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">{{ __('lang_v1.payment_method') }}</th>
                                        <th class="text-center">{{ __('lang_v1.enable') }}</th>
                                        <th class="text-center {{ empty($accounts) ? 'hide' : '' }}">{{ __('lang_v1.default_accounts') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payment_types as $key => $label)
                                    <tr>
                                        <td class="text-center">{{ $label }}</td>
                                        <td class="text-center">
                                            <input type="checkbox" name="default_payment_accounts[{{ $key }}][is_enabled]" value="1" checked>
                                        </td>
                                        <td class="text-center {{ empty($accounts) ? 'hide' : '' }}">
                                            <select name="default_payment_accounts[{{ $key }}][account]" class="form-control input-sm">
                                                @foreach ($accounts as $account_key => $account_name)
                                                <option value="{{ $account_key }}">{{ $account_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
            </div>

        </form>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
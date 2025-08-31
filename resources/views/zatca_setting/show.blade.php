<div class="modal-dialog" role="document">
    <div class="modal-content">


        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('zatca.settings.show')</h4>
        </div>

        <div class="modal-body">
            <div>
                <div>

                    <div class="form-group">
                        <label for="name">{{ __('zatca.settings.name') . ':*' }}</label>
                        <input type="text" name="name" id="name" class="form-control" readonly placeholder="{{ __('zatca.settings.name') }}" value="{{ $zatca->name }}">
                    </div>


                    <div class="form-group">
                        <label for="mobile">{{ __('zatca.settings.mobile') . ':' }}</label>
                        <input type="text" name="mobile" id="mobile" class="form-control input_number" readonly placeholder="{{ __('zatca.settings.mobile') }}" value="{{ $zatca->mobile }}">
                    </div>
                    <div class="form-group">
                        <label for="trn">{{ __('zatca.settings.trn') . ':' }}</label>
                        <input type="text" name="trn" id="trn" class="form-control" readonly placeholder="{{ __('zatca.settings.trn') }}" value="{{ $zatca->trn }}">
                        <span style="color: #808080a1;">Example : 300000000000003</span>

                    </div>
                    <div class="form-group">
                        <label for="crn">{{ __('zatca.settings.crn') . ':' }}</label>
                        <input type="text" name="crn" id="crn" class="form-control" readonly placeholder="{{ __('zatca.settings.crn') }}" value="{{ $zatca->crn }}">
                    </div>
                    <div class="form-group">
                        <label for="street_name">{{ __('zatca.settings.street_name') . ':' }}</label>
                        <input type="text" name="street_name" id="street_name" class="form-control" readonly placeholder="{{ __('zatca.settings.street_name') }}" value="{{ $zatca->street_name }}">
                    </div>
                    <div class="form-group">
                        <label for="building_number">{{ __('zatca.settings.building_number') . ':' }}</label>
                        <input type="text" name="building_number" id="building_number" class="form-control input_number" readonly placeholder="{{ __('zatca.settings.building_number') }}" value="{{ $zatca->building_number }}">
                        <span style="color: #808080a1;">Example : 1234</span>

                    </div>
                    <div class="form-group">
                        <label for="plot_identification">{{ __('zatca.settings.plot_identification') . ':' }}</label>
                        <input type="text" name="plot_identification" id="plot_identification" class="form-control" readonly placeholder="{{ __('zatca.settings.plot_identification') }}" value="{{ $zatca->plot_identification }}">
                        <span style="color: #808080a1;">Example : 1234</span>

                    </div>
                    <div class="form-group">
                        <label for="region">{{ __('zatca.settings.region') . ':' }}</label>
                        <input type="text" name="region" id="region" class="form-control" readonly placeholder="{{ __('zatca.settings.region') }}" value="{{ $zatca->region }}">
                    </div>
                    <div class="form-group">
                        <label for="city">{{ __('zatca.settings.city') . ':' }}</label>
                        <input type="text" name="city" id="city" class="form-control" readonly placeholder="{{ __('zatca.settings.city') }}" value="{{ $zatca->city }}">
                    </div>
                    <div class="form-group">
                        <label for="postal_number">{{ __('zatca.settings.postal_number') . ':' }}</label>
                        <input type="text" name="postal_number" id="postal_number" class="form-control input_number" readonly placeholder="{{ __('zatca.settings.postal_number') }}" value="{{ $zatca->postal_number }}">
                        <span style="color: #808080a1;">Example : 12345</span>

                    </div>
                    <div class="form-group">
                        <label for="egs_serial_number">{{ __('zatca.settings.egs_serial_number') . ':' }}</label>
                        <input type="text" name="egs_serial_number" id="egs_serial_number" class="form-control" readonly placeholder="{{ __('zatca.settings.egs_serial_number') }}" value="{{ $zatca->egs_serial_number }}">
                        <span style="color: #808080a1;">Example : 1-ABC|2-ABC|3-ABC</span>

                    </div>
                    <div class="form-group">
                        <label for="business_category">{{ __('zatca.settings.business_category') . ':' }}</label>
                        <input type="text" name="business_category" id="business_category" class="form-control" readonly placeholder="{{ __('zatca.settings.business_category') }}" value="{{ $zatca->business_category }}">
                    </div>
                    <div class="form-group">
                        <label for="common_name">{{ __('zatca.settings.common_name') . ':' }}</label>
                        <input type="text" name="common_name" id="common_name" class="form-control " readonly placeholder="{{ __('zatca.settings.common_name') }}" value="{{ $zatca->common_name }}">
                    </div>
                    <div class="form-group">
                        <label for="organization_unit_name">{{ __('zatca.settings.organization_unit_name') . ':' }}</label>
                        <input type="text" name="organization_unit_name" id="organization_unit_name" class="form-control " readonly placeholder="{{ __('zatca.settings.organization_unit_name') }}" value="{{ $zatca->organization_unit_name }}">
                    </div>
                    <div class="form-group">
                        <label for="organization_name">{{ __('zatca.settings.organization_name') . ':' }}</label>
                        <input type="text" name="organization_name" id="organization_name" class="form-control " readonly placeholder="{{ __('zatca.settings.organization_name') }}" value="{{ $zatca->organization_name }}">
                    </div>
                    <div class="form-group">
                        <label for="country_name">{{ __('zatca.settings.country_name') . ':' }}</label>
                        <select name="country_name" id="country_name" class="form-control" disabled>
                            <option value="">{{ __('zatca.settings.country_name') }}</option>
                            @foreach($countries as $key => $value)
                                <option value="{{ $key }}" {{ $key == $zatca->country_name ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="registered_address">{{ __('zatca.settings.registered_address') . ':' }}</label>
                        <input type="text" name="registered_address" id="registered_address" class="form-control" readonly placeholder="{{ __('zatca.settings.registered_address') }}" value="{{ $zatca->registered_address }}">
                    </div>
                    <div class="form-group">
                        <label for="otp">{{ __('zatca.settings.otp') . ':' }}</label>
                        <input type="text" name="otp" id="otp" class="form-control input_number" readonly placeholder="{{ __('zatca.settings.otp') }}" value="{{ $zatca->otp }}">
                    </div>
                    <div class="form-group">
                        <label for="email_address">{{ __('zatca.settings.email_address') . ':' }}</label>
                        <input type="text" name="email_address" id="email_address" class="form-control" readonly placeholder="{{ __('zatca.settings.email_address') }}" value="{{ $zatca->email_address }}">
                    </div>
                    <div class="form-group">
                        <label for="invoice_type">{{ __('zatca.settings.invoice_type') . ':' }}</label>
                        <select name="invoice_type" id="invoice_type" class="form-control" disabled>
                            <option value="">{{ __('zatca.settings.invoice_type') }}</option>
                            @foreach($issuingTypes as $key => $value)
                                <option value="{{ $key }}" {{ $key == $zatca->invoice_type ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="is_production">{{ __('zatca.settings.is_production') . ':' }}</label>
                        <input type="checkbox" name="is_production" id="is_production" class="form-check icheck" disabled {{ $zatca->is_production ? 'checked' : '' }}>
                    </div>


                </div>



            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>


    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

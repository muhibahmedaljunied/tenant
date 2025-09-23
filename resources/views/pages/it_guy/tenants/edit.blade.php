<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form action="{{ action('ItGuy\TenantController@update', $tenant->id) }}" method="POST" id="tenant_edit_form">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang('tenant.edit_tenant')</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="actual_name">{{ __('tenant.name') }}:*</label>
                        {{-- @foreach ($tenant as $value) --}}
                            <input type="text" name="actual_name" value="{{ $tenant->name }}" class="form-control"
                                required placeholder="{{ __('tenant.name') }}">
                        {{-- @endforeach --}}
                    </div>

                    <div class="form-group col-sm-12">
                        <label for="domain">{{ __('tenant.domain') }}:*</label>
                        {{-- @foreach ($tenant as $value) --}}
                            <input type="text" name="domain" value="{{ $tenant->domain->domain }}" class="form-control"
                                required placeholder="{{ __('tenant.domain') }}">
                        {{-- @endforeach --}}
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="account_status">{{ __('tenant.account_status') }}:*</label>

                        <select class="form-control">
                            @foreach ($account_status as $value)
                                <option value="{{ $value }}" {{ $value == $tenant->account_status ? 'selected' : '' }}>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="payment_status">{{ __('tenant.payment_status') }}:*</label>
                        <select class="form-control">
                            @foreach ($payment_status as $value)
                                <option value="{{ $value }}" {{ $value == $tenant->payment_status ? 'selected' : '' }} >
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-sm-12">
                        <label for="remarks">{{ __('tenant.remarks') }}:*</label>
                        <input type="text" name="remarks" value="{{ $tenant->remarks }}" class="form-control"
                            required placeholder="{{ __('tenant.remarks') }}">
                    </div>








                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
            </div>

        </form>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

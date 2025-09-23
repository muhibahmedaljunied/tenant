<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form action="{{ action('ItGuy\TenantController@store') }}" method="POST" id="tenant_add_form"
            enctype="multipart/form-data">
            @csrf

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang('tenant.add_tenant')</h4>
            </div>

            <div class="modal-body">
                <div class="row">


                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">@lang('tenant.name'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-id-badge"></i>
                                </span>
                                <input type="text" name="name" id="tenant_id" class="form-control"
                                    placeholder="@lang('tenant.name')">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">


                        <div class="form-group">
                            <label for="account_status">@lang('tenant.account_status'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                <select name="account_status" id="account_status" class="form-control" required>

                                    <option value="" disabled selected>@lang('messages.please_select')</option>
                                    @foreach ($account_status as $key => $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach


                                </select>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="domain">@lang('tenant.domain'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-briefcase"></i>
                                </span>
                                <input type="text" name="domain" id="domain" class="form-control"
                                    placeholder="@lang('tenant.domain')">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-4">


                        <div class="form-group">
                            <label for="payment_status">@lang('tenant.payment_status'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                <select name="payment_status" id="payment_status" class="form-control" required>

                                    <option value="" disabled selected>@lang('messages.please_select')</option>
                                    @foreach ($payment_status as $key => $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>


                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="remarks">{{ __('tenant.description') }}:*</label>
                            <input type="text" name="remarks" id="description" class="form-control" required
                                placeholder="{{ __('tenant.description') }}">
                        </div>
                    </div>







                </div>

            </div>

            <div class="modal-footer">
                {{-- <button type="submit" class="btn btn-primary">@lang('messages.save')</button> --}}
                <button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
            </div>

        </form>

    </div>
</div>

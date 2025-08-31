<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        @php
            $form_id = 'contact_add_form';
            // if (isset($quick_add)) {
            //     $form_id = 'quick_add_contact';
            // }

            // if (isset($store_action)) {
            //     $url = $store_action;
            //     $type = 'lead';
            //     $customer_groups = [];
            // } else {
                $url = action('PeriodController@store_account_period');
                $type = isset($selected_type) ? $selected_type : '';
                $sources = [];
                $life_stages = [];
                $users = [];
            // }
        @endphp
        <form action="{{ $url }}" method="POST" id="{{ $form_id }}">
            @csrf


            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang('chart_of_accounts.add_open_period')</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 contact_type_div">
                        <div class="form-group">
                            <label for="contact_type">@lang('chart_of_accounts.name_year'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                <select name="type" id="contact_type" class="form-control" required>
                                    <option value="" disabled selected>@lang('messages.please_select')</option>
                                    @foreach ($types as $key => $value)
                                        <option value="{{ $key }}" {{ $type == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="start_date">@lang('chart_of_accounts.start_period'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" name="start_date" id="start_date" class="form-control start-date-picker"
                                    placeholder="@lang('chart_of_accounts.start_period')" readonly >
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="start_date">@lang('chart_of_accounts.end_period'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" name="start_date" id="start_date" class="form-control start-date-picker"
                                    placeholder="@lang('chart_of_accounts.end_period')" readonly >
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="supplier_business_name">@lang('chart_of_accounts.status'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-briefcase"></i>
                                </span>
                                <input type="text" name="supplier_business_name" id="supplier_business_name"
                                    class="form-control" placeholder="@lang('chart_of_accounts.status')">
                            </div>
                        </div>
                    </div>
                

                 
                </div>
             
            
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
            </div>

        </form>


    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    $(document).on('change', '#tax_number', function(e) {
        // e.preventDefault();
        if ($(this).val().length > 0) {
            $('#address_line_1,#city,#state,#country,#zip_code').attr('required', '');
        } else {
            $('#address_line_1,#city,#state,#country,#zip_code').removeAttr('required');
        }
    })
</script>

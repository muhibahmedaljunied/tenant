<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        <form action="{{ action('AcMasterController@update', [$ac_master->id]) }}" method="POST" id="master_edit_form">
            @csrf
            @method('PUT')
            
            {{-- You can include a hidden input for href_redirect if needed --}}
            <input type="hidden" name="href_redirect" value="{{ action('AcMasterController@index') }}">
        
     
   
        

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('chart_of_accounts.edit_mastert')</h4>
        </div>

        <div class="modal-body">


            <div class="form-group">
                <label for="account_name_ar">{{ __('chart_of_accounts.account_name_ar') }}:*</label>
                <input type="text" name="account_name_ar" id="account_name_ar"
                       value="{{ old('account_name_ar', $ac_master->account_name_ar) }}"
                       class="form-control" required
                       placeholder="{{ __('chart_of_accounts.account_name_ar') }}">
            </div>
            
            <div class="form-group">
                <label for="account_name_en">{{ __('chart_of_accounts.account_name_en') }}</label>
                <input type="text" name="account_name_en" id="account_name_en"
                       value="{{ old('account_name_en', $ac_master->account_name_en) }}"
                       class="form-control"
                       placeholder="{{ __('chart_of_accounts.account_name_en') }}">
            </div>
            
            <div class="form-group">
                <label for="account_number">{{ __('chart_of_accounts.account_number') }}:</label>
                <input type="text" name="account_number" id="account_number"
                       value="{{ old('account_number', $ac_master->account_number) }}"
                       class="form-control" required readonly
                       placeholder="{{ __('lang_v1.starts_at') }}">
            </div>
            
            <div class="form-group">
                <label for="parent_acct_no">{{ __('chart_of_accounts.parent_acct_no') }}:</label>
                <select name="parent_acct_no" id="parent_acct_no" class="form-control select2">
                    <option value="" {{ is_null($ac_master->parent_acct_no) ? 'selected' : '' }} disabled>
                        @lang('chart_of_accounts.no_have_parent')
                    </option>
                    @foreach ($masters as $account)
                        <option value="{{ $account->account_number }}"
                            {{ $account->account_number == $ac_master->parent_acct_no ? 'selected' : '' }}>
                            {{ $account->account_name_ar }} ({{ $account->account_number }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="pay_collect" id="pay_collect" value="1"
                            {{ $ac_master->pay_collect ? 'checked' : '' }}>
                        @lang('chart_of_accounts.pay_collect')
                    </label>
                </div>
            </div>
            



        </div><!-- /.modal-body -->
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>

    </form>


    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

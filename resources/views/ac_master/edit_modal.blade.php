<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open([
            'url' => action('AcMasterController@update', [$ac_master->id]),
            'href_redirect' => action('AcMasterController@index'),
            'method' => 'PUT',
            'id' => 'master_edit_form',
        ]) !!}

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('chart_of_accounts.edit_mastert')</h4>
        </div>

        <div class="modal-body">


            <div class="form-group">
                {!! Form::label('account_name_ar', __('chart_of_accounts.account_name_ar') . ':*') !!}
                {!! Form::text('account_name_ar', $ac_master->account_name_ar, [
                    'class' => 'form-control',
                    'required',
                    'placeholder' => __('chart_of_accounts.account_name_ar'),
                ]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('account_name_en', __('chart_of_accounts.account_name_en')) !!}
                {!! Form::text('account_name_en', $ac_master->account_name_en, [
                    'class' => 'form-control',
                    'placeholder' => __('chart_of_accounts.account_name_en'),
                ]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('account_number', __('chart_of_accounts.account_number') . ':') !!}
                {!! Form::text('account_number', $ac_master->account_number, [
                    'class' => 'form-control',
                    'required',
                    'placeholder' => __('lang_v1.starts_at'),
                    'readonly',
                ]) !!}
            </div>

            <div class="form-group  ">
                {!! Form::label('parent_acct_no', __('chart_of_accounts.parent_acct_no') . ':') !!}

                <select name="parent_acct_no" class="form-control select2">
                    <option  {{ is_null($ac_master->parent_acct_no) ? 'selected':'' }} disabled>@lang('chart_of_accounts.no_have_parent')</option>
                    @foreach ($masters as $account)
                        <option value="{{ $account->account_number }}"
                            @if ($account->account_number == $ac_master->parent_acct_no) selected @endif>{{ $account->account_name_ar }} ({{ $account->account_number }})</option>
                    @endforeach

                </select>
            </div>

            <div class="form-group">
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('pay_collect', 1, $ac_master->pay_collect, ['id' => 'pay_collect']) !!} @lang('chart_of_accounts.pay_collect')</label>
                </div>
            </div>



        </div><!-- /.modal-body -->
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>

        {!! Form::close() !!}


    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

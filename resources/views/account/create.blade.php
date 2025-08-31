<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form action="{{ action('AccountController@store') }}" method="POST" id="payment_account_form">
            @csrf

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang('account.add_account')</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="name">@lang('lang_v1.name'):</label>
                    <input type="text" name="name" id="name" class="form-control" required placeholder="@lang('lang_v1.name')">
                </div>

                <div class="form-group">
                    <label for="account_number">@lang('account.account_number'):</label>
                    <input type="text" name="account_number" id="account_number" class="form-control" required placeholder="@lang('account.account_number')">
                </div>

                <div class="form-group">
                    <label for="account_type_id">@lang('account.account_type'):</label>
                    <select name="account_type_id" id="account_type_id" class="form-control select2">
                        <option>@lang('messages.please_select')</option>
                        @foreach($account_types as $account_type)
                            <optgroup label="{{ $account_type->name }}">
                                <option value="{{ $account_type->id }}">{{ $account_type->name }}</option>
                                @foreach($account_type->sub_types as $sub_type)
                                    <option value="{{ $sub_type->id }}">{{ $sub_type->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="opening_balance">@lang('account.opening_balance'):</label>
                    <input type="text" name="opening_balance" id="opening_balance" class="form-control input_number" placeholder="@lang('account.opening_balance')" value="0">
                </div>

                <div class="form-group">
                    <label for="note">@lang('brand.note')</label>
                    <textarea name="note" id="note" class="form-control" placeholder="@lang('brand.note')" rows="4"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
            </div>
        </form>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

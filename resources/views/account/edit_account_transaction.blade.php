<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ action('AccountController@updateAccountTransaction', ['id' => $account_transaction->id ]) }}" method="POST" id="edit_account_transaction_form">
      @csrf

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@if($account_transaction->sub_type == 'opening_balance')@lang( 'lang_v1.edit_opening_balance' ) @elseif($account_transaction->sub_type == 'fund_transfer') @lang( 'lang_v1.edit_fund_transfer' ) @elseif($account_transaction->sub_type == 'deposit') @lang( 'lang_v1.edit_deposit' ) @endif</h4>
    </div>

    <div class="modal-body">
            <div class="form-group">
                <strong>@lang('account.selected_account')</strong>: 
                {{$account_transaction->account->name}}
            </div>

            @if($account_transaction->sub_type == 'deposit')
            @php
              $label = !empty($account_transaction->type == 'debit') ? __( 'account.deposit_from' ) :  __('lang_v1.deposit_to');
            @endphp 
            <div class="form-group">  
                <label for="account_id">{{ $label .":" }}</label>
                <select name="account_id" id="account_id" class="form-control">
                  <option value="">{{ __('messages.please_select') }}</option>
                  @foreach($accounts as $key => $value)
                    <option value="{{ $key }}" {{ $account_transaction->account_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                  @endforeach
                </select>
            </div>
            @endif

            @if($account_transaction->sub_type == 'fund_transfer') 
            @php
              $label = !empty($account_transaction->type == 'credit') ? __( 'account.transfer_to' ) :__('lang_v1.transfer_from')  ;
            @endphp 
            <div class="form-group">  
                <label for="account_id">{{ $label .":" }}</label>
                <select name="account_id" id="account_id" class="form-control">
                  <option value="">{{ __('messages.please_select') }}</option>
                  @foreach($accounts as $key => $value)
                    <option value="{{ $key }}" {{ $account_transaction->account_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                  @endforeach
                </select>
            </div>
            @endif

            <div class="form-group">
                <label for="amount">{{ __( 'sale.amount' ) .":*" }}</label>
                <input type="text" name="amount" id="amount" value="{{ @num_format($account_transaction->amount) }}" class="form-control input_number" required placeholder="{{ __( 'sale.amount' ) }}">
            </div>
            @if($account_transaction->sub_type == 'deposit')
            @php
              $label = !empty($account_transaction->type == 'debit') ? __('lang_v1.deposit_to') :  __( 'account.deposit_from' );
            @endphp 
            <div class="form-group">  
                <label for="from_account">{{ $label .":" }}</label>
                <select name="from_account" id="from_account" class="form-control">
                  <option value="">{{ __('messages.please_select') }}</option>
                  @foreach($accounts as $key => $value)
                    <option value="{{ $key }}" {{ ($account_transaction->transfer_transaction->account_id ?? null) == $key ? 'selected' : '' }}>{{ $value }}</option>
                  @endforeach
                </select>
            </div>
            @endif
            @if($account_transaction->sub_type == 'fund_transfer') 
            @php
              $label = !empty($account_transaction->type == 'credit') ? __('lang_v1.transfer_from') :  __( 'account.transfer_to' );
            @endphp 
            <div class="form-group">
                <label for="to_account">{{ $label .":*" }}</label>
                <select name="to_account" id="to_account" class="form-control" required>
                  <option value="">{{ __('messages.please_select') }}</option>
                  @foreach($accounts as $key => $value)
                    <option value="{{ $key }}" {{ ($account_transaction->transfer_transaction->account_id ?? null) == $key ? 'selected' : '' }}>{{ $value }}</option>
                  @endforeach
                </select>
            </div>
            @endif
 
            <div class="form-group">
                <label for="operation_date">{{ __( 'messages.date' ) .":*" }}</label>
                <div class="input-group date">
                  <input type="text" name="operation_date" id="od_datetimepicker" value="{{ @format_datetime($account_transaction->operation_date) }}" class="form-control" required placeholder="{{ __( 'messages.date' ) }}">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
            </div>
            @if($account_transaction->sub_type == 'fund_transfer' || $account_transaction->sub_type == 'deposit')
            <div class="form-group">
                <label for="note">{{ __( 'brand.note' ) }}</label>
                <textarea name="note" id="note" class="form-control" placeholder="{{ __( 'brand.note' ) }}" rows="4">{{ $account_transaction->note }}</textarea>
            </div>
            @endif
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.submit' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script type="text/javascript">
  $(document).ready( function(){
    $('#od_datetimepicker').datetimepicker({
      format: moment_date_format + ' ' + moment_time_format
    });
  });
</script>
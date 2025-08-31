<div class="modal-dialog" role="document">
  <div class="modal-content">
    <form action="{{ action('TransactionPaymentController@store') }}" method="post" id="transaction_payment_add_form" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
      @if(!empty($transaction->location))
        <input type="hidden" name="default_payment_accounts" id="default_payment_accounts" value="{{ $transaction->location->default_payment_accounts }}">
      @endif
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'purchase.add_payment' )</h4>
      </div>
      <div class="modal-body">
        <div class="row">
        @if(!empty($transaction->contact))
          <div class="col-md-4">
            <div class="well">
              <strong>
              @if(in_array($transaction->type, ['purchase', 'purchase_return']))
                @lang('purchase.supplier') 
              @elseif(in_array($transaction->type, ['sell', 'sell_return']))
                @lang('contact.customer') 
              @endif
              </strong>:{{ $transaction->contact->name }}<br>
              @if($transaction->type == 'purchase')
              <strong>@lang('business.business'): </strong>{{ $transaction->contact->supplier_business_name }}
              @endif
            </div>
          </div>
        @endif
        <div class="col-md-4">
          <div class="well">
          @if(in_array($transaction->type, ['sell', 'sell_return']))
            <strong>@lang('sale.invoice_no'): </strong>{{ $transaction->invoice_no }}
          @else
            <strong>@lang('purchase.ref_no'): </strong>{{ $transaction->ref_no }}
          @endif
          @if(!empty($transaction->location))
            <br>
            <strong>@lang('purchase.location'): </strong>{{ $transaction->location->name }}
          @endif
          </div>
        </div>
        <div class="col-md-4">
          <div class="well">
            <strong>@lang('sale.total_amount'): </strong><span class="display_currency" data-currency_symbol="true">{{ $transaction->final_total }}</span><br>
            <strong>@lang('purchase.payment_note'): </strong>
            @if(!empty($transaction->additional_notes))
            {{ $transaction->additional_notes }}
            @else
              --
            @endif
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          @if(!empty($transaction->contact))
            <strong>@lang('lang_v1.advance_balance'):</strong> <span class="display_currency" data-currency_symbol="true">{{$transaction->contact->balance}}</span>
            <input type="hidden" name="advance_balance" id="advance_balance" value="{{$transaction->contact->balance}}" data-error-msg="{{ __('lang_v1.required_advance_balance_not_available') }}">
          @endif
        </div>
      </div>
      <div class="row payment_row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="amount">{{ __('sale.amount') }}:*</label>
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
              </span>
              <input type="text" name="amount" id="amount" class="form-control input_number" required placeholder="Amount" value="{{ @num_format($payment_line->amount) }}" data-rule-max-value="{{ @num_format($payment_line->amount) }}" data-msg-max-value="{{ __('lang_v1.max_amount_to_be_paid_is', ['amount' => $amount_formated]) }}">
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="paid_on">{{ __('lang_v1.paid_on') }}:*</label>
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </span>
              <input type="text" name="paid_on" id="paid_on" class="form-control" value="{{ @format_datetime($payment_line->paid_on) }}" readonly required>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="method">{{ __('purchase.payment_method') }}:*</label>
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
              </span>
              <select name="method" id="method" class="form-control select2 payment_types_dropdown" required style="width:100%;">
                @foreach($payment_types as $key => $value)
                  <option value="{{ $key }}" {{ $payment_line->method == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        @if(!empty($accounts))
          <div class="col-md-6">
            <div class="form-group">
              <label for="account_id">{{ __('lang_v1.payment_account') }}:</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fas fa-money-bill-alt"></i>
                </span>
                <select name="account_id" id="account_id" class="form-control select2" style="width:100%;">
                  <option value="">--</option>
                  @foreach($accounts as $key => $value)
                    <option value="{{ $key }}" {{ (!empty($payment_line->account_id) && $payment_line->account_id == $key) ? 'selected' : '' }}>{{ $value }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        @endif
        <div class="col-md-4">
          <div class="form-group">
            <label for="document">{{ __('purchase.attach_document') }}:</label>
            <input type="file" name="document" id="document" accept="{{ implode(',', array_keys(config('constants.document_upload_mimes_types'))) }}">
            <p class="help-block">
            @includeIf('components.document_help_text')</p>
          </div>
        </div>
        <div class="clearfix"></div>
          @include('transaction_payment.payment_type_details')
        <div class="col-md-12">
          <div class="form-group">
            <label for="note">{{ __('lang_v1.payment_note') }}:</label>
            <textarea name="note" id="note" class="form-control" rows="3">{{ $payment_line->note }}</textarea>
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
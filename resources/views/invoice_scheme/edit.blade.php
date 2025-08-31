<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ action('InvoiceSchemeController@update', [$invoice->id]) }}" method="post" id="invoice_scheme_add_form">
      @csrf
      @method('PUT')

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'invoice.edit_invoice' )</h4>
    </div>

    <div class="modal-body">
      <div class="row">
        <div class="option-div-group">
          <div class="col-sm-4">
            <div class="form-group">
              <div class="option-div @if($invoice->scheme_type == 'blank') {{ 'active'}} @endif">
                <h4>FORMAT: <br>XXXX <i class="fa fa-check-circle pull-right icon"></i></h4>
                <input type="radio" name="scheme_type" value="blank" @if($invoice->scheme_type == 'blank') {{ 'checked'}} @endif>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <div class="option-div  @if($invoice->scheme_type == 'year') {{ 'active'}} @endif">
                <h4>FORMAT: <br>{{ date('Y') }}-XXXX <i class="fa fa-check-circle pull-right icon"></i></h4>
                <input type="radio" name="scheme_type" value="year" @if($invoice->scheme_type == 'year') {{ 'checked'}} @endif>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <label>@lang('invoice.preview'):</label>
            <div id="preview_format">@lang('invoice.not_selected')</div>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
            <label for="name">{{ __( 'invoice.name' ) . ':*' }}</label>
            <input type="text" name="name" id="name" class="form-control" required placeholder="{{ __( 'invoice.name' ) }}" value="{{ old('name', $invoice->name) }}">
          </div>
        </div>
        <div id="invoice_format_settings">
        <div class="col-sm-6">
          <div class="form-group">
            <label for="prefix">{{ __( 'invoice.prefix' ) . ':' }}</label>
            <div class="input-group col-md-12 col-sm-12">
              <span class="input-group-addon">
                  <i class="fa fa-info"></i>
              </span>
                @php
                  $disabled = '';
                  $prefix = $invoice->prefix;
                  if( $invoice->scheme_type == 'year'){
                    $prefix = date('Y') . '-';
                    $disabled = 'disabled';
                  }
                @endphp
                <input type="text" name="prefix" id="prefix" class="form-control" placeholder="" value="{{ old('prefix', $prefix) }}" @if($disabled) disabled @endif>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="start_number">{{ __( 'invoice.start_number' ) . ':' }}</label>
            <div class="input-group col-md-12 col-sm-12">
              <span class="input-group-addon">
                  <i class="fa fa-info"></i>
              </span>
                <input type="number" name="start_number" id="start_number" class="form-control" required min="0" value="{{ old('start_number', $invoice->start_number) }}">
            </div>
          </div>
        </div>
        <div class="clearfix">
        <div class="col-sm-6">
          <div class="form-group">
            <label for="total_digits">{{ __( 'invoice.total_digits' ) . ':' }}</label>
            <div class="input-group col-md-12 col-sm-12">
              <span class="input-group-addon">
                  <i class="fa fa-info"></i>
              </span>
              <select name="total_digits" id="total_digits" class="form-control" required>
                @foreach(['4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9'=>'9', '10' => '10'] as $key => $value)
                  <option value="{{ $key }}" @if(old('total_digits', $invoice->total_digits) == $key) selected @endif>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>
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
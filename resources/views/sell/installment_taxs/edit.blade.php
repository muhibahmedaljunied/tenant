<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ action('TaxRateController@update', [$tax_rate->id]) }}" method="POST" id="tax_rate_edit_form">
      @csrf
      @method('PUT')

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'tax_rate.edit_taxt_rate' )</h4>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <label for="name">{{ __( 'tax_rate.name' ) . ':*' }}</label>
          <input type="text" name="name" id="name" class="form-control" required placeholder="{{ __( 'tax_rate.name' ) }}" value="{{ $tax_rate->name }}">
        </div>

        <div class="form-group">
          <label for="amount">{{ __( 'tax_rate.rate' ) . ':*' }}</label> @show_tooltip(__('lang_v1.tax_exempt_help'))
          <input type="text" name="amount" id="amount" class="form-control input_number" required value="{{ $tax_rate->amount }}">
        </div>
        <div class="form-group">
          <label for="count_months">{{ __( 'عدد الشهور' ) . ':' }}</label> @show_tooltip('خاص بضريبة القسط ')
          <input type="text" name="count_months" id="count_months" class="form-control input_number" value="{{ $tax_rate->count_months }}">
        </div>
        <div class="form-group">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="for_tax_group" value="1" class="input_icheck" {{ !empty($tax_rate->for_tax_group) ? 'checked' : '' }}> @lang( 'lang_v1.for_tax_group_only' )
            </label> @show_tooltip(__('lang_v1.for_tax_group_only_help'))
          </div>
        </div>
        <div class="form-group">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="is_composite" value="1" class="input_icheck" {{ !empty($tax_rate->is_composite) ? 'checked' : '' }}> فايدة مركبة
            </label> @show_tooltip(__('lang_v1.خاص بفوائد القسط'))
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <form action="{{ route('essentialsAllowanceAndDeduction-update', $allowance->id) }}" method="post" id="add_allowance_form">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'essentials::lang.edit_allowance_and_deduction' )</h4>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="form-group col-md-12">
          <label for="description">{{ __( 'lang_v1.description' ) . ':*' }}</label>
          <input type="text" name="description" id="description" class="form-control" required placeholder="{{ __( 'lang_v1.description' ) }}" value="{{ old('description', $allowance->description) }}">
        </div>
        <div class="form-group col-md-12">
          <label for="type">{{ __( 'lang_v1.type' ) . ':*' }}</label>
          <select name="type" id="type" class="form-control" required>
            <option value="allowance" {{ old('type', $allowance->type) == 'allowance' ? 'selected' : '' }}>{{ __('essentials::lang.allowance') }}</option>
            <option value="deduction" {{ old('type', $allowance->type) == 'deduction' ? 'selected' : '' }}>{{ __('essentials::lang.deduction') }}</option>
          </select>
        </div>
        <div class="form-group col-md-12">
          <label for="employees">{{ __('essentials::lang.employee') . ':*' }}</label>
          <select name="employees[]" id="employees" class="form-control select2" required multiple>
            @foreach($users as $key => $value)
              <option value="{{ $key }}" {{ (collect(old('employees', $selected_users))->contains($key)) ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-md-6">
          <label for="amount_type">{{ __( 'essentials::lang.amount_type' ) . ':*' }}</label>
          <select name="amount_type" id="amount_type" class="form-control" required>
            <option value="fixed" {{ old('amount_type', $allowance->amount_type) == 'fixed' ? 'selected' : '' }}>{{ __('lang_v1.fixed') }}</option>
            <option value="percent" {{ old('amount_type', $allowance->amount_type) == 'percent' ? 'selected' : '' }}>{{ __('lang_v1.percentage') }}</option>
          </select>
        </div>
        <div class="form-group col-md-6">
          <label for="amount">{{ __( 'sale.amount' ) . ':*' }}</label>
          <input type="text" name="amount" id="amount" class="form-control input_number" placeholder="{{ __( 'sale.amount' ) }}" required value="{{ old('amount', @num_format($allowance->amount)) }}">
        </div>
        <div class="form-group col-md-12">
          <label for="applicable_date">{{ __( 'essentials::lang.applicable_date' ) . ':' }}</label> @show_tooltip(__('essentials::lang.applicable_date_help'))
          <div class="input-group data">
            <input type="text" name="applicable_date" id="applicable_date" class="form-control" placeholder="{{ __( 'essentials::lang.applicable_date' ) }}" readonly value="{{ old('applicable_date', $applicable_date) }}">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
          </div>
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
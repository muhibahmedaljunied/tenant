<div class="modal fade" id="payroll_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
	<div class="modal-dialog" role="document">
	  <div class="modal-content">

	    <form action="{{ route('payroll-create') }}" method="get" id="add_payroll_step1">

	    <div class="modal-body">
	      	<div class="form-group">
	        	<label for="employee_id">{{ __( 'essentials::lang.employee' ) . ':*' }}</label>
	        	<select name="employee_id" id="employee_id" class="form-control select2" required style="width: 100%;">
                    <option value="">{{ __( 'messages.please_select' ) }}</option>
                    @foreach($employees as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
	      	</div>

	      	<div class="form-group">
	      		<label for="month_year">{{ __( 'essentials::lang.month_year' ) . ':*' }}</label>
	      		<div class="input-group">
                    <input type="text" name="month_year" id="month_year" class="form-control" placeholder="{{ __( 'essentials::lang.month_year' ) }}" required readonly value="{{ old('month_year') }}">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
	      	</div>
	    </div>

	    <div class="modal-footer">
	      <button type="submit" class="btn btn-primary">@lang( 'essentials::lang.proceed' )</button>
	      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
	    </div>

	    </form>

	  </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
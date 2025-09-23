<div class="modal-dialog modal-xl" role="document">
  <div class="modal-content">

    <form action="{{ route('attendance-store') }}" method="post" id="attendance_form">
      @csrf

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'essentials::lang.add_latest_attendance' )</h4>
      </div>

      <div class="modal-body">
      	<div class="row">
      		<div class="form-group col-md-12">
              <label for="employee">{{ __('essentials::lang.select_employee') . ':' }}</label>
              <select name="employee" id="select_employee" class="form-control select2" style="width: 100%;">
                <option value="">{{ __('essentials::lang.select_employee') }}</option>
                @foreach($employees as $key => $value)
                  <option value="{{ $key }}" {{ old('employee') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
      		</div>
      		<table class="table" id="employee_attendance_table">
      			<thead>
      				<th width="10%">@lang('essentials::lang.employee')</th>
      				<th width="15%">@lang('essentials::lang.clock_in_time')</th>
      				<th width="15%">@lang('essentials::lang.clock_out_time')</th>
      				<th width="15%">@lang('essentials::lang.shift')</th>
      				<th width="12%">@lang('essentials::lang.ip_address')</th>
      				<th width="15%">@lang('essentials::lang.clock_in_note')</th>
      				<th width="15%">@lang('essentials::lang.clock_out_note')</th>
      				<th width="3%">&nbsp;</th>
      			</thead>
      			<tbody>
      			</tbody>
      		</table>
      	</div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
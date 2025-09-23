<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ route('attendance-update', [$attendance->id]) }}" method="post" id="attendance_form">
      @csrf
      @method('PUT')
      <input type="hidden" name="employees" value="{{ $attendance->employee->id }}" id="employees">
      <input type="hidden" name="attendance_id" value="{{ $attendance->id }}" id="attendance_id">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'essentials::lang.edit_attendance' )</h4>
      </div>

      <div class="modal-body">
      	<div class="row">
      		<div class="form-group col-md-12">
      			<strong>@lang('essentials::lang.employees'): </strong> {{$attendance->employee->user_full_name}}
      		</div>
      		<div class="form-group col-md-6">
              <label for="clock_in_time">{{ __( 'essentials::lang.clock_in_time' ) . ':*' }}</label>
              <div class="input-group date">
                <input type="text" name="clock_in_time" id="clock_in_time" class="form-control" placeholder="{{ __( 'essentials::lang.clock_in_time' ) }}" value="{{ @format_datetime($attendance->clock_in_time) }}" readonly required>
                <span class="input-group-addon"><i class="fas fa-clock"></i></span>
              </div>
            </div>
            <div class="form-group col-md-6">
              <label for="clock_out_time">{{ __( 'essentials::lang.clock_out_time' ) . ':' }}</label>
              <div class="input-group date">
                <input type="text" name="clock_out_time" id="clock_out_time" class="form-control" placeholder="{{ __( 'essentials::lang.clock_out_time' ) }}" value="{{ !empty($attendance->clock_out_time) ? @format_datetime($attendance->clock_out_time) : '' }}" readonly>
                <span class="input-group-addon"><i class="fas fa-clock"></i></span>
              </div>
            </div>
            <div class="form-group col-md-6">
              <label for="ip_address">{{ __( 'essentials::lang.ip_address' ) . ':' }}</label>
              <input type="text" name="ip_address" id="ip_address" class="form-control" placeholder="{{ __( 'essentials::lang.ip_address') }}" value="{{ $attendance->ip_address }}">
            </div>
            <div class="form-group col-md-12">
              <label for="clock_in_note">{{ __( 'essentials::lang.clock_in_note' ) . ':' }}</label>
              <textarea name="clock_in_note" id="clock_in_note" class="form-control" placeholder="{{ __( 'essentials::lang.clock_in_note') }}" rows="3">{{ $attendance->clock_in_note }}</textarea>
            </div>
            <div class="form-group col-md-12">
              <label for="clock_out_note">{{ __( 'essentials::lang.clock_out_note' ) . ':' }}</label>
              <textarea name="clock_out_note" id="clock_out_note" class="form-control" placeholder="{{ __( 'essentials::lang.clock_out_note') }}" rows="3">{{ $attendance->clock_out_note }}</textarea>
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
<tr data-user_id="{{$user->id}}">
	<td>
		{{$user->user_full_name}}
	</td>
	<td>
		@if(empty($attendance->clock_in_time))
			<div class="input-group date">
				<input type="text" name="attendance[{{ $user->id }}][clock_in_time]" class="form-control date_time_picker" placeholder="{{ __( 'essentials::lang.clock_in_time' ) }}" readonly required>
				<span class="input-group-addon"><i class="fas fa-clock"></i></span>
			</div>
		@else
			{{@format_datetime($attendance->clock_in_time)}} <br>
			<small class="text-muted">(@lang('essentials::lang.clocked_in') - {{\Carbon::parse($attendance->clock_in_time)->diffForHumans(\Carbon::now())}})</small>

			<input type="hidden" name="attendance[{{ $user->id }}][id]" value="{{ $attendance->id }}">
		@endif
	</td>
	<td>
		<div class="input-group date">
			<input type="text" name="attendance[{{ $user->id }}][clock_out_time]" class="form-control date_time_picker" placeholder="{{ __( 'essentials::lang.clock_out_time' ) }}" readonly>
			<span class="input-group-addon"><i class="fas fa-clock"></i></span>
		</div>
	</td>
	<td>
		<select name="attendance[{{ $user->id }}][essentials_shift_id]" class="form-control">
			<option value="">{{ __( 'messages.please_select' ) }}</option>
			@foreach($shifts as $shift_id => $shift_name)
				<option value="{{ $shift_id }}" {{ (!empty($attendance->essentials_shift_id) && $attendance->essentials_shift_id == $shift_id) ? 'selected' : '' }}>{{ $shift_name }}</option>
			@endforeach
		</select>
	</td>
	<td>
		<input type="text" name="attendance[{{ $user->id }}][ip_address]" class="form-control" placeholder="{{ __( 'essentials::lang.ip_address') }}" value="{{ !empty($attendance->ip_address) ? $attendance->ip_address : '' }}">
	</td>
	<td>
		<textarea name="attendance[{{ $user->id }}][clock_in_note]" class="form-control" placeholder="{{ __( 'essentials::lang.clock_in_note') }}" rows="3">{{ !empty($attendance->clock_in_note) ? $attendance->clock_in_note : '' }}</textarea>
	</td>
	<td>
		<textarea name="attendance[{{ $user->id }}][clock_out_note]" class="form-control" placeholder="{{ __( 'essentials::lang.clock_out_note') }}" rows="3">{{ !empty($attendance->clock_out_note) ? $attendance->clock_out_note : '' }}</textarea>
	</td>
	<td><button type="button" class="btn btn-xs btn-danger remove_attendance_row"><i class="fa fa-times"></i></button></td>
</tr>
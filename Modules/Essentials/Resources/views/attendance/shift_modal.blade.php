<div class="modal-dialog" role="document">
  <div class="modal-content">
    <form action="{{ empty($shift) ? action('\\Modules\\Essentials\\Http\\Controllers\\ShiftController@store') : action('\\Modules\\Essentials\\Http\\Controllers\\ShiftController@update', [$shift->id]) }}" method="{{ empty($shift) ? 'post' : 'post' }}" id="add_shift_form">
      @csrf
      @if(!empty($shift))
        @method('PUT')
      @endif
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'essentials::lang.add_shift' )</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="name">{{ __( 'user.name' ) . ':*' }}</label>
          <input type="text" name="name" id="name" class="form-control" placeholder="{{ __( 'user.name') }}" value="{{ !empty($shift->name) ? $shift->name : '' }}" required>
        </div>
        <div class="form-group">
          <label for="shift_type">{{ __('essentials::lang.shift_type') . ':*' }}</label> @show_tooltip(__('essentials::lang.shift_type_tooltip'))
          <select name="type" id="shift_type" class="form-control select2" required>
            <option value="fixed_shift" {{ (!empty($shift->type) && $shift->type == 'fixed_shift') ? 'selected' : '' }}>{{ __('essentials::lang.fixed_shift') }}</option>
            <option value="flexible_shift" {{ (!empty($shift->type) && $shift->type == 'flexible_shift') ? 'selected' : '' }}>{{ __('essentials::lang.flexible_shift') }}</option>
          </select>
        </div>
        <div class="form-group time_div">
          <label for="start_time">{{ __( 'restaurant.start_time' ) . ':*' }}</label>
          <div class="input-group date">
            <input type="text" name="start_time" id="start_time" class="form-control" placeholder="{{ __( 'restaurant.start_time' ) }}" value="{{ !empty($shift->start_time) ? @format_time($shift->start_time) : '' }}" readonly required>
            <span class="input-group-addon"><i class="fas fa-clock"></i></span>
          </div>
        </div>
        <div class="form-group time_div">
          <label for="end_time">{{ __( 'restaurant.end_time' ) . ':*' }}</label>
          <div class="input-group date">
            <input type="text" name="end_time" id="end_time" class="form-control" placeholder="{{ __( 'restaurant.end_time' ) }}" value="{{ !empty($shift->end_time) ? @format_time($shift->end_time) : '' }}" readonly required>
            <span class="input-group-addon"><i class="fas fa-clock"></i></span>
          </div>
        </div>
        <div class="form-group">
          <label for="holidays">{{ __( 'essentials::lang.holiday' ) . ':' }}</label>
          <select name="holidays[]" id="holidays" class="form-control select2" multiple>
            @foreach($days as $day_key => $day_val)
              <option value="{{ $day_key }}" {{ (!empty($shift->holidays) && in_array($day_key, $shift->holidays)) ? 'selected' : '' }}>{{ $day_val }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.submit' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>
    </form>
  </div>
</div>
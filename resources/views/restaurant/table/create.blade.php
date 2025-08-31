<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ action('Restaurant\\TableController@store') }}" method="POST" id="table_add_form">
      @csrf

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'restaurant.add_table' )</h4>
      </div>

      <div class="modal-body">

        @if(count($business_locations) == 1)
          @php 
              $default_location = current(array_keys($business_locations->toArray())) 
          @endphp
        @else
          @php $default_location = null; @endphp
        @endif
        <div class="form-group">
          <label for="location_id">{{ __('purchase.business_location').':*' }}</label>
          <select name="location_id" id="location_id" class="form-control select2" required>
            <option value="">{{ __('messages.please_select') }}</option>
            @foreach($business_locations as $key => $value)
              <option value="{{ $key }}" {{ (string)$key === (string)$default_location ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
          </select>
        </div>
        
        <div class="form-group">
          <label for="name">{{ __( 'restaurant.table_name' ) . ':*' }}</label>
          <input type="text" name="name" id="name" class="form-control" required placeholder="{{ __( 'restaurant.table_name' ) }}">
        </div>

        <div class="form-group">
          <label for="description">{{ __( 'restaurant.short_description' ) . ':' }}</label>
          <input type="text" name="description" id="description" class="form-control" placeholder="{{ __( 'restaurant.short_description' ) }}">
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
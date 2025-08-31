<div class="modal fade" id="add_booking_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

         <form action="{{ action('Restaurant\BookingController@store') }}" method="post" id="add_booking_form">
    @csrf
    <!-- Your form fields go here -->


            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang('restaurant.add_booking')</h4>
            </div>

            <div class="modal-body">
                @if (count($business_locations) == 1)
                    @php
                        $default_location = current(array_keys($business_locations->toArray()));
                    @endphp
                @else
                    @php $default_location = null; @endphp
                @endif
                <div class="row">
<div class="col-sm-12">
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
            </span>
            <select id="booking_location_id" name="location_id" class="form-control" required>
                <option value="">{{ __('purchase.business_location') }}</option>
                @foreach($business_locations as $key => $value)
                    <option value="{{ $key }}" {{ $default_location == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="clearfix"></div>

<div class="col-sm-6">
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-user"></i>
            </span>
            <select id="booking_customer_id" name="contact_id" class="form-control" required>
                <option value="">{{ __('contact.customer') }}</option>
                @foreach($customers as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            <span class="input-group-btn">
                <button type="button" class="btn btn-default bg-white btn-flat add_new_customer" data-name="" @if(!auth()->user()->can('customer.create')) disabled @endif>
                    <i class="fa fa-plus-circle text-primary fa-lg"></i>
                </button>
            </span>
        </div>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-user"></i>
            </span>
            <select id="correspondent" name="correspondent" class="form-control">
                <option value="">{{ __('restaurant.select_correspondent') }}</option>
                @foreach($correspondents as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="clearfix"></div>
<div id="restaurant_module_span"></div>
<div class="clearfix"></div>

                    {{--  --}}
             <div class="col-sm-6">
    <div class="form-group">
        <label for="start_time">{{ __('restaurant.start_time') }}:*</label>
        <div class="input-group date">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
            <input type="text" id="start_time" name="booking_start" class="form-control" placeholder="{{ __('restaurant.start_time') }}" required readonly>
        </div>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
        <label for="end_time">{{ __('restaurant.end_time') }}:*</label>
        <div class="input-group date">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
            <input type="text" id="end_time" name="booking_end" class="form-control" placeholder="{{ __('restaurant.end_time') }}" required readonly>
        </div>
    </div>
</div>

<div class="col-sm-12">
    <div class="form-group">
        <label for="booking_note">{{ __('restaurant.customer_note') }}:</label>
        <textarea id="booking_note" name="booking_note" class="form-control" placeholder="{{ __('restaurant.customer_note') }}" rows="3"></textarea>
    </div>
</div>

<div class="col-sm-12">
    <div class="form-group">
        <div class="checkbox">
            <label>
                <input type="checkbox" id="send_notification" name="send_notification" value="1" class="input-icheck" checked>
                {{ __('restaurant.send_notification_to_customer') }}
            </label>
        </div>
    </div>
</div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
                </div>

                </form>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

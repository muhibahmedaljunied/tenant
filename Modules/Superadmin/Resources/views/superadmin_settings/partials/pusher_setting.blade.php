<div class="pos-tab-content">
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group">
            	<label for="PUSHER_APP_ID">{{ __('superadmin::lang.pusher_app_id') }}:</label>
            	<input type="text" name="PUSHER_APP_ID" id="PUSHER_APP_ID" class="form-control" placeholder="{{ __('superadmin::lang.pusher_app_id') }}" value="{{ old('PUSHER_APP_ID', $default_values['PUSHER_APP_ID']) }}">
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <label for="PUSHER_APP_KEY">{{ __('superadmin::lang.pusher_app_key') }}:</label>
                <input type="text" name="PUSHER_APP_KEY" id="PUSHER_APP_KEY" class="form-control" placeholder="{{ __('superadmin::lang.pusher_app_key') }}" value="{{ old('PUSHER_APP_KEY', $default_values['PUSHER_APP_KEY']) }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group">
                <label for="PUSHER_APP_SECRET">{{ __('superadmin::lang.pusher_app_secret') }}:</label>
                <input type="text" name="PUSHER_APP_SECRET" id="PUSHER_APP_SECRET" class="form-control" placeholder="{{ __('superadmin::lang.pusher_app_secret') }}" value="{{ old('PUSHER_APP_SECRET', $default_values['PUSHER_APP_SECRET']) }}">
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <label for="PUSHER_APP_CLUSTER">{{ __('superadmin::lang.pusher_app_cluster') }}:</label>
                <input type="text" name="PUSHER_APP_CLUSTER" id="PUSHER_APP_CLUSTER" class="form-control" placeholder="{{ __('superadmin::lang.pusher_app_cluster') }}" value="{{ old('PUSHER_APP_CLUSTER', $default_values['PUSHER_APP_CLUSTER']) }}">
            </div>
        </div>
    </div>
</div>
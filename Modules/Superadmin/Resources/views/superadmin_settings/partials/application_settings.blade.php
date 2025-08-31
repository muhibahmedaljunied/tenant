<div class="pos-tab-content">
    <div class="row">
    	<div class="col-xs-4">
            <div class="form-group">
            	<label for="APP_NAME">{{ __('superadmin::lang.app_name') }}:</label>
            	<input type="text" name="APP_NAME" id="APP_NAME" class="form-control" placeholder="{{ __('superadmin::lang.app_name') }}" value="{{ old('APP_NAME', $default_values['APP_NAME']) }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
            	<label for="APP_TITLE">{{ __('superadmin::lang.app_title') }}:</label>
            	<input type="text" name="APP_TITLE" id="APP_TITLE" class="form-control" placeholder="{{ __('superadmin::lang.app_title') }}" value="{{ old('APP_TITLE', $default_values['APP_TITLE']) }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
            	<label for="APP_LOCALE">{{ __('superadmin::lang.app_default_language') }}:</label>
            	<select name="APP_LOCALE" id="APP_LOCALE" class="form-control">
            		@foreach($languages as $key => $value)
            			<option value="{{ $key }}" {{ old('APP_LOCALE', $default_values['APP_LOCALE']) == $key ? 'selected' : '' }}>{{ $value }}</option>
            		@endforeach
            	</select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group">
                <label>
                <input type="checkbox" name="ALLOW_REGISTRATION" value="1" class="input-icheck" {{ old('ALLOW_REGISTRATION', !empty($default_values['ALLOW_REGISTRATION'])) ? 'checked' : '' }}>
                @lang('superadmin::lang.allow_registration')
                </label>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <label>
                <input type="checkbox" name="superadmin_enable_register_tc" value="1" class="input-icheck" {{ old('superadmin_enable_register_tc', !empty($settings['superadmin_enable_register_tc'])) ? 'checked' : '' }}>
                @lang('superadmin::lang.enable_register_tc')
                </label>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-12">
            <div class="form-group">
                <label for="superadmin_register_tc">{{ __('superadmin::lang.register_tc') }}:</label>
                <textarea name="superadmin_register_tc" id="superadmin_register_tc" class="form-control">{{ old('superadmin_register_tc', !empty($settings['superadmin_register_tc']) ? $settings['superadmin_register_tc'] : '') }}</textarea>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="GOOGLE_MAP_API_KEY">{{ __('superadmin::lang.google_map_api_key') }}:</label>
                <input type="text" name="GOOGLE_MAP_API_KEY" id="GOOGLE_MAP_API_KEY" class="form-control" placeholder="{{ __('superadmin::lang.google_map_api_key') }}" value="{{ old('GOOGLE_MAP_API_KEY', $default_values['GOOGLE_MAP_API_KEY']) }}">
            </div>
        </div>
    </div>
</div>
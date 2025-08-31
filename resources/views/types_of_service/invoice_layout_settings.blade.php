<div class="box box-solid">
    <div class="box-header">
      <h3 class="box-title">@lang('lang_v1.types_of_service_module_settings')</h3>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-sm-3">
          <div class="form-group">
            <label for="types_of_service_label">{{ __('lang_v1.types_of_service_label') }}:</label>
            <input type="text" name="module_info[types_of_service][types_of_service_label]" id="types_of_service_label" class="form-control" placeholder="{{ __('lang_v1.types_of_service_label') }}" value="{{ !empty($module_info['types_of_service']['types_of_service_label']) ? $module_info['types_of_service']['types_of_service_label'] : '' }}">
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <br>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="module_info[types_of_service][show_types_of_service]" value="1" class="input-icheck" {{ !empty($module_info['types_of_service']['show_types_of_service']) ? 'checked' : '' }}> @lang('lang_v1.show_types_of_service')
              </label>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <br>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="module_info[types_of_service][show_tos_custom_fields]" value="1" class="input-icheck" {{ !empty($module_info['types_of_service']['show_tos_custom_fields']) ? 'checked' : '' }}> @lang('lang_v1.show_tos_custom_fields')
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
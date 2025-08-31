<div class="pos-tab-content">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="theme_color">@lang('lang_v1.theme_color'):</label>
                <select name="theme_color" id="theme_color" class="form-control select2" style="width:100%;">
                    <option value="" disabled selected>@lang('messages.please_select')</option>
                    @foreach ($theme_colors as $key => $value)
                        <option value="{{ $key }}" {{ $business->theme_color == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="default_datatable_page_entries">@lang('lang_v1.default_datatable_page_entries'):</label>
                <select name="common_settings[default_datatable_page_entries]" id="default_datatable_page_entries"
                    class="form-control select2" style="width:100%;">
                    @foreach ([25, 50, 100, 200, 500, 1000, -1] as $entries)
                        <option value="{{ $entries }}"
                            {{ !empty($common_settings['default_datatable_page_entries']) && $common_settings['default_datatable_page_entries'] == $entries ? 'selected' : '' }}>
                            {{ $entries == -1 ? __('lang_v1.all') : $entries }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_tooltip" class="input-icheck" value="1"
                            {{ $business->enable_tooltip ? 'checked' : '' }}>
                        @lang('business.show_help_text')
                    </label>
                </div>
            </div>
        </div>

    </div>
</div>

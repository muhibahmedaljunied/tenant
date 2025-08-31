<div class="pos-tab-content">
    <div class="row">
    	<div class="col-xs-4">
            <div class="form-group">
                <label for="essentials_todos_prefix">{{ __('essentials::lang.essentials_todos_prefix') . ':' }}</label>
                <input type="text" name="essentials_todos_prefix" id="essentials_todos_prefix" class="form-control" placeholder="{{ __('essentials::lang.essentials_todos_prefix') }}" value="{{ !empty($settings['essentials_todos_prefix']) ? $settings['essentials_todos_prefix'] : '' }}">
            </div>
        </div>
    </div>
</div>
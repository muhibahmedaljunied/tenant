<div class="pos-tab-content active">
	<div class="row">
		<div class="col-xs-4">
            <div class="form-group">
            	<label for="leave_ref_no_prefix">{{ __('essentials::lang.leave_ref_no_prefix') . ':' }}</label>
            	<input type="text" name="leave_ref_no_prefix" id="leave_ref_no_prefix" class="form-control" placeholder="{{ __('essentials::lang.leave_ref_no_prefix') }}" value="{{ !empty($settings['leave_ref_no_prefix']) ? $settings['leave_ref_no_prefix'] : '' }}">
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <label for="leave_instructions">{{ __('essentials::lang.leave_instructions') . ':' }}</label>
                <textarea name="leave_instructions" id="leave_instructions" class="form-control" placeholder="{{ __('essentials::lang.leave_instructions') }}">{{ !empty($settings['leave_instructions']) ? $settings['leave_instructions'] : '' }}</textarea>
            </div>
        </div>
	</div>
</div>
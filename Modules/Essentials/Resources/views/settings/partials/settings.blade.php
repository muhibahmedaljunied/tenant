<div class="pos-tab-content">
    <div class="row">
    	<div class="col-xs-4">
            <div class="form-group">
            	<label for="leave_ref_no_prefix">{{ __('essentials::lang.leave_ref_no_prefix') . ':' }}</label>
            	<input type="text" name="leave_ref_no_prefix" id="leave_ref_no_prefix" class="form-control" placeholder="{{ __('essentials::lang.leave_ref_no_prefix') }}" value="{{ !empty($settings['leave_ref_no_prefix']) ? $settings['leave_ref_no_prefix'] : '' }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="payroll_ref_no_prefix">{{ __('essentials::lang.payroll_ref_no_prefix') . ':' }}</label>
                <input type="text" name="payroll_ref_no_prefix" id="payroll_ref_no_prefix" class="form-control" placeholder="{{ __('essentials::lang.payroll_ref_no_prefix') }}" value="{{ !empty($settings['payroll_ref_no_prefix']) ? $settings['payroll_ref_no_prefix'] : '' }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="checkbox">
                <label>
                    <br/>
                    <input type="checkbox" name="allow_users_for_attendance" value="1" class="input-icheck" {{ !empty($settings['allow_users_for_attendance']) ? 'checked' : '' }}> @lang('essentials::lang.allow_users_for_attendance')
                </label>
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
<div class="pos-tab-content">
	<div class="row">
		<div class="col-xs-4">
            <div class="form-group">
                <label for="payroll_ref_no_prefix">{{ __('essentials::lang.payroll_ref_no_prefix') . ':' }}</label>
                <input type="text" name="payroll_ref_no_prefix" id="payroll_ref_no_prefix" class="form-control" placeholder="{{ __('essentials::lang.payroll_ref_no_prefix') }}" value="{{ !empty($settings['payroll_ref_no_prefix']) ? $settings['payroll_ref_no_prefix'] : '' }}">
            </div>
        </div>
	</div>
</div>
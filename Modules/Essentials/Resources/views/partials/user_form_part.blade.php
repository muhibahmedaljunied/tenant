<div class="row">
	<div class="col-md-12">
		@component('components.widget', ['title' => __('essentials::lang.hrm_details')])
	<div class="col-md-4">
    <div class="form-group">
        <label for="essentials_department_id">@lang('essentials::lang.department'):</label>
        <div class="form-group">
            <select name="essentials_department_id" id="essentials_department_id" class="form-control select2" style="width: 100%;" placeholder="@lang('messages.please_select')">
                @foreach ($departments as $key => $value)
                    <option value="{{ $key }}" {{ !empty($user->essentials_department_id) && $user->essentials_department_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group">
        <label for="essentials_designation_id">@lang('essentials::lang.designation'):</label>
        <div class="form-group">
            <select name="essentials_designation_id" id="essentials_designation_id" class="form-control select2" style="width: 100%;" placeholder="@lang('messages.please_select')">
                @foreach ($designations as $key => $value)
                    <option value="{{ $key }}" {{ !empty($user->essentials_designation_id) && $user->essentials_designation_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

		@endcomponent
	</div>
</div>
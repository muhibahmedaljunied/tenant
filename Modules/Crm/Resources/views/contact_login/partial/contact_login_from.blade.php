@php
	$index = isset($index) ? (int) $index : '';
@endphp
<div class="row">
	<div class="col-md-12">
		<hr>
		<button type="button" class="btn btn-primary more_btn" data-target="#add_contact_person_div_{{$index}}">@lang('crm::lang.add_contact_person', ['number' => $index + 1]) <i class="fa fa-chevron-down"></i></button>
	</div>
</div>
<br>
<div class="row @if($index !== 0)hide @endif" id="add_contact_person_div_{{$index}}">
<div class="col-md-2">
    <div class="form-group">
        <label for="surname{{ $index }}">@lang('business.prefix'):</label>
        <input type="text" name="{{ $index === '' ? 'surname' : "contact_persons[$index][surname]" }}" id="surname{{ $index }}" class="form-control" placeholder="@lang('business.prefix_placeholder')">
    </div>
</div>

<div class="col-md-5">
    <div class="form-group">
        <label for="first_name{{ $index }}">@lang('business.first_name'):</label>
        <input type="text" name="{{ $index === '' ? 'first_name' : "contact_persons[$index][first_name]" }}" id="first_name{{ $index }}" class="form-control" required placeholder="@lang('business.first_name')">
    </div>
</div>

<div class="col-md-5">
    <div class="form-group">
        <label for="last_name{{ $index }}">@lang('business.last_name'):</label>
        <input type="text" name="{{ $index === '' ? 'last_name' : "contact_persons[$index][last_name]" }}" id="last_name{{ $index }}" class="form-control" placeholder="@lang('business.last_name')">
    </div>
</div>

<div class="clearfix"></div>

<div class="col-md-6">
    <div class="form-group">
        <label for="email{{ $index }}">@lang('business.email'):</label>
        <input type="text" name="{{ $index === '' ? 'email' : "contact_persons[$index][email]" }}" id="email{{ $index }}" class="form-control" placeholder="@lang('business.email')">
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="contact_number{{ $index }}">@lang('lang_v1.mobile_number'):</label>
        <input type="text" name="{{ $index === '' ? 'contact_number' : "contact_persons[$index][contact_number]" }}" id="contact_number{{ $index }}" class="form-control" placeholder="@lang('lang_v1.mobile_number')" value="{{ !empty($user->contact_number) ? $user->contact_number : '' }}">
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="alt_number{{ $index }}">@lang('business.alternate_number'):</label>
        <input type="text" name="{{ $index === '' ? 'alt_number' : "contact_persons[$index][alt_number]" }}" id="alt_number{{ $index }}" class="form-control" placeholder="@lang('business.alternate_number')" value="{{ !empty($user->alt_number) ? $user->alt_number : '' }}">
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="family_number{{ $index }}">@lang('lang_v1.family_contact_number'):</label>
        <input type="text" name="{{ $index === '' ? 'family_number' : "contact_persons[$index][family_number]" }}" id="family_number{{ $index }}" class="form-control" placeholder="@lang('lang_v1.family_contact_number')" value="{{ !empty($user->family_number) ? $user->family_number : '' }}">
    </div>
</div>

<div class="clearfix"></div>

<div class="col-md-12">
    <div class="form-group">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="{{ $index === '' ? 'allow_login' : "contact_persons[$index][allow_login]" }}" value="1" class="input-icheck allow_login" data-loginDiv="loginDiv{{ $index }}">
                @lang('lang_v1.allow_login')
            </label>
        </div>
    </div>
</div>

</div>
<div class="row hide" id="loginDiv{{$index}}">
	<div class="col-md-6">
    <div class="form-group">
        <label for="username{{ $index }}">@lang('business.username'):</label>
        <input type="text" name="{{ $index ==='' ? 'username' : "contact_persons[$index][username]" }}" id="username{{ $index }}" class="form-control" required placeholder="@lang('business.username')">
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="password{{ $index }}">@lang('business.password'):</label>
        <input type="password" name="{{ $index === '' ? 'password' : "contact_persons[$index][password]" }}" id="password{{ $index }}" class="form-control" required placeholder="@lang('business.password')">
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="confirm_password{{ $index }}">@lang('business.confirm_password'):</label>
        <input type="password" name="{{ $index === '' ? 'confirm_password' : "contact_persons[$index][confirm_password]" }}" id="confirm_password{{ $index }}" class="form-control" required placeholder="@lang('business.confirm_password')" data-rule-equalTo="#password{{ $index }}">
    </div>
</div>

<div class="clearfix"></div>

<div class="col-md-4">
    <div class="form-group">
        <label>
            <input type="checkbox" name="{{ $index === '' ? 'is_active' : "contact_persons[$index][is_active]" }}" value="active" class="input-icheck status" checked>
            @lang('lang_v1.status_for_user')
        </label>
        @show_tooltip(__('lang_v1.tooltip_enable_user_active'))
    </div>
</div>

</div>
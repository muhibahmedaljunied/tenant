@extends('layouts.app')
@section('title', __('lang_v1.my_profile'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('lang_v1.my_profile')</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
<form action="{{ action('UserController@updatePassword') }}" method="post" id="edit_password_form" class="form-horizontal">
    @csrf
<div class="row">
    <div class="col-sm-12">
        <div class="box box-solid"> <!--business info box start-->
            <div class="box-header">
                <div class="box-header">
                    <h3 class="box-title"> @lang('user.change_password')</h3>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="current_password" class="col-sm-3 control-label">{{ __('user.current_password') }}:</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-lock"></i>
                            </span>
                            <input type="password" name="current_password" id="current_password" class="form-control" placeholder="{{ __('user.current_password') }}" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="new_password" class="col-sm-3 control-label">{{ __('user.new_password') }}:</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-lock"></i>
                            </span>
                            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="{{ __('user.new_password') }}" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm_password" class="col-sm-3 control-label">{{ __('user.confirm_new_password') }}:</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-lock"></i>
                            </span>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="{{ __('user.confirm_new_password') }}" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary pull-right">@lang('messages.update')</button>
            </div>
        </div>
    </div>
</div>
</form>
<form action="{{ action('UserController@updateProfile') }}" method="post" id="edit_user_profile_form" enctype="multipart/form-data">
    @csrf
<div class="row">
    <div class="col-sm-8">
        <div class="box box-solid"> <!--business info box start-->
            <div class="box-header">
                <div class="box-header">
                    <h3 class="box-title"> @lang('user.edit_profile')</h3>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group col-md-2">
                    <label for="surname">{{ __('business.prefix') }}:</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-info"></i>
                        </span>
                        <input type="text" name="surname" id="surname" class="form-control" placeholder="{{ __('business.prefix_placeholder') }}" value="{{ $user->surname }}">
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="first_name">{{ __('business.first_name') }}:</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-info"></i>
                        </span>
                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="{{ __('business.first_name') }}" value="{{ $user->first_name }}" required>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label for="last_name">{{ __('business.last_name') }}:</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-info"></i>
                        </span>
                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="{{ __('business.last_name') }}" value="{{ $user->last_name }}">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="email">{{ __('business.email') }}:</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-info"></i>
                        </span>
                        <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('business.email') }}" value="{{ $user->email }}">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="language">{{ __('business.language') }}:</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-info"></i>
                        </span>
                        <select name="language" id="language" class="form-control select2">
                            @foreach($languages as $key => $value)
                                <option value="{{ $key }}" {{ $user->language == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @component('components.widget', ['title' => __('lang_v1.profile_photo')])
            @if(!empty($user->media))
                <div class="col-md-12 text-center">
                    {!! $user->media->thumbnail([150, 150], 'img-circle') !!}
                </div>
            @endif
            <div class="col-md-12">
                <div class="form-group">
                    <label for="profile_photo">{{ __('lang_v1.upload_image') }}:</label>
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*">
                    <small><p class="help-block">@lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])</p></small>
                </div>
            </div>
        @endcomponent
    </div>
</div>
@include('user.edit_profile_form_part', ['bank_details' => !empty($user->bank_details) ? json_decode($user->bank_details, true) : null])
<div class="row">
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary pull-right">@lang('messages.update')</button>
    </div>
</div>
</form>

</section>
<!-- /.content -->
@endsection
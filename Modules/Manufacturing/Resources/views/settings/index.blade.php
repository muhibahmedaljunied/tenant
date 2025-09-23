@extends('layouts.app')
@section('title', __('messages.settings'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('messages.settings')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        {{-- <form action="{{ action('\Modules\Manufacturing\Http\Controllers\SettingsController@store') }}" method="post"
            id="manufacturing_settings_form"> --}}
            <form action="{{ route('settings-store') }}" method="post"
                id="manufacturing_settings_form">

                @csrf
                <!-- Your form fields go here -->

                <div class="row">
                    <div class="col-xs-12">
                        <!--  <pos-tab-container> -->
                        <div class="col-xs-12 pos-tab-container">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pos-tab-menu">
                                <div class="list-group">
                                    <a href="#" class="list-group-item text-center active">@lang('messages.settings')</a>
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">
                                <div class="pos-tab-content active">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label
                                                    for="ref_no_prefix">{{ __('manufacturing::lang.mfg_ref_no_prefix') }}:</label>
                                                <input type="text" id="ref_no_prefix" name="ref_no_prefix"
                                                    value="{{ !empty($manufacturing_settings['ref_no_prefix']) ? $manufacturing_settings['ref_no_prefix'] : '' }}"
                                                    placeholder="{{ __('manufacturing::lang.mfg_ref_no_prefix') }}"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <br>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="disable_editing_ingredient_qty"
                                                            name="disable_editing_ingredient_qty" value="1"
                                                            class="input-icheck"
                                                            {{ !empty($manufacturing_settings['disable_editing_ingredient_qty']) ? 'checked' : '' }}>
                                                        {{ __('manufacturing::lang.disable_editing_ingredient_qty') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <br>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="enable_updating_product_price"
                                                            name="enable_updating_product_price" value="1"
                                                            class="input-icheck"
                                                            {{ !empty($manufacturing_settings['enable_updating_product_price']) ? 'checked' : '' }}>
                                                        {{ __('manufacturing::lang.enable_editing_product_price_after_production') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--  </pos-tab-container> -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary pull-right">@lang('messages.update')</button>
                    </div>
                </div>

                <div class="col-xs-12">
                    <p class="help-block"><i>{!! __('manufacturing::lang.version_info', ['version' => $version]) !!}</i></p>
                </div>
            </form>
    </section>
@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $(".file-input").fileinput(fileinput_setting);
        });
    </script>

@endsection

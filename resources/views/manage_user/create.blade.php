@extends('layouts.app')

@section('title', __('user.add_user'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('user.add_user')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <form action="{{ action('ManageUserController@store') }}" method="POST" id="user_add_form">


        <div class="row">
            <div class="col-md-12">
                @component('components.widget')
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="surname">@lang('business.prefix'):</label>
                            <input type="text" name="surname" id="surname" class="form-control"
                                placeholder="@lang('business.prefix_placeholder')">
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="first_name">@lang('business.first_name'):</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" required
                                placeholder="@lang('business.first_name')">
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="last_name">@lang('business.last_name'):</label>
                            <input type="text" name="last_name" id="last_name" class="form-control"
                                placeholder="@lang('business.last_name')">
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">@lang('business.email'):</label>
                            <input type="text" name="email" id="email" class="form-control" required
                                placeholder="@lang('business.email')">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="checkbox">
                                <br />
                                <label>
                                    <input type="checkbox" name="is_active" value="active" class="input-icheck status" checked>
                                    @lang('lang_v1.status_for_user')
                                </label>
                                @show_tooltip(__('lang_v1.tooltip_enable_user_active'))
                            </div>
                        </div>
                    </div>
                @endcomponent
            </div>
            <div class="col-md-12">
                @component('components.widget', ['title' => __('lang_v1.roles_and_permissions')])
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="allow_login" value="1" class="input-icheck"
                                        id="allow_login" checked>
                                    @lang('lang_v1.allow_login')
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="user_auth_fields">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="username">@lang('business.username'):</label>
                                @if (!empty($username_ext))
                                    <div class="input-group">
                                        <input type="text" name="username" id="username" class="form-control"
                                            placeholder="@lang('business.username')">
                                        <span class="input-group-addon">{{ $username_ext }}</span>
                                    </div>
                                    <p class="help-block" id="show_username"></p>
                                @else
                                    <input type="text" name="username" id="username" class="form-control"
                                        placeholder="@lang('business.username')">
                                @endif
                                <p class="help-block">@lang('lang_v1.username_help')</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="password">@lang('business.password'):</label>
                                <input type="password" name="password" id="password" class="form-control" required
                                    placeholder="@lang('business.password')">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="confirm_password">@lang('business.confirm_password'):</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                                    required placeholder="@lang('business.confirm_password')">
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role">@lang('user.role'):</label>
                            @show_tooltip(__('lang_v1.admin_role_location_permission_help'))
                            <select name="role" id="role" class="form-control select2">
                                @foreach ($roles as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <h4>@lang('role.access_locations') @show_tooltip(__('tooltip.access_locations_permission'))</h4>
                    </div>

                    <div class="col-md-9">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="access_all_locations" value="access_all_locations"
                                        class="input-icheck" checked>
                                    @lang('role.all_locations')
                                </label>
                                @show_tooltip(__('tooltip.all_location_permission'))
                            </div>
                        </div>

                        @foreach ($locations as $location)
                            <div class="col-md-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="location_permissions[]"
                                            value="location.{{ $location->id }}" class="input-icheck">
                                        {{ $location->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endcomponent
            </div>


            <div class="col-md-12">
                @component('components.widget', ['title' => __('sale.sells')])
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cmmsn_percent">@lang('lang_v1.cmmsn_percent'):</label>
                            @show_tooltip(__('lang_v1.commsn_percent_help'))
                            <input type="text" name="cmmsn_percent" id="cmmsn_percent" class="form-control input_number"
                                placeholder="@lang('lang_v1.cmmsn_percent')">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="max_sales_discount_percent">@lang('lang_v1.max_sales_discount_percent'):</label>
                            @show_tooltip(__('lang_v1.max_sales_discount_percent_help'))
                            <input type="text" name="max_sales_discount_percent" id="max_sales_discount_percent"
                                class="form-control input_number" placeholder="@lang('lang_v1.max_sales_discount_percent')">
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="checkbox">
                                <br />
                                <label>
                                    <input type="checkbox" name="selected_contacts" value="1" class="input-icheck"
                                        id="selected_contacts">
                                    @lang('lang_v1.allow_selected_contacts')
                                </label>
                                @show_tooltip(__('lang_v1.allow_selected_contacts_tooltip'))
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 hide selected_contacts_div">
                        <div class="form-group">
                            <label for="selected_contacts">@lang('lang_v1.selected_contacts'):</label>
                            <div class="form-group">
                                <select name="selected_contact_ids[]" id="selected_contacts" class="form-control select2"
                                    multiple style="width: 100%;">
                                    @foreach ($contacts as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endcomponent
            </div>


        </div>
        @include('user.edit_profile_form_part')

        @if (!empty($form_partials))
            @foreach ($form_partials as $partial)
                {!! $partial !!}
            @endforeach
        @endif
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary pull-right"
                    id="submit_user_button">@lang('messages.save')</button>
            </div>
        </div>
        </form>
    @stop
    @section('javascript')
        <script type="text/javascript">
            __page_leave_confirmation('#user_add_form');
            $(document).ready(function() {
                $('#selected_contacts').on('ifChecked', function(event) {
                    $('div.selected_contacts_div').removeClass('hide');
                });
                $('#selected_contacts').on('ifUnchecked', function(event) {
                    $('div.selected_contacts_div').addClass('hide');
                });

                $('#allow_login').on('ifChecked', function(event) {
                    $('div.user_auth_fields').removeClass('hide');
                });
                $('#allow_login').on('ifUnchecked', function(event) {
                    $('div.user_auth_fields').addClass('hide');
                });
            });

            $('form#user_add_form').validate({
                rules: {
                    first_name: {
                        required: true,
                    },
                    email: {
                        email: true,
                        remote: {
                            url: "/business/register/check-email",
                            type: "post",
                            data: {
                                email: function() {
                                    return $("#email").val();
                                }
                            }
                        }
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                    confirm_password: {
                        equalTo: "#password"
                    },
                    username: {
                        minlength: 5,
                        remote: {
                            url: "/business/register/check-username",
                            type: "post",
                            data: {
                                username: function() {
                                    return $("#username").val();
                                },
                                @if (!empty($username_ext))
                                    username_ext: "{{ $username_ext }}"
                                @endif
                            }
                        }
                    }
                },
                messages: {
                    password: {
                        minlength: 'Password should be minimum 5 characters',
                    },
                    confirm_password: {
                        equalTo: 'Should be same as password'
                    },
                    username: {
                        remote: 'Invalid username or User already exist'
                    },
                    email: {
                        remote: '{{ __('validation.unique', ['attribute' => __('business.email')]) }}'
                    }
                }
            });
            $('#username').change(function() {
                if ($('#show_username').length > 0) {
                    if ($(this).val().trim() != '') {
                        $('#show_username').html("{{ __('lang_v1.your_username_will_be') }}: <b>" + $(this).val() +
                            "{{ $username_ext }}</b>");
                    } else {
                        $('#show_username').html('');
                    }
                }
            });
        </script>
    @endsection

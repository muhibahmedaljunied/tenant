@extends('layouts.app')

@section('title', __( 'user.edit_user' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'user.edit_user' )</h1>
</section>

<!-- Main content -->
<section class="content">
    <form action="{{ action('ManageUserController@update', [$user->id]) }}" method="POST" id="user_edit_form">
        @csrf
        @method('PUT')
    <div class="row">
        <div class="col-md-12">
        @component('components.widget', ['class' => 'box-primary'])
            <div class="col-md-2">
                <div class="form-group">
                  <label for="surname">{{ __( 'business.prefix' ) . ':' }}</label>
                  <input type="text" name="surname" id="surname" class="form-control" value="{{ old('surname', $user->surname) }}" placeholder="{{ __( 'business.prefix_placeholder' ) }}">
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                  <label for="first_name">{{ __( 'business.first_name' ) . ':*' }}</label>
                  <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}" required placeholder="{{ __( 'business.first_name' ) }}">
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                  <label for="last_name">{{ __( 'business.last_name' ) . ':' }}</label>
                  <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}" placeholder="{{ __( 'business.last_name' ) }}">
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4">
                <div class="form-group">
                  <label for="email">{{ __( 'business.email' ) . ':*' }}</label>
                  <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required placeholder="{{ __( 'business.email' ) }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                  <div class="checkbox">
                    <br>
                    <label>
                        <input type="checkbox" name="is_active" value="1" class="input-icheck status" {{ $is_checked_checkbox ? 'checked' : '' }}> {{ __('lang_v1.status_for_user') }}
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
                        <input type="checkbox" name="allow_login" value="1" class="input-icheck" id="allow_login" {{ !empty($user->allow_login) ? 'checked' : '' }}> {{ __( 'lang_v1.allow_login' ) }}
                      </label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="user_auth_fields @if(empty($user->allow_login)) hide @endif">
            @if(empty($user->allow_login))
                <div class="col-md-4">
                    <div class="form-group">
                      <label for="username">{{ __( 'business.username' ) . ':' }}</label>
                      @if(!empty($username_ext))
                        <div class="input-group">
                          <input type="text" name="username" id="username" class="form-control" placeholder="{{ __( 'business.username' ) }}">
                          <span class="input-group-addon">{{$username_ext}}</span>
                        </div>
                        <p class="help-block" id="show_username"></p>
                      @else
                          <input type="text" name="username" id="username" class="form-control" placeholder="{{ __( 'business.username' ) }}">
                      @endif
                      <p class="help-block">@lang('lang_v1.username_help')</p>
                    </div>
                </div>
            @endif
            <div class="col-md-4">
                <div class="form-group">
                  <label for="password">{{ __( 'business.password' ) . ':' }}</label>
                  <input type="password" name="password" id="password" class="form-control" placeholder="{{ __( 'business.password') }}" @if(empty($user->allow_login)) required @endif>
                  <p class="help-block">@lang('user.leave_password_blank')</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                  <label for="confirm_password">{{ __( 'business.confirm_password' ) . ':' }}</label>
                  <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="{{ __( 'business.confirm_password' ) }}" @if(empty($user->allow_login)) required @endif>
                  
                </div>
            </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <div class="form-group">
                  <label for="role">{{ __( 'user.role' ) . ':*' }}</label> @show_tooltip(__('lang_v1.admin_role_location_permission_help'))
                  <select name="role" id="role" class="form-control select2" style="width: 100%;">
                    @foreach($roles as $id => $role)
                      <option value="{{$id}}" @if(!empty($user->roles->first()->id) && $user->roles->first()->id == $id) selected @endif>{{$role}}</option>
                    @endforeach
                  </select>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-3">
                <h4>@lang( 'role.access_locations' ) @show_tooltip(__('tooltip.access_locations_permission'))</h4>
            </div>
            <div class="col-md-9">
                <div class="col-md-12">
                    <div class="checkbox">
                        <label>
                          <input type="checkbox" name="access_all_locations" value="access_all_locations" class="input-icheck" {{ (!is_array($permitted_locations) && $permitted_locations == 'all') ? 'checked' : '' }}> {{ __( 'role.all_locations' ) }} 
                        </label>
                        @show_tooltip(__('tooltip.all_location_permission'))
                    </div>
                  </div>
              @foreach($locations as $location)
                <div class="col-md-12">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="location_permissions[]" value="location.{{ $location->id }}" class="input-icheck" {{ (is_array($permitted_locations) && in_array($location->id, $permitted_locations)) ? 'checked' : '' }}> {{ $location->name }}
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
                  <label for="cmmsn_percent">{{ __( 'lang_v1.cmmsn_percent' ) . ':' }}</label> @show_tooltip(__('lang_v1.commsn_percent_help'))
                  <input type="text" name="cmmsn_percent" id="cmmsn_percent" class="form-control input_number" value="{{ old('cmmsn_percent', !empty($user->cmmsn_percent) ? @num_format($user->cmmsn_percent) : 0) }}" placeholder="{{ __( 'lang_v1.cmmsn_percent' ) }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                  <label for="max_sales_discount_percent">{{ __( 'lang_v1.max_sales_discount_percent' ) . ':' }}</label> @show_tooltip(__('lang_v1.max_sales_discount_percent_help'))
                  <input type="text" name="max_sales_discount_percent" id="max_sales_discount_percent" class="form-control input_number" value="{{ old('max_sales_discount_percent', !is_null($user->max_sales_discount_percent) ? @num_format($user->max_sales_discount_percent) : null) }}" placeholder="{{ __( 'lang_v1.max_sales_discount_percent' ) }}">
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="checkbox">
                    <br/>
                      <label>
                        <input type="checkbox" name="selected_contacts" value="1" class="input-icheck" id="selected_contacts" {{ $user->selected_contacts ? 'checked' : '' }}> {{ __( 'lang_v1.allow_selected_contacts' ) }}
                      </label>
                      @show_tooltip(__('lang_v1.allow_selected_contacts_tooltip'))
                    </div>
                </div>
            </div>
            
            <div class="col-sm-4 selected_contacts_div @if(!$user->selected_contacts) hide @endif">
                <div class="form-group">
                  <label for="selected_contacts">{{ __('lang_v1.selected_contacts') . ':' }}</label>
                    <div class="form-group">
                      <select name="selected_contact_ids[]" id="selected_contacts_select" class="form-control select2" multiple style="width: 100%;">
                        @foreach($contacts as $id => $contact)
                          <option value="{{$id}}" @if(is_array($contact_access) && in_array($id, $contact_access)) selected @endif>{{$contact}}</option>
                        @endforeach
                      </select>
                    </div>
                </div>
            </div>
            @endcomponent
        </div>
    </div>
    @include('user.edit_profile_form_part', ['bank_details' => !empty($user->bank_details) ? json_decode($user->bank_details, true) : null])

    @if(!empty($form_partials))
      @foreach($form_partials as $partial)
        {!! $partial !!}
      @endforeach
    @endif
    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary pull-right" id="submit_user_button">@lang( 'messages.update' )</button>
        </div>
    </div>
    </form>
  @stop
@section('javascript')
<script type="text/javascript">
  $(document).ready(function(){
    __page_leave_confirmation('#user_edit_form');
    
    $('#selected_contacts').on('ifChecked', function(event){
      $('div.selected_contacts_div').removeClass('hide');
    });
    $('#selected_contacts').on('ifUnchecked', function(event){
      $('div.selected_contacts_div').addClass('hide');
    });
    $('#allow_login').on('ifChecked', function(event){
      $('div.user_auth_fields').removeClass('hide');
    });
    $('#allow_login').on('ifUnchecked', function(event){
      $('div.user_auth_fields').addClass('hide');
    });
  });

  $('form#user_edit_form').validate({
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
                                    return $( "#email" ).val();
                                },
                                user_id: {{$user->id}}
                            }
                        }
                    },
                    password: {
                        minlength: 5
                    },
                    confirm_password: {
                        equalTo: "#password",
                    },
                    username: {
                        minlength: 5,
                        remote: {
                            url: "/business/register/check-username",
                            type: "post",
                            data: {
                                username: function() {
                                    return $( "#username" ).val();
                                },
                                @if(!empty($username_ext))
                                  username_ext: "{{$username_ext}}"
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
                        remote: '{{ __("validation.unique", ["attribute" => __("business.email")]) }}'
                    }
                }
            });
</script>
@endsection
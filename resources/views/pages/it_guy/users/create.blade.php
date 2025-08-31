@extends('layouts.app2')
@section('title', __('user.add_user'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('user.add_user')</h1>
    </section>

    <!-- Main content -->

    <section class="content">
        <form action="{{ action('ItGuy\UserController@store') }}" method="POST" id="add_expense_form"
            enctype="multipart/form-data">
            @csrf
            <div class="box box-solid">
                <div class="box-body">

                  
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
          


     


        </div>

                </div>
            </div>

            <button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
        </form>
    </section>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.paid_on').datetimepicker({
                format: moment_date_format + ' ' + moment_time_format,
                ignoreReadonly: true,
            });
        });

      
        $(document).on('change', '#recur_interval_type', function() {
            if ($(this).val() == 'months') {
                $('.recur_repeat_on_div').removeClass('hide');
            } else {
                $('.recur_repeat_on_div').addClass('hide');
            }
        });

   
    </script>
@endsection

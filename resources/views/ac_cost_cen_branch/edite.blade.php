@extends('layouts.app')
@section('title', __('chart_of_accounts.edit_cost_center'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('chart_of_accounts.edit_cost_center')</h1>
        <!-- <ol class="breadcrumb">
                                                                                        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                                                                                        <li class="active">Here</li>
                                                                                    </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        @php
            $form_class = empty($duplicate_product) ? 'create' : '';
        @endphp
        <form action="{{ action('AcCostCenBrancheController@update', [$ac_cost_details->id]) }}" method="POST" id="cost_center_edit_form" class="cost_center_form{{ $form_class }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
        @component('components.widget', ['class' => 'box-primary'])
            <div class="row">
                <div class="clearfix"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cost_description">{{ __('chart_of_accounts.cost_description') }}</label>
                        <input type="text" name="cost_description" id="cost_description" class="form-control" value="{{ old('cost_description', $ac_cost_details->cost_description) }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="parent_cost_no">{{ __('chart_of_accounts.parent_cost_no') }}</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fas fa-money-bill-alt"></i>
                            </span>
                            <select name="parent_cost_no" id="parent_cost_no" class="form-control">
                                <option value="">Select Parent Cost Center</option>
                                @foreach($ac_cost_cen_branches as $key => $value)
                                    <option value="{{ $key }}" {{ old('parent_cost_no', $ac_cost_details->parent_cost_no) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="users">{{ __('chart_of_accounts.users_permission') }}:</label> @show_tooltip(__('chart_of_accounts.users_permission_info'))
                        <select name="users[]" id="users" class="form-control select2" multiple>
                            @foreach($users as $key => $value)
                                <option value="{{ $key }}" {{ (collect(old('users', $ac_cost_details->ac_cost_cen_branches->pluck('id')))->contains($key)) ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


            </div>
        @endcomponent

        <div class="row">
            <div class="col-sm-12">
                <input type="hidden" name="submit_type" id="submit_type">
                <div class="text-center">
                    <div class="btn-group">
                        <button type="submit" value="submit"
                            class="btn btn-primary submit_product_form">@lang('messages.save')</button>
                    </div>

                </div>
            </div>
        </div>
        </form>
    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {


        });
    </script>
@endsection

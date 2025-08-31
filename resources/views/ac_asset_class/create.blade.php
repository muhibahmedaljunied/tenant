@extends('layouts.app')
@section('title', __('assets.add_asset_classes'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('assets.add_asset_classes')</h1>
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
<form action="{{ action('AcAssetClassController@store') }}" method="post" id="asset_class_add_form" class="asset_class_form{{ $form_class }}" enctype="multipart/form-data">
    @csrf
    <!-- Your form fields go here -->


        @component('components.widget', ['class' => 'box-primary'])
            <div class="row">
                <div class="clearfix"></div>
      <div class="col-md-6">
    <div class="form-group">
        <label for="asset_class_name_ar">{{ __('assets.asset_class_name_ar') }}:*</label>
        <input type="text" id="asset_class_name_ar" name="asset_class_name_ar" class="form-control" required>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="asset_class_name_en">{{ __('assets.asset_class_name_en') }}</label>
        <input type="text" id="asset_class_name_en" name="asset_class_name_en" class="form-control">
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <div class="checkbox">
            <label>
                <input type="checkbox" id="is_depreciable" name="is_depreciable" value="1">
                {{ __('assets.is_depreciable') }}
            </label>
        </div>
    </div>
</div>


                <div class="clearfix"></div>


               <div class="col-md-6">
    <div class="form-group">
        <label for="asset_account">{{ __('assets.asset_account') }}</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="asset_account" name="asset_account" class="form-control select2" required>
                <option value="">{{ __('Select Acc') }}</option>
                @foreach($lastChildrenBranch as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

                <div class="clearfix"></div>
                <div id="depreciable_div" >
             <div class="col-md-6">
    <div class="form-group">
        <label for="asset_expense_account">{{ __('assets.asset_expense_account') }}</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="asset_expense_account" name="asset_expense_account" class="form-control select2">
                <option value="">{{ __('Select Acc') }}</option>
                @foreach($lastChildrenBranch as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="accumulated_consumption_account">{{ __('assets.accumulated_consumption_account') }}</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="accumulated_consumption_account" name="accumulated_consumption_account" class="form-control select2">
                <option value="">{{ __('Select Acc') }}</option>
                @foreach($lastChildrenBranch as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="clearfix"></div>

<div class="col-md-6">
    <div class="form-group">
        <label for="useful_life_type">{{ __('assets.useful_life_type') }}:</label>
        <select id="useful_life_type" name="useful_life_type" class="form-control select2">
            <option value="years">{{ __('assets.useful_life_years') }}</option>
            <option value="percent">{{ __('assets.useful_life_percent') }}</option>
        </select>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="useful_life" id="years_lable_div">{{ __('assets.years') }}</label>
        <label for="useful_life" id="percent_lable_div">{{ __('assets.percent') }}</label>
        <input type="text" id="useful_life" name="useful_life" class="form-control">
    </div>
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

            $('#depreciable_div').hide();
            $('#percent_lable_div').hide();
            $(document).on('change', '#is_depreciable', function() {
                // var is_depreciable = $(this).val();
                if (this.checked) {
                    $('#depreciable_div').show();
                }else{
                    $('#depreciable_div').hide();
                }
            });
            $(document).on('change', '#useful_life_type', function() {
                var useful_life_type = $(this).val();
                if (useful_life_type == 'years') {
                    $('#years_lable_div').show();
                    $('#percent_lable_div').hide();
                }else{
                    $('#percent_lable_div').show();
                    $('#years_lable_div').hide();
                }
            });


        });
    </script>
@endsection

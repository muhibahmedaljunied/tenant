@extends('layouts.app')
@section('title', __('assets.edit_asset_classes'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('assets.edit_asset_classes')</h1>
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
        <form action="{{ action('AcAssetClassController@update', [$asset_class_details->id]) }}" method="POST" id="asset_classes_edit_form" class="asset_classes_form{{ $form_class }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
        @component('components.widget', ['class' => 'box-primary'])
            <div class="row">
                <div class="clearfix"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="asset_class_name_ar">{{ __('assets.asset_class_name_ar') }}</label>
                        <input type="text" name="asset_class_name_ar" id="asset_class_name_ar" class="form-control" value="{{ old('asset_class_name_ar', $asset_class_details->asset_class_name_ar) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="asset_class_name_en">{{ __('assets.asset_class_name_en') }}</label>
                        <input type="text" name="asset_class_name_en" id="asset_class_name_en" class="form-control" value="{{ old('asset_class_name_en', $asset_class_details->asset_class_name_en) }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_depreciable" id="is_depreciable" value="1" {{ old('is_depreciable', $asset_class_details->is_depreciable) ? 'checked' : '' }}>
                                @lang('assets.is_depreciable')
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
                            <select name="asset_account" id="asset_account" class="form-control">
                                <option value="">Select Acc</option>
                                @foreach($lastChildrenBranch as $key => $value)
                                    <option value="{{ $key }}" {{ old('asset_account', $asset_class_details->asset_account) == $key ? 'selected' : '' }}>{{ $value }}</option>
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
                                <select name="asset_expense_account" id="asset_expense_account" class="form-control select2">
                                    <option value="">Select Acc</option>
                                    @foreach($lastChildrenBranch as $key => $value)
                                        <option value="{{ $key }}" {{ old('asset_expense_account', $asset_class_details->asset_expense_account) == $key ? 'selected' : '' }}>{{ $value }}</option>
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
                                <select name="accumulated_consumption_account" id="accumulated_consumption_account" class="form-control select2">
                                    <option value="">Select Acc</option>
                                    @foreach($lastChildrenBranch as $key => $value)
                                        <option value="{{ $key }}" {{ old('accumulated_consumption_account', $asset_class_details->accumulated_consumption_account) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="useful_life_type">{{ __('assets.useful_life_type') }}:</label>
                            <select name="useful_life_type" id="useful_life_type" class="form-control select2">
                                <option value="years" @if (old('useful_life_type', $asset_class_details->useful_life_type) == 'years') selected @endif>
                                    @lang('assets.useful_life_years')</option>
                                <option value="percent" @if (old('useful_life_type', $asset_class_details->useful_life_type) == 'percent') selected @endif>
                                    @lang('assets.useful_life_percent')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            @if (old('useful_life_type', $asset_class_details->useful_life_type) == 'years')
                                <label id="years_lable_div">{{ __('assets.years') }}</label>
                            @endif
                            @if (old('useful_life_type', $asset_class_details->useful_life_type) == 'percent')
                                <label id="percent_lable_div">{{ __('assets.percent') }}</label>
                            @endif
                            <input type="text" name="useful_life" id="useful_life" class="form-control" value="{{ old('useful_life', $asset_class_details->useful_life) }}">
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
            var is_depreciable = {!! $asset_class_details->is_depreciable !!};
            if(is_depreciable==0){
                $('#depreciable_div').hide();
            }
            $(document).on('change', '#is_depreciable', function() {
                if (this.checked) {
                    $('#depreciable_div').show();
                } else {
                    $('#depreciable_div').hide();
                }
            });
            $(document).on('change', '#useful_life_type', function() {
                var useful_life_type = $(this).val();
                if (useful_life_type == 'years') {
                    $('#years_lable_div').show();
                    $('#percent_lable_div').hide();
                } else {
                    $('#percent_lable_div').show();
                    $('#years_lable_div').hide();
                }
            });
        });
    </script>
@endsection

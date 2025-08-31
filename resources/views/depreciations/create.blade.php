@extends('layouts.app')
@section('content')
    <section class="content">
        @component('components.widget', ['class' => 'box-primary'])
            <form action="{{ action('DepreciationController@store') }}" method="post" id="depreciation_add_form">
                @csrf
                <!-- Your form fields go here -->


                <div class="form-group">
                    <label for="reference_number">{{ __('lang_v1.reference_number') }}:*</label>
                    <input type="text" id="reference_number" name="reference_number" class="form-control"
                        value="{{ $ref_num }}" required>
                </div>

                <div class="form-group">
                    <label for="asset_class">{{ __('lang_v1.asset_class') }}:*</label>
                    <select id="asset_class" name="asset_class_id" class="form-control select2"
                        data-placeholder="Select an option" data-allow-clear="true" required
                        data-url="{{ action('AcAssetController@index') }}">
                        <option value="">{{ __('lang_v1.asset_class') }}</option>
                        @foreach ($assets as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id="asset_container">
                    <label for="asset">{{ __('lang_v1.asset') }}:*</label>
                    <select id="asset" name="asset_id" class="form-control select2" style="width: 100%"
                        data-placeholder="Select an Option">
                        <option value="">{{ __('lang_v1.asset') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="period">{{ __('lang_v1.depreciation_period') }}:*</label>
                    <select id="period" name="depreciation_period" class="form-control select2" required>
                        <option value="weekly">{{ __('lang_v1.weekly') }}</option>
                        <option value="monthly">{{ __('lang_v1.monthly') }}</option>
                        <option value="yearly">{{ __('lang_v1.yearly') }}</option>
                    </select>
                </div>

                <div class='row'>
                  <div class="col-md-6">
    <label for="from">{{ __('lang_v1.from') }}:*</label>
</div>
<div class="col-md-6">
    <label for="to">{{ __('lang_v1.to') }}:*</label>
</div>

<div class="col-md-6">
    <div class="col-md-6">
        <label for="from_year">{{ __('lang_v1.year') }}:*</label>
        <input type="text" id="from_year" name="from_year" class="form-control year-picker" readonly>
    </div>
    <div class="col-md-6">
        <label for="from_label">{{ __('lang_v1.week') }}:*</label>
        <select id="period_start" name="from" class="form-control select2" data-placeholder="Select an option">
            <option value="">{{ __('lang_v1.week') }}</option>
        </select>
    </div>
</div>

<div class="col-md-6">
    <div class="col-md-6">
        <label for="to_year">{{ __('lang_v1.year') }}:*</label>
        <input type="text" id="to_year" name="to_year" class="form-control year-picker" readonly>
    </div>
    <div class="col-md-6">
        <label for="to_label">{{ __('lang_v1.week') }}:*</label>
        <select id="period_end" name="to" class="form-control select2" data-placeholder="Select an option">
            <option value="">{{ __('lang_v1.week') }}</option>
        </select>
    </div>
</div>

                </div>
                <div class="row mt-15">
                    <div class="col-sm-12">
                        <div class="text-right">
                            <div class="btn-group">
                                <button type="submit" value="submit"
                                    class="btn btn-primary submit_product_form">@lang('messages.save')</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        @endcomponent
    </section>
@endsection
@section('css')
    <link rel='stylesheet' href="{{ asset('css/yearpicker.css') }}" />
@endsection
@section('javascript')
    <script src="{{ asset('js/yearpicker.js') }}"></script>
    <script src='{{ asset('js/depreciations.js') }}'></script>
@endsection

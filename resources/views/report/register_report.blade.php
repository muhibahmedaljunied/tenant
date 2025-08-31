@extends('layouts.app')
@section('title', __('report.register_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('report.register_report')}}</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
                <form method="GET" action="{{ action('ReportController@getStockReport') }}" id="register_report_filter_form">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="register_user_id">@lang('report.user'):</label>
                            <select name="register_user_id" id="register_user_id" class="form-control select2" style="width:100%;">
                                <option value="">@lang('report.all_users')</option>
                                @foreach ($users as $key => $user)
                                    <option value="{{ $key }}">{{ $user }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="register_status">@lang('sale.status'):</label>
                            <select name="register_status" id="register_status" class="form-control select2" style="width:100%;">
                                <option value="">@lang('report.all')</option>
                                <option value="open">@lang('cash_register.open')</option>
                                <option value="close">@lang('cash_register.close')</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="register_report_date_range">@lang('report.date_range'):</label>
                            <input type="text" name="register_report_date_range" id="register_report_date_range" class="form-control" placeholder="@lang('lang_v1.select_a_date_range')" readonly>
                        </div>
                    </div>
                </form>
            @endcomponent
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-primary'])
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="register_report_table">
                        <thead>
                            <tr>
                                <th>@lang('report.open_time')</th>
                                <th>@lang('report.close_time')</th>
                                <th>@lang('sale.location')</th>
                                <th>@lang('report.user')</th>
                                <th>@lang('cash_register.total_card_slips')</th>
                                <th>@lang('cash_register.total_cheques')</th>
                                <th>@lang('cash_register.total_cash')</th>
                                <th>@lang('messages.action')</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            @endcomponent
        </div>
    </div>
</section>

<!-- /.content -->
<div class="modal fade view_register" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

@endsection

@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
@endsection
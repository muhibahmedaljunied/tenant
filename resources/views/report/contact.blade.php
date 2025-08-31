@extends('layouts.app')
@section('title', __('report.customer') . ' - ' . __('report.supplier') . ' ' . __('report.reports'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('report.customer')}} & {{ __('report.supplier')}} {{ __('report.reports')}}</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cnt_customer_group_id">{{ __( 'lang_v1.customer_group_name' ) . ':' }}</label>
                        <select name="cnt_customer_group_id" id="cnt_customer_group_id" class="form-control select2" style="width:100%">
                            @foreach($customer_group as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="contact_type">{{ __( 'lang_v1.type' ) . ':' }}</label>
                        <select name="contact_type" id="contact_type" class="form-control select2" style="width:100%">
                            @foreach($types as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            @endcomponent
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-primary'])
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="supplier_report_tbl">
                    <thead>
                        <tr>
                            <th>@lang('report.contact')</th>
                            <th>@lang('report.total_purchase')</th>
                            <th>@lang('lang_v1.total_purchase_return')</th>
                            <th>@lang('report.total_sell')</th>
                            <th>@lang('lang_v1.total_sell_return')</th>
                            <th>@lang('lang_v1.opening_balance_due')</th>
                            <th>@lang('report.total_due') &nbsp;&nbsp;<i class="fa fa-info-circle text-info no-print" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="{{ __('messages.due_tooltip')}}" aria-hidden="true"></i></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-gray font-17 footer-total text-center">
                            <td><strong>@lang('sale.total'):</strong></td>
                            <td><span class="display_currency" id="footer_total_purchase" data-currency_symbol ="true"></span></td>
                            <td><span class="display_currency" id="footer_total_purchase_return" data-currency_symbol ="true"></span></td>
                            <td><span class="display_currency" id="footer_total_sell" data-currency_symbol ="true"></span></td>
                            <td><span class="display_currency" id="footer_total_sell_return" data-currency_symbol ="true"></span></td>
                            <td><span class="display_currency" id="footer_total_opening_bal_due" data-currency_symbol ="true"></span></td>
                            <td><span class="display_currency" id="footer_total_due" data-currency_symbol ="true"></span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endcomponent
        </div>
    </div>
</section>
<!-- /.content -->

@endsection

@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
@endsection
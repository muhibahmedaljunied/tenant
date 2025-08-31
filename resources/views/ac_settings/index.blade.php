@extends('layouts.app')
@section('title', __('chart_of_accounts.accounts_routing'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('chart_of_accounts.accounts_routing')</h1>
        <br>

    </section>

    <!-- Main content -->
    <section class="content">
        {{-- @can('accounts_routing.update')
            {!! Form::open(['url' => action('AcRoutingAccountsController@update'), 'method' => 'post', 'class' => 'ac_routing',
                       ]) !!}
        @endcan --}}
        <div class="row">
            <div class="col-xs-12">
                <!--  <pos-tab-container> -->
                <div class="col-xs-12 pos-tab-container">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pos-tab-menu">
                        <div class="list-group">
                            {{-- <a href="#" class="list-group-item text-center">@lang('business.tax') @show_tooltip(__('tooltip.business_tax'))</a> --}}
                            <a href="#" class="list-group-item text-center active">@lang('chart_of_accounts.income_statement')</a>
                            <a href="#" class="list-group-item text-center">@lang('chart_of_accounts.balance_sheet')</a>
                            <a href="#" class="list-group-item text-center">@lang('chart_of_accounts.purchases')</a>
                            <a href="#" class="list-group-item text-center">@lang('chart_of_accounts.sales')</a>
                            <a href="#" class="list-group-item text-center">@lang('chart_of_accounts.inventory_sec')</a>
                            <a href="#" class="list-group-item text-center">@lang('chart_of_accounts.treasury')</a>
                            {{-- <a href="#" class="list-group-item text-center">@lang('chart_of_accounts.vat_due_sec')</a> --}}
                            <a href="#" class="list-group-item text-center">@lang('chart_of_accounts.payment_methods')</a>
                            <a href="#" class="list-group-item text-center">@lang('chart_of_accounts.point_of_sale')</a>
                            {{-- <a href="#" class="list-group-item text-center">@lang('chart_of_accounts.stocktaking_inventory')</a> --}}

                        </div>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">
                        <!-- tab 1 start -->
                        @include('ac_settings.partials.settings_income_statement', ['can_update' => $can_update])
                        <!-- tab 1 end -->
                        <!-- tab 2 start -->
                        @include('ac_settings.partials.settings_balance_sheet', ['can_update' => $can_update])
                        <!-- tab 2 end -->
                        <!-- tab 3 start -->
                        @include('ac_settings.partials.settings_purchases', ['can_update' => $can_update])
                        <!-- tab 3 end -->
                        <!-- tab 4 start -->
                        @include('ac_settings.partials.settings_sales', ['can_update' => $can_update])
                        <!-- tab 4 end -->
                        <!-- tab 5 start -->
                        @include('ac_settings.partials.settings_inventory_sec', ['can_update' => $can_update])
                        <!-- tab 5 end -->
                        <!-- tab 6 start -->
                        @include('ac_settings.partials.settings_treasury', ['can_update' => $can_update])
                        <!-- tab 6 end -->
                        <!-- tab 7 start -->
                        {{-- @include('ac_settings.partials.settings_vat_due_sec', ['can_update' => $can_update]) --}}
                        <!-- tab 7 end -->
                        @include('ac_settings.partials.settings_payment_methods', ['can_update' => $can_update])
                        <!-- tab 8 -->

                        @include('ac_settings.partials.settings_point_of_sale', ['can_update' => $can_update])
                        
                        <!-- tab 9 -->
                        
                        {{-- @include('ac_settings.partials.settings_stocktaking_inventory', ['can_update' => $can_update]) --}}

                    </div>
                </div>
                <!--  </pos-tab-container> -->
            </div>
        </div>

        
    </section>
    <!-- /.content -->
@stop
@section('javascript')
    <script type="text/javascript">
        __page_leave_confirmation('.ac_routing');

    </script>
@endsection

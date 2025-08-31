@extends('layouts.app')
@section('title', __('chart_of_accounts.journal_entries'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header no-print">
        <h1>
            @lang('chart_of_accounts.periods')
        </h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
        @if ($entryType === 'opening')
            <ul class="nav nav-tabs">
                <li class="nav-item @if ($type === 'account') active @endif">
                    <a class="nav-link" @if ($type === 'account') aria-current="page" @endif
                        href="{{ action('PeriodController@index', ['type' => 'account']) }}">فتح سنه ماليه</a>
                </li>
                <li class="nav-item @if ($type === 'product') active @endif">
                    <a class="nav-link" href="{{ action('PeriodController@index', ['type' => 'product']) }}">اقفال سنه ماليه
                    </a>
                </li>

            </ul>
        @endif
        @component('components.widget', [
            'class' => 'box-primary',
            'title' =>
                // $entryType === 'opening' ? __('chart_of_accounts.add_open_period') : __('chart_of_accounts.add_close_period'),
                $type === 'account' ? __('chart_of_accounts.add_open_period') : __('chart_of_accounts.add_close_period'),
        ])
            @can('sell.create')
                @slot('tool')
                    <div class="box-tools">

                        @if ($type === 'account')
                            <button type="button" class="btn btn-block btn-primary btn-modal"
                                data-href="{{ action('PeriodController@open_period') }}" data-container=".contact_modal">
                                <i class="fa fa-plus"></i> @lang(`chart_of_accounts.$entryType`)</button>
                        @endif

                        {{-- @if ($type === 'product')
                            <button type="button" class="btn btn-block btn-primary btn-modal"
                                data-href="{{ action('PeriodController@close_period') }}" data-container=".contact_modal">
                                <i class="fa fa-plus"></i> @lang(`chart_of_accounts.$entryType`)</button>
                        @endif --}}


                    </div>
                @endslot
            @endcan
            @if (auth()->user()->can('direct_sell.access') ||
                    auth()->user()->can('view_own_sell_only') ||
                    auth()->user()->can('view_commission_agent_sell'))
                @php
                    $custom_labels = json_decode(session('business.custom_labels'), true);
                @endphp
                @if ($type === 'account')
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped ajax_view" id="ac_journal_entry_table">
                            <thead>
                                <tr>
                                    <th>@lang('chart_of_accounts.number')</th>
                                    <th>@lang('chart_of_accounts.name_year')</th>
                                    <th>@lang('chart_of_accounts.start_period')</th>
                                    <th>@lang('chart_of_accounts.end_period')</th>

                                    <th>@lang('chart_of_accounts.status')</th>

                                    <th>@lang('messages.action')</th>


                                </tr>
                            </thead>
                            <tbody></tbody>

                        </table>
                    </div>
                @endif
                @if ($type === 'product')
                    {{-- <span>muhib</span> --}}
                    <div class="row">
                   
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="supplier_business_name">@lang('chart_of_accounts.close_account'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-briefcase"></i>
                                    </span>
                                    <input type="text" name="supplier_business_name" id="supplier_business_name"
                                        class="form-control" placeholder="@lang('chart_of_accounts.close_account')">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="start_date">@lang('chart_of_accounts.close_date'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" name="start_date" id="start_date"
                                        class="form-control start-date-picker" placeholder="@lang('chart_of_accounts.end_period')" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm3">
                            <br>
                            <button type="submit" class="btn btn-primary">@lang('chart_of_accounts.close_losse_earn')</button>
                            {{-- <a href="javascript:void"  class="btn btn-primary"><span> @lang('chart_of_accounts.close_losse_earn')
                            </span></a> --}}

                        </div>




                    </div>
                @endif
            @endif
        @endcomponent
    </section>
    <!-- /.content -->


    <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>



    <!-- This will be printed -->
    <!-- <section class="invoice print_section" id="receipt_section">
                                                                </section> -->

@stop

@section('javascript')
    <script type="text/javascript">
        $(document).on('shown.bs.modal', '.contact_modal', function(e) {
            // initAutocomplete();
        });
    </script>


@endsection

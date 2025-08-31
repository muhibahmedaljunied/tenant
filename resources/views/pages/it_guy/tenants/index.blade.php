@extends('layouts.app2')
@section('title', __('tenant.tenants'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('tenant.tenants')</h1>
    </section>

    <!-- Main content -->
    <section class="content">


        <div class="row">
            <div class="col-md-12">
                @component('components.widget', ['class' => 'box-primary', 'title' => __('tenant.all_tenants')])
                
                        @slot('tool')
                            <div class="box-tools">
                                <a class="btn btn-block btn-primary" href="{{ action('ItGuy\TenantController@create') }}">
                                    <i class="fa fa-plus"></i> @lang('messages.add')
                                </a>
                            </div>
                        @endslot
          


                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tenants_table">
                            <thead>
                                <tr>
                                    <th>@lang('messages.action')</th>
                                    <th>@lang('tenant.name')</th>
                                    <th>@lang('tenant.domain')</th>
                                    <th>@lang('tenant.account_status')</th>
                                    <th>@lang('tenant.payment_status')</th>
                                    <th>@lang('tenant.created_at')</th>
                                    <th>@lang('tenant.updated_at')</th>


                           
                        

                              
                                </tr>
                            </thead>
                        </table>
           
                    </div>
                @endcomponent
            </div>
        </div>
    </section>

    <!-- /.content -->
    <!-- /.content -->
    <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
@stop
@section('javascript')
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection

@extends('layouts.app2')
@section('title', __('tenant.tenants'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('tenant.tenants')
            <small>@lang('tenant.tenant_management')</small>
        </h1>
        <!-- <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                    <li class="active">Here</li>
                </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">


        <div class="row">
            <div class="col-md-12">
                @component('components.widget', ['class' => 'box-primary', 'title' => __('tenant.all_tenants')])
                    {{-- @slot('tool')
                        <div class="box-tools">
                            <a class="btn btn-block btn-primary" href="{{ action('ItGuy\TenantController@create') }}">
                                <i class="fa fa-plus"></i> @lang('messages.add')
                            </a>
                        </div>
                    @endslot --}}

                    @slot('tool')
                        <div class="box-tools">
                            <button type="button" class="btn btn-block btn-primary btn-modal"
                                data-href="{{ action('ItGuy\TenantController@create') }}" data-container=".tenant_modal">
                                <i class="fa fa-plus"></i> @lang('messages.add')</button>
                        </div>
                    @endslot




                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tenants_table">
                            <thead>
                                <tr>

                                    <th>@lang('tenant.name')</th>
                                    <th>@lang('tenant.domain')</th>
                                    <th>@lang('tenant.account_status')</th>
                                    <th>@lang('tenant.payment_status')</th>
                                    <th>@lang('tenant.created_at')</th>
                                    <th>@lang('tenant.updated_at')</th>
                                    <th>@lang('messages.action')</th>





                                </tr>
                            </thead>
                        </table>

                    </div>
                @endcomponent
            </div>
        </div>
    </section>



    <div class="modal fade tenant_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    {{-- 
    <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div> --}}
@stop

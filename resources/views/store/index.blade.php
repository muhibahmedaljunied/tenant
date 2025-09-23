@extends('layouts.app')
@section('title', __( 'store.stores' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'store.stores' )
        <small>@lang( 'store.manage_your_stores' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'store.all_your_stores' )])
        @can('store.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{action('StoreController@create')}}" 
                        data-container=".store_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
        @endcan
        
        @can('store.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="store_table">
                    <thead>
                        <tr>
                            <th>@lang( 'store.name' )</th>
                            <th>@lang( 'store.short_name' )</th>
                            <th>@lang( 'store.business_locations' ) @show_tooltip(__('tooltip.store_allow_decimal'))</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade store_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

@endsection


@extends('layouts.app')
@section('title', __('depreciation.show_depreciation'))

@section('content')


<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'depreciation.show_depreciation' )])
        <table class='table table-borderless table-responsive'>
            <tr>
                <th class='col-md-3'>الرقم المرجعي</th>
                <td>{{ $depreciation->reference_number }}</td>
            </tr>
            <tr>
                <th class='col-md-3'>تصنيف الاصل</th>
                <td>{{ $depreciation->assetClass->asset_class_name_ar }}</td>
            </tr>
            <tr>
                <th class='col-md-3'>اسم الاصل</th>
                <td>{{ $depreciation->asset->asset_name_ar }}</td>
            </tr>
            <tr>
                <th class='col-md-3'>نوع فترة الاهلاك</th>
                <td>{{ __("depreciation.{$depreciation->depreciation_period}") }}</td>
            </tr>
            <tr>
                <th class='col-md-3'>الفترة</th>
                <th class='col-md-1'>من</th>
                <td>{{ $depreciation->depreciationFrom->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <th class='col-md-3'></th>
                <th class='col-md-1'>الي</th>
                <td>{{ $depreciation->depreciationTo->format('d-m-Y') }}</td>
            </tr>
        </table>
    @endcomponent

</section>
<!-- /.content -->
@stop

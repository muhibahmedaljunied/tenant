@extends('layouts.app')
@section('title', __('installment::lang.customer_instalment'))

@section('content')

    @include('installment::layouts.partials.style')

    <section class="content-header">
        <h1>@lang('installment::lang.customer_instalment')</h1>
    </section>

    @csrf
    <section class="content no-print">
        @component('components.widget', ['class' => 'box-primary', 'title' => ''])
            @can('installment.view')
               <section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
                <form method="GET" action="{{ action('ReportController@getStockReport') }}" id="stock_report_filter_form">
                    @csrf
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="location_id">@lang('purchase.business_location'):</label>

                            {{-- muhib make comment for this --}}
                            {{-- <select name="location_id" id="location_id" class="form-control select2" style="width:100%;">
                                @foreach ($business_locations as $key => $location)
                                    <option value="{{ $key }}">{{ $location }}</option>
                                @endforeach
                            </select> --}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="category_id">@lang('category.category'):</label>
                            {{-- <select name="category_id" id="category_id" class="form-control select2" style="width:100%;">
                                <option value="">@lang('messages.all')</option>
                                @foreach ($categories as $key => $category)
                                    <option value="{{ $key }}">{{ $category }}</option>
                                @endforeach
                            </select> --}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sub_category_id">@lang('product.sub_category'):</label>
                            <select name="sub_category_id" id="sub_category_id" class="form-control select2" style="width:100%;">
                                <option value="">@lang('messages.all')</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="brand">@lang('product.brand'):</label>
                            {{-- <select name="brand" id="brand" class="form-control select2" style="width:100%;">
                                <option value="">@lang('messages.all')</option>
                                @foreach ($brands as $key => $brand)
                                    <option value="{{ $key }}">{{ $brand }}</option>
                                @endforeach
                            </select> --}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="unit">@lang('product.unit'):</label>
                            {{-- <select name="unit" id="unit" class="form-control select2" style="width:100%;">
                                <option value="">@lang('messages.all')</option>
                                @foreach ($units as $key => $unit)
                                    <option value="{{ $key }}">{{ $unit }}</option>
                                @endforeach
                            </select> --}}
                        </div>
                    </div>
                
                    {{-- muhib remove from here  condition --}}
                </form>
            @endcomponent
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-solid'])
                <table class="table no-border">
                    <tr>
                        <td>@lang('report.closing_stock') (@lang('lang_v1.by_purchase_price'))</td>
                        <td>@lang('report.closing_stock') (@lang('lang_v1.by_sale_price'))</td>
                        <td>@lang('lang_v1.potential_profit')</td>
                        <td>@lang('lang_v1.profit_margin')</td>
                    </tr>
                    <tr>
                        <td><h3 id="closing_stock_by_pp" class="mb-0 mt-0"></h3></td>
                        <td><h3 id="closing_stock_by_sp" class="mb-0 mt-0"></h3></td>
                        <td><h3 id="potential_profit" class="mb-0 mt-0"></h3></td>
                        <td><h3 id="profit_margin" class="mb-0 mt-0"></h3></td>
                    </tr>
                </table>
            @endcomponent
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @include('report.partials.stock_report_table')
        </div>
    </div>
</section>

            @endcan

            {{-- <button type="button" class="btn  btn-primary " onclick="tprint(69)"  >
                    <i class="fa fa-plus"></i> @lang( 'messages.add' )</button> --}}

            <div class="view-div">
                @can('installment.view')
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped " id="data_table2">
                            <thead>
                                <tr>
                                    <th style="width: 140px">تاريخ بداية الأقساط</th>
                                    <th style="width: 140px">المبلغ الإجمالي</th>
                                    <th>قيمة القسط </th>
                                    <th style="width: 100px">عدد الأقساط </th>

                                    <th>عدد الأقساط المسددة</th>
                                    <th style="width: 150px"></th>


                                </tr>
                            </thead>

                        </table>
                    </div>
                @endcan
            </div>
        @endcomponent



    </section>

    <div class="modal fade div_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <section class="invoice print_section" id="installment_section">
    </section>
@endsection


@section('javascript')
    {{--   <script  src='{{Module::asset('installment:js/app.js?v=' . $asset_v)}}'></script> --}}
    @include('installment::layouts.partials.javascripts')


    <script type="text/javascript">
        $(document).ready(function() {

            data_table2 = $('#data_table2').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/installment/getinstallment?id=',
                    data: function(d) {
                        d.id = $('#customer_id').val();
                    }
                },
                columnDefs: [{
                    targets: 5,
                    orderable: false,
                    searchable: false,
                }, ],
            });
            $('#customer_id').on('change', function() {
                var customer_id = $('#customer_id').val();
                $.ajax({
                    method: 'GET',
                    url: '/installment/getcustomerdata/' + customer_id,
                    data: {
                        id: customer_id
                    },
                    success: function(result) {
                        $('#balance_due').val(result['balance_due'].toFixed(2));
                    }
                });
                data_table2.ajax.reload();

            });

        });





        $(document).on('click', 'button.delete_installment_button', function() {
            swal({
                title: LANG.sure,
                text: 'سوف يتم حذف مجموعة الأقساط ',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then(willDelete => {
                if (willDelete) {
                    var href = $(this).data('href');
                    var data = $(this).serialize();
                    $.ajax({
                        method: 'DELETE',
                        url: href,
                        dataType: 'json',
                        data: data,
                        success: function(result) {
                            if (result.success == true) {
                                toastr.success(result.msg);
                                data_table2.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        },
                    });
                }
            });

        });
    </script>

@endsection

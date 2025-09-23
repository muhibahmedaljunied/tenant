@extends('layouts.app')
@section('title', __('lang_v1.items_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>تقرير حركة صنف </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="search_product">{{ __('lang_v1.search_product') . ':' }}</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-search"></i>
                            </span>
                            <input type="hidden" value="" id="variation_id">
                            <input type="text" name="search_product" class="form-control" id="search_product" placeholder="{{ __('lang_v1.search_product_placeholder') }}" autofocus>
                        </div>
                    </div>
                </div>
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-primary'])
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="items_report_tablee">
                        <thead>
                            <tr>
                                <th>تاريخ العملية</th>
                                <th> نوع العملية</th>
                                <th> رقم العملية </th>
                                <th> جهة الاتصال  </th>
                                <th>عدد الداخل </th>
                                <th>عدد الخارج</th>
                                <th>الرصيد بعد العملية </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            @endcomponent
        </div>
    </div>
</section>
<!-- /.content -->
<div class="modal fade view_register" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
<div class="modal fade view_modal modal-dialog" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
@endsection

@section('javascript')
<script src="{{ url('js/report.js?v=' . $asset_v) }}"></script>
<script type="text/javascript">
    $(document).on('shown.bs.modal', '.view_modal', function(e) {
        initAutocomplete();
    });

    if ($('#search_product').length > 0) {
        $('#search_product').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: '/purchases/get_products?check_enable_stock=false',
                    dataType: 'json',
                    data: { term: request.term },
                    success: function(data) {
                        response($.map(data, function(v) {
                            if (v.variation_id) {
                                return { label: v.text, value: v.variation_id };
                            }
                            return false;
                        }));
                    },
                });
            },
            minLength: 2,
            select: function(event, ui) {
                $('#variation_id').val(ui.item.value).change();
                event.preventDefault();
                $(this).val(ui.item.label);
            },
            focus: function(event, ui) {
                event.preventDefault();
                $(this).val(ui.item.label);
            },
        });
    }

    $("#variation_id").change(function() {
        var id = $("#variation_id").val();
        if (id) {
            $.ajax({
                type: 'GET',
                url: '/reports/get_product_trans',
                data: {
                    '_token': $('meta[name="_token"]').attr('content'),
                    'variation_id': id,
                },
                success: function(data) {
                    $("#items_report_tablee tbody").html(data);
                }
            });
        }
    });
</script>
@endsection
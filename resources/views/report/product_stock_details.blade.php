@extends('layouts.app')
@section('title', __('lang_v1.product_stock_details'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('lang_v1.product_stock_details')}}</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary" id="accordion">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFilter">
                            <i class="fa fa-filter" aria-hidden="true"></i> @lang('report.filters')
                        </a>
                    </h3>
                </div>
                <div id="collapseFilter" class="panel-collapse active collapse in" aria-expanded="true">
                    <div class="box-body">
                        <div class="row">
                            <form method="GET" action="{{ action('ReportController@productStockDetails') }}">
                                @csrf
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="search_product">@lang('lang_v1.search_product'):</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-search"></i>
                                            </span>
                                            <select name="variation_id" id="variation_id" class="form-control">
                                                <option value="">@lang('lang_v1.search_product_placeholder')</option>
                                                <!-- Populate dynamically if needed -->
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="location_id">@lang('purchase.business_location'):</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-map-marker"></i>
                                            </span>
                                            <select name="location_id" id="location_id" class="form-control select2" required>
                                                <option value="">@lang('messages.please_select')</option>
                                                @foreach ($business_locations as $key => $location)
                                                    <option value="{{ $key }}">{{ $location }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <br>
                                        <button type="submit" class="btn btn-primary">@lang('lang_v1.search')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
    <script type="text/javascript">
        $(document).ready( function () {
            //get customer
            $('#variation_id').select2({
                ajax: {
                    url: '/purchases/get_products',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                          term: params.term, // search term
                        };
                    },
                    processResults: function (data) {
                        var data_formated = [];
                        data.forEach(function (item) {
                            var temp = {
                                'id': item.variation_id,
                                'text': item.text
                            }
                            data_formated.push(temp);
                        });
                        return {
                            results: data_formated
                        };
                    }
                },
                minimumInputLength: 1,
            });
        });
    </script>
@endsection
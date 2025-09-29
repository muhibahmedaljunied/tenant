@extends('layouts.app')

@section('title', __('sale.pos_sale'))

@section('content')
    <style>
        .btn-primary.active,
        .btn-primary.foucs {
            background-color: #AE0E0E !important;
            border-color: #AE0E0E !important;
        }
    </style>
    <section class="content no-print">
        <input type="hidden" id="amount_rounding_method" value="{{ $pos_settings['amount_rounding_method'] ?? '' }}">
        @if (!empty($pos_settings['allow_overselling']))
            <input type="hidden" id="is_overselling_allowed">
        @endif
        @if (session('business.enable_rp') == 1)
            <input type="hidden" id="reward_point_enabled">
        @endif
        @php
            $is_discount_enabled = $pos_settings['disable_discount'] != 1 ? true : false;
            $is_rp_enabled = session('business.enable_rp') == 1 ? true : false;
        @endphp
        <form action="{{ action('SellPosController@store') }}" method="POST" id="add_pos_sell_form">
            @csrf
            <div class="row mb-12">

                <div class="col-md-12">
                    <div class="row">
                        <div
                            class="@if (empty($pos_settings['hide_product_suggestion'])) col-md-7 @else col-md-10 col-md-offset-1 @endif no-padding pr-12">
                            <div class="box box-solid mb-12">
                                <div class="box-body pb-0">
                                    <input type="hidden" name="location_id" id="location_id"
                                        value="{{ $default_location->id ?? null }}"
                                        data-receipt_printer_type="{{ !empty($default_location->receipt_printer_type) ? $default_location->receipt_printer_type : 'browser' }}"
                                        data-default_payment_accounts="{{ $default_location->default_payment_accounts ?? '' }}">



                                    <input type="hidden" name="sub_type" value="{{ $sub_type ?? '' }}">

                                    <input type="hidden" id="item_addition_method"
                                        value="{{ $business_details->item_addition_method }}">

                                    @include('sale_pos.partials.pos_form')
                                    @include('sale_pos.partials.pos_form_totals')
                                    @include('sale_pos.partials.payment_modal')

                                    @if (empty($pos_settings['disable_suspend']))
                                        @include('sale_pos.partials.suspend_note_modal')
                                    @endif

                                    @if (empty($pos_settings['disable_recurring_invoice']))
                                        @include('sale_pos.partials.recurring_invoice_modal')
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if (empty($pos_settings['hide_product_suggestion']) && !isMobile())
                            <div class="col-md-5 no-padding">
                                @include('sale_pos.partials.pos_sidebar')
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @include('sale_pos.partials.pos_form_actions')
        </form>

    </section>

    <!-- This will be printed -->
    <section class="invoice print_section" id="receipt_section">
    </section>
    <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        @include('contact.create', ['quick_add' => true])
    </div>
    @if (empty($pos_settings['hide_product_suggestion']) && isMobile())
        @include('sale_pos.partials.mobile_product_suggestions')
    @endif
    <!-- /.content -->
    <div class="modal fade register_details_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade close_register_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <!-- quick product modal -->
    <div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>

    <div class="modal fade" id="expense_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    @include('sale_pos.partials.configure_search_modal')

    @include('sale_pos.partials.recent_transactions_modal')

    @include('sale_pos.partials.weighing_scale_modal')

@stop
@section('css')
    <!-- include module css -->
    @if (!empty($pos_module_data))
        @foreach ($pos_module_data as $key => $value)
            @if (!empty($value['module_css_path']))
                @includeIf($value['module_css_path'])
            @endif
        @endforeach
    @endif
@stop
@section('javascript')
    <script src="{{ url('js/pos.js?v=' . $asset_v) }}"></script>
    <script src="{{ url('js/printer.js?v=' . $asset_v) }}"></script>
    <script src="{{ url('js/product.js?v=' . $asset_v) }}"></script>
    <script src="{{ url('js/opening_stock.js?v=' . $asset_v) }}"></script>
    @include('sale_pos.partials.keyboard_shortcuts')



    <!-- Call restaurant module if defined -->
    @if (in_array('tables', $enabled_modules) ||
            in_array('modifiers', $enabled_modules) ||
            in_array('service_staff', $enabled_modules))
        <script src="{{ url('js/restaurant.js?v=' . $asset_v) }}"></script>
    @endif
    <!-- include module js -->
    @if (!empty($pos_module_data))
        @foreach ($pos_module_data as $key => $value)
            @if (!empty($value['module_js_path']))
                @includeIf($value['module_js_path'], ['view_data' => $value['view_data']])
            @endif
        @endforeach
    @endif


    <script type="text/javascript">
        $('#select_location_id').on('change', function() {

            // console.log('hebo_change');
            var locationIds = $(this).val();
            if (!locationIds) {
                $('#select_store_id').html(
                    '<option value="">{{ __('messages.please_select') }}</option>');
                $('#select_store_id').trigger('change');
                return;
            }
            $.ajax({
                url: '{{ route('getStoresByLocationsPos') }}',
                type: 'POST',
                data: {
                    location_ids: locationIds,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    var options =
                        '<option value="">{{ __('messages.please_select') }}</option>';
                    $.each(data, function(key, value) {
                        options += '<option value="' + key + '">' +
                            value + '</option>';
                    });
                    $('#select_store_id').html(options).trigger('change');
                }
            });
        });



     
    </script>

@endsection

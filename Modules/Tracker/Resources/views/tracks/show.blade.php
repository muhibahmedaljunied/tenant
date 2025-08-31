@extends('layouts.app')
@section('title', $track->name . ' - خط السير')

@php
    $type = 'customer';
    $reward_enabled = request()->session()->get('business.enable_rp') == 1 && in_array($type, ['customer']);
@endphp
@section('content')
    <section class="content-header">
        <h1>خط السير - {{ $track->name }}</h1>
    </section>
    <section class="content">
        <input type="hidden" id="contact_type" value="customer"/>
        <input type="hidden" id="track_id" value="{{ $track->id }}"/>
        <input type="hidden" id="noaction" value="true"/>
        @component('components.widget', ['class' => 'box-primary', 'title' =>'معلومات خط السير'])
            <div class="row">
                <div class="col-md-4">
                    <p class="well">الاسم: {{ $track->name }}</p>
                    <p class="well">الوصف: {{ $track->description }}</p>
                </div>
                <div class="col-md-4">
                    <p class="well">الاقليم: {{ $track->province->name }}</p>
                    <p class="well">القطاع: {{ $track->sector->name }}</p>
                </div>
                <div class="col-md-4">
                    <p class="well">منطقة التوزيع: {{ $track->distributionArea->name }}</p>
                    <p class="well">المسئول: {{ $track->user->getUserFullNameAttribute() }}</p>
                </div>
            </div>
        @endcomponent
        @component('components.widget', ['class' => 'box-primary'])
            <div class="nav nav-tabs" id="contact_tabs" role="tablist">
                <li role="presentation" class="active"><a href="#contacts">العملاء</a></li>
                <li role="presentation"><a href="#logs">السجلات</a></li>
            </div>
            <div class="tab-content">
                @if(auth()->user()->can('supplier.view') || auth()->user()->can('customer.view') || auth()->user()->can('supplier.view_own') || auth()->user()->can('customer.view_own'))
                    <div id="contacts" class="overflow-x-auto pt-2">
                        <table class="table table-bordered table-striped tab-pane" id="contact_table" role="tabpanel">
                            <thead>
                            <tr>
                                <th>@lang('lang_v1.contact_id')</th>
                                <th>@lang('business.business_name')</th>
                                <th>@lang('user.name')</th>
                                <th>@lang('business.email')</th>
                                <th>@lang('contact.tax_no')</th>
                                <th>@lang('lang_v1.credit_limit')</th>
                                <th>@lang('contact.pay_term')</th>
                                <th>@lang('account.opening_balance')</th>
                                <th>@lang('lang_v1.advance_balance')</th>
                                <th>@lang('lang_v1.added_on')</th>
                                <th>@lang('lang_v1.customer_group')</th>
                                <th>@lang('business.address')</th>
                                <th>@lang('lang_v1.location')</th>
                                <th>@lang('contact.mobile')</th>
                                <th>@lang('contact.total_sale_due')</th>
                                <th>@lang('lang_v1.total_sell_return_due')</th>
                                @php
                                    $custom_labels = json_decode(session('business.custom_labels'), true);
                                @endphp
                                <th>
                                    {{ $custom_labels['contact']['custom_field_1'] ?? __('lang_v1.contact_custom_field1') }}
                                </th>
                                <th>
                                    {{ $custom_labels['contact']['custom_field_2'] ?? __('lang_v1.contact_custom_field2') }}
                                </th>
                                <th>
                                    {{ $custom_labels['contact']['custom_field_3'] ?? __('lang_v1.contact_custom_field3') }}
                                </th>
                                <th>
                                    {{ $custom_labels['contact']['custom_field_4'] ?? __('lang_v1.contact_custom_field4') }}
                                </th>
                                <th>
                                    {{ $custom_labels['contact']['custom_field_5'] ?? __('lang_v1.custom_field', ['number' => 5]) }}
                                </th>
                                <th>
                                    {{ $custom_labels['contact']['custom_field_6'] ?? __('lang_v1.custom_field', ['number' => 6]) }}
                                </th>
                                <th>
                                    {{ $custom_labels['contact']['custom_field_7'] ?? __('lang_v1.custom_field', ['number' => 7]) }}
                                </th>
                                <th>
                                    {{ $custom_labels['contact']['custom_field_8'] ?? __('lang_v1.custom_field', ['number' => 8]) }}
                                </th>
                                <th>
                                    {{ $custom_labels['contact']['custom_field_9'] ?? __('lang_v1.custom_field', ['number' => 9]) }}
                                </th>
                                <th>
                                    {{ $custom_labels['contact']['custom_field_10'] ?? __('lang_v1.custom_field', ['number' => 10]) }}
                                </th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr class="bg-gray font-17 text-center footer-total">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td
                                        @if($type == 'supplier')
                                            colspan="6"
                                        @elseif( $type == 'customer')
                                            @if($reward_enabled)
                                                colspan="9"
                                        @else
                                            colspan="8"
                                        @endif
                                        @endif>
                                    <strong>
                                        @lang('sale.total'):
                                    </strong>
                                </td>
                                <td id="footer_contact_due"></td>
                                <td id="footer_contact_return_due"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
                <div id="logs" class="d-none pt-2">
                    @foreach($track->activities as $activity)
                        @switch($activity->description)
                            @case('created')
                                <div class="alert alert-success d-flex justify-content-between">
                                    تم إنشاء خط السير من قبل {{ $activity->causer->getUserFullNameAttribute() }}
                                    <span>
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                @break
                            @case('updated')
                                <div class="alert alert-warning d-flex justify-content-between">
                                    تم تعديل خط السير من قبل {{ $activity->causer->getUserFullNameAttribute() }}
                                    <span>
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                @break
                            @case('contact.attached')
                                <div class="alert alert-info d-flex justify-content-between">
                                    تم إضافة العميل {{ $activity->properties->get('name') }} في خط السير من
                                    قبل {{ $activity->causer->getUserFullNameAttribute() }}
                                    <span>
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                @break
                            @case('contact.detached')
                                <div class="alert alert-danger d-flex justify-content-between">
                                    تم إزالة العميل {{ $activity->properties->get('name') }} من خط السير من
                                    قبل {{ $activity->causer->getUserFullNameAttribute() }}
                                    <span>
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                @break
                            @default
                                <div class="alert alert-info d-flex justify-content-between">
                                    {{ $activity->description }}
                                    <span>
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>
                                </div>
                        @endswitch
                    @endforeach
                </div>
            </div>
        @endcomponent
    </section>
@endsection
@section('css')
    <style>
        .overflow-x-auto {
            overflow-x: auto;
        }

        .d-none {
            display: none;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .pt-2 {
            padding-top: 20px;
        }
    </style>
@endsection
@section('javascript')
    <script>
        $(document).ready(function () {
            const elID = $('#contact_tabs li.active a').attr('href');
            $('#contact_tabs~.tab-content>div').addClass('d-none');
            $(elID).removeClass('d-none');
        });
        $('#contact_tabs a').on('click', function (event) {
            event.preventDefault();
            const elID = $(this).attr('href');
            $('#contact_tabs li').removeClass('active');
            $(this).parent().addClass('active');
            $('#contact_tabs~.tab-content>div').addClass('d-none');
            $(elID).removeClass('d-none');
        })
    </script>
@endsection

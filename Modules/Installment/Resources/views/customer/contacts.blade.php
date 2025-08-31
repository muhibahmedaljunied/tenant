@extends('layouts.app')
@section('title',__('installment::lang.customer_instalment'))

@section('content')

    @include('installment::layouts.partials.style')

    <section class="content-header">
        <h1>@lang('installment::lang.customer_instalment')</h1>
    </section>

    @csrf
    <section class="content no-print">
        @component('components.widget', ['class' => 'box-primary', 'title' =>''])
            @can('installment.view')


                <div class="row">
            <div class="col-lg-3">
    <div class="form-group">
        <label for="customer_id">{{ __('installment::lang.customers') }}:</label>
        <select id="customer_id" name="customer_id" class="form-control select2">
            @foreach($customers as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-lg-2">
    <div class="form-group">
        <label for="balance_due">إجمالي المديونية:</label>
        <input type="text" id="balance_due" name="balance_due" value="00.00" class="form-control text-disabled" readonly>
    </div>
</div>



                </div>
                <div class="row">
              <div class="col-md-2">
    <div class="form-group">
        <label for="installment_status">{{ __('installment::lang.installment_status') }}:</label>
        <select name="installment_status" id="installment_status" class="form-control">
            <option value="0">{{ __('installment::lang.all_installment') }}</option>
            <option value="1">{{ __('installment::lang.paid_installment') }}</option>
            <option value="2">{{ __('installment::lang.due_installment') }}</option>
            <option value="3">{{ __('installment::lang.late_installment') }}</option>
        </select>
    </div>
</div>

<div class="col-md-2">
    <div class="form-group">
        <label for="datefrom">{{ __('installment::lang.datefrom') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </span>
            <input type="text" name="datefrom" id="datefrom" value="{{ Carbon::now()->startOfYear()->format('Y-m-d') }}" class="form-control date-picker" readonly>
        </div>
    </div>
</div>

<div class="col-md-2">
    <div class="form-group">
        <label for="dateto">{{ __('installment::lang.dateto') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </span>
            <input type="text" name="dateto" id="dateto" value="{{ Carbon::now()->endOfYear()->format('Y-m-d') }}" class="form-control date-picker" readonly>
        </div>
    </div>
</div>

                </div>


            @endcan

            {{-- <button type="button" class="btn  btn-primary " onclick="tprint(69)"  >
                     <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>--}}



            <div class="view-div">
                @can('installment.view')

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped " id="data_table">
                            <thead>
                            <tr>
                                <th >العميل</th>
                                <th>حالة القسط</th>
                                <th >عدد الأقساط</th>
                                <th ></th>


                            </tr>
                            </thead>

                        </table>
                    </div>


                @endcan
            </div>



        @endcomponent



    </section>

    <div class="modal fade div_modal" tabindex="-1" role="dialog"
         aria-labelledby="gridSystemModalLabel">
    </div>

    <section class="invoice print_section" id="installment_section">
    </section>
@endsection


@section('javascript')
    {{--   <script  src='{{Module::asset('installment:js/app.js?v=' . $asset_v)}}'></script>--}}
    @include('installment::layouts.partials.javascripts')


    <script type="text/javascript">

        $(document).ready(function () {

            data_table = $('#data_table').DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url:'/installment/contactwithinstallment',
                    data:function(d) {
                        d.id= $('#customer_id').val();
                        d.installment_status= $('#installment_status').val();
                        d.dateform= $('#datefrom').val();
                        d.dateto= $('#dateto').val();
                    }
                },

                 columnDefs: [
                    {
                        targets:3,
                        orderable: false,
                        searchable: false,
                    },
                ],
            });
            $('#customer_id').on('change',function () {
                var customer_id = $('#customer_id').val();
                $.ajax({
                    method: 'GET',
                    url: '/installment/getcustomerdata/' + customer_id,
                    data: {
                        id: customer_id
                    },
                    success: function (result) {
                        $('#balance_due').val(result['balance_due'].toFixed(2));
                    }
                });
                data_table.ajax.reload();

            });


            $(document).on('change','#installment_status', function () {
                data_table.ajax.reload();
            });

            $('.date-picker').change(function() {
                data_table.ajax.reload();
            });

        });







    </script>

@endsection


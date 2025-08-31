@extends('layouts.app')
@section('title', __('zatca.invoices.title'))

@section('content')
    <section class="content-header">
        <h1>@lang('zatca.invoices.title')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('zatca.invoices.title')])
            {{-- @can('customer.view') --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="zatca_invoices_table">
                    <thead>
                        <tr>
                            <th>@lang('zatca.invoices.number')</th>
                            <th>@lang('zatca.invoices.issue_at')</th>
                            <th>@lang('account.transaction_type')</th>
                            <th>@lang('zatca.settings.egs_serial_number')</th>
                            <th>@lang('zatca.invoices.total')</th>
                            <th>@lang('zatca.invoices.tax_total')</th>
                            <th>@lang('zatca.invoices.total_discount')</th>
                            <th>@lang('zatca.invoices.total_net')</th>
                            <th>@lang('zatca.invoices.sent_to_zatca') </th>
                            <th>@lang('zatca.completeness')</th>
                            <th>@lang('messages.action')</th>
                        </tr>
                    </thead>
                </table>
            </div>
            {{-- @endcan --}}
        @endcomponent

        <div class="modal fade zatca_invoice_show" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->
@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            zatca_settings_dt = $("#zatca_invoices_table").DataTable({
                processing: true,
                serverSide: true,
                scrollY: "75vh",
                autoWidth: false,
                scrollX: true,
                scrollCollapse: true,
                ajax: {
                    url: "{{ route('zatca.invoices.index') }}",
                    data: function(d) {
                        console.log(d);
                    }
                },
                aaSorting: [
                    [1, 'desc']
                ],
                columns: [{
                        data: 'invoiceNumber',
                        name: 'invoiceNumber',
                        searchable: true,
                    }, 
                    {
                        data: 'issue_at',
                        name: 'issue_at',
                        searchable: true,
                    },
                    {
                        data: 'transaction_type',
                        name: 'transaction_type',
                        searchable: false,
                    },
                    {
                        data: 'egs_serial_number',
                        name: 'egs_serial_number',
                        searchable: true,
                    },
                    {
                        data: 'total',
                        name: 'total',
                        searchable: false,
                    },

                    {
                        data: 'tax_total',
                        name: 'tax_total',
                        searchable: false,
                    },

                    {
                        data: 'total_discount',
                        name: 'total_discount',
                        searchable: false,
                    },

                    {
                        data: 'total_net',
                        name: 'total_net',
                        searchable: false,
                    },

                    {
                        data: 'sent_to_zatca',
                        name: 'sent_to_zatca',
                        searchable: false,
                    },
                    {
                        data: 'sent_to_zatca_status',
                        name: 'sent_to_zatca_status',
                        searchable: false,
                    },
                    {

                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false
                    }
                ],
                "fnDrawCallback": function(oSettings) {
                    __currency_convert_recursively($('#zatca_invoices_table'));
                },
            });
            // $(document).on('click','',function (e) {
            //     e.preventDefault();
            //     let current_btn = $(this);
            //     $.ajax({
            //         url: ""
            //     });
            // })
        });
    </script>
@endsection

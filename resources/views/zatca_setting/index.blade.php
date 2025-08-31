@extends('layouts.app')
@section('title', __('zatca.settings.title'))

@section('content')
    <section class="content-header">
        <h1>@lang('zatca.settings.title')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('zatca.settings.title')])
            {{-- @can('zatca.create') --}}
            @slot('tool')
                <div class="box-tools">
                    <a class="btn btn-block btn-primary" href="{{ action('Zatca\ZatcaSettingsController@create') }}">
                        <i class="fa fa-plus"></i> @lang('messages.add')</a>
                </div>
            @endslot
            {{-- @endcan --}}
            {{-- @can('customer.view') --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="zatca_settings_table">
                    <thead>
                        <tr>
                            <th>@lang('zatca.settings.name')</th>
                            <th>@lang('zatca.settings.trn')</th>
                            <th>@lang('zatca.settings.crn')</th>
                            <th>@lang('zatca.settings.egs_serial_number')</th>
                            <th>ONBOARDING STATUS (COMPLIANCE) </th>
                            <th>ONBOARDING STATUS (PRODUCATION) </th>
                            <th>@lang('messages.action')</th>
                        </tr>
                    </thead>
                </table>
            </div>
            {{-- @endcan --}}
        @endcomponent

        <div class="modal fade zatca_show" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->
@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            zatca_settings_dt = $("#zatca_settings_table").DataTable({
                processing: true,
                serverSide: true,
                scrollY: "75vh",
                autoWidth: false,
                scrollX: true,
                scrollCollapse: true,
                ajax: {
                    url: "{{ route('zatca.settings.index') }}",
                    data: function(d) {
                        console.log(d);
                    }
                },
                aaSorting: [
                    [1, 'desc']
                ],
                columns: [{
                    data: 'name',
                    name: 'name',
                    searchable: true,
                }, {
                    data: 'trn',
                    name: 'trn',
                    searchable: true,
                }, {
                    data: 'crn',
                    name: 'crn',
                    searchable: true,
                }, 
                {
                    data: 'egs_serial_number',
                    name: 'egs_serial_number',
                    searchable: true,
                },
                {
                    data: 'certificate',
                    name: 'certificate',
                    searchable: false,
                },

                {
                    data: 'production_certificate',
                    name: 'production_certificate',
                    searchable: false,
                },
                 {
                    data: 'action',
                    name: 'action',
                    searchable: false,
                    orderable: false
                }],
            });

        });
    </script>
@endsection

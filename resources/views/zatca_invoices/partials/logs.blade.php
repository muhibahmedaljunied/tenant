@if (!empty($responses))
    <table class="table table-condensed">
        <thead>
            <tr class="bg-green">
                <th>@lang('lang_v1.date')</th>
                <th>@lang('lang_v1.description')</th>
                <th>@lang('zatca.invoices.sent_to_zatca_status')</th>
                {{-- <th>@lang('brand.note')</th> --}}
            </tr>
        </thead>
        @forelse($responses as $response)
            <tr>
                <td>{{ @format_datetime($response->created_at) }}</td>
                <td>
                    {{-- @dd(json_decode($response->response,true)) --}}
                    @foreach (json_decode($response->response,true)['validationResults']['errorMessages'] as $error)
                        <p class="text-danger">{{ $error }}</p>
                    @endforeach
                    {{-- {{ json_decode($response->response,true) }} --}}
                </td>
                <td>
                    @include('zatca_invoices.datatable.status', [
                        'status' => strtolower($response->status),
                    ])
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">
                    @lang('purchase.no_records_found')
                </td>
            </tr>
        @endforelse
    </table>
@endif

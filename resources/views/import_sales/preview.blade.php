@extends('layouts.app')
@section('title', __('lang_v1.preview_imported_sales'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('lang_v1.preview_imported_sales')</h1>
</section>

<!-- Main content -->
<section class="content">
    <form action="{{ action('ImportSalesController@import') }}" method="post" id="import_sale_form">
        @csrf
        <input type="hidden" name="file_name" value="{{ $file_name }}">
    @component('components.widget')
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="group_by">{{ __('lang_v1.group_sale_line_by') . ':*' }}</label> @show_tooltip(__('lang_v1.group_by_tooltip'))
                <select name="group_by" id="group_by" class="form-control select2" required>
                    <option value="">{{ __('messages.please_select') }}</option>
                    @foreach($parsed_array[0] as $key => $value)
                        <option value="{{ $key }}" @if(old('group_by') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="location_id">{{ __('business.business_location') . ':*' }}</label>
                <select name="location_id" id="location_id" class="form-control" required>
                    <option value="">{{ __('messages.please_select') }}</option>
                    @foreach($business_locations as $key => $value)
                        <option value="{{ $key }}" @if(old('location_id') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @endcomponent
    @component('components.widget')
    <div class="row">
        <div class="col-md-12">
            <div class="scroll-top-bottom" style="max-height: 400px;">
                <table class="table table-condensed table-striped">
                    @foreach(array_slice($parsed_array, 0, 101) as $row)
                        <tr>
                            <td>@if($loop->index > 0 ){{$loop->index}} @else # @endif</td>
                            @foreach($row as $k => $v)
                                @if($loop->parent->index == 0)
                                    <th>{{$v}}</th>
                                @else
                                    <td>{{$v}}</td>
                                @endif
                            @endforeach
                        </tr>
                        @if($loop->index == 0)
                            <tr>
                            <td>@if($loop->index > 0 ){{$loop->index}}@endif</td>
                            @foreach($row as $k => $v)
                                <td>
                                    <select name="import_fields[{{ $k }}]" class="form-control import_fields select2" style="width: 100%;">
                                        <option value="">{{ __('lang_v1.skip') }}</option>
                                        @foreach($import_fields as $field_key => $field_value)
                                            <option value="{{ $field_key }}" @if(isset($match_array[$k]) && $match_array[$k] == $field_key) selected @endif>{{ $field_value }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            @endforeach
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    @endcomponent
    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary pull-right">@lang('messages.submit')</button>
        </div>
    </div>
    </form>
</section>
@stop
@section('javascript')
<script type="text/javascript">
    $(document).on('submit', 'form#import_sale_form', function(){
        var import_fields = [];

        $('.import_fields').each( function() {
            if ($(this).val()) {
                import_fields.push($(this).val());
            }
        });

        if (import_fields.indexOf('customer_phone_number') == -1 && import_fields.indexOf('customer_email') == -1) {
            alert("{{__('lang_v1.email_or_phone_required')}}");
            return false;
        }
        if (import_fields.indexOf('product') == -1 && import_fields.indexOf('sku') == -1) {
            alert("{{__('lang_v1.product_name_or_sku_is_required')}}");
            return false;
        }
        if (import_fields.indexOf('quantity') == -1) {
            alert("{{__('lang_v1.quantity_is_required')}}");
            return false;
        }
        if (import_fields.indexOf('unit_price') == -1) {
            alert("{{__('lang_v1.unit_price_is_required')}}");
            return false;
        }

        if(hasDuplicates(import_fields)) {
            alert("{{__('lang_v1.cannot_select_a_field_twice')}}");
            return false;
        }
        
    });

    function hasDuplicates(array) {
        return (new Set(array)).size !== array.length;
    }
</script>
@endsection
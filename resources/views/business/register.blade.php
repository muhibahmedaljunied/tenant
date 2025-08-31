@extends('layouts.auth2_old')
@section('title', __('lang_v1.register'))

@section('content')
    <style>
.div-content{
    background-color: white;
    padding-right: 15px;
    padding-left: 15px;
    padding-top: 10px;
    padding-bottom: 15px;
    border-radius: 10px;
    margin-top: 20px;
}
        .div-content-titel{
            text-align: center;
            font-size: 24px;
            border-bottom: 2px solid;
            margin-bottom: 25px;
            margin-top: 25px;
        }
    </style>
<div style=" max-width: 800px;margin: auto; margin-top: 30px;">

    <p class="form-header text-white">@lang('business.register_and_get_started_in_minutes')</p>
<form action="{{ route('business.postRegister') }}" method="POST" id="business_register_form" enctype="multipart/form-data">
    @csrf

    {{-- Include the registration form --}}
    @include('business.partials.register_form')

    {{-- Hidden input for package ID --}}
    <input type="hidden" name="package_id" value="{{ $package_id }}">

    <div class="form-group" style="width: 100%; text-align: center; margin-top: -10px; background-color: white; padding-bottom: 10px; padding-top: 20px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
        <button type="submit" class="btn btn-primary btn-flat btn-login" style="border-radius: 10px; height: 50px; font-size: 19px;">
            @lang('lang_v1.register')
        </button>
    </div>
</form>

</div>
@stop
@section('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        $('#change_lang').change( function(){
            window.location = "{{ route('business.getRegister') }}?lang=" + $(this).val();
        });
    })
</script>
@endsection
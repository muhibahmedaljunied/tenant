@inject('request', 'Illuminate\Http\Request')



@if ($request->segment(1) == 'pos' && ($request->segment(2) == 'create' || $request->segment(3) == 'edit'))
    @php
        $pos_layout = true;
    @endphp
@else
    @php
        $pos_layout = false;
    @endphp
@endif

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"
    dir="{{ in_array(trim(session()->get('user.language', config('app.locale'))), config('constants.langs_rtl')) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ Session::get('business.name') }}</title>

    @include('layouts.partials.css')

    @yield('css')



    <style>
        @media screen and (max-width: 576px) {

            .sidebar-open .main-header {

                transform: translate(230px, 0);
            }
        }
    </style>
</head>


<body
    class="@if ($pos_layout) hold-transition lockscreen @else hold-transition skin-@if (!empty(session('business.theme_color'))){{ session('business.theme_color') }}@else{{ 'black-light' }} @endif sidebar-mini @endif">



    <div class="wrapper thetop">
   


            @include('layouts.partials.header2')
            @include('layouts.partials.sidebar')
    

        <!-- Content Wrapper. Contains page content -->
        <div class="@if (!$pos_layout) content-wrapper @endif">
            <!-- empty div for vuejs -->
            <div id="app">
                @yield('vue')
            </div>
        
            @if (session('status'))
                <input type="hidden" id="status_span" data-status="{{ session('status.success') }}"
                    data-msg="{{ session('status.msg') }}">
            @endif
            @yield('content')

            <div class='scrolltop no-print'>
                <div class='scroll icon'><i class="fas fa-angle-up"></i></div>
            </div>

            @if (config('constants.iraqi_selling_price_adjustment'))
                <input type="hidden" id="iraqi_selling_price_adjustment">
            @endif

            <!-- This will be printed -->
            <section class="invoice print_section" id="receipt_section">
            </section>

        </div>
        @include('home.todays_profit_modal')
        <!-- /.content-wrapper -->

        @if (!$pos_layout)
            @include('layouts.partials.footer')
  
        @endif

        <audio id="success-audio">
            <source src="{{ url('/audio/success.ogg?v=' . $asset_v) }}" type="audio/ogg">
            <source src="{{ url('/audio/success.mp3?v=' . $asset_v) }}" type="audio/mpeg">
        </audio>
        <audio id="error-audio">
            <source src="{{ url('/audio/error.ogg?v=' . $asset_v) }}" type="audio/ogg">
            <source src="{{ url('/audio/error.mp3?v=' . $asset_v) }}" type="audio/mpeg">
        </audio>
        <audio id="warning-audio">
            <source src="{{ url('/audio/warning.ogg?v=' . $asset_v) }}" type="audio/ogg">
            <source src="{{ url('/audio/warning.mp3?v=' . $asset_v) }}" type="audio/mpeg">
        </audio>
    </div>

    @if (!empty($__additional_html))
        {!! $__additional_html !!}
    @endif

    @include('layouts.partials.javascripts')

    <div class="modal fade view_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

    @if (!empty($__additional_views) && is_array($__additional_views))
        @foreach ($__additional_views as $additional_view)
            @includeIf($additional_view)
        @endforeach
    @endif
    @stack('scripts')
</body>

</html>

@extends('layouts.app2')

@section('title', __('home.home'))

@section('content')
    <style>
        @font-face {
            font-family: "icomoon";
            src: url("fonts/icomoon.eot");
            src: url("fonts/icomoon.eot?#iefix") format("embedded-opentype"), url("fonts/icomoon.woff") format("woff"), url("fonts/icomoon.ttf") format("truetype"), url("fonts/icomoon.svg#icomoon") format("svg");
            font-weight: normal;
            font-style: normal;
        }

        .info-box-text {
            color: #01070e;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 2px;
        }

        h5 {
            font-family: 'Cairo', sans-serif;
            color: inherit;

        }

        .row-custom .col-custom {
            display: flex;
            /* margin: 0px; */
            padding: 0px 4px;
            margin: 0px 0px;
        }

        .box,
        .info-box {
            margin-bottom: 7px;
            box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15) !important;
            border-radius: 5px;
        }

        .box-icon {
            color: #40485b !important;
            background: white !important;
            text-align: center;
            border: none;
        }

        .box-icon:hover {
            background: #40485b !important;
            color: white !important;
            text-align: center;
        }

        .parent-box {
            border: 1px solid #ddd;
            height: 110px;
            background: white;
            padding-top: 20px;
            color: #40485b;
            text-align: center;
        }

        .parent-box h5 {
            font-family: 'Cairo', sans-serif;
            font-size: 13px;
        }

        .parent-box i {
            font-size: 36px;



        }

        .list-group-item {
            position: relative;
            display: block;
            padding: 5px 15px;
            margin-bottom: -1px;
            background: inherit;
            border: none;

        }

        .parent-box:hover {
            font-size: 36px;
            background: #40485b !important;
            color: white !important;

        }

        .list-group-item a {
            color: inherit;
            font-size: 10px;
            text-decoration: inherit;
        }

        .list-group-item a:hover {
            color: inherit;
            font-size: 10px;
            text-decoration: revert;
        }

        .icon-user-tie:before {
            content: "\e976";
        }

        .list-group-item {
            position: relative;
            display: block;
            padding: 9px 15px;
            margin-bottom: -1px;
            background: inherit;
            border: none;
        }

        .list-group {
            color: #40485b !important;
            background: white !important;
            text-decoration: none;
            height: 331px;

        }

        .list-group:hover {
            background: #40485b !important;
            color: white !important;
            text-decoration: revert;
        }

        .row {
            background: #fafafa;
        }

        .info-box-new-style .info-box-content {
            padding: 6px 12px 6px 12px;
            margin-left: 64px;
        }

        .info-box-number {
            float: left;
        }

        .total-labels {
            font-size: 20px !important;
            float: right;
        }

        .change-charts {
            z-index: 100;
            position: relative;
            top: 36px;
            left: -55%;
        }

        .cont {
            background-color: #3c3e4f;
            color: white;
            display: block;
            height: 140px;
            text-align: center;
            padding-top: 2px;
            font-size: 23px;
            border-radius: 10px;
            /*border: 1px solid #590631;*/
            margin: auto;
            margin-bottom: auto;
            margin-bottom: 35px;
            max-width: 200px;
            transition: all .5s ease;
            /*  border-top: 3px solid #18466f;*/
            border-bottom: 9px solid #8c1818;
        }

        .cont>h3,
        h2 {
            color: #ffffff;
        }

        .cont:hover {
            background-color: #2084ae;
        }

        .cont:hover h2,
        .cont:hover h3 {
            color: white;
        }
    </style>


    <div
        style="background-color: white;
                width: 95%;
                margin: auto;
                margin-top: 20px;
                padding: 20px;
                border-top: solid;
                border-top-color: currentcolor;
                border-top-style: solid;
                border-top-width: medium;
                border-radius: 10px 10px 0px 0px;">
        <div class="row">
            <div class="col-lg-2 ">
                <a href="/pos/create" class="cont">
                    <h2><i class="fas fa-dollar-sign"></i></h2>
                    <h3>@lang('tenant.users') </h3>

                </a>
            </div>

            <div class="col-lg-2 ">
                <a href="/pos/create" class="cont">
                    <h2><i class="fas fa-dollar-sign"></i></h2>
                    <h3>@lang('tenant.tenants') </h3>

                </a>
            </div>


            {{-- <div class="col-lg-2 ">
                <a href="/pos/create" class="cont">
                    <h2><i class="fas fa-dollar-sign"></i></h2>
                    <h3>@lang('lang_v1.kasher') </h3>

                </a>
            </div>


            <div class="col-lg-2 ">
                <a href="/pos/create" class="cont">
                    <h2><i class="fas fa-dollar-sign"></i></h2>
                    <h3>@lang('lang_v1.kasher') </h3>

                </a>
            </div>

            <div class="col-lg-2 ">
                <a href="/pos/create" class="cont">
                    <h2><i class="fas fa-dollar-sign"></i></h2>
                    <h3>@lang('lang_v1.kasher') </h3>

                </a>
            </div>

            <div class="col-lg-2 ">
                <a href="/pos/create" class="cont">
                    <h2><i class="fas fa-dollar-sign"></i></h2>
                    <h3>@lang('lang_v1.kasher') </h3>

                </a>
            </div> --}}

            <?php
            
            // echo session()->get('user.language');
            // var_dump(config('app.locale'));
            // var_dump(config('constants.langs_rtl'));
            // echo in_array(trim(session()->get('user.language', config('app.locale'))), config('constants.langs_rtl'));
            ?>


        </div>

        <br>





    </div>






    <!-- /.content -->
    <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
@stop
@section('javascript')
    <script src="{{ asset('js/home.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>

@endsection

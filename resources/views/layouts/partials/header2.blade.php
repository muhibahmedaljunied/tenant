@inject('request', 'Illuminate\Http\Request')
<!-- Main Header -->

<style>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300&display=swap" rel="stylesheet">@import url('https://fonts.googleapis.com/css2?family=Cairo&display=swap');
</style>
<style>
    /*Main body font*/
    body {
        font-family: 'Cairo', sans-serif;
    }

    .skin-blue-light .sidebar-menu>li.active a {

        background: #3ebfbe !important;
    }

    .skin-blue .main-header .navbar,
    .skin-blue-light .main-header .navbar {
        background: #ffffff;
        color: black !important;
    }

    .skin-blue-light .main-header .navbar .nav>li>a {
        color: #000;
    }

    element.style {}

    b,
    strong {
        font-weight: 700;
    }

    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    user agent stylesheet strong {
        font-weight: bold;
    }

    .skin-blue .main-header .navbar,
    .skin-blue-light .main-header .navbar {
        background: #ffffff;
        color: black !important;
    }

    .skin-blue-light .main-header .navbar .sidebar-toggle {
        color: #000;
    }

    .btn-success {
        background: #3ebfbe;
        border-color: #426958;
    }

    .skin-blue .content-wrapper .content-header-custom,
    .skin-blue-light .content-wrapper .content-header-custom {
        background: #fafafa;
        color: black !important;
        padding: 15px 15px 200px;
    }


    .skin-blue .content-wrapper .content-header-custom a,
    .skin-blue .content-wrapper .content-header-custom h1,
    .skin-blue .content-wrapper .content-header-custom small,
    .skin-blue-light .content-wrapper .content-header-custom a,
    .skin-blue-light .content-wrapper .content-header-custom h1,
    .skin-blue-light .content-wrapper .content-header-custom small {
        color: black !important;

    }

    @media screen and (max-width: 576px) {
        .sidebar-open .main-header {
            -webkit-transform: translate(230px, 0);
            -ms-transform: translate(230px, 0);
            -o-transform: translate(230px, 0);
            transform: translate(-230px, 0);
        }
    }
</style>
<header class="main-header no-print">

    <a href="{{ route('home') }}" class="logo">

        {{-- <span class="logo-lg">{{ Session::get('business.name') }} <i class="fa fa-circle text-success"
                id="online_indicator"></i></span> --}}

    </a>


    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            &#9776;
            <span class="sr-only">Toggle navigation</span>
        </a>



        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">



            <div class="btn-group">
                <button id="header_shortcut_dropdown" type="button"
                    class="btn btn-success dropdown-toggle btn-flat pull-left m-8 btn-sm mt-10" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-plus-circle fa-lg"></i>
                </button>

            </div>






      






            <ul class="nav navbar-nav">

                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        @php
                            $profile_photo = auth()->user();
                        @endphp
                        @if (!empty($profile_photo))
                            <img src="{{ $profile_photo->display_url }}" class="user-image" alt="User Image">
                        @endif
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span>{{ Auth::User()->username }} </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">

                            <p>
                                {{ Auth::User()->username }}
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            {{-- <div class="pull-left">
                                <a href="{{ action('ItGuy\UserController@getProfile') }}"
                                    class="btn btn-default btn-flat">@lang('lang_v1.profile')</a>
                            </div> --}}
                            <div class="pull-right">
                                <a href="{{ action('Auth\LoginController@logout') }}"
                                    class="btn btn-default btn-flat">@lang('lang_v1.sign_out')</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
            </ul>
        </div>
    </nav>
</header>

<div class="modal fade divcurrency" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>

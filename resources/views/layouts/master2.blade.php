<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<html lang="{{ app()->getLocale() }}">
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>Royal App | @yield('title')</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="This is your Royal Tropical Tour App" name="description" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta content="Royal Team" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="{{ asset('assets/global/plugins/googleapis/css/googleapis.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        @yield('page-css')
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{ asset('assets/global/css/components.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/css/app.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/bootstrap-toastr/toastr.css') }}" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="{{ asset('assets/layouts/layout2/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/layouts/layout2/css/themes/blue.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
        <link href="{{ asset('assets/layouts/layout2/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="{{ asset('assets/layouts/layout2/img/favicon.png') }}" /> </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid">
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo" style="/*height: 45px;*/">
                    <!--a href="{{ route('home') }}">
                        <img src="{{ asset('assets/layouts/layout2/img/logo-default-royal.png') }}" alt="logo" class="logo-default" style="/*margin: 16px 0 0;*/" /> </a-->
                    <div class="show-date" style="display: inline-block; margin-top: 2px;">
                        <p style="color: #fff; font-weight: 700; font-size: 16px;">{{ $currentDate }}</p>
                    </div>
                    <div class="menu-toggler sidebar-toggler" style="/*margin: 12px 0 0;*/">
                        <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                    </div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN PAGE TOP -->
                <div class="page-top" style="/*height: 45px;*/">
                    <div class="master-logo-container">
                        <a href="{{ route('home') }}">
                            <img id="logo1" src="{{ asset('assets/layouts/layout2/img/logo-royal-224x50.png') }}" />
                            <img id="logo2" src="{{ asset('assets/layouts/layout2/img/logo-royal-elemental.png') }}" style="margin-top: 5px;"/>
                        </a>
                    </div>
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                            <!-- BEGIN USER LOGIN DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="dropdown dropdown-user" style="/*height: 45px;*/">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" style="/*padding: 11px;*/">
                                    <img alt="" class="img-circle" src="{{ asset('assets/layouts/layout2/img/avatar.png') }}" />
                                    <span class="username username-hide-on-mobile"> {{ Auth::user()->name }} </span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <!--li>
                                        <a href="#">
                                            <i class="icon-user"></i> My Profile </a>
                                    </li>
                                    <li class="divider"> </li-->
                                    <li>
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="icon-key"></i> Log Out </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END PAGE TOP -->
            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->

        <!-- BEGIN CONTAINER -->
        <div class="page-container" style="/*margin-top: 45px;*/">
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- END SIDEBAR -->
                <div class="page-sidebar navbar-collapse collapse">
                    <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-hover-submenu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                    @if(Auth::user()->hasRole('client'))
                        <li class="nav-item {{ isset($menuContract) ? 'active open' : '' }}">
                            <a href="{{ route('client.hotel.index') }}" class="nav-link nav-toggle">
                                <i class="fa fa-building-o"></i>
                                <span class="title">Hotel</span>
                                <span class="{{ isset($menuContract) ? 'selected' : '' }}"></span>
                                <!--span class="arrow"></span-->
                            </a>
                        </li>
                    @else
                        <li class="nav-item start {{ isset($menuHome) ? 'active open' : '' }}">
                            <a href="{{ route('home') }}" class="nav-link nav-toggle">
                                <i class="icon-home"></i>
                                <span class="title">Home</span>
                                <span class="{{ isset($menuHome) ? 'selected' : '' }}"></span>
                            </a>
                        </li>
                        <!--li class="nav-item start {{ isset($menuDashboard) ? 'active open' : '' }}">
                            <a href="{{ route('dashboard') }}" class="nav-link nav-toggle">
                                <i class="fa fa-bar-chart"></i>
                                <span class="title">Dashboard</span>
                                <span class="{{ isset($menuDashboard) ? 'selected' : '' }}"></span>
                            </a>
                        </li-->
                        <!--li class="nav-item {{ isset($menuCar) ? 'active open' : '' }}">
                            <a href="{{ route('car.model.index') }}" class="nav-link nav-toggle">
                                <i class="fa fa-car"></i>
                                <span class="title">Car</span>
                                <span class="{{ isset($menuCar) ? 'selected' : '' }}"></span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item {{ isset($submenuCarCategory) ? 'active open' : '' }}">
                                    <a href="{{ route('car.category.index') }}" class="nav-link ">
                                        <i class="fa fa-list-ul"></i>
                                        <span class="title">Categories</span>
                                        <span class="{{ isset($submenuCarCategory) ? 'selected' : '' }}"></span>
                                    </a>
                                </li>
                                <li class="nav-item {{ isset($submenuCarBrand) ? 'active open' : '' }}">
                                    <a href="{{ route('car.model.index') }}" class="nav-link ">
                                        <i class="fa fa-car"></i>
                                        <span class="title">Models</span>
                                        <span class="{{ isset($submenuCarBrand) ? 'selected' : '' }}"></span>
                                    </a>
                                </li>
                            </ul>
                        </li-->
                        <li class="nav-item {{ isset($menuHotel) ? 'active open' : '' }}">
                            <a href="{{ route('hotel.index') }}" class="nav-link nav-toggle">
                                <i class="fa fa-building-o"></i>
                                <span class="title">Hotel</span>
                                <span class="{{ isset($menuHotel) ? 'selected' : '' }}"></span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item {{ isset($submenuHotelPaxType) ? 'active open' : '' }}">
                                    <a href="{{ route('hotel.paxtype.index') }}" class="nav-link ">
                                        <i class="fa fa-male"></i>
                                        <span class="title">Pax Types</span>
                                        <span class="{{ isset($submenuHotelPaxType) ? 'selected' : '' }}"></span>
                                    </a>
                                </li>
                                <li class="nav-item {{ isset($submenuHotelRoomType) ? 'active open' : '' }}">
                                    <a href="{{ route('hotel.roomtype.index') }}" class="nav-link ">
                                        <i class="fa fa-hotel"></i>
                                        <span class="title">Room Types</span>
                                        <span class="{{ isset($submenuHotelRoomType) ? 'selected' : '' }}"></span>
                                    </a>
                                </li>
                                <li class="nav-item {{ isset($submenuHotelBoardType) ? 'active open' : '' }}">
                                    <a href="{{ route('hotel.boardtype.index') }}" class="nav-link ">
                                        <i class="fa fa-cutlery"></i>
                                        <span class="title">Board Types</span>
                                        <span class="{{ isset($submenuHotelBoardType) ? 'selected' : '' }}"></span>
                                    </a>
                                </li>
                                <li class="nav-item {{ isset($submenuHotelChain) ? 'active open' : '' }}">
                                    <a href="{{ route('hotel.hotelchain.index') }}" class="nav-link ">
                                        <i class="fa fa-cubes"></i>
                                        <span class="title">Hotels Chain</span>
                                        <span class="{{ isset($submenuHotelChain) ? 'selected' : '' }}"></span>
                                    </a>
                                </li>
                                <li class="nav-item {{ isset($submenuHotel) ? 'active open' : '' }}">
                                    <a href="{{ route('hotel.index') }}" class="nav-link ">
                                        <i class="fa fa-building-o"></i>
                                        <span class="title">Hotels</span>
                                        <span class="{{ isset($submenuHotel) ? 'selected' : '' }}"></span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ isset($menuContract) ? 'active open' : '' }}">
                            <a href="{{ route('contract.provider.hotel.index') }}" class="nav-link nav-toggle">
                                <i class="fa fa-file-text-o"></i>
                                <span class="title">Contract</span>
                                <span class="{{ isset($menuContract) ? 'selected' : '' }}"></span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item {{ isset($submenuContractProvider) ? 'active open' : '' }}">
                                    <a href="{{ route('contract.provider.hotel.index') }}" class="nav-link ">
                                        <i class="fa fa-gears"></i>
                                        <span class="title">Providers</span>
                                        <span class="{{ isset($submenuContractProvider) ? 'selected' : '' }}"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="nav-item {{ isset($submenuContractProviderHotel) ? 'active open' : '' }}">
                                            <a href="{{ route('contract.provider.hotel.index') }}" class="nav-link ">
                                                <i class="fa fa-building-o"></i>
                                                <span class="title">Hotels</span>
                                                <span class="{{ isset($submenuContractProviderHotel) ? 'selected' : '' }}"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item {{ isset($submenuContractClient) ? 'active open' : '' }}">
                                    <a href="{{ route('contract.client.hotel.index') }}" class="nav-link ">
                                        <i class="fa fa-users"></i>
                                        <span class="title">Clients</span>
                                        <span class="{{ isset($submenuContractClient) ? 'selected' : '' }}"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="nav-item {{ isset($submenuContractClientHotel) ? 'active open' : '' }}">
                                            <a href="{{ route('contract.client.hotel.index') }}" class="nav-link ">
                                                <i class="fa fa-building-o"></i>
                                                <span class="title">Hotels</span>
                                                <span class="{{ isset($submenuContractClientHotel) ? 'selected' : '' }}"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ isset($menuNomenclators) ? 'active open' : '' }}">
                            <a href="{{ route('administration.market.index') }}" class="nav-link nav-toggle">
                                <i class="fa fa-diamond"></i>
                                <span class="title">Nomenclators</span>
                                <span class="{{ isset($menuNomenclators) ? 'selected' : '' }}"></span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item start {{ isset($submenuMarket) ? 'active open' : '' }}">
                                    <a href="{{ route('administration.market.index') }}" class="nav-link ">
                                        <i class="fa fa-tag"></i>
                                        <span class="title">Price Rates</span>
                                        <span class="{{ isset($submenuMarket) ? 'selected' : '' }}"></span>
                                    </a>
                                </li>
                                <li class="nav-item start {{ isset($submenuLocations) ? 'active open' : '' }}">
                                    <a href="{{ route('administration.location.index') }}" class="nav-link ">
                                        <i class="fa fa-globe"></i>
                                        <span class="title">Locations</span>
                                        <span class="{{ isset($submenuLocations) ? 'selected' : '' }}"></span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--li class="nav-item ">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="fa fa-microphone "></i>
                                <span class="title">Tranfer</span>
                                <span class="arrow"></span>
                            </a>
                        </li-->
                        @if(Auth::user()->hasRole('administrator'))
                            <li class="nav-item {{ isset($menuAdministration) ? 'active open' : '' }}">
                                <a href="{{ route('administration.user.index') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-lock"></i>
                                    <span class="title">Administration</span>
                                    <span class="{{ isset($menuAdministration) ? 'selected' : '' }}"></span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item start {{ isset($submenuUser) ? 'active open' : '' }}">
                                        <a href="{{ route('administration.user.index') }}" class="nav-link ">
                                            <i class="fa fa-user"></i>
                                            <span class="title">Users</span>
                                            <span class="{{ isset($submenuUser) ? 'selected' : '' }}"></span>
                                        </a>
                                    </li>
                                    <li class="nav-item start {{ isset($submenuTrace) ? 'active open' : '' }}">
                                        <a href="{{ route('administration.trace.index') }}" class="nav-link ">
                                            <i class="fa fa-binoculars"></i>
                                            <span class="title">Traces</span>
                                            <span class="{{ isset($submenuTrace) ? 'selected' : '' }}"></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endif
                    </ul>
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <h1 class="page-title"> @yield('page-title')
                        <small>@yield('page-sub-title')</small>
                    </h1>
                    @if (!isset($menuHome))
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <i class="icon-home"></i>
                                <a href="{{ route('home') }}">Home</a>
                                <i class="fa fa-angle-right"></i>
                            </li>
                            @for($i = 0; $i < count($breadcrumb); $i++)
                                <li>
                                    <span>{{ $breadcrumb[$i] }}</span>
                                    @if (count($breadcrumb) != $i + 1)
                                        <i class="fa fa-angle-right"></i>
                                    @endif
                                </li>
                            @endfor
                        </ul>
                        @yield('page-toolbar')
                    </div>
                    @endif
                    <!-- END PAGE HEADER-->

                    @yield('content')

                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->

        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner"> 2019 &copy; Royal Team
                <!--a target="_blank" href="http://keenthemes.com">Keenthemes</a> &nbsp;|&nbsp;
                <a href="http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes" title="Purchase Metronic just for 27$ and get lifetime updates for free" target="_blank">Purchase Metronic!</a-->
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->

            <!--[if lt IE 9]>
            <script src="{{ asset('assets/global/plugins/respond.min.js') }}"></script>
            <script src="{{ asset('assets/global/plugins/excanvas.min.js') }}"></script>
            <script src="{{ asset('assets/global/plugins/ie8.fix.min.js') }}"></script>
            <![endif]-->
            <!-- BEGIN CORE PLUGINS -->
            <script src="{{ asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
            <!-- END CORE PLUGINS -->
            <!-- BEGIN PAGE LEVEL PLUGINS -->
            @yield('page-plugins')
            <!-- END PAGE LEVEL PLUGINS -->
            <!-- BEGIN THEME GLOBAL SCRIPTS -->
            <script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
            <!-- END THEME GLOBAL SCRIPTS -->
            <!-- BEGIN PAGE LEVEL SCRIPTS -->
            @yield('page-script')
            <!-- END PAGE LEVEL SCRIPTS -->
            <!-- BEGIN THEME LAYOUT SCRIPTS -->
            <script src="{{ asset('assets/layouts/layout/scripts/layout.min.js') }}" type="text/javascript"></script>
            <!--script src="{{ asset('assets/layouts/layout/scripts/demo.min.js') }}" type="text/javascript"></script-->
            <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/layouts/global/scripts/quick-nav.min.js') }}" type="text/javascript"></script>
            <!-- END THEME LAYOUT SCRIPTS -->
            <script>
                function decodeHTML(html) {
                    var txt = document.createElement('textarea');
                    txt.innerHTML = html;
                    return txt.value;
                };

                $(document).ready(function()
                {
                    $('.sidebar-toggler').on('click', function(e) {
                       if ($('body').hasClass('page-sidebar-closed'))
                           $('.show-date').css('display', 'inline-block');
                       else
                           $('.show-date').css('display', 'none');
                    });
                })
            </script>

            @yield('custom-scripts')

        </div>
    </body>
</html>
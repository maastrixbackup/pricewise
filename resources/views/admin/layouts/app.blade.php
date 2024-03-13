<!DOCTYPE html>
@php
//dd($themeChange);
@endphp
@if ($themeChange)
<html lang="en" class="{{ $themeChange->theme_style ? $themeChange->theme_style : $themeChange->sidebar_color }} {{ $themeChange->header_color ? $themeChange->header_color : '' }}">
@else
<!-- <html lang="en" class="color-sidebar sidebarcolor4"> -->
<html lang="en" class="color-sidebar">
@endif

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/Favicon.png') }}">
    <!--plugins-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <!-- progress bar -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <!--end progress bar -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous">


    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons2.css') }}" rel="stylesheet">
     <link href="https://poptelecom.co.uk/poptelecom/assets/css/icons2.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}" />

    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />

    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.2.0/css/dataTables.dateTime.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <title>@yield('title')</title>
    @stack('css')

</head>

<body>
    <div class="overlay1" style="display: none;">
        <div class="overlay__inner">
            <div class="overlay__content"><span class="spinner"></span></div>
        </div>
    </div>
    <!--wrapper-->
    <div class="wrapper">
        @include('admin.layouts.sidebar')
        @include('admin.layouts.header')

        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                @yield('content')
            </div>
        </div>
        <!--end page wrapper -->
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <footer class="page-footer">
            <p class="mb-0">Copyright © {{ date('Y') }}. All right reserved.</p>
        </footer>
    </div>
    <!--end wrapper-->

    <!--start switcher-->
    <!-- <div class="switcher-wrapper">
        <div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
        </div>
        <div class="switcher-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-uppercase">Theme Customizer</h5>
                <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
            </div>
            <hr />
            <form id="themeForm" method="post" action="{{ route('admin.theme-store') }}">
                @csrf
                <input type="hidden" name="admin_id" id="admin_id" value="{{ Auth::guard('admin')->user()->id }}">
                <h6 class="mb-1">Theme Styles</h6>
                <a href="{{ route('admin.theme-destroy', ['id' => Auth::guard('admin')->user()->id]) }}" type="button" class="btn btn-secondary ">Default</a>
                <button type="submit" class="btn btn-primary ">Apply</button>
                <hr />
                <div class="d-flex align-items-center justify-content-between">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode" value="light-theme">
                        <label class="form-check-label" for="lightmode">Light</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="darkmode" value="dark-theme">
                        <label class="form-check-label" for="darkmode">Dark</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="semidark" value="semi-dark">
                        <label class="form-check-label" for="semidark">Semi Dark</label>
                    </div>
                </div>
                <hr />
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault" value="minimal-theme">
                    <label class="form-check-label" for="minimaltheme">Minimal Theme</label>
                </div>
                <hr />
                <h6 class="mb-0">Header Colors</h6>
                <hr />
                <div class="header-colors-indigators">
                    <div class="row row-cols-auto g-3">
                        <div class="col">

                            <div class="indigator headercolor1" id="headercolor1"><input class="indigator headercolor themecss" type="radio" name="headerRadio" id="headercolor" value="color-header headercolor1"></div>
                        </div>
                        <div class="col">
                            <div class="indigator headercolor2" id="headercolor2"><input class="indigator headercolor themecss" type="radio" name="headerRadio" id="headercolor" value="color-header headercolor2"></div>
                        </div>
                        <div class="col">
                            <div class="indigator headercolor3" id="headercolor3"><input class="indigator headercolor themecss" type="radio" name="headerRadio" id="headercolor" value="color-header headercolor3"></div>
                        </div>
                        <div class="col">
                            <div class="indigator headercolor4" id="headercolor4"><input class="indigator headercolor themecss" type="radio" name="headerRadio" id="headercolor" value="color-header headercolor4"></div>
                        </div>
                        <div class="col">
                            <div class="indigator headercolor5" id="headercolor5"><input class="indigator headercolor themecss" type="radio" name="headerRadio" id="headercolor" value="color-header headercolor5"></div>
                        </div>
                        <div class="col">
                            <div class="indigator headercolor6" id="headercolor6"><input class="indigator headercolor themecss" type="radio" name="headerRadio" id="headercolor" value="color-header headercolor6"></div>
                        </div>
                        <div class="col">
                            <div class="indigator headercolor7" id="headercolor7"><input class="indigator headercolor themecss" type="radio" name="headerRadio" id="headercolor" value="color-header headercolor7"></div>
                        </div>
                        <div class="col">
                            <div class="indigator headercolor8" id="headercolor8"><input class="indigator headercolor themecss" type="radio" name="headerRadio" id="headercolor" value="color-header headercolor8"></div>
                        </div>
                    </div>
                </div>
                <hr />
                <h6 class="mb-0">Sidebar Colors</h6>
                <hr />
                <div class="header-colors-indigators">
                    <div class="row row-cols-auto g-3">
                        <div class="col">
                            <div class="indigator sidebarcolor1" id="sidebarcolor1"><input class="indigator sidebarcolor themecss" type="radio" name="sidebarRadio" id="sidebarcolor" value="color-sidebar sidebarcolor1"></div>
                        </div>
                        <div class="col">
                            <div class="indigator sidebarcolor2" id="sidebarcolor2"><input class="indigator sidebarcolor themecss" type="radio" name="sidebarRadio" id="sidebarcolor" value="color-sidebar sidebarcolor2"></div>
                        </div>
                        <div class="col">
                            <div class="indigator sidebarcolor3" id="sidebarcolor3"><input class="indigator sidebarcolor themecss" type="radio" name="sidebarRadio" id="sidebarcolor" value="color-sidebar sidebarcolor3"></div>
                        </div>
                        <div class="col">
                            <div class="indigator sidebarcolor4" id="sidebarcolor4"><input class="indigator sidebarcolor themecss" type="radio" name="sidebarRadio" id="sidebarcolor" value="color-sidebar sidebarcolor4"></div>
                        </div>
                        <div class="col">
                            <div class="indigator sidebarcolor5" id="sidebarcolor5"><input class="indigator sidebarcolor themecss" type="radio" name="sidebarRadio" id="sidebarcolor" value="color-sidebar sidebarcolor5"></div>
                        </div>
                        <div class="col">
                            <div class="indigator sidebarcolor6" id="sidebarcolor6"><input class="indigator sidebarcolor themecss" type="radio" name="sidebarRadio" id="sidebarcolor" value="color-sidebar sidebarcolor6"></div>
                        </div>
                        <div class="col">
                            <div class="indigator sidebarcolor7" id="sidebarcolor7"><input class="indigator sidebarcolor themecss" type="radio" name="sidebarRadio" id="sidebarcolor" value="color-sidebar sidebarcolor7"></div>
                        </div>
                        <div class="col">
                            <div class="indigator sidebarcolor8" id="sidebarcolor8"><input class="indigator sidebarcolor themecss" type="radio" name="sidebarRadio" id="sidebarcolor" value="color-sidebar sidebarcolor8"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div> -->
    <!--end switcher-->

    <style>
        nav.sidebar ul.flex-column {
            overflow-y: scroll;
            height: 90vh;
            display: block;
        }

        nav.sidebar ul::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            background-color: #F5F5F5;
        }

        nav.sidebar ul::-webkit-scrollbar {
            width: 3px;
            background-color: #F5F5F5;
        }

        nav.sidebar ul::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
            background-color: #555;
        }

        .sidebar li .submenu {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .sidebar li.nav-item.has-submenu>a {
            display: flex;
            align-items: center;
            gap: 10px
        }

        .sidebar li.nav-item.has-submenu>a i {
            margin-left: auto;
        }

        .sidebar li.nav-item.has-submenu>a {
            color: #000;
            font-weight: 500;
            padding: 15px 20px;
        }

        .nav-link.add-product-btn {
            background: linear-gradient(77deg, rgb(15 217 255) 3%, rgb(108 251 255) 67%);
            text-align: center;
            border-radius: 30px;
            padding: 6px 0;
        }

        .switcher-body {
            padding: 20px 0px;
        }

        .switcher-body .btn-close-div {
            text-align: right;
            padding: 0px 10px;
        }

        .sidebar li.nav-item.has-submenu {
            border-bottom: 1px solid #e0e0e0;
        }

        .sidebar li .submenu a {
            color: #000
        }

        .sidebar li .submenu a i {
            padding-right: 10px;
        }

        .sidebar li .submenu {
            padding: 0 10px;
        }
    </style>


    <!--start right menu-->
    <div class="right-menu-wrapper">
        <div class="switcher-body">
            <div class="btn-close-div">
                <button type="button" class="btn-close ms-auto close-right-menu-btn" aria-label="Close"></button>
            </div>
            <nav class="sidebar">
                <ul class="nav flex-column" id="nav_accordion">
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="#"> Report <i class="fas fa-sort-down"></i></a>
                        <ul class="submenu collapse">
                            <li><a class="nav-link" href="#">UK Sales </a></li>
                            <li><a class="nav-link" href="#">Dubai Sales </a></li>
                        </ul>
                    </li>
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="#"> Shortcuts <i class="fas fa-sort-down"></i></a>
                        <ul class="submenu collapse">
                            <li><a class="nav-link" href="{{URL::to('/agent-order').'/'.Auth::guard('admin')->user()->id }}" target="_blank"><i class="fal fa-cart-plus"></i>Add New Order </a></li>
                            <li><a class="nav-link" href="#"><i class="fal fa-globe"></i> Check Products </a>
                            </li>
                            <li><a href="#" class="nav-link add-product-btn"><i class="fal fa-plus"></i>Add
                                    Product</a></li>
                            <li><a class="nav-link" href="#"><i class="fal fa-cart-plus"></i>Manage User
                                    Targets </a></li>
                        </ul>
                    </li>
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="#"> User Roles <i class="fas fa-sort-down"></i></a>
                        <ul class="submenu collapse">
                            <li><a class="nav-link" href="#"><i class="fal fa-cart-plus"></i>Product Options
                                </a></li>
                            <li><a class="nav-link" href="#"><i class="fal fa-globe"></i> Product Api settings
                                </a></li>
                        </ul>
                    </li>

                    @php
                    $dashboard = App\Models\AssignedMenu::where('menu_id',319)->where('admin_id',Auth::guard('admin')->id())->first();
                    @endphp
                    @if($dashboard)
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>
                    @endif

                    @php
                    $sort = App\Models\AssignedMenu::where('menu_id',257)->where('admin_id',Auth::guard('admin')->id())->first();
                    @endphp
                    @if($sort)
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.sort') }}">
                            Sorting
                        </a>
                    </li>
                    @endif

                    @php
                    $order = App\Models\AssignedMenu::where('menu_id',253)->where('admin_id',Auth::guard('admin')->id())->first();

                    $abandoned_order = App\Models\AssignedMenu::where('menu_id',258)->where('admin_id',Auth::guard('admin')->id())->first();


                    $locked_order = App\Models\AssignedMenu::where('menu_id',259)->where('admin_id',Auth::guard('admin')->id())->first();


                    $pending_dir_order = App\Models\AssignedMenu::where('menu_id',260)->where('admin_id',Auth::guard('admin')->id())->first();
                    @endphp


                    @if($order)

                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.order') }}">Orders
                        </a></li>
                    @endif

                    @if($abandoned_order)
                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.order.abandoned') }}">Abandoned
                            Orders
                        </a></li>
                    @endif

                    @if($locked_order)
                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.order.locked') }}">Locked
                            Orders
                        </a></li>
                    @endif

                    @if($pending_dir_order)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.order.pending-direct-debit') }}">Pending Direct Debit
                        </a></li>
                    @endif



                    @php

                    $broadband = App\Models\AssignedMenu::where('menu_id',281)->where('admin_id',Auth::guard('admin')->id())->first();

                    $broadband_features = App\Models\AssignedMenu::where('menu_id',282)->where('admin_id',Auth::guard('admin')->id())->first();

                    $broadband_categories = App\Models\AssignedMenu::where('menu_id',283)->where('admin_id',Auth::guard('admin')->id())->first();

                    $broadband_speed = App\Models\AssignedMenu::where('menu_id',284)->where('admin_id',Auth::guard('admin')->id())->first();

                    $broadband_product_price_types = App\Models\AssignedMenu::where('menu_id',285)->where('admin_id',Auth::guard('admin')->id())->first();

                    $broadband_spl_offers = App\Models\AssignedMenu::where('menu_id',286)->where('admin_id',Auth::guard('admin')->id())->first();

                    $broadband_includes = App\Models\AssignedMenu::where('menu_id',287)->where('admin_id',Auth::guard('admin')->id())->first();

                    $broadband_data = App\Models\AssignedMenu::where('menu_id',288)->where('admin_id',Auth::guard('admin')->id())->first();

                    $broadband_minutes = App\Models\AssignedMenu::where('menu_id',289)->where('admin_id',Auth::guard('admin')->id())->first();

                    $broadband_contract_length = App\Models\AssignedMenu::where('menu_id',290)->where('admin_id',Auth::guard('admin')->id())->first();


                    @endphp


                    @if($broadband)
                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.services.index') }}">Broadband</a></li>
                    @endif
                    @if($broadband_features)
                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.features.index') }}">Broadband Features</a></li>
                    @endif
                    @if($broadband_categories)
                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.categories.index') }}">Broadband Categories</a></li>
                    @endif
                    @if($broadband_speed)
                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.packages.index') }}">Broadband Speed</a></li>
                    @endif
                    @if($broadband_product_price_types)
                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.productpricetypes.index') }}">Broadband Product Price Types</a></li>
                    @endif
                    @if($broadband_spl_offers)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.broadband-offers.index') }}">Broadband Special Offers</a>
                    </li>
                    @endif
                    @if($broadband_includes)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.includes.index') }}">Broadband Includes</a>
                    </li>
                    @endif
                    @if($broadband_data)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.broadband-datas.index') }}">Broadband Data</a>
                    </li>
                    @endif
                    @if($broadband_minutes)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.broadband-minutes.index') }}">Broadband Minutes</a>
                    </li>
                    @endif
                    @if($broadband_contract_length)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.broadband-contract-lengths.index') }}">Broadband Contract Lengths</a>
                    </li>
                    @endif



                    @php

                    $landline = App\Models\AssignedMenu::where('menu_id',291)->where('admin_id',Auth::guard('admin')->id())->first();

                    $landline_features = App\Models\AssignedMenu::where('menu_id',292)->where('admin_id',Auth::guard('admin')->id())->first();

                    $landline_packages = App\Models\AssignedMenu::where('menu_id',293)->where('admin_id',Auth::guard('admin')->id())->first();

                    $landline_spl_offr = App\Models\AssignedMenu::where('menu_id',294)->where('admin_id',Auth::guard('admin')->id())->first();

                    $landline_contr_length = App\Models\AssignedMenu::where('menu_id',295)->where('admin_id',Auth::guard('admin')->id())->first();

                    $landline_category = App\Models\AssignedMenu::where('menu_id',296)->where('admin_id',Auth::guard('admin')->id())->first();

                    @endphp

                    @if($landline)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.landline-products.index') }}">Landlines</a>
                    </li>
                    @endif

                    @if($landline_features)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.landline-features.index') }}">Landline Features</a>
                    </li>
                    @endif


                    @if($landline_packages)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.landline-packages.index') }}">Landline Packages</a>
                    </li>
                    @endif

                    @if($landline_spl_offr)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.landline-offers.index') }}">Landline Special Offers</a>
                    </li>
                    @endif

                    @if($landline_contr_length)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.landline-contract-lengths.index') }}">Landline Contract Lengths</a>
                    </li>
                    @endif

                    @if($landline_category)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.landline-category.index') }}">Landline Category</a>
                    </li>
                    @endif


                    @php

                    $mobile = App\Models\AssignedMenu::where('menu_id',297)->where('admin_id',Auth::guard('admin')->id())->first();

                    $mobile_features = App\Models\AssignedMenu::where('menu_id',298)->where('admin_id',Auth::guard('admin')->id())->first();

                    $mobile_special_offer = App\Models\AssignedMenu::where('menu_id',299)->where('admin_id',Auth::guard('admin')->id())->first();

                    $mobile_service_provider = App\Models\AssignedMenu::where('menu_id',300)->where('admin_id',Auth::guard('admin')->id())->first();

                    $mobile_subscription_month = App\Models\AssignedMenu::where('menu_id',301)->where('admin_id',Auth::guard('admin')->id())->first();

                    $mobile_pkg = App\Models\AssignedMenu::where('menu_id',302)->where('admin_id',Auth::guard('admin')->id())->first();

                    $mobile_data = App\Models\AssignedMenu::where('menu_id',303)->where('admin_id',Auth::guard('admin')->id())->first();

                    $mobile_minutes = App\Models\AssignedMenu::where('menu_id',304)->where('admin_id',Auth::guard('admin')->id())->first();

                    $mobile_contract_length = App\Models\AssignedMenu::where('menu_id',305)->where('admin_id',Auth::guard('admin')->id())->first();

                    $mobile_category = App\Models\AssignedMenu::where('menu_id',306)->where('admin_id',Auth::guard('admin')->id())->first();



                    @endphp

                    @if($mobile)

                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.mobiles.index') }}">Mobiles</a>
                    </li>
                    @endif

                    @if($mobile_features)


                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.mobile-features.index') }}">Mobile Features</a>
                    </li>

                    @endif

                    @if($mobile_special_offer)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.mobile-offers.index') }}">Mobile Special Offers</a>
                    </li>
                    @endif

                    @if($mobile_service_provider)

                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.service-providers.index') }}">Mobile Service Provider</a>
                    </li>
                    @endif

                    @if($mobile_subscription_month)

                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.subscription-months.index') }}">Mobile Subscription Month</a>
                    </li>

                    @endif

                    @if($mobile_pkg)

                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.mobile-packages.index') }}">Mobile Packages</a>
                    </li>

                    @endif


                    @if($mobile_data)


                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.mobile-datas.index') }}">Mobile Data</a>
                    </li>

                    @endif

                    @if($mobile_minutes)

                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.mobile-minutes.index') }}">Mobile Minutes</a>
                    </li>

                    @endif

                    @if($mobile_contract_length)

                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.mobile-contract-lengths.index') }}">Mobile Contract Lengths</a>
                    </li>
                    @endif

                    @if($mobile_category)

                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.mobile-category.index') }}">Mobile Category</a>
                    </li>

                    @endif


                    @php

                    $top_deal = App\Models\AssignedMenu::where('menu_id',307)->where('admin_id',Auth::guard('admin')->id())->first();

                    $top_deal_features = App\Models\AssignedMenu::where('menu_id',308)->where('admin_id',Auth::guard('admin')->id())->first();

                    $top_deal_packages = App\Models\AssignedMenu::where('menu_id',309)->where('admin_id',Auth::guard('admin')->id())->first();

                    $top_deal_spl_offrs = App\Models\AssignedMenu::where('menu_id',310)->where('admin_id',Auth::guard('admin')->id())->first();

                    $top_deal_contract_length = App\Models\AssignedMenu::where('menu_id',311)->where('admin_id',Auth::guard('admin')->id())->first();

                    @endphp


                    @if($top_deal)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.topdeal-products.index') }}">Top Deals</a>
                    </li>
                    @endif

                    @if($top_deal_features)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.topdeal-features.index') }}">Top Deals Features</a>
                    </li>
                    @endif

                    @if($top_deal_packages)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.topdeal-packages.index') }}">Top Deals Packages</a>
                    </li>
                    @endif

                    @if($top_deal_spl_offrs)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.topdeal-offers.index') }}">Top Deals Special Offers</a>
                    </li>
                    @endif

                    @if($top_deal_contract_length)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.topdeal-contract-lengths.index') }}">Top Deals Contract Lengths</a>
                    </li>
                    @endif


                    @php

                    $tv = App\Models\AssignedMenu::where('menu_id',312)->where('admin_id',Auth::guard('admin')->id())->first();

                    $tv_contract = App\Models\AssignedMenu::where('menu_id',313)->where('admin_id',Auth::guard('admin')->id())->first();


                    @endphp

                    @if($tv)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.tv-products.index') }}">Tv</a>
                    </li>
                    @endif
                    @if($tv_contract)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.tv-contract-lengths.index') }}">Tv Contract Lengths</a></li>
                    @endif

                    @php
                    $shop_product = App\Models\AssignedMenu::where('menu_id',250)->where('admin_id',Auth::guard('admin')->id())->first();

                    $shop_category = App\Models\AssignedMenu::where('menu_id',314)->where('admin_id',Auth::guard('admin')->id())->first();

                    $shop_company = App\Models\AssignedMenu::where('menu_id',315)->where('admin_id',Auth::guard('admin')->id())->first();

                    $wifi_use = App\Models\AssignedMenu::where('menu_id',316)->where('admin_id',Auth::guard('admin')->id())->first();

                    $wifi_band = App\Models\AssignedMenu::where('menu_id',317)->where('admin_id',Auth::guard('admin')->id())->first();

                    $ethernet_speed = App\Models\AssignedMenu::where('menu_id',318)->where('admin_id',Auth::guard('admin')->id())->first();


                    @endphp

                    @if($shop_product)

                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.products.index') }}">Shop
                            Products</a></li>
                    @endif


                    @if($shop_category)

                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.shop-categories.index') }}">Shop Categories</a></li>

                    @endif

                    @if($shop_company)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.shop-companies.index') }}">Shop Companies</a></li>

                    @endif

                    @if($wifi_use)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.wifiuse.index') }}">Wifi
                            Use</a></li>

                    @endif

                    @if($wifi_band)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.wifiband.index') }}">Wifi
                            Band</a></li>
                    @endif

                    @if($ethernet_speed)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.ethernet-speed.index') }}">Ethernet Speed</a></li>

                    @endif





                    @php
                    $extra= App\Models\AssignedMenu::where('menu_id',249)->where('admin_id',Auth::guard('admin')->id())->first();

                    $add_extra = App\Models\AssignedMenu::where('menu_id',261)->where('admin_id',Auth::guard('admin')->id())->first();

                    $add_extra_info = App\Models\AssignedMenu::where('menu_id',262)->where('admin_id',Auth::guard('admin')->id())->first();
                    @endphp

                    @if($add_extra)
                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.additional-extras.index') }}">Additional Extras</a></li>
                    @endif

                    @if($add_extra_info)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.additional-extras-info.index') }}">Additional Extras info</a></li>
                    @endif

                    @php
                    $discount = App\Models\AssignedMenu::where('menu_id',248)->where('admin_id',Auth::guard('admin')->id())->first();
                    @endphp

                    @if($discount)

                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.discounts.index') }}">
                            Discount
                        </a>
                    </li>
                    @endif

                    @php
                    $affiliate = App\Models\AssignedMenu::where('menu_id',255)->where('admin_id',Auth::guard('admin')->id())->first();
                    @endphp

                    @if($affiliate)

                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.affiliates.index') }}">
                            Affiliate
                        </a>
                    </li>
                    @endif

                    @php
                    $affiliate = App\Models\AssignedMenu::where('menu_id',255)->where('admin_id',Auth::guard('admin')->id())->first();
                    @endphp

                    @if($affiliate)

                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.cmspages.index') }}">CMS Settings</a>

                    </li>

                    @endif


                    @php
                    $customers = App\Models\AssignedMenu::where('menu_id',246)->where('admin_id',Auth::guard('admin')->id())->first();
                    @endphp

                    @if($customers)

                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.customers.index') }}">
                            Customers
                        </a>
                    </li>
                    @endif

                    @php
                    $support = App\Models\AssignedMenu::where('menu_id',241)->where('admin_id',Auth::guard('admin')->id())->first();
                    $tkt_srvc_type = App\Models\AssignedMenu::where('menu_id',263)->where('admin_id',Auth::guard('admin')->id())->first();
                    $tkt_priority = App\Models\AssignedMenu::where('menu_id',264)->where('admin_id',Auth::guard('admin')->id())->first();
                    $tkt_status = App\Models\AssignedMenu::where('menu_id',265)->where('admin_id',Auth::guard('admin')->id())->first();
                    @endphp
                    @if($support)
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.tickets.index') }}">
                            Tickets
                        </a>
                    </li>
                    @endif

                    @if($tkt_srvc_type)
                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.ticketservicetype.index') }}">Tickets Service Type</a>
                    </li>
                    @endif

                    @if($tkt_priority)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.ticketpriority.index') }}">Tickets Priority</a>
                    </li>
                    @endif

                    @if($tkt_status)
                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.ticketstatus.index') }}">Tickets Status</a>
                    </li>
                    @endif


                    @php
                    $task = App\Models\AssignedMenu::where('menu_id',242)->where('admin_id',Auth::guard('admin')->id())->first();

                    $task_service_type = App\Models\AssignedMenu::where('menu_id',266)->where('admin_id',Auth::guard('admin')->id())->first();

                    $task_priority = App\Models\AssignedMenu::where('menu_id',267)->where('admin_id',Auth::guard('admin')->id())->first();

                    $task_sts = App\Models\AssignedMenu::where('menu_id',268)->where('admin_id',Auth::guard('admin')->id())->first();
                    @endphp



                    @if($task)

                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.task.index') }}">
                            Task
                        </a>
                    </li>
                    @endif

                    @if($task_service_type)
                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.taskservicetype.index') }}">Task Service Type</a>
                    </li>
                    @endif
                    @if($task_priority)
                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.taskpriority.index') }}">Task Priority</a>
                    </li>
                    @endif
                    @if($task_sts)
                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.taskstatus.index') }}">Task Status</a>
                    </li>
                    @endif

                    @php
                    $help_support = App\Models\AssignedMenu::where('menu_id',252)->where('admin_id',Auth::guard('admin')->id())->first();
                    $help_support_cat = App\Models\AssignedMenu::where('menu_id',269)->where('admin_id',Auth::guard('admin')->id())->first();
                    @endphp



                    @if($help_support)

                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.blog.index') }}">Help & Support</a>
                    </li>
                    @endif
                    @if($help_support_cat)
                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.blog-categories.index') }}"></i>Help & Support Category</a>
                    </li>

                    @endif

                    @php
                    $faq = App\Models\AssignedMenu::where('menu_id',254)->where('admin_id',Auth::guard('admin')->id())->first();

                    $faq_cat = App\Models\AssignedMenu::where('menu_id',270)->where('admin_id',Auth::guard('admin')->id())->first();
                    @endphp

                    @if($faq)


                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.faq.index') }}">FAQs</a>
                    </li>

                    @endif

                    @if($faq_cat)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.faq-categories.index') }}">FAQs Category</a>
                    </li>


                    @endif

                    @php
                    $role_list = App\Models\AssignedMenu::where('menu_id',6)->where('admin_id',Auth::guard('admin')->id())->first();

                    $permission_list = App\Models\AssignedMenu::where('menu_id',10)->where('admin_id',Auth::guard('admin')->id())->first();

                    $user_list = App\Models\AssignedMenu::where('menu_id',271)->where('admin_id',Auth::guard('admin')->id())->first();
                    @endphp

                    @if($user_list)


                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.users.index') }}">Users</a>
                    </li>
                    @endif
                    @if($role_list)
                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.roles.index') }}">Roles</a>
                    </li>
                    @endif

                    @if($permission_list)

                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.permissions.index') }}">Permissions</a>
                    </li>
                    @endif




                    @php
                    $agent = App\Models\AssignedMenu::where('menu_id',256)->where('admin_id',Auth::guard('admin')->id())->first();
                    @endphp

                    @if($agent)

                    <li class="nav-item has-submenu"><a class="nav-link" href="{{ route('admin.agents') }}">Agent</a>
                    </li>
                    @endif


                    @php
                    $mailchimp = App\Models\AssignedMenu::where('menu_id',243)->where('admin_id',Auth::guard('admin')->id())->first();

                    $mailchimp_Sub = App\Models\AssignedMenu::where('menu_id',272)->where('admin_id',Auth::guard('admin')->id())->first();

                    $mailchimp_contact = App\Models\AssignedMenu::where('menu_id',273)->where('admin_id',Auth::guard('admin')->id())->first();

                    @endphp

                    @if($mailchimp)

                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.create-campaign') }}">Craete MailChimp</a>
                    </li>
                    @endif

                    @if($mailchimp_Sub)
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.subscribers-list') }}">MailChimp Subscribers
                            List</a>
                    </li>
                    @endif
                    @if($mailchimp_contact)
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.contacts-list') }}">MailChimp Contacts
                            List</a>
                    </li>
                    @endif



                    @php
                    $report = App\Models\AssignedMenu::where('menu_id',244)->where('admin_id',Auth::guard('admin')->id())->first();
                    @endphp

                    @if($report)
                    <li class="nav-item has-submenu"> <a class="nav-link" href="{{ route('admin.order-report') }}">Order Report</a>
                    </li>
                    @endif

                    @php
                    $setting = App\Models\AssignedMenu::where('menu_id',245)->where('admin_id',Auth::guard('admin')->id())->first();

                    $vat_setting = App\Models\AssignedMenu::where('menu_id',274)->where('admin_id',Auth::guard('admin')->id())->first();


                    $web_setting = App\Models\AssignedMenu::where('menu_id',275)->where('admin_id',Auth::guard('admin')->id())->first();


                    $mailchimp_setting = App\Models\AssignedMenu::where('menu_id',276)->where('admin_id',Auth::guard('admin')->id())->first();

                    $newletter_temp = App\Models\AssignedMenu::where('menu_id',277)->where('admin_id',Auth::guard('admin')->id())->first();

                    $sagepay_setting = App\Models\AssignedMenu::where('menu_id',278)->where('admin_id',Auth::guard('admin')->id())->first();

                    $talktalk_setting = App\Models\AssignedMenu::where('menu_id',279)->where('admin_id',Auth::guard('admin')->id())->first();

                    $akj_setting = App\Models\AssignedMenu::where('menu_id',280)->where('admin_id',Auth::guard('admin')->id())->first();
                    @endphp

                    @if($setting)

                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.status.index') }}">Available Status</a>
                    </li>
                    @endif

                    @if($vat_setting)
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.vat') }}">VAT Setting</a>
                    </li>
                    @endif

                    @if($web_setting)
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.website-setting') }}">Website
                            Setting</a>
                    </li>
                    @endif

                    @if($mailchimp_setting)
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.mailchimp-setting') }}">MailChimp
                            Setting</a>
                    </li>
                    @endif

                    @if($newletter_temp)
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.newsletter-template') }}">Newsletter Template</a>
                    </li>
                    @endif

                    @if($sagepay_setting)
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.sagepay-setting') }}">Sagepay
                            Setting</a>
                    </li>
                    @endif

                    @if($talktalk_setting)
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.talktalk-setting') }}">Talktalk
                            Setting</a>
                    </li>
                    @endif

                    @if($akj_setting)
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="{{ route('admin.akj-setting') }}">AKJ Setting</a>
                    </li>
                    @endif
                </ul>
            </nav>
            <!--end navigation-->
        </div>
    </div>
    <!--end right menu-->
    <!--============================= Scripts =============================-->
    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/plugins/chartjs/js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/chartjs/js/Chart.extension.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="https://codervent.com/rocker/demo/vertical/assets/plugins/validation/jquery.validate.min.js"></script>
    <script src="https://codervent.com/rocker/demo/vertical/assets/plugins/validation/validation-script.js"></script>

    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- CKeditor Plugins-->
    <script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/plugins/ckeditor/plugins/colorbutton/plugin.js') }}"></script>
    <script src="{{ asset('assets/plugins/ckeditor/plugins/button/plugin.js') }}"></script>
    <script src="{{ asset('assets/plugins/ckeditor/plugins/panelbutton/plugin.js') }}"></script>
    <script src="{{ asset('assets/plugins/ckeditor/plugins/imageuploader/plugin.js') }}"></script>
    <script src="{{ asset('assets/plugins/ckfinder/ckfinder.js') }}"></script>

    <!--app JS-->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.sidebar .nav-link').forEach(function(element) {

                element.addEventListener('click', function(e) {

                    let nextEl = element.nextElementSibling;
                    let parentEl = element.parentElement;

                    if (nextEl) {
                        e.preventDefault();
                        let mycollapse = new bootstrap.Collapse(nextEl);

                        if (nextEl.classList.contains('show')) {
                            mycollapse.hide();
                        } else {
                            mycollapse.show();
                            // find other submenus with class=show
                            var opened_submenu = parentEl.parentElement.querySelector(
                                '.submenu.show');
                            // if it exists, then close all of them
                            if (opened_submenu) {
                                new bootstrap.Collapse(opened_submenu);
                            }
                        }
                    }
                }); // addEventListener
            }) // forEach
        });
    </script>

    <script type="text/javascript">
        $("#themeForm").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
                $(element).closest('.form-group').addClass("has-success");
            },

            rules: {
                name: "required",
            },
            messages: {
                name: "Template Name is missing",
            },
            submitHandler: function(form) {
                var themeStyle = $(".form-check-input:checked").val();
                var headerColor = $(".headercolor:checked").val();
                var sidebarColor = $(".sidebarcolor:checked").val();
                $.ajax({
                    url: form.action,
                    method: "post",
                    data: $(form).serialize(),
                    success: function(data) {
                        //success
                        $("#themeForm").trigger("reset");
                        if (data.status) {
                            location.href = data.redirect_location;
                        } else {
                            toastr.error(data.message.message, '');
                        }
                    },
                    error: function(e) {
                        toastr.error('Something went wrong . Please try again later!!', '');
                    }
                });
                return false;
            }
        });
    </script>

    @stack('scripts')
    {!! Toastr::message() !!}
</body>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
    $(function() {
        $("#sortable").sortable();
    });
</script>
<script>
    $(function() {
        $("#sortable_fttp").sortable();
        $("#sortable_broadband").sortable();
        $("#sortable_landline").sortable();
        $("#sortable_mobile").sortable();
        $("#sortable_tv").sortable();
        $("#sortable_topdeal").sortable();
        $("#sortable_router").sortable();
        $("#sortable_order_process").sortable();
    });
</script>
<script>
    $("#sort_btn").click(function() {
        var obj = {};
        $.map($("#sortable").find("li"), function(el) {
            var id = el.id;
            var sorting = $(el).index();
            obj[id] = sorting;

        });
        //console.log(obj);
        $.ajax({
            url: '{{ route("admin.setOrder")}}',
            type: "GET",
            data: {
                obj: obj,
            },
            success: function(data) {
                toastr.success("Sort Order Changed Succesfully", '');
            },
            error: function(data) {
                toastr.error("There was some error,please try again later", '');
            },
        });
    });
</script>
<script>
    $("#sort_btn_fttp").click(function() {
        var obj = {};
        $.map($("#sortable_fttp").find("li"), function(el) {
            var id = el.id;
            var sorting = $(el).index();
            obj[id] = sorting;

        });
        console.log(obj);
        $.ajax({
            url: "{{ route('admin.setOrder_fttp') }}",
            type: "GET",
            data: {
                obj: obj,
            },
            success: function(data) {
                toastr.success("Sort Order Changed Succesfully", '');
            },
            error: function(data) {
                toastr.error("There was some error,please try again later", '');
            },
        });
    });
</script>

<script>
    $("#sort_btn_broadband").click(function() {
        
        var obj = {};
        $.map($("#sortable_broadband").find("li"), function(el) {
            var id = el.id;
            var sorting = $(el).index();
            obj[id] = sorting;

        });
        console.log(obj);
        $.ajax({
            url: "{{ route('admin.setOrderBroadband') }}",
            type: "GET",
            data: {
                obj: obj,
            },
            success: function(data) {
                toastr.success("Sort Order Changed Succesfully", '');
            },
            error: function(data) {
                toastr.error("There was some error,please try again later", '');
            },
        });
    });
</script>

<script>
    $("#sort_btn_landline").click(function() {
        
        var obj = {};
        $.map($("#sortable_landline").find("li"), function(el) {
            var id = el.id;
            var sorting = $(el).index();
            obj[id] = sorting;

        });
        console.log(obj);
        $.ajax({
            url: "{{ route('admin.setOrderLandline') }}",
            type: "GET",
            data: {
                obj: obj,
            },
            success: function(data) {
                toastr.success("Sort Order Changed Succesfully", '');
            },
            error: function(data) {
                toastr.error("There was some error,please try again later", '');
            },
        });
    });
</script>

<script>
    $("#sort_btn_mobile").click(function() {
        
        var obj = {};
        $.map($("#sortable_mobile").find("li"), function(el) {
            var id = el.id;
            var sorting = $(el).index();
            obj[id] = sorting;

        });
        console.log(obj);
        $.ajax({
            url: "{{ route('admin.setOrderMobile') }}",
            type: "GET",
            data: {
                obj: obj,
            },
            success: function(data) {
                toastr.success("Sort Order Changed Succesfully", '');
            },
            error: function(data) {
                toastr.error("There was some error,please try again later", '');
            },
        });
    });
</script>

<script>
    $("#sort_btn_tv").click(function() {
       
        var obj = {};
        $.map($("#sortable_tv").find("li"), function(el) {
            var id = el.id;
            var sorting = $(el).index();
            obj[id] = sorting;

        });
        console.log(obj);
        $.ajax({
            url: "{{ route('admin.setOrderTv') }}",
            type: "GET",
            data: {
                obj: obj,
            },
            success: function(data) {
                toastr.success("Sort Order Changed Succesfully", '');
            },
            error: function(data) {
                toastr.error("There was some error,please try again later", '');
            },
        });
    });
</script>

<script>
    $("#sort_btn_topdeal").click(function() {
        
        var obj = {};
        $.map($("#sortable_topdeal").find("li"), function(el) {
            var id = el.id;
            var sorting = $(el).index();
            obj[id] = sorting;

        });
        console.log(obj);
        $.ajax({
            url: "{{ route('admin.setOrderTopdeal') }}",
            type: "GET",
            data: {
                obj: obj,
            },
            success: function(data) {
                toastr.success("Sort Order Changed Succesfully", '');
            },
            error: function(data) {
                toastr.error("There was some error,please try again later", '');
            },
        });
    });
</script>

<script>
    $("#sort_btn_router").click(function() {
        
        var obj = {};
        $.map($("#sortable_router").find("li"), function(el) {
            var id = el.id;
            var sorting = $(el).index();
            obj[id] = sorting;

        });
        console.log(obj);
        $.ajax({
            url: "{{ route('admin.setOrderRouter') }}",
            type: "GET",
            data: {
                obj: obj,
            },
            success: function(data) {
                toastr.success("Sort Order Changed Succesfully", '');
            },
            error: function(data) {
                toastr.error("There was some error,please try again later", '');
            },
        });
    });
</script>

<script>
    $("#sort_btn_order_process").click(function() {
        
        var obj = {};
        $.map($("#sortable_order_process").find("li"), function(el) {
            var id = el.id;
            var sorting = $(el).index();
            obj[id] = sorting;

        });
        console.log(obj);
        $.ajax({
            url: "{{ route('admin.setOrderProcess') }}",
            type: "GET",
            data: {
                obj: obj,
            },
            success: function(data) {
                toastr.success("Sort Order Changed Succesfully", '');
            },
            error: function(data) {
                toastr.error("There was some error,please try again later", '');
            },
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        $("body").on("click", ".chk-edit", function() {
            var current_object = $(this);
            var id = current_object.attr('data-id');
            var own_id = current_object.attr('data-own-id');
            var admin_id = current_object.attr('data-admin-id');
            var admin_name = current_object.attr('data-admin-name');
           
            swal({
                title: admin_name+" Already working in this order.",
                text: "",
                type: "warning",
                showCancelButton: true,
                dangerMode: false,
                cancelButtonClass: '#DD6B55',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Take Over!',
            }, function(result) {
                if (result) {
                    $.ajax({
                        url: "{{ route('admin.orders.chk.edit') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function(data) {
                            location.href = data.redirect_location;
                        },
                        error: function(data) {
                            toastr.error("There was some error,please try again later", '');
                        },
                    });
                }
            });
        });
    });
</script>

</html>
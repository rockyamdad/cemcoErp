<!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.0
Version: 1.5.3
Author: KeenThemes
Website: http://www.keenthemes.com/
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
-->
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>CEMCO ERP</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <meta name="MobileOptimized" content="320">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->

    <link href="{{URL::asset('assets/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
    <link rel="stylesheet" type="text/css"
          href="{{ URL::asset('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css') }}"/>

    <link href="{{ URL::asset('assets/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar/fullcalendar.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet"
          type="text/css"/>

    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/data-tables/DT_bootstrap.css') }}"/>
    <!-- END PAGE LEVEL PLUGIN STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link href="{{ URL::asset('assets/css/style-metronic.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/css/style-responsive.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/css/plugins.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/css/pages/tasks.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/css/themes/default.css') }}" rel="stylesheet" type="text/css" id="style_color"/>
    <link href="{{ URL::asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>
    @yield('styles')
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">

<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse navbar-fixed-top">
    <!-- BEGIN TOP NAVIGATION BAR -->
    <div class="header-inner">
        <!-- BEGIN LOGO -->
        <a class="navbar-brand" href="index.html">
            <img src="{{ URL::asset('assets/img/logo.png') }}" alt="CEMCO" class="img-responsive"/>
        </a>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <img src="{{ URL::asset('assets/img/menu-toggler.png') }}" alt=""/>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <ul class="nav navbar-nav pull-right">
            <!-- BEGIN USER LOGIN DROPDOWN -->
            <li class="dropdown user">
                <a href="index.html#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                   data-close-others="true">
                    <img alt="" src="{{URL::asset('assets/css/custom.css')}}"/>
                    <span class="username">{{Session::get('user_name')}}</span>
                    <i class="fa fa-angle-down"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="{{ URL::to('logout') }}"><i class="fa fa-key"></i> Log Out</a>
                    </li>
                </ul>
            </li>
            <!-- END USER LOGIN DROPDOWN -->
        </ul>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<div class="clearfix"></div>
<!-- BEGIN CONTAINER -->
<div class="page-container">

    <div id="ajax" class="modal fade" tabindex="-1" >


        </div>
    <div id="ajax2" class="modal fade" tabindex="-1" >


    </div>
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <ul class="page-sidebar-menu">
            <li>
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler hidden-phone"></div>
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            </li>
            <li>
                <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                <form class="sidebar-search" action="extra_search.html" method="POST">
                    <div class="form-container">
                        <div class="input-box">
                            <a href="javascript:;" class="remove"></a>
                            <input type="text" placeholder="Search..."/>
                            <input type="button" class="submit" value=" "/>
                        </div>
                    </div>
                </form>
                <!-- END RESPONSIVE QUICK SEARCH FORM -->
            </li>
            <li class="start @if (Request::is('dashboard/'))active @endif">
                <a href="{{ URL::to('dashboard/') }}">
                    <i class="fa fa-home"></i>
                    <span class="title">Dashboard</span>
                    @if (Request::is('dashboard/'))<span class="selected"></span>@endif
                </a>
            </li>

            <li class=" @if (Request::is('users/')) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-group"></i>
                    <span class="title">Users</span>
                    @if (Request::is('users/'))<span class="selected"></span>@endif
                    <span class="arrow @if (Request::is('users/'))open @endif"></span>
                </a>

                <ul class="sub-menu">
                    <li
                    @if (Request::is('add/')) class="active" @endif>
                    <a href="{{ URL::to('add/') }}">Add User </a>
            </li>
            <li
            @if (Request::is('list/')) class="active" @endif>
            <a href="{{ URL::to('list/') }}">User List </a>
            </li>
        </ul>
        </li>

        <li class="@if (Request::is('branches/branchList'))active @endif">
            <a href="javascript:;">
                <i class="fa fa-puzzle-piece"></i>
                <span class="title">Branch</span>
                @if (Request::is('branches/*'))<span class="selected"></span>@endif
                <span class="arrow @if (Request::is('branches/*'))open @endif"></span>
            </a>

            <ul class="sub-menu">
                <li
                @if (Request::is('branchAdd/'))class="active"@endif>
                <a href="{{ URL::to('branchAdd') }}">Add Branch </a>
        </li>
        <li
        @if (Request::is('branchList/'))class="active"@endif>
        <a href="{{ URL::to('branchList') }}">Branch List </a>
        </li>
        </ul>
        </li>

        <li class="@if (Request::is('productCategories/'))active @endif">
            <a href="javascript:;">
                <i class="fa fa-barcode"></i>
                <span class="title">Products</span>
                @if (Request::is('productCategories/'))<span class="selected"></span>@endif
                <span class="arrow @if (Request::is('productCategories/'))open @endif"></span>
            </a>

            <ul class="sub-menu">
                <li
                @if (Request::is('productCategories/index/'))class="active"@endif>
                <a href="{{ URL::to('productCategories/index/') }}"><i class="fa fa-sitemap"></i>Product Category
                    Section </a>
        </li>

        <li
        @if (Request::is('productsubcategories/index/'))class="active"@endif>
        <a href="{{ URL::to('productsubcategories/index/') }}"><i class="fa fa-sitemap"></i>Product SubCategory Section
        </a>
        </li>

        <li
        @if (Request::is('products/index/'))class="active"@endif>
        <a href="{{ URL::to('products/index/') }}">Product Section </a>
        </li>

        </ul>
        </li>

        <li class="@if (Request::is('imports/'))active @endif">
            <a href="javascript:;">
                <i class="fa fa-mail-reply-all"></i>
                <span class="title">Import</span>
                @if (Request::is('imports/'))<span class="selected"></span>@endif
                <span class="arrow @if (Request::is('imports/'))open @endif"></span>
            </a>

            <ul class="sub-menu">
                <li
                @if (Request::is('imports/create'))class="active"@endif>
                <a href="{{ URL::to('imports/create') }}">Add Import Info </a>
        </li>
        <li
        @if (Request::is('imports/index'))class="active"@endif>
        <a href="{{ URL::to('imports/index') }}">Import List </a>
        </li>
        </ul>
        </li>

        <li class="@if (Request::is('stocks/*'))active @endif">
            <a href="javascript:;">
                <i class="fa fa-puzzle-piece"></i>
                <span class="title">Stock</span>
                @if (Request::is('stocks/*'))<span class="selected"></span>@endif
                <span class="arrow @if (Request::is('stocks/*'))open @endif"></span>
            </a>

            <ul class="sub-menu">
                <li
                @if (Request::is('stocks/create'))class="active"@endif>
                <a href="{{ URL::to('stocks/create') }}">Add Stock </a>
                 </li>
                <li
                @if (Request::is('stocks/index'))class="active"@endif>
                <a href="{{ URL::to('stocks/index') }}">Stock List </a>
                </li>

        </ul>
        </li>

        <li class="@if (Request::is('requisitions/*'))active @endif">
            <a href="javascript:;">
                <i class="fa fa-puzzle-piece"></i>
                <span class="title">Stock Requisition</span>
                @if (Request::is('requisitions/*'))<span class="selected"></span>@endif
                <span class="arrow @if (Request::is('requisitions/*'))open @endif"></span>
            </a>

            <ul class="sub-menu">
                <li
                @if (Request::is('requisitions/create'))class="active"@endif>
                <a href="{{ URL::to('requisitions/create') }}">Add Stock Requisition </a>
        </li>
        <li
        @if (Request::is('requisitions/index'))class="active"@endif>
        <a href="{{ URL::to('requisitions/index') }}">StockRequisition List </a>
        </li>

        </ul>
        </li>
            <li class="@if (Request::is('partilizers/*'))active @endif">
                <a href="javascript:;">
                    <i class="fa fa-puzzle-piece"></i>
                    <span class="title">PartiLizer Section</span>
                    @if (Request::is('partilizers/*'))<span class="selected"></span>@endif
                    <span class="arrow @if (Request::is('partilizers/*'))open @endif"></span>
                </a>

                <ul class="sub-menu">
                    <li
                            @if (Request::is('accountcategory/index'))class="active"@endif>
                        <a href="{{ URL::to('accountcategory/index') }}"> Account category</a>
                    </li>
                    <li
                            @if (Request::is('accountnames/index'))class="active"@endif>
                        <a href="{{ URL::to('accountnames/index') }}"> Account Names </a>
                    </li>
                    <li
                            @if (Request::is('purchases/index'))class="active"@endif>
                        <a href="{{ URL::to('purchases/index') }}"> Purchase Invoices </a>
                    </li>

                </ul>
            </li>


        <li class="@if (Request::is('parties/'))active @endif">
            <a href="javascript:;">
                <i class="fa fa-barcode"></i>
                <span class="title">Settings</span>
                @if (Request::is('parties/'))<span class="selected"></span>@endif
                <span class="arrow @if (Request::is('parties/'))open @endif"></span>
            </a>

            <ul class="sub-menu">
                <li
                @if (Request::is('parties/index/'))class="active"@endif>
                <a href="{{ URL::to('parties/index/') }}"><i class="fa fa-sitemap"></i>Party Section                    Section </a>
        </li>


        </ul>
        </li>


        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN PAGE -->
    <!-- BEGIN PAGE -->
    <div class="page-content">

        @yield('content')
    </div>
    <!-- END PAGE -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="footer">
    <div class="footer-inner">
        2015 &copy; CEMCO.
    </div>
    <div class="footer-tools">
			<span class="go-top">
			<i class="fa fa-angle-up"></i>
			</span>
    </div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="{{ URL::asset('assets/plugins/respond.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/excanvas.min.js') }}"></script>
<![endif]-->
<script src="{{ URL::asset('assets/plugins/jquery-1.10.2.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/jquery-migrate-1.2.1.min.js') }}" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/jquery.cookie.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
<script type="text/javascript"
        src="{{ URL::asset('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.js') }}"></script>

<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript"
        src="{{ URL::asset('assets/plugins/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::asset('assets/plugins/jquery-validation/dist/additional-methods.min.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/flot/jquery.flot.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/flot/jquery.flot.resize.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/jquery.pulsate.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/moment.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/gritter/js/jquery.gritter.js') }}" type="text/javascript"></script>
<!-- IMPORTANT! fullcalendar depends on jquery-ui-1.10.3.custom.min.js for drag & drop support -->
<script src="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar/fullcalendar.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/scripts/ui-modals.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/jquery.sparkline.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/DT_bootstrap.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-touchspin/bootstrap.touchspin.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-modal/js/bootstrap-modal.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') }}"
        type="text/javascript"></script>

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ URL::asset('assets/scripts/app.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/scripts/index.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/scripts/tasks.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/scripts/form-validation.js') }}" type="text/javascript"></script>
<!--<script src="{{ URL::asset('assets/scripts/table-managed.js') }}"></script>-->
<script src="{{ URL::asset('assets/scripts/form-components.js') }}"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script type="text/javascript">

    jQuery(document).ready(function () {
        App.init();
        $('#user_table').DataTable();
        $('#sub_category_table').DataTable();
        $('#product_table').DataTable();
        $('#party_table').DataTable();
        $('#imports_table').DataTable();
        Tasks.initDashboardWidget();


    });

</script>
<!-- END JAVASCRIPTS -->
<script type="text/javascript">  var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-37564768-1']);
    _gaq.push(['_setDomainName', 'keenthemes.com']);
    _gaq.push(['_setAllowLinker', true]);
    _gaq.push(['_trackPageview']);
    (function () {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();</script>
@yield('javascript');
</body>
<!-- END BODY -->
</html>
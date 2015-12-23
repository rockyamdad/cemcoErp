@extends('baseLayout')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Dashboard Section
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{URL::to('dashboard')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>

            <li><a href="{{URL::to('dashboard')}}">dashboard</a></li>
        </ul>
        @if (Session::has('flash_notice'))
            <div id="flash_notice" class="alert alert-success">{{ Session::get('flash_notice') }}</div>
        @endif
    </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat blue">
                <div class="visual">
                    <i class="fa fa-barcode"></i>
                </div>
                <div class="details">
                    <div class="number">
                       {{$totalProducts[0]->totalQuantity ? $totalProducts[0]->totalQuantity : 0}}
                    </div>
                    <div class="desc">
                       Total Products
                    </div>
                </div>
                <a class="more" href="{{URL::to('/products')}}">
                    View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat green">
                <div class="visual">
                    <i class="fa fa-plane"></i>
                </div>
                <div class="details">
                    <div class="number">{{$totalImports[0]->totalImport ? $totalImports[0]->totalImport : 0}}</div>
                    <div class="desc">Total Imports</div>
                </div>
                <a class="more" href="{{URL::to('imports/index')}}">
                    View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat purple">
                <div class="visual">

                </div>
                <div class="details">
                    <div class="number">{{$totalSales[0]->todaySale ? $totalSales[0]->todaySale : 0.00}}&nbsp;Tk</div>
                    <div class="desc">Today's Sale</div>
                </div>
                <a class="more" href="{{URL::to('sales/')}}">
                    View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat yellow">
                <div class="visual">
                </div>
                <div class="details">
                    <div class="number">
                        {{$totalPurchase[0]->todayPurchase ? $totalPurchase[0]->todayPurchase : 0.00}} &nbsp;Tk
                    </div>
                    <div class="desc">Today's Purchase</div>
                </div>
                <a class="more" href="{{URL::to('purchases/')}}">
                    View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>


</div>
@stop
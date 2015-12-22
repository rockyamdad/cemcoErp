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
</div>
@stop
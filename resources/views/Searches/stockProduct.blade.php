@extends('baseLayout')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" />

@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Stock Product Report
        </h3>

        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>

<div class="col-md-16">
    <!-- BEGIN VALIDATION STATES-->
    <div class="portlet box purple">
        <div class="portlet-title">
            <div class="caption"><i class="fa fa-reorder"></i>  Stock Product Report</div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            {!!Form::open(array('url' => 'stock-product-search-result', 'method' => 'post', 'class'=>'form-horizontal', 'id'=>'Search_stock_product_form'))!!}
            <div class="form-body">
                <div class="alert alert-danger display-hide">
                    <button data-close="alert" class="close"></button>
                    You have some form errors. Please check below.
                </div>
                <div class="alert alert-success display-hide">
                    <button data-close="alert" class="close"></button>
                    Your form validation is successful!
                </div>
                <input type="hidden" name="branch_session" id="branch_session" value="{{Session::get('user_branch')}}">
                <input type="hidden" name="role_session" id="role_session" value="{{Session::get('user_role')}}">
                @if(Session::get('user_role')=='admin')
                <div class="form-group">
                    {!!HTML::decode(Form::label('branch_id','Choose Branch<span class="required">*</span>',array('class'
                    => 'control-label col-md-3')))!!}
                    <div class="col-md-4">
                        {!!Form::select('branch_id',[null=>'Please Select Branch'] +$branchAll,'null',
                        array('class'=>'form-control ','id'=>'branch_id') )!!}
                    </div>
                </div>
                @endif
                <div class="form-group">
                    {!!HTML::decode(Form::label('stock_info_id','Choose Stocks<span class="required">*</span>',array('class' =>
                    'control-label col-md-3')))!!}
                    <div class="col-md-4">
                        {!! Form::select('stock_info_id',[null=>'Please Select Stocks'] +$allStockInfos,'null', array('class'=>'form-control','id'=>'stock_info_id'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!HTML::decode(Form::label('category_id','Choose Category',array('class' =>
                    'control-label col-md-3')))!!}
                    <div class="col-md-4">
                        {!!Form::select('category_id',[null=>'Please Select Category'] +$categoriesAll,'null', array('class'=>'form-control ','id'=>'category_id') )!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!HTML::decode(Form::label('product_id','Choose Product',array('class' =>
                    'control-label col-md-3')))!!}
                    <div class="col-md-4">
                        {!!Form::select('product_id',[null=>'Please Select Product'] +$productAll,'null', array('class'=>'form-control ','id'=>'product_id') )!!}                    </div>
                </div>
                <div class="form-group">

                        {!!HTML::decode(Form::label('from_date','From:',array('class' =>
                   'control-label col-md-3')))!!}
                        <div class="col-md-4">
                            <div class="input-append date input-icon" data-date="12-02-2012" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                <i class="fa fa-calendar"></i>
                                {!!Form::text('from_date',null,array('size'=>'16','class' =>
                      'form-control m-wrap m-ctrl-medium date-picker'))!!}
                               <span class="add-on"><i class="icon-calendar"></i></span>

                            </div>
                        </div>
                </div>
                <div class="form-group">

                    {!!HTML::decode(Form::label('to_date','To:',array('class' =>
               'control-label col-md-3')))!!}
                    <div class="col-md-4">
                        <div class="input-append date input-icon" data-date="12-02-2012" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                            <i class="fa fa-calendar"></i>
                            {!!Form::text('to_date',null,array('size'=>'16','class' =>'form-control m-wrap m-ctrl-medium date-picker'))!!}
                            <span class="add-on"><i class="icon-calendar"></i></span>

                        </div>
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="col-md-offset-3 col-md-3">
                        <button type="submit" class="btn blue btn-block margin-top-10" style="margin-left: 35px;">SEARCH <i class="m-icon-swapright m-icon-white"></i></button>
                    </div>
                </div>
                {!!Form::close()!!}
                <!-- END FORM-->
            </div>
        </div>
        <!-- END VALIDATION STATES-->
    </div>
    @stop
    @section('javascript')
    {!! HTML::script('js/search.js') !!}
    {!! HTML::script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::script('assets/plugins/select2/select2.min.js') !!}
    @stop
    </div>



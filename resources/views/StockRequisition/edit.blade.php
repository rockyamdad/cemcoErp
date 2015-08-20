@extends('baseLayout')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}"/>

@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Stock Requisition Section
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{URL::to('dashboard')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>

            <li><a href="{{URL::to('requisitions/create')}}">Make Stock Requisition</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>

<div class="col-md-16">
    <!-- BEGIN VALIDATION STATES-->
    <div class="portlet box purple">
        <div class="portlet-title">
            <div class="caption"><i class="fa fa-reorder"></i>Make Stock Requisition</div>
            <div class="actions">
                <a class="btn dark" href="{{ URL::to('requisitions/index') }}">Stock Requisition List</a>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            {!!Form::open(array('url' => '/saveStockRequisitions', 'method' => 'post', 'class'=>'form-horizontal',
            'id'=>'stock_requisition_form'))!!}
            <div class="form-body">
                <div style="float: left;width: 80%; margin-left: 20px">
                    @if (Session::has('message'))
                    <div class="alert alert-success">
                        <button data-close="alert" class="close"></button>
                        {{ Session::get('message') }}
                    </div>
                    @endif
                </div>
                <div class="alert alert-danger display-hide">
                    <button data-close="alert" class="close"></button>
                    You have some form errors. Please check below.
                </div>
                <div class="alert alert-success display-hide">
                    <button data-close="alert" class="close"></button>
                    Your form validation is successful!
                </div>
                <div class="portlet-body form" id="testt">
                    <!-- BEGIN FORM-->
                    <div class="form-body">

                        <div class="row">
                            <table class="table table-striped table-bordered table-primary table-condensed" id="requisitionTable">
                                <thead>
                                <tr>
                                    <th width="">Party Name</th>
                                    <th width="">Product Name</th>
                                    <th width="">Requisition Quantity</th>
                                    <th width="">Remarks</th>
                                    <th width="">Action</th>
                                </tr>

                                </thead>
                                <tbody>

                                </tbody>
                                @if($stockRequisition)
                                        <tr>
                                            <td>{{$stockRequisition->party_id}}</td>
                                            <td>{{$stockRequisition->product_id}}</td>
                                            <td>{{$stockRequisition->product_requisition}}</td>
                                            <td>{{$stockRequisition->remarks}}</td>
                                            <td><input type="button"  id="deleteRequisition" style="width:127px;" value="delete" class="btn red deleteRequisition" rel="{{ $stockRequisition->id }}" ></td>

                                        </tr>
                                @endif
                                <tr class="clone_">
                                    <td>
                                    <div class="form-group">
                                            <div class="col-md-11">
                                                {!!Form::select('party_id',[null=>'Please Select Party'] + $partyAll,'null', array('class'=>'form-control ','id'=>'party_id') )!!}
                                            </div>
                                        </div>

                                    </td>
                                    <td> <div class="form-group">
                                            <div class="col-md-11">
                                                {!!Form::select('product_id',[null=>'Please Select Product'] +$productAll,'null', array('class'=>'form-control ','id'=>'product_id') )!!}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="col-md-11">
                                                {!!Form::text('requisition_quantity',null,array('placeholder' => 'Requisition Quantity', 'class' =>
                                                'form-control','id'=>'requisition_quantity'))!!}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="col-md-11">
                                                {!!Form::text('remarks',null,array('placeholder' => 'Remarks', 'class' =>
                                                'form-control'))!!}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {!!Form::button('Add Requisition',array('type' => 'button','class' => 'btn blue','id' => 'saveRequisition'))!!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="form-actions fluid">
                        <div class="col-md-offset-3 col-md-9">
                            {!!Form::button('Save',array('type' => 'submit','class' => 'btn green','id' => 'save'))!!}
                            {!!Form::button('Cancel',array('type'=>'reset', 'class' => 'btn default','id' => 'cancel'))!!}
                        </div>
                    </div>

                    <!-- END FORM-->
                </div>

             <!--   <div class="form-group">
                    {!!HTML::decode(Form::label('product_type','Product Type<span class="required">*</span>',array('class' =>
                    'control-label col-md-3')))!!}
                    <div class="col-md-4">
                        {!! Form::select('product_type',[null=>'Please Select Type'] + array('Local' => 'Local', 'Foreign' =>
                        'Foreign','Finish Goods'=>'Finish Goods'),'null', array('class'=>'form-control','id'=>'product_type'))!!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Choose Product<span class="required">*</span></label>

                    <div class="col-md-4">
                        <select id="product_id" name="product_id" class="form-control">
                            <option value="">Select Product</option>
                        </select>

                    </div>
                </div>


                <div class="form-group">
                    {!!HTML::decode(Form::label('entry_type','Entry Type<span class="required">*</span>',array('class' =>
                    'control-label col-md-3')))!!}
                    <div class="col-md-4">
                        {!! Form::select('entry_type',[null=>'Please Select Type'] + array('1' => 'StockIn', '0' =>
                        'StockOut'),'null', array('class'=>'form-control','id'=>'entry_type'))!!}
                    </div>
                </div>

                <div class="form-group">
                    {!! HTML::decode(Form::label('product_quantity','Product Quantity<span class="required">*</span>',array('class'
                    => 'control-label col-md-3'))) !!}
                    <div class="col-md-4">
                        {!!Form::text('product_quantity',null,array('placeholder' => 'Product Quantity', 'class' =>
                        'form-control'))!!}
                    </div>
                </div>
                <div class="form-group import_num_section">

                </div>


                <div class="form-actions fluid">
                    <div class="col-md-offset-3 col-md-9">
                        {!!Form::button('Save',array('type' => 'submit','class' => 'btn green','id' => 'save'))!!}
                        {!!Form::button('Cancel',array('type'=>'reset', 'class' => 'btn default','id' => 'cancel'))!!}

                    </div>
                </div>-->
                {!!Form::close()!!}
                <!-- END FORM-->
            </div>
        </div>
        <!-- END VALIDATION STATES-->
    </div>
    @stop
    @section('javascript')
    {!! HTML::script('js/stockRequisition.js') !!}
    {!! HTML::script('assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js') !!}
    {!! HTML::script('assets/plugins/select2/select2.min.js') !!}

    @stop



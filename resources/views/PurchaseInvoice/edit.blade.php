@extends('baseLayout')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}"/>

@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
                Purchase Invoice  Section
            </h3>
            <ul class="page-breadcrumb breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="{{URL::to('dashboard')}}">Home</a>
                    <i class="fa fa-angle-right"></i>
                </li>

                <li>Edit Purchase Invoice</li>
            </ul>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <div class="col-md-16">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet box purple">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-reorder"></i>Edit Purchase Invoice</div>
                <div class="actions">
                    <a class="btn dark" href="{{ URL::to('purchases/index') }}">Purchase Invoice List</a>
                </div>
            </div>
            <div class="portlet-body form">

               {!!Form::model($purchase[0],array('action' => array('PurchaseInvoiceController@updatePurchaseInvoiceData',
           $purchase[0]->invoice_id), 'method' => 'POST', 'class'=>'form-horizontal', 'id'=>'purchase_form'))!!}
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
                            <div class="form-group">
                                <div class="col-md-5">
                                    {!!Form::select('party_id',[null=>'Please Select Party'] + $suppliersAll,$purchase[0]->party_id, array('class'=>'form-control ','id'=>'party_id') )!!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-5">
                                    {!!Form::hidden('invoice_id',null,array('class' => 'form-control','id'=>'detail_invoice_id'))!!}
                                </div>
                            </div>

                            <div class="row">
                                <table class="table table-striped table-bordered table-primary table-condensed" id="purchaseTable">
                                    <thead>
                                    <tr>
                                        <th width="">Product Name</th>
                                        <th width="">Price</th>
                                        <th width="">Quantity</th>
                                        <th width="">Remarks</th>
                                        <th width="">Action</th>
                                    </tr>

                                    </thead>

                                    @foreach($purchaseDetails as $purchaseDetail)
                                        <tr>
                                            <td> {{ $purchaseDetail->product->name }}</td>
                                            <td> {{ $purchaseDetail->price }}</td>
                                            <td> {{ $purchaseDetail->quantity }}</td>
                                            <td>
                                                @if($purchaseDetail->remarks)
                                                {{ $purchaseDetail->remarks }}
                                                @else
                                                    {{"Not Available"}}
                                                @endif
                                            </td>
                                            <td> <input type="button"  style="width:127px;" value="delete" class="btn red deletePurchase" rel={{$purchaseDetail->id}} /></td>

                                        </tr>

                                    @endforeach
                                    <tbody>

                                    </tbody>
                                    <tr class="clone_">
                                        <td>
                                            <div class="form-group">
                                                <div class="col-md-11">
                                                    {!!Form::select('product_id',[null=>'Please Select Product'] +$localProducts,'null', array('class'=>'form-control ','id'=>'product_id') )!!}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="col-md-11">
                                                    {!!Form::text('price',null,array('placeholder' => 'Price', 'class' =>
                                                    'form-control','id'=>'price'))!!}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="col-md-11">
                                                    {!!Form::text('quantity',null,array('placeholder' => 'Purchase Quantity', 'class' =>
                                                    'form-control','id'=>'quantity'))!!}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="col-md-11">
                                                    {!!Form::text('remarks',null,array('placeholder' => 'Remarks', 'class' =>
                                                    'form-control','id'=>'remarks'))!!}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {!!Form::button('Add Product',array('type' => 'button','class' => 'btn blue editPurchaseInvoice' ,'rel'=>$purchase[0]->invoice_id))!!}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>


                        <!-- END FORM-->
                    </div>

                    {!!Form::close()!!}
                            <!-- END FORM-->
                </div>
            </div>
            <!-- END VALIDATION STATES-->
        </div>
    </div>
@stop
@section('javascript')
    {!! HTML::script('js/purchaseInvoice.js') !!}
    {!! HTML::script('assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js') !!}
    {!! HTML::script('assets/plugins/select2/select2.min.js') !!}

@stop



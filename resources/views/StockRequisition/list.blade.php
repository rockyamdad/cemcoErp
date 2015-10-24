@extends('baseLayout')
@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Stock Section
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{URL::to('dasboard/')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>

            <li><a href="{{URL::to('requisitions/index')}}">Stock Requisition List</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>

<div class="row">

    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box light-grey">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-globe"></i>Stock Requisition</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div style="float: left;width: 80%; margin-left: 20px">
                @if (Session::has('message'))
                <div class="alert alert-success">
                    <button data-close="alert" class="close"></button>
                    {{ Session::get('message') }}
                </div>
                @endif
            </div>

            <div class="portlet-body">

                <div class="table-toolbar">
                    <div class="btn-group">
                        <a class="btn green" href="{{ URL::to('requisitions/create') }}">Make Stock Requisition&nbsp;&nbsp;<i
                                class="fa fa-plus"></i></a>

                    </div>

                </div>
                <table class="table table-striped table-bordered table-hover" id="Requisitiontable">
                    <thead>
                    <tr>
                        <th class="table-checkbox"><input type="checkbox" class="group-checkable"
                                                          data-set="#user_table .checkboxes"/></th>
                        <th>Product Name</th>
                        <th>Party Name</th>
                        <th>Requisition No</th>
                        <th>Requisition Quantity</th>
                        <th>Remarks</th>
                        <th>Created By</th>
                      <!--  <th>Status</th>-->
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($requisitions as $requisition )
                    <tr class="odd gradeX">
                        <td><input type="checkbox" class="checkboxes" value="1"/></td>
                        <td>{{$requisition->product->name}}</td>
                        <td>{{$requisition->party->name}}</td>
                        <td>{{$requisition->requisition_id}}</td>
                        <td>{{$requisition->requisition_quantity}}</td>
                        <td>{{$requisition->remarks}}</td>
                        <td>{{$requisition->user->name}}</td>
                      <!--  <td>@if($requisition->status == 'Activate')
                            <span class="label label-sm label-success">Activate</span>
                            @else
                            <span class="label label-sm label-danger">DeActivate</span>
                            @endif
                        </td>-->
                        <td>
                            @if( Session::get('user_role') == "admin")
                            <a class="btn blue btn-sm" href="{{ URL::to('requisitions/edit/'. $requisition->id ) }}"><i
                                    class="fa fa-edit"></i>Edit Product</a>
                            <a class="btn red btn-sm" href="{{ URL::to('requisitions/del/'.$requisition->id)}}"
                               onclick="return confirm('Are you sure you want to delete this item?');"><i
                                    class="fa fa-trash-o"></i> Delete</a>
                            @else

                            <a class="btn blue btn-sm issued" rel="{{ $requisition->id }}" data-toggle="modal" href="#issuedRequisition" >
                                   Issued Requisition</a>
                            @endif

                        </td>

                    </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>


        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<div id="issuedRequisition" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3>Issued Requisition</h3>
            </div>
            <div class="modal-body">

                {!!Form::open(array('url' => '/updateIssuedRequisition', 'method' => 'post', 'class'=>'form-horizontal',
                'id'=>'issued_requisition_form'))!!}
                <div class="form-body">

                    <div class="alert alert-danger display-hide">
                        <button data-close="alert" class="close"></button>
                        You have some form errors. Please check below.
                    </div>
                    <div class="alert alert-success display-hide">
                        <button data-close="alert" class="close"></button>
                        Your form validation is successful!
                    </div>
                    <div class="portlet-body form" id="testt">
                        {!! Form::hidden('id',null,array('id'=>'requisitionId'))!!}
                        <!-- BEGIN FORM-->
                        <div class="form-body">
                            <div class="form-group">
                                {!!HTML::decode(Form::label('issued_quantity','Issue Quantity<span class="required">*</span>',array('class' =>
                                'control-label col-md-4')))!!}
                                <div class="col-md-7">
                                    {!!Form::text('issued_quantity',null,array('placeholder' => 'Requisition Quantity', 'class' =>
                                    'form-control','id'=>'requisition_quantity'))!!}
                                </div>
                            </div>

                            <div class="form-actions fluid">
                                <div class="col-md-offset-3 col-md-9">
                                    {!!Form::button('Save changes',array('type' => 'submit','class' => 'btn blue','id' => 'save'))!!}
                                    <button type="button" data-dismiss="modal" class="btn">Close</button>
                                </div>
                            </div>
                         </div>
                    </div>

                    {!!Form::close()!!}

            </div>
           <!-- <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn">Close</button>
                <button type="button" class="btn blue">Save changes</button>
            </div>-->

        </div>
    </div>
</div>
    </div>


@stop
@section('javascript')
{!! HTML::script('js/stockRequisition.js') !!}
{!! HTML::script('assets/plugins/select2/select2.min.js') !!}

@stop

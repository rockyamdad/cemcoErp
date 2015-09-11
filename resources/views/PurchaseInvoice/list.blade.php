@extends('baseLayout')
@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Purchase Invoice Section Section
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{URL::to('dasboard/')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>

            <li><a href="{{URL::to('purchases/index')}}"> Purchase Invoice List</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>

<div class="row">

    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box light-grey">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-globe"></i> Purchase Invoice</div>
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
                        <a class="btn green" href="{{ URL::to('purchases/create') }}">Make  Purchase Invoice&nbsp;&nbsp;<i
                                class="fa fa-plus"></i></a>

                    </div>

                </div>
                <table class="table table-striped table-bordered table-hover" id="Purchasetable">
                    <thead>
                    <tr>
                        <th class="table-checkbox"><input type="checkbox" class="group-checkable"
                                                          data-set="#user_table .checkboxes"/></th>
                        <th>Purchase Invoice Id</th>
                        <th>Party Name</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($purchases as $purchase )
                    <tr class="odd gradeX">
                        <td><input type="checkbox" class="checkboxes" value="1"/></td>
                        <td>{{$purchase->invoice_id}}</td>
                        <td>{{$purchase->party->name}}</td>
                        <td>{{$purchase->status}}</td>
                        <td>{{$purchase->created_by}}</td>



                       <td>
                            @if( Session::get('user_role') == "admin")
                            <a class="btn blue btn-sm"  href="{{ URL::to('purchases/edit/'. $purchase->id ) }}"><i
                                    class="fa fa-edit"></i>Edit Product</a>
                            <a class="btn blue btn-sm details" rel="{{ $purchase->id }}" data-toggle="modal"  data-target="#purchaseInvoice" href="{{ URL::to('purchases/details/'. $purchase->invoice_id ) }}" >
                                <i class="fa fa-eye"></i> Detail</a>

                            <a class="btn red btn-sm" href="{{ URL::to('purchases/del/'.$purchase->id)}}"
                               onclick="return confirm('Are you sure you want to delete this item?');"><i
                                    class="fa fa-trash-o"></i> Delete</a>
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

@stop
@section('javascript')
{!! HTML::script('js/purchaseInvoice.js') !!}
{!! HTML::script('assets/plugins/select2/select2.min.js') !!}

@stop

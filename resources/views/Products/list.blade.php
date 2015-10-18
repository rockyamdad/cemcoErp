@extends('baseLayout')
@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Product Section
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{URL::to('dasboard/')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>

            <li><a href="{{URL::to('products/index')}}">Product List</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>

<div class="row">

    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box light-grey">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-globe"></i>Product</div>
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
                        <a class="btn green" href="{{ URL::to('products/create') }}">Add Product &nbsp;&nbsp;<i
                                class="fa fa-plus"></i></a>

                    </div>

                </div>
                <table class="table table-striped table-bordered table-hover" id="product_table">
                    <thead>
                    <tr>
                        <th class="table-checkbox"><input type="checkbox" class="group-checkable"
                                                          data-set="#user_table .checkboxes"/></th>
                        <th>Name</th>
                        <th>Branch Name</th>
                        <th>Category Name</th>
                        <th>Sub Category Name</th>
                        <th>HS Code</th>
                        <th>Origin</th>
                        <th>Total Quantity</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product )
                    <tr class="odd gradeX">
                        <td><input type="checkbox" class="checkboxes" value="1"/></td>
                        <td>{{$product->name}}</td>
                        <td>{{$product->branch->name}}</td>
                        <td>{{$product->category->name}}</td>
                        @if($product->sub_category_id == 0)
                            <td>N/A</td>
                        @else
                            <td>{{$product->sub_category_id}}</td>
                        @endif
                        <td>{{$product->hs_code}}</td>
                        <td>{{$product->origin}}</td>
                        <td>{{$product->total_quantity}}</td>
                        <td>{{$product->user_id}}</td>
                        <td>
                            <a class="btn blue btn-sm" href="{{ URL::to('products/edit/'. $product->id ) }}"><i
                                    class="fa fa-edit"></i>Edit Product</a>
                            <a class="btn red btn-sm" href="{{ URL::to('products/delete/'.$product->id)}}"
                               onclick="return confirm('Are you sure you want to delete this item?');"><i
                                    class="fa fa-trash-o"></i> Delete</a>
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


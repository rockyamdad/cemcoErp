@extends('baseLayout')
@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Sales Return Section
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{URL::to('dasboard/')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>

            <li><a href="{{URL::to('salesreturn/index')}}">Sales Return List</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>

<div class="row">

    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box light-grey">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-globe"></i>Sales Return</div>
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
                    @if (Session::has('wrong'))
                        <div class="alert alert-danger">
                            <button data-close="alert" class="close"></button>
                            {{ Session::get('wrong') }}
                        </div>
                    @endif
            </div>

            <div class="portlet-body">

                <div class="table-toolbar">
                    <div class="btn-group">
                        <a class="btn green" href="{{ URL::to('salesreturn/create') }}">Return Sales &nbsp;&nbsp;<i
                                class="fa fa-plus"></i></a>

                    </div>

                </div>
                <table class="table table-striped table-bordered table-hover" id="stock_table">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Ref No</th>
                        <th>Party</th>
                        <th>Branch</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Return</th>
                        <th>Consignment</th>
                        <th>Remarks</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sl=1;
                    ?>
                    @foreach($salesreturn as $stock )

                    <tr class="odd gradeX">
                        <td><?php echo $sl; ?></td>
                        <td>{{$stock->cus_ref_no}}</td>
                        <td>{{$stock->party_id}}</td>
                        <td>{{$stock->branch_id}}</td>
                        <td>{{$stock->product_id}}</td>
                        <td>{{$stock->quantity}}</td>
                        <td>{{$stock->return_amount}}</td>
                        <td>{{$stock->consignment_name}}</td>
                        <td>{{$stock->remarks}}</td>
                        <td>{{$stock->consignment_name}}</td>

                        <td>
                            <a class="btn blue btn-sm" href="{{ URL::to('stocks/edit/'. $stock->id ) }}"><i
                                    class="fa fa-edit"></i>Edit</a>
                            <a class="btn red btn-sm" href="{{ URL::to('stocks/delete/'.$stock->id)}}"
                               onclick="return confirm('Are you sure you want to delete this item?');"><i
                                    class="fa fa-trash-o"></i> Delete</a>
                        </td>

                    </tr>
                    <?php
                    $sl++;
                    ?>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
{!! $salesreturn->render() !!}
@stop
@section('javascript')
    {!! HTML::script('js/stock.js') !!}
    {!! HTML::script('assets/plugins/select2/select2.min.js') !!}
@stop
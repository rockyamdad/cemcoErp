@extends('baseLayout')
@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Expense Section
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{URL::to('dasboard/')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>

            <li><a href="{{URL::to('expenses/index')}}"> Expenses List</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>

<div class="row">

    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box light-grey">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-globe"></i> Expenses</div>
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
                        <a class="btn green" href="{{ URL::to('expenses/create') }}">Make  Expense&nbsp;&nbsp;<i
                                class="fa fa-plus"></i></a>

                    </div>

                </div>
                <table class="table table-striped table-bordered table-hover" id="Expensetable">
                    <thead>
                    <tr>
                        <th class="table-checkbox"><input type="checkbox" class="group-checkable"
                                                          data-set="#user_table .checkboxes"/></th>
                        <th>Invoice Id</th>
                        <th>Category</th>
                        <th>Particular</th>
                        <th>Purpose</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($expenseAll as $expense )
                    <tr class="odd gradeX">
                        <td><input type="checkbox" class="checkboxes" value="1"/></td>
                        <td>{{$expense->invoice_id}}</td>
                        <td>{{$expense->category}}</td>
                        @if($expense->particular)
                            <td>{{$expense->particular}}</td>
                        @else
                            <td>{{"Not Available"}}</td>
                        @endif
                        @if($expense->purpose)
                            <td>{{$expense->purpose}}</td>
                        @else
                            <td>{{"Not Available"}}</td>
                        @endif

                        <td>{{$expense->amount}}</td>
                        @if($expense->remarks)
                            <td>{{$expense->remarks}}</td>
                        @else
                            <td>{{"Not Available"}}</td>
                        @endif

                        @if($expense->status == 'Activate')
                            <td><span class="label label-sm label-danger">Due</span></td>
                        @elseif($expense->status == 'Partial')
                            <td><span class="label label-sm label-warning">Partial</span></td>
                        @elseif($expense->status == 'Completed')
                            <td><span class="label label-sm label-success">Completed</span></td>
                        @endif


                        <td>{{$expense->created_by}}</td>

                       <td>
                            @if( Session::get('user_role') == "admin")
                            <a class="btn blue btn-sm"  href="{{ URL::to('expenses/edit/'. $expense->id ) }}"><i
                                    class="fa fa-edit"></i>Edit</a>
                            <a class="btn blue btn-sm details" rel="{{ $expense->invoice_id }}" data-toggle="modal"  data-target="#Expense" href="{{ URL::to('expenses/details/'. $expense->invoice_id ) }}" >
                                <i class="fa fa-eye"></i> Detail</a>
                               @if($expense->status != 'Completed')
                                   <a class="btn purple btn-sm makePayment"  rel="{{ $expense->invoice_id }}" data-toggle="modal"  data-target="#expensePayment" href="{{ URL::to('expenses/make') }}" >
                                       <i class="fa fa-usd"></i> Payment</a>
                                @endif
                            <a class="btn red btn-sm" href="{{ URL::to('expenses/delete/'.$expense->id)}}"
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
{!! HTML::script('js/expenses.js') !!}
{!! HTML::script('assets/plugins/select2/select2.min.js') !!}

@stop
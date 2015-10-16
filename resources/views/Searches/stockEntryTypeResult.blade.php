@extends('baseLayout')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
             Stock Search Result
            </h3>

        </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box light-grey">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-reorder"></i>   Stock Search Result</div>
                    <div class="actions">
                        <a class="btn blue" href="/entry">Back</a>

                    </div>
                </div>

                <div class="portlet-body">
                    @if($results)
                    <table class="table table-striped table-bordered table-hover" id="stock_search_result_table">
                        <thead style="background-color:royalblue">
                        <tr>

                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Stock Name</th>
                            <th>Remarks</th>
                            <th>Consignment Name</th>
                            <th>Date</th>
                            <th>Created By</th>

                        </tr>
                        </thead>
                        <tbody>

                            @foreach($results as $result )
                                <tr class="odd gradeX">

                                    <td>{{$result->pName}}</td>
                                    <td>{{$result->product_quantity}}</td>
                                    <td>{{$result->sName}}</td>
                                    <td>{{$result->remarks}}</td>
                                    <td>{{$result->consignment_name}}</td>
                                    <td>{{$result->created_at}}</td>
                                    <td>{{$result->uName}}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                        @else
                           <h4  style="color:red">No Search Result</h4>
                        @endif

                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>

@stop
@section('javascript')
    {!! HTML::script('js/search.js') !!}
@stop
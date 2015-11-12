@extends('baseLayout')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
             Stock Requisition Search Result
            </h3>

        </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box light-grey">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-reorder"></i>   Stock Requisition Search Result</div>
                    <div class="actions">
                        <a class="btn blue" href="/requisition">Back</a>
                    </div>
                </div>

                <div class="portlet-body">
                    @if($results)
                    <table class="table table-striped table-bordered table-hover" id="stock_requisition_search_result_table">
                        <thead style="background-color:royalblue">
                        <tr>

                            <th>Party Name</th>
                            <th>Product Name</th>
                            <th>Requisition Quantity</th>
                            <th>Issued Quantity</th>
                            <th>Date</th>
                            <th>Remarks</th>
                            <th>Created By</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $result )
                        <?php
                        $categoryName = \App\Category::find($result->cId);
                        $subCategoryName = \App\SubCategory::find($result->sId);
                        ?>

                            <tr class="odd gradeX">

                                <td>{{$result->partyName}}</td>
                                <td>{{$result->pName.'('.$categoryName->name.')'.'('.$subCategoryName->name.')'}}</td>
                                <td>{{$result->requisition_quantity}}</td>
                                <td>{{$result->issued_quantity}}</td>
                                <td>{{$result->created_at}}</td>
                                <td>{{$result->remarks}}</td>
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
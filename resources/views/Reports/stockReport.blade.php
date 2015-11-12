@extends('baseLayout')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
             Stock Report
            </h3>

        </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <?php
            use Illuminate\Support\Facades\URL;
            $sDate = date('Ymd', strtotime($date1));
            $eDate = date('Ymd', strtotime($date2));
            $type  = $product_type;
            $url = URL::to('reports/print/'.$sDate.'/'.$eDate.'/'.$type);
            ?>
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box light-grey">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-reorder"></i>   Stock Report of {{$date1}} to {{$date2}} for {{$product_type}} Products</div>
                    <div class="actions">
                        <a class="btn blue" href="/stocks">Back</a>

                        <a class="btn dark" onclick="javascript: window.open('{{$url}}','MsgWindow', 'width=1100,height=500').print();">Print</a>
                    </div>

                </div>

                <div class="portlet-body">

                    @if($results)
                    <table class="table table-striped table-bordered table-hover" id="stock_requisition_search_result_table">
                        <thead style="background-color:cadetblue">
                        <tr>

                            <th>Category Name</th>
                            <th>Product Name</th>
                            <th>StockIn</th>
                            <th>Before hand</th>
                            <th>Total StockIn</th>
                            <th>StockOut</th>
                            <th>Wastage</th>
                            <th>Total Out+Ng</th>
                            <th  style="background-color:green">Balance</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                                $grandTotalStockIn = 0;
                                $grandTotalStockOut = 0;
                                $grandTotalStockWastage = 0;
                                $grandTotalStockBf = 0;
                                $grandTotalStockInBf = 0;
                                $grandTotalStockOutNg = 0;
                                $grandTotalBalance = 0;
                        ?>

                        @foreach($results as $result )
                            <?php
                                    $stocks = new \App\Report();
                                    $bf = $stocks->getStockBf($product_type,$date1,$result->product_id);
                                    $stockIn = $stocks->getStockIn($product_type,$date1,$date2,$result->product_id);
                                    $stockOut = $stocks->getStockOut($product_type,$date1,$date2,$result->product_id);
                                    $wastage = $stocks->getStockWastage($product_type,$date1,$date2,$result->product_id);
                                    $totalIn = $bf[0]->stockBf + $stockIn[0]->stockIn;
                                    $totalOutNg =  $stockOut[0]->stockOut + $wastage[0]->stockWastage;
                                    $balance =  $totalIn - $totalOutNg;

                            ?>

                            <tr class="odd gradeX">

                                <td>{{$result->category}}</td>
                                <td>{{$result->pName}}</td>
                                <td>@if($stockIn[0]->stockIn){{ $stockIn[0]->stockIn }}@else {{ 0 }}@endif</td>
                                <td>@if($bf[0]->stockBf){{ $bf[0]->stockBf }}@else {{ 0 }}@endif</td>
                                <td>{{ $totalIn }}</td>
                                <td>@if($stockOut[0]->stockOut){{ $stockOut[0]->stockOut }}@else {{ 0 }}@endif</td>
                                <td>@if($wastage[0]->stockWastage){{ $wastage[0]->stockWastage }}@else {{ 0 }}@endif</td>
                                <td>{{ $totalOutNg }}</td>
                                <td>{{ $balance }}</td>

                            </tr>
                            <?php
                                $grandTotalStockIn  = $grandTotalStockIn + $stockIn[0]->stockIn;
                                $grandTotalStockBf  = $grandTotalStockBf + $bf[0]->stockBf;
                                $grandTotalStockOut = $grandTotalStockOut + $stockOut[0]->stockOut;
                                $grandTotalStockWastage = $grandTotalStockWastage + $wastage[0]->stockWastage;
                                $grandTotalStockInBf  = $grandTotalStockInBf + $totalIn;
                                $grandTotalStockOutNg     = $grandTotalStockOutNg + $totalOutNg;
                                $grandTotalBalance    = $grandTotalBalance + $balance;
                            ?>
                        @endforeach
                        <tr style="background-color:#b4cef8;">

                            <td>Grand Total</td>
                            <td></td>
                            <td>{{$grandTotalStockIn}}</td>
                            <td>{{$grandTotalStockBf}}</td>
                            <td>{{$grandTotalStockInBf}}</td>
                            <td>{{$grandTotalStockOut}}</td>
                            <td>{{$grandTotalStockWastage}}</td>
                            <td>{{$grandTotalStockOutNg}}</td>
                            <td>{{ $grandTotalBalance }}</td>

                        </tr>
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

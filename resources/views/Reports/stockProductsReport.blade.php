@extends('baseLayout')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
             Stock Products Report
            </h3>

         </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <?php
            use Illuminate\Support\Facades\URL;

            $url = URL::to('reports/printstocksproducts');
            ?>
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box light-grey">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-reorder"></i>   Stock Report of Products</div>
                    <div class="actions">

                        <a class="btn dark" onclick="javascript: window.open('{{$url}}','MsgWindow', 'width=1100,height=500').print();">Print</a>
                    </div>

                </div>

                <div class="portlet-body">


                    <table class="table table-striped table-bordered table-hover" id="stock_products_report_table">
                        <thead style="background-color:cadetblue">
                        <tr>

                            <th>Stock Name</th>
                            <th>Product Name</th>
                            <th  style="background-color:blue">Total Quantity On Hand</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $grandTotal = 0;
                        ?>

                        @foreach($results as $result )
                            <?php
                            $pName = \App\Product::find($result->product_id);
                            $sName = \App\StockInfo::find($result->stock_info_id);
                            $grandTotal = $grandTotal + $result->sum;
                                    ?>

                            <tr class="odd gradeX">
                                <td>{{$sName->name}}</td>
                                <td>{{$pName->name}}</td>
                                <td>{{$result->sum}}</td>


                            </tr>

                        @endforeach
                        <tr>
                            <td>Total</td>
                            <td></td>
                            <td>{{$grandTotal}}</td>
                        </tr>

                        </tbody>
                    </table>

                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>

@stop

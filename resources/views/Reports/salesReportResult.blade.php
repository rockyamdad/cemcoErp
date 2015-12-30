@extends('baseLayout')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" />

@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
           <center> <h3 class="page-title">
                Sales Report of Branch Name
            </h3>
           </center>
         </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <?php
            use Illuminate\Support\Facades\URL;
                    $curent_url = $_SERVER['REQUEST_URI'];

                    if($curent_url == '/reports/stocksproductsresult')
                        {
                            $url = URL::to('reports/printstocksproductsresult/'.$product_type.'/'.$stock_info_id.'/'.$branch_id.'/'.$category_id);
                        }else{
                            $url = URL::to('reports/printstocksproducts');
                    }
            ?>
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box light-grey">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-reorder"></i>   Sales Report of From Date to To Date</div>

                     <div class="actions">
                         <a class="btn btn-sm blue hidden-print" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
                       </div>

                </div>

                <div class="portlet-body">

                    <table class="table table-striped table-bordered table-hover" id="stock_products_report_table">
                        <thead style="background-color:cadetblue">
                        <tr>

                            <th>Invoice No</th>
                            <th style="text-align: right;">Total Sale</th>
                            <th style="text-align: right;">Total Payment </th>
                            <th style="text-align: right;">Due</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $totalSale = 0;
                        $totalPayment = 0;
                        $totalDue = 0;
                        ?>

                        @foreach($results as $result )
                            <?php
                           $reports = new \App\Report();
                            $payment = $reports->getPaymentForSalesReport($date1,$date2,$result->invoice);
                                    ?>

                            <tr class="odd gradeX">
                                <td>{{$result->invoice}}</td>
                                <td style="text-align: right;">{{$result->totalSale}}</td>
                                <td style="text-align: right;">
                                    @if($payment[0]->totalPayment)
                                        {{$payment[0]->totalPayment}}
                                    @else
                                         {{0}}
                                    @endif
                                </td>
                                <td style="text-align: right;">{{$result->totalSale - $payment[0]->totalPayment}}</td>


                            </tr>
                            <?php
                                $totalSale = $totalSale + $result->totalSale;
                                $totalPayment = $totalPayment + $payment[0]->totalPayment;
                                $totalDue = $totalDue + ($result->totalSale - $payment[0]->totalPayment);
                            ?>
                        @endforeach
                        <tr>
                            <td><b>Grand Total</b></td>
                            <td style="text-align: right;">{{$totalSale}}</td>
                            <td style="text-align: right;">{{$totalPayment}}</td>
                            <td style="text-align: right;">{{$totalDue}}</td>

                        </tr>

                        </tbody>
                    </table>

                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>

@stop
@section('javascript')
    {!! HTML::script('js/report.js') !!}
    {!! HTML::script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::script('assets/plugins/select2/select2.min.js') !!}
@stop

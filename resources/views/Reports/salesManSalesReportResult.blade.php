@extends('baseLayout')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" />

@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                <?php
                $branches = \App\Branch::find($branch_id);
                ?>
           <center> <h3 class="page-title">
                   @if ($branches)
                        Sales Man Sales Report For {{$branches->name}}
                   @else
                       Sales Man Sales Report
                   @endif
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
                    @if($date1 and $date2)
                    <?php

                    $date01 = explode('/', $date1);
                    $month1  = $date01[0];
                    $day1 = $date01[1];
                    $year1   = $date01[2];
                    $date001=$day1.'/'.$month1.'/'.$year1;

                    $date02 = explode('/', $date2);
                    $month2  = $date02[0];
                    $day2 = $date02[1];
                    $year2   = $date02[2];
                    $date002=$day2.'/'.$month2.'/'.$year2;

                    ?>
                    <div class="caption"><i class="fa fa-reorder"></i>Date : {{$date001}} to {{$date002}}</div>
                    @endif
                     <div class="actions">
                         <a class="btn btn-sm blue hidden-print" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
                       </div>

                </div>

                <div class="portlet-body">
                    @if($results)
                    <table class="table table-striped table-bordered table-hover" id="stock_products_report_table">
                        <thead style="background-color:cadetblue">
                        <tr>

                            <th>Sales Man</th>
                            <th>Invoice Id</th>
                            <th style="text-align: right;">invoice Total </th>
                            <th style="text-align: right;">invoice Payment </th>
                            <th style="text-align: right;">invoice Due</th>

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
                                $payment = 0;
                                $salesMan = \App\User::find($result->salesMan);

                            $reports = new \App\Report();
                            $paymentSales = $reports->getPaymentForSalesReport($date1,$date2,$result->invoice);
                            $payment = $payment + $paymentSales[0]->totalPayment;

                            ?>

                            <tr class="odd gradeX">
                                <td>{{$salesMan->username}}</td>
                                <td>{{$result->invoice}}</td>
                                <td style="text-align: right;">{{$result->totalSale-$result->discount_amount}}</td>
                                <td style="text-align: right;">
                                        {{$payment}}
                                </td>
                                <td style="text-align: right;"><?php $res = $result->totalSale - $payment - $result->discount_amount; if ($res>0) echo $res; else echo 0 ?></td>


                            </tr>
                            <?php
                                $totalSale = $totalSale + $result->totalSale - $result->discount_amount;
                                $totalPayment = $totalPayment + $payment;
                                $totalDue = $totalDue + ($result->totalSale - $payment - $result->discount_amount);
                            ?>
                        @endforeach
                        <tr>
                            <td><b>Grand Total</b></td>
                            <td><b></b></td>
                            <td style="text-align: right;">{{$totalSale}}</td>
                            <td style="text-align: right;">{{$totalPayment}}</td>
                            <td style="text-align: right;">{{$totalDue}}</td>

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
@section('javascript')
    {!! HTML::script('js/report.js') !!}
    {!! HTML::script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::script('assets/plugins/select2/select2.min.js') !!}
@stop

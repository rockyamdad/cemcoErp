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
            <h3 class="page-title">
                @if ($branches)
                    Cash Sales Due Report For Branch : {{$branches->name}}
                @else
                    Cash Sales Due Report
                @endif
            </h3>

         </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box light-grey">
                <div class="portlet-title">
                    <?php
                    if ($date1 && $date2) {
                        $date01 = explode('/', $date1);
                        $month1  = $date01[0];
                        $day1 = $date01[1];
                        $year1   = $date01[2];
                        $date11 =$day1.'/'.$month1.'/'.$year1;

                        $date02 = explode('/', $date2);
                        $month2  = $date02[0];
                        $day2 = $date02[1];
                        $year2   = $date02[2];
                        $date22 =$day2.'/'.$month2.'/'.$year2;
                    }
                    ?>
                    <div class="caption"><i class="fa fa-reorder"></i>
                        <?php
                        if ($date1 && $date2) {
                        ?>

                        Date : {{$date11}} to {{$date22}}
                        <?php
                        } else {
                        ?>
                        No date selected
                        <?php
                        }
                        ?>

                    </div>

                     <div class="actions">
                         <a class="btn btn-sm blue hidden-print" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
                       </div>

                </div>

                <div class="portlet-body">
                    @if($results)
                    <table class="table table-striped table-bordered table-hover" id="stock_products_report_table">
                        <thead style="background-color:cadetblue">
                        <tr>
                            <th>Cash Sale</th>
                            <th style="text-align: right;">Total Sale</th>
                            <th style="text-align: right;">Total Discount</th>
                            <th style="text-align: right;">Total Payment </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $totalSale = 0;
                        $totalPayment = 0;
                        $totalDiscount = 0;
                        $totalDue = 0;
                        ?>

                        @foreach($results as $result )
                            <?php
                           $party = \App\Party::find($result->party);
                           $reports = new \App\Report();
                           $payment = $reports->getPaymentForCashSalesDueReport($date1,$date2,$result->party);
                                    ?>
                            @if(($result->totalSale - $payment[0]->totalPayment) > 1)
                                <tr class="odd gradeX">
                                    <td>{{$result->party}}</td>
                                    <td style="text-align: right;">{{$result->totalSale}}</td>
                                    <td style="text-align: right;">{{$result->discount_amount}}</td>
                                    <td style="text-align: right;">
                                        @if($payment[0]->totalPayment)
                                            {{$payment[0]->totalPayment}}
                                        @else
                                             {{0}}
                                        @endif
                                    </td>
                                </tr>
                                <?php
                                $totalSale = $totalSale + $result->totalSale ;
                                $totalPayment = $totalPayment + $payment[0]->totalPayment;
                                $totalDiscount = $totalDiscount + $result->discount_amount;
                                ?>
                            @endif
                        @endforeach
                        <tr>
                            <td><b>Grand Total</b></td>
                            <td style="text-align: right;">{{$totalSale}}</td>
                            <td style="text-align: right;">{{$totalDiscount}}</td>
                            <td style="text-align: right;">{{$totalPayment}}</td>

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

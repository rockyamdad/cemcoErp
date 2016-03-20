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
                $parties = \App\Party::find($party_id);
            ?>
            <h3 class="page-title">
             Purchase Due Report for {{$parties->name}}
            </h3>

         </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box light-grey">
                <div class="portlet-title">
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

                     <div class="actions">
                         <a class="btn btn-sm blue hidden-print" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
                       </div>

                </div>

                <div class="portlet-body">

                    <table class="table table-striped table-bordered table-hover" id="sales_party_ledger_report_table">
                        <thead style="background-color:cadetblue">
                        <tr>
                            <th>Date</th>
                            <th style="text-align: right;">PatiCulars</th>
                            <th style="text-align: right;">Debit </th>
                            <th style="text-align: right;">Credit </th>
                            <th style="text-align: right;">Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $flag = '';
                        $openingBalance = ($credit[0]->totalCredit - $debit[0]->totalDebit);
                        ?>
                        <tr class="odd gradeX" >
                            <td>Opening Balance</td>
                            <td></td>
                            <td></td>
                            <td></td>

                            <td >
                                {{$openingBalance}}
                            </td>

                        </tr>

                        @foreach($results as $result )
                            <?php
                                $reports = new \App\Report();
                                $payments = $reports->getPaymentForPurchasePartyLedgerReport($date1,$date2,$result->invoice);
                            ?>
                            <tr>
                                <td>{{\App\Transaction::convertDate($result->date)}}</td>
                                <td style="background-color: #0077b3">Product Received({{$result->invoice}})</td>
                                <td></td>
                                <td>{{$result->total}}</td>
                                <td>
                                    @if($flag == '')
                                        {{$openingBalance + $result->total}}
                                    @else
                                        {{$balance + $result->total}}
                                    @endif
                                </td>

                            </tr>
                            <?php
                            if($flag == ''){
                                $balance  = $openingBalance + $result->total;
                            }else{
                                $balance  = $balance + $result->total;
                            }
                            ?>
                            <?php $flag = 'value';?>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{\App\Transaction::convertDate($payment->date)}}</td>
                                    <td>
                                        @if($payment->payment_method == 'Cash')
                                           Cash
                                        @else
                                           Check ({{$payment->cheque_no}})
                                        @endif
                                    </td>
                                    <td>{{$payment->total}}</td>
                                    <td></td>
                                    <td>
                                        @if($flag == '')
                                            {{$openingBalance - $payment->total}}
                                        @else
                                            {{$balance - $payment->total}}
                                        @endif
                                    </td>
                                </tr>

                                <?php
                                    if($flag == ''){
                                        $balance  = $openingBalance - $payment->total;
                                    }else{
                                        $balance  = $balance - $payment->total;
                                    }
                                $flag = 'value';
                                ?>

                            @endforeach


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
    {!! HTML::script('js/report.js') !!}
    {!! HTML::script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::script('assets/plugins/select2/select2.min.js') !!}
@stop

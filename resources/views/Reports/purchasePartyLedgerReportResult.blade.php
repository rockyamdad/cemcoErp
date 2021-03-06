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
             Purchase Due Report for {{$parties ? $parties->name: ''}}
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
                        $balance = 0;
                        $openingBalance = ($open_balance + $credit[0]->totalCredit - $debit[0]->totalDebit);
                        ?>
                        <tr class="odd gradeX" >
                            <td>Opening Balance</td>
                            <td></td>
                            <td></td>
                            <td></td>

                            <td  class="text-right">
                                {{number_format($openingBalance,2)}}
                            </td>

                        </tr>
                        @if($results2)
                        @foreach($results2 as $result )
                            <?php
                            $particular = $result->particular;
                            $debitAmount = 0;
                            if (strpos($particular, 'Cash') !== false || (strpos($particular, 'Sales') !== false) || strpos($particular, 'Check') !== false) {
                                $balance -= $result->amount;
                            } else {
                                $debitAmount = $result->amount;
                            }

                            //$reports = new \App\Report();
                            //$payments = $reports->getPaymentForSalesPartyLedgerReport($date1,$date2,$result2->invoice);
                            ?>
                            <tr>
                                <td>{{\App\Transaction::convertDate($result->created_at)}}</td>

                                <td <?php if($debitAmount != 0) echo 'style="background-color: #0077b3; color: #ffffff;"'; ?> ><?php if($debitAmount != 0) echo 'Product Received'; else echo 'Payment' ?> <?php if($debitAmount != 0) { echo '<a target="_blank" style="color: white;" href="'.URL::to('purchases?invoice_id='.$result->particular).'">'; ?>({{$result->particular}})<?php echo '</a>'; } else { echo str_replace("Check","Cheque",$result->particular);  } ?></td>
                                <td class="text-right">
                                    <?php
                                    if (strpos($particular, 'Cash') !== false || (strpos($particular, 'Sales') !== false) || strpos($particular, 'Check') !== false) {
                                        echo number_format($result->amount,2);
                                    }
                                    ?>

                                </td>
                                <td  class="text-right">
                                    <?php
                                    if ($debitAmount == 0)
                                        $debitAmount = '';
                                    else{
                                        $balance += $result->amount;
                                        echo number_format($debitAmount,2);
                                    }

                                    ?>
                                </td>
                                <td  class="text-right">{{number_format($balance,2)}}</td>


                            </tr>



                        @endforeach

                        @endif
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

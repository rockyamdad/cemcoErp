@extends('baseLayout')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" />
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
         <?php
            $account = \App\NameOfAccount::find($account_id);
            if($account)
            $accountCat = \App\AccountCategory::find($account->account_category_id);
          ?>
        <center>
            <h3 class="page-title">
                @if($account and $accountCat)
                    Accounts Report  for <span style="color: black"> {{$account->name}} ({{$accountCat->name}})</span>
                @else
                    Accounts Report
                @endif
            </h3>
            </center>
         </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box light-grey">

                <div class="portlet-title">
                    <?php
                    if ($date1 && $date2) {
                        $date01 = explode('/', $date1);
                        $month1  = $date01[0];
                        $day1 = $date01[1];
                        $year1   = $date01[2];
                        $date1 =$day1.'/'.$month1.'/'.$year1;

                        $date02 = explode('/', $date2);
                        $month2  = $date02[0];
                        $day2 = $date02[1];
                        $year2   = $date02[2];
                        $date2 =$day2.'/'.$month2.'/'.$year2;
                    }
                    ?>
                    <div class="caption"><i class="fa fa-reorder"></i>
                        <?php
                        if ($date1 && $date2) {
                        ?>

                        Date : {{$date1}} to {{$date2}}
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
                    <table class="table table-striped table-bordered table-hover" id="accounts_report_table" cellspacing="0">
                        <thead style="background-color:cadetblue">
                            <tr>
                                <th>Description</th>
                                <th style="text-align: right;">Debit</th>
                                <th style="text-align: right;">Credit</th>
                                <th style="text-align: right;">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $flag = '';
                            $openingBalance = ($balanceIn[0]->balanceIn - $balanceOut[0]->balanceOut) +($currentBalance->opening_balance - ($totalBalanceIn[0]->totalBalanceIn - $totalBalanceOut[0]->totalBalanceOut));
                        ?>
                        <tr class="odd gradeX" >
                            <td>Opening Balance</td>
                            <td></td>
                            <td>
                            </td>
                            <td style="text-align: right;">
                                {{$openingBalance}}
                            </td>

                        </tr>
                        @foreach($results as $result )


                            <tr class="odd gradeX">
                                <td>

                                    @if($result->type != 'Receive')
                                        <?php
                                        $purchase = \App\PurchaseInvoice::where('invoice_id','=',$result->invoice_id)->first();
                                        if ($purchase)
                                        $party = \App\Party::find($purchase->party_id);
                                        ?>
                                            @if($result->cheque_no)
                                                Cash Paid To {{$party->name}} {{ $result->remarks ? '('.$result->remarks.')'  : ''}}
                                            @else
                                                Paid by cheque no {{$result->cheque_no}} To {{$party->name}} {{ $result->remarks ? '('.$result->remarks.')'  : ''}}
                                            @endif
                                    @else
                                        <?php
                                        $sale = \App\Sale::where('invoice_id','=',$result->invoice_id)->first();
                                        if($sale->party_id)
                                        $party = \App\Party::find($sale->party_id);
                                        ?>
                                            @if($result->cheque_no)
                                                Received by cheque no {{$result->cheque_no}} from {{$sale->party_id ? $party->name: $sale->cash_sale}} {{ $result->remarks ? '('.$result->remarks.')' : ''}}
                                            @else
                                                Cash received from {{$sale->party_id ? $party->name: $sale->cash_sale}} {{ $result->remarks ? '('.$result->remarks.')' : ''}}
                                            @endif
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    @if($result->type != 'Receive')
                                        {{$result->amount}}
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    @if($result->type == 'Receive')
                                        {{$result->amount}}
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    @if($result->type != 'Receive')
                                        @if($flag == '')
                                            {{$openingBalance - $result->amount}}
                                        @else
                                            {{$balance - $result->amount}}
                                        @endif
                                    @else
                                        @if($flag == '')
                                            {{$openingBalance + $result->amount}}
                                        @else
                                            {{$balance + $result->amount}}
                                        @endif

                                    @endif

                                </td>

                            </tr>
                            <?php
                                if($result->type != 'Receive'){
                                    if($flag == ''){
                                        $balance  = $openingBalance - $result->amount;
                                    }else{
                                        $balance  = $balance - $result->amount;
                                    }
                                } else{
                                    if($flag == ''){
                                        $balance  = $openingBalance + $result->amount;
                                    }else{
                                        $balance  = $balance + $result->amount;
                                    }
                                }

                            $flag = 'value';
                            //$total = $total + ($result->amount);
                            ?>
                        @endforeach
                        {{--<tr>
                            <td><b>Grand Total</b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>--}}

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

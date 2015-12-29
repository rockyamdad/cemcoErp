@extends('baseLayout')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" />

@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
             Accounts  Report
            </h3>

         </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <div class="portlet box light-grey">
                <?php
                $account = \App\NameOfAccount::find($account_id);
                $accountCat = \App\AccountCategory::find($account->account_category_id);
                ?>
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-reorder"></i>Accounts Report  for <span style="color: black"> {{$account->name}} ({{$accountCat->name}})</span></div>

                     <div class="actions">
                         <a class="btn btn-sm blue hidden-print" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
                       </div>

                </div>

                <div class="portlet-body">

                    <table class="table table-striped table-bordered table-hover" id="accounts_report_table" cellspacing="0">
                        <thead style="background-color:cadetblue">
                            <tr>
                                <th>Txn Date</th>
                                <th>Txn Type</th>
                                <th>Cheque</th>
                                <th>Description</th>
                                <th>Withdrawal</th>
                                <th>Deposit</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $flag = '';
                            $openingBalance = ($balanceIn[0]->balanceIn - $balanceOut[0]->balanceOut) +($currentBalance->opening_balance - ($totalBalanceIn[0]->totalBalanceIn - $totalBalanceOut[0]->totalBalanceOut));
                        ?>
                        <tr class="odd gradeX" >
                            <td>Opening Balance</td>
                            <td>

                            </td>
                            <td>

                            </td>
                            <td></td>

                            <td>

                            </td>
                            <td>

                            </td>
                            <td>
                                {{$openingBalance}}
                            </td>

                        </tr>
                        @foreach($results as $result )

                            <tr class="odd gradeX">
                                <td>{{$result->date}}</td>
                                <td>
                                    @if($result->type != 'Receive')
                                        Withdrawal from account
                                    @else
                                        Deposit to account
                                    @endif
                                </td>
                                <td>
                                    @if($result->cheque_no)
                                        Yes-{{$result->cheque_no}}
                                    @endif
                                </td>
                                <td>{{$result->remarks}}</td>

                                <td>
                                    @if($result->type != 'Receive')
                                        {{$result->amount}}
                                    @endif
                                </td>
                                <td>
                                    @if($result->type == 'Receive')
                                        {{$result->amount}}
                                    @endif
                                </td>
                                <td>
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
                        <tr>
                            <td><b>Grand Total</b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
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

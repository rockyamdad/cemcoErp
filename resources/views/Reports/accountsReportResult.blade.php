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
                ?>
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-reorder"></i>Accounts Report  for  {{$account->name}}</div>

                     <div class="actions">
                         <a class="btn btn-sm blue hidden-print" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
                       </div>

                </div>

                <div class="portlet-body">

                    <table class="table table-striped table-bordered table-hover" id="accounts_report_table">
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
                        $total = 0;
                        ?>

                        @foreach($results as $result )

                            <tr class="odd gradeX">
                                <td>{{$result->date}}</td>
                                <td>
                                    @if($result->type == 'Payment')
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
                                    @if($result->type == 'Payment')
                                        {{$result->amount}}
                                    @endif
                                </td>
                                <td>
                                    @if($result->type != 'Payment')
                                        {{$result->amount}}
                                    @endif
                                </td>
                                <td></td>

                            </tr>
                            <?php
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

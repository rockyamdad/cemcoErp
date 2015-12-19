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
             Balance Transfer  Report
            </h3>

         </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <div class="portlet box light-grey">

                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-reorder"></i> Balance Transfer Report </div>

                     <div class="actions">
                         <a class="btn btn-sm blue hidden-print" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
                       </div>

                </div>

                <div class="portlet-body">

                    <table class="table table-striped table-bordered table-hover" id="expense_products_report_table">
                        <thead style="background-color:cadetblue">
                        <tr>
                            <th>From Account</th>
                            <th>To  Account</th>
                            <th>Amount</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total = 0;
                        ?>

                        @foreach($results as $result )
                            <?php
                                $reports = new \App\Report();
                                $results2 = $reports->getBalanceTransferForFromAccount($result->fromAccount);
                                $fromAccount = \App\NameOfAccount::find($result->fromAccount);
                            ?>

                            @foreach($results2 as $result2 )
                                <?php
                                $reports = new \App\Report();
                                $results3 = $reports->getBalanceTransferForToAccount($result->fromAccount, $result2->toAccount);
                                $remainingAmount = $result2->fromAmount - $results3[0]->toAmount;
                                $toAccount = \App\NameOfAccount::find($result2->toAccount);
                                ?>
                                @if($remainingAmount >= 0)
                                    <tr>
                                        <td>{{$fromAccount->name}}</td>
                                        <td>{{$toAccount->name}}</td>
                                        <td>{{$remainingAmount}}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{$toAccount->name}}</td>
                                        <td>{{$fromAccount->name}}</td>
                                        <td>{{-$remainingAmount}}</td>
                                    </tr>
                                @endif
                            @endforeach
                            <?php
                           // $total = $total + ($result->amount);
                            ?>
                        @endforeach
                        <tr>
                            <td><b>Grand Total</b></td>
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

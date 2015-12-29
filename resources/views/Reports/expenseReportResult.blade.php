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
             Expense  Report
            </h3>

         </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <div class="portlet box light-grey">
                <?php
                $branches = \App\Branch::find($branch_id);
                ?>
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
                    <div class="caption"><i class="fa fa-reorder"></i>Branch Name: {{$branches->name}} for {{$date001}} to {{$date002}}</div>

                     <div class="actions">
                         <a class="btn btn-sm blue hidden-print" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
                       </div>

                </div>

                <div class="portlet-body">

                    <table class="table table-striped table-bordered table-hover" id="expense_products_report_table">
                        <thead style="background-color:cadetblue">
                        <tr>
                            <th>Date</th>
                            <th>Invoice No</th>
                            <th>Category</th>
                            <th>Purpose</th>
                            <th>Particular</th>
                            <th>Amount</th>
                            <th>Remarks</th>
                            <th>Created By</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total = 0;
                        ?>

                        @foreach($results as $result )
                            <?php

                              $user = \App\User::find($result->user_id);
                            ?>

                            <tr class="odd gradeX">
                                <td>{{$result->date}}</td>
                                <td>{{$result->invoice}}</td>
                                <td>{{$result->category}}</td>
                                <td>{{$result->purpose}}</td>
                                <td>{{$result->particular}}</td>
                                <td>{{$result->amount}}</td>
                                <td>{{$result->remarks}}</td>
                                <td>{{$user->name}}</td>

                            </tr>
                            <?php
                            $total = $total + ($result->amount);
                            ?>
                        @endforeach
                        <tr>
                            <td><b>Grand Total</b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{$total}}</td>
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

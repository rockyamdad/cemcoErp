@extends('baseLayout')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" />
@stop
@section('content')


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Voucher Section
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{URL::to('dashboard/')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>

            <li><a href="{{URL::to('sales/index')}}"> Voucher List</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>

<div class="row">

    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box light-grey">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-globe"></i>Vouchers</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div style="float: left;width: 80%; margin-left: 20px">
                @if (Session::has('message'))
                <div class="alert alert-success">
                    <button data-close="alert" class="close"></button>
                    {{ Session::get('message') }}
                </div>
                @endif
                    @if (Session::has('error'))
                        <div class="alert alert-danger">
                            <button data-close="alert" class="close"></button>
                            {{ Session::get('error') }}
                        </div>
                    @endif
            </div>

            <div class="portlet-body">

                <div class="table-toolbar">
                    <div class="btn-group">
                        <a class="btn green" href="{{ URL::to('sales/index') }}">Sales&nbsp;&nbsp;</a>
                    </div>

                </div>
                {!!Form::open(array('action'=>'SaleController@postVoucher','method' => 'post', 'class'=>'form-horizontal'
                ))!!}
                <div class="form-group">

                    <div class="col-md-3">
                        {!!Form::select('party_id',[null=>'Please Select Party'] +$buyersAll,'null', array('class'=>'form-control ','id'=>'party') )!!}
                    </div>

                        <div class="col-md-3">
                            <div class="input-append date input-icon" data-date="12-02-2012" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                <i class="fa fa-calendar"></i>
                                {!!Form::text('from_date',null,array('size'=>'8','class' =>
                                'form-control m-wrap m-ctrl-medium date-picker','placeholder' => 'From'))!!}
                                <span class="add-on"><i class="icon-calendar"></i></span>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-append date input-icon" data-date="12-02-2012" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                <i class="fa fa-calendar"></i>
                                {!!Form::text('to_date',null,array('size'=>'8','class' =>'form-control m-wrap m-ctrl-medium date-picker','placeholder' => 'To'))!!}
                                <span class="add-on"><i class="icon-calendar"></i></span>

                            </div>
                        </div>

                    <div class=" fluid">
                        <div class=" col-md-2">
                            <button type="submit" class="btn blue btn-block " >SEARCH <i class="m-icon-swapright m-icon-white"></i></button>
                        </div>
                    </div>

                </div>

                {!!Form::close()!!}

                <table class="table table-striped table-bordered table-hover" id="vouchertable">
                    <thead  style="background-color: #557386">
                    <tr>
                        <th>SL</th>
                        <th>Voucher Id</th>
                        <th>Party Name</th>
                        <th>Cash Sale</th>
                        <th>Created Date</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $sl=1;
                    ?>
                    @if(count($vouchers)>0)
                        @foreach($vouchers as $voucher )
                            <?php
                            $party = \App\Party::find($voucher->party);
                            ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $sl; ?></td>
                                    <td>{{$voucher->voucher}}</td>
                                    <td>@if($party)
                                           {{$party->name}}
                                        @endif
                                    </td>
                                    <td>@if($voucher->cash_sale)
                                            {{$voucher->cash_sale}}
                                        @endif
                                    </td>
                                    <td>{{$voucher->date}}
                                    </td>
                                    <td>{{$voucher->total}}</td>
                                    <td> <a class="btn green" href="{{URL::to('sales/vouchershow/'.$voucher->voucher)}}">Voucher</a></td>

                                </tr>
                                <?php
                                $sl++;
                                ?>
                        @endforeach
                    @endif
                    </tbody>
                </table>

            </div>
        </div>
        @if(count($vouchers)>0)
            <center>
            <div class="actions">
                <a class="btn blue" href="/sales">Back</a>
                {{--   <a class="btn dark" href="">Print</a>--}}
            </div>
            </center>
        @endif


        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
@stop
@section('javascript')
{!! HTML::script('js/sales.js') !!}
{!! HTML::script('js/partilizer.js') !!}
{!! HTML::script('js/report.js') !!}
{!! HTML::script('assets/plugins/select2/select2.min.js') !!}
{!! HTML::script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
@stop

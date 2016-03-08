@extends('baseLayout')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}"/>

@stop
@section('content')
        <div class="row">

            <div class="col-xs-2 invoice-block" style="margin-left: 880px;">
                <a class="btn btn-sm blue hidden-print" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
                <a class="btn btn-sm blue hidden-print" href="{{\Illuminate\Support\Facades\URL::to('sales/showinvoice2/'.$invoiceId)}}">Print Details <i class="fa fa-print"></i></a>
            </div>
        </div>
    <div class="invoice">
        <div class="row invoice-logo">
            <div class="col-xs-12 invoice-logo-space"><img src="../../assets/img/SUNSHINE TRADING CORPORATION.jpg" height="80"  alt="" />
           </div>
            <div class="col-xs-12 invoice-logo-space">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Kamal Chamber (2nd Floor), 61 Jubilee Road, Chittagong<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tel 031 2865220, 2853063, Email: cemcogroupbd@gmail.com<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Website: www.cemcogroupbd.com</b>
            </div>
            <hr />
            {{--<div class="col-xs-6">
               <p># {{$sale->invoice_id}} <span class="muted">--{{$sale->created_at}}</span></p>
            </div>--}}
        </div>

        <div class="row">

            <div class="col-xs-4">
               {{-- <h4>Client:</h4>--}}
                <?php
                    $party = \App\Party::find($sale->party_id);
                    ?>

                <table>
                <tr>
                    <td><b>Customer</b></td>
                    <td>: {{$party->name}}</td>
                </tr>
                <tr>
                    <td><b>Address</b></td>
                    <td>: {{$party->address}}</td>
                </tr>
                <tr>
                    <td><b>Contact</b></td>
                    <td>: {{$party->phone}}</td>
                </tr>
                </table>
            </div>
            <div class="col-xs-4">

            </div>
            <div class="col-xs-4 invoice-payment">
                <table>
                <tr>
                    <td><b>Invoice #</b></td>
                    <td>: {{$sale->invoice_id}}</td>
                </tr>
                <tr>
                    <td><b>Date</b></td>
                    <td>: {{date("d-m-Y")}}</td>
                </tr>
                </table>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-xs-12">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th class="hidden-480">Description</th>
                        <th style="text-align: right;" class="hidden-480">Quantity</th>
                        <th style="text-align: right;" class="hidden-480">Unit Price</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    $total = 0;
                    ?>
                    @foreach($saleDetails as $saleDetail )
                        <?php
                                $products = \App\Product::find($saleDetail->product_id);
                                ?>
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$products->name}}</td>
                            <td>{{$saleDetail->remarks}}</td>
                            <td style="text-align: right;">{{$saleDetail->quantity}}</td>
                            <td style="text-align: right;">{{$saleDetail->price}}</td>
                            <td style="text-align: right;">{{$saleDetail->price * $saleDetail->quantity}}</td>
                        </tr>
                        <?php
                        $total = $total + ($saleDetail->price * $saleDetail->quantity);
                        $i++;
                        ?>
                    @endforeach
                        <tr>
                            <td>Grand Total:</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align: right;">{{$total}}</td>
                        </tr>
                    </tbody>
                </table>


                <div class="row">

                    <div class="col-xs-12">
                        <b>Remarks:</b><br>
                        <div id="remrks"></div>
                        <div id="remrksForm">
                            <textarea class="col-xs-6" id="remIn">1. PAYMENT MUST BE MAID WITHIN 15 DAYS BY CHEQUE OR CASH
2. NO REPLACEMENT WARANTY
                        </textarea>
                            <button class="btn btn-danger" id="confirmRemarks">Confirm</button>
                        </div>

                    </div>



                </div>
                <br><br>
                <div class="row">

                    <div style="text-decoration: underline" class="col-xs-4">
                        <center>
                            <b>Received By</b>
                        </center>
                    </div>
                    <div class="col-xs-4">

                    </div>
                    <div style="text-decoration: underline" class="col-xs-4 invoice-payment">
                        <center>
                            <b>Delivered By</b>
                        </center>
                    </div>

                </div>


            </div>
        </div>
        <br>
        <br>

    </div>


@stop
@section('javascript')
    {!! HTML::script('js/sales.js') !!}
    {!! HTML::script('assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js') !!}
    {!! HTML::script('assets/plugins/select2/select2.min.js') !!}
@stop



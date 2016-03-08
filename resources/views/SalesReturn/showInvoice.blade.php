@extends('baseLayout')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}"/>

@stop
@section('content')
    <div class="row">

        <div class="col-xs-2 invoice-block" style="margin-left: 880px;">
            <a class="btn btn-sm blue hidden-print" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
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

            <div class="col-xs-8">

            </div>
            <div class="col-xs-4 invoice-payment">
                <table>
                    <tr>
                        <td><b>Invoice #</b></td>
                        <td>: {{$srInvoice->invoice_id}}</td>
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
                        <th>Product Type</th>
                        <th>Product</th>
                        <th style="text-align: right;" class="hidden-480">Quantity</th>
                        <th style="text-align: right;" class="hidden-480">Unit Price</th>
                        <th style="text-align: right;" class="hidden-480">Discount</th>
                        <th style="text-align: right;" class="hidden-480">Return Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    $total = 0;
                    ?>
                    @foreach($srDetails as $row )
                        <?php
                        $products = \App\Product::find($row->product_id);
                        ?>
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$products->product_type}}</td>
                            <td>{{$products->name}}</td>
                            {{--<td>{{$saleDetail->remarks}}</td>--}}
                            <td style="text-align: right;">{{$row->quantity}}</td>
                            <td style="text-align: right;">{{$row->unit_price}}</td>
                            <td style="text-align: right;">{{$srInvoice->discount_percentage}}%</td>
                            <td style="text-align: right;">{{$row->return_amount}}</td>
                        </tr>
                        <?php
                        $total = $total + ($row->return_amount);
                        $i++;
                        ?>
                    @endforeach
                    <tr>
                        <td>Grand Total:</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right;">{{$total}}</td>
                    </tr>
                    </tbody>
                </table>
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



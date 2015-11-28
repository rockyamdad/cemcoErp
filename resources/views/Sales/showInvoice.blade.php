@extends('baseLayout')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}"/>

@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
                Sales  Invoice
            </h3>
            <ul class="page-breadcrumb breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="{{URL::to('dashboard')}}">Home</a>
                    <i class="fa fa-angle-right"></i>
                </li>

                <li><a href="#">Sales Invoice</a></li>
                <a class="btn blue btn-sm" style="margin-left: 747px;" href="/sales/index">Back</a>

            </ul>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>

    </div>
    <div class="invoice">
        <div class="row invoice-logo">
            <div class="col-xs-6 invoice-logo-space"><img src="../../assets/img/logo.png" alt="" /> </div>
            <div class="col-xs-6">
               <p># {{$sale->invoice_id}} <span class="muted">--{{$sale->created_at}}</span></p>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-xs-4">
                <h4>Client:</h4>
                <?php
                    $party = \App\Party::find($sale->party_id);
                    ?>
                <ul class="list-unstyled">
                    <li>{{$party->name}}</li>
                    <li>{{$party->contact_person_name}}</li>
                    <li>{{$party->phone}}</li>
                    <li>{{$party->email}}</li>
                    <li>{{$party->address}}</li>
                </ul>
            </div>
            <div class="col-xs-4">

            </div>
            <div class="col-xs-4 invoice-payment">
                <h4>Payment Details:</h4>
                <ul class="list-unstyled">
                    <li><strong>Invoice #:</strong> {{$sale->invoice_id}}</li>
                    <li><strong>Account Name:</strong> {{$party->name}}</li>
                </ul>
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
                        <th class="hidden-480">Quantity</th>
                        <th class="hidden-480">Unit Cost</th>
                        <th>Total</th>
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
                            <td>{{$saleDetail->quantity}}</td>
                            <td>{{$saleDetail->price}}</td>
                            <td>{{$saleDetail->price * $saleDetail->quantity}}</td>
                        </tr>
                        <?php
                        $total = $total + ($saleDetail->price * $saleDetail->quantity);
                        $i++;
                        ?>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
{{--            <div class="col-xs-8">
                <div class="well">
                    <address>
                        <strong>Loop, Inc.</strong><br />
                        795 Park Ave, Suite 120<br />
                        San Francisco, CA 94107<br />
                        <abbr title="Phone">P:</abbr> (234) 145-1810
                    </address>
                    <address>
                        <strong>Full Name</strong><br />
                        <a href="mailto:#">first.last@email.com</a>
                    </address>
                </div>
            </div>--}}
            <div class="col-xs-2 invoice-block" style="margin-left: 880px;">
                <ul class="list-unstyled amounts">
                   {{-- <li><strong>Sub - Total amount:</strong> $9265</li>
                    <li><strong>Discount:</strong> 12.9%</li>
                    <li><strong>VAT:</strong> -----</li>--}}
                    <li><strong>Grand Total:</strong> ${{$total}}</li>
                </ul>
                <br />
                <a class="btn btn-sm blue hidden-print" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
            </div>
        </div>
    </div>


@stop
@section('javascript')
    {!! HTML::script('js/sales.js') !!}
    {!! HTML::script('assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js') !!}
    {!! HTML::script('assets/plugins/select2/select2.min.js') !!}
@stop



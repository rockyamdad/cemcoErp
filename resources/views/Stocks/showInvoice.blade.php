@extends('baseLayout')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}"/>

@stop
@section('content')
    <div class="row">

        <div class="col-xs-2 invoice-block" style="margin-left: 880px;">
            <a class="btn btn-sm btn-success" href="{{ URL::to('stocks/index') }}">Back</a>
            <a class="btn btn-sm blue hidden-print" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
        </div>
    </div>
    <div class="invoice">
        <div class="row invoice-logo">
            <div class="col-md-12 invoice-logo-space">
                <?php
                $branch = \App\Branch::find($stockDetails[0]->branch_id);
                ?>

                <div class="col-md-8 branchInfo" >
                    <h2>{{$branch->name}}</h2>
                    <p1>{{$branch->location}}</p1>
                </div>
                <div  class="col-md-4 companyLogo">
                    <img width="150px" src="../../assets/img/cemco.jpg"  alt="" />
                </div>


            </div>

            <hr> <br>
            {{--<div class="col-xs-6">
               <p># {{$sale->invoice_id}} <span class="muted">--{{$sale->created_at}}</span></p>
            </div>--}}
        </div> <br><br>

        <div class="row" style="margin-top: -10px;">

            <div class="col-xs-8">
                <table>

                        <?php foreach($stockDetails as $stockDetail ){ ?>
                            <tr>
                                <td><b>Entry Type</b></td>
                                <td>: {{$stockDetail->entry_type}}</td>
                            </tr>
                            <?php if ($stockDetail->entry_type == 'Transfer') { $stockName = \App\StockInfo::find($stockDetail->to_stock_info_id); ?>
                            <tr>
                                <td><b>Transfer To</b></td>
                                <td>: {{$stockName->name}}</td>
                            </tr>
                            <?php } ?>
                            <?php break; ?>

                        <?php } ?>

                    <tr>
                        <td><b>User: </b></td>
                        <?php
                        $userid=Session::get('user_id');
                        $uerInfo = App\User::find($userid);
                        ?>
                        <td>{{$uerInfo->name}}</td>
                    </tr>
                </table>

            </div>
            <div class="col-xs-4 invoice-payment">
                <table>
                    <tr>
                        <td><b>Invoice #</b></td>
                        <td>: {{$stock->invoice_id}}</td>
                    </tr>
                    <tr>
                        <td><b>Date</b></td>
                        <td>: {{date("d-m-Y")}}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th style="text-align: right;" class="hidden-480">Quantity</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    $total = 0;
                    ?>
                    @foreach($stockDetails as $stockDetail )
                        <?php
                        $categoryName = \App\Category::find($stockDetail->product->category_id);
                        $subCategoryName = \App\SubCategory::find($stockDetail->product->sub_category_id);
                        $products = \App\Product::find($stockDetail->product_id);
                        ?>
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$products->name.' ('.$categoryName->name.')'.' ('.$subCategoryName->name.')'}}</td>
                            {{--<td>{{$saleDetail->remarks}}</td>--}}
                            <td style="text-align: right;">{{$stockDetail->quantity}}</td>
                        </tr>
                        <?php
                        $total = $total + ($stockDetail->quantity);
                        $i++;
                        ?>
                    @endforeach
                    <tr>
                        <td>Grand Total:</td>
                        <td></td>
                        <td style="text-align: right;">{{$total}}</td>
                    </tr>
                    </tbody>
                </table>
                <br>

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


    </div>
    <style>
        table {font-size: 12px;}
        table tr th {font-size: 12px;}
    </style>

@stop
@section('javascript')
    {!! HTML::script('js/sales.js') !!}
    {!! HTML::script('assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js') !!}
    {!! HTML::script('assets/plugins/select2/select2.min.js') !!}
@stop



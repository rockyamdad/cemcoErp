@extends('baseLayout')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Dashboard Section
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{URL::to('dashboard')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>

            <li><a href="{{URL::to('dashboard')}}">dashboard</a></li>
        </ul>
        @if (Session::has('flash_notice'))

            <div id="flash_notice"  class="alert alert-success fade in"> <a href="#" class="close" data-dismiss="alert">&times;</a>{{ Session::get('flash_notice') }}</div>
        @endif
    </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat blue">
                <div class="visual">
                    <i class="fa fa-barcode"></i>
                </div>
                <div class="details">
                    <div class="number">
                       {{$totalProducts}}
                    </div>
                    <div class="desc">
                       Total Products
                    </div>
                </div>
                <a class="more" href="{{URL::to('/products')}}">
                    View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat green">
                <div class="visual">
                    <i class="fa fa-plane"></i>
                </div>
                <div class="details">
                    <div class="number">{{$totalImports[0]->totalImport ? $totalImports[0]->totalImport : 0}}</div>
                    <div class="desc">Total Imports</div>
                </div>
                <a class="more" href="{{URL::to('imports/index')}}">
                    View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat purple">
                <div class="visual">

                </div>
                <div class="details">
                    <div class="number">{{$totalSales[0]->todaySale ? $totalSales[0]->todaySale : 0.00}}&nbsp;Tk</div>
                    <div class="desc">Today's Sales Collection</div>
                </div>
                <a class="more" href="{{URL::to('sales/')}}">
                    View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat yellow">
                <div class="visual">
                </div>
                <div class="details">
                    <div class="number">
                        {{$totalPurchase[0]->todayPurchase ? $totalPurchase[0]->todayPurchase : 0.00}} &nbsp;Tk
                    </div>
                    <div class="desc">Today's Purchase</div>
                </div>
                <a class="more" href="{{URL::to('purchases/')}}">
                    View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>


</div>

<div class="row ">
    <div class="col-md-6 col-sm-6">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-bell-o"></i>Pending Cheque Register</div>

            </div>
            <div class="portlet-body">

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Party Name</th>
                                            <th>Bank Name</th>
                                            <th>Cheque No</th>
                                            <th>Cheque Date</th>
                                            <th>Amount</th>
                                            <th>Received by</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $slNo = 1;
                                        ?>
                                            @foreach($register as $reg)
                                                <?php
                                                    $sale = \App\Sale::where('invoice_id','=',$reg->invoice_id)->first();
                                                    $partyname = \App\Party::find($sale->party_id);
                                                ?>
                                                <tr>
                                                    <td>{{$slNo}}</td>
                                                    <td>{{$partyname->name}}</td>
                                                    <td>{{$reg->cheque_bank}}</td>
                                                    <td>{{$reg->cheque_no}}</td>
                                                    <td>{{$reg->cheque_date}}</td>
                                                    <td>{{$reg->amount}}</td>
                                                    <td>{{$reg->user->username}}</td>
                                                    <td class="party-status"><span class="label label-sm label-danger">Pending</span></td>
                                                    <td>
                                                        <a data-id="" class="btn btn-sm purple changeStatus"
                                                        href="{{ URL::to('chequeregister/complete2/'. $reg->id ) }}"><i
                                                        class="fa fa-check"></i>Complete</a>
                                                    </td>
                                                </tr>
                                                <?php
                                                    $slNo++;
                                                ?>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>

                <div class="scroller-footer">
                    <div class="pull-right">
                        <a href="{{ URL::to('chequeregister/index') }}">See All Records <i class="m-icon-swapright m-icon-gray"></i></a> &nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6">
        <div class="portlet box green tasks-widget">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-money"></i>Latest Tracsections</div>
                <div class="tools">
                    <a href="index.html#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="index.html" class="reload"></a>
                </div>

            </div>
            <div class="portlet-body">
                <div class="task-footer">
								<span class="pull-right">
								<a href="index.html#">See All Tasks <i class="m-icon-swapright m-icon-gray"></i></a> &nbsp;
								</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box purple">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-cogs"></i>Accounts</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Branch Name</th>
                            <th>Account Name</th>
                            <th>Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $slNo = 1;
                        ?>
                            @foreach($accountsBalance as $account)
                                <?php
                                    $branch = \App\Branch::find($account->branch_id);
                                ?>
                                <tr>
                                    <td>{{$slNo}}</td>
                                    <td>{{$branch->name}}</td>
                                    <td>{{$account->name}}</td>
                                    <td>{{$account->opening_balance}}</td>
                                </tr>
                                <?php
                                    $slNo++;
                                ?>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
    <div class="col-md-6">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-cogs"></i>Account Balance Transfer</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>From Account</th>
                            <th>To  Account</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $slNo = 1;
                        ?>

                        @foreach($accountBalanceTransfers as $result )
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
                                        <td>{{$slNo}}</td>
                                        <td>{{$fromAccount->name}}</td>
                                        <td>{{$toAccount->name}}</td>
                                        <td>{{$remainingAmount}}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{$slNo}}</td>
                                        <td>{{$toAccount->name}}</td>
                                        <td>{{$fromAccount->name}}</td>
                                        <td>{{-$remainingAmount}}</td>
                                    </tr>
                                @endif
                            @endforeach
                            <?php
                                $slNo++;
                            ?>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-cogs"></i>Today's Stocks</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Branch Name</th>
                            <th>Total StocktIn</th>
                            <th>Total StocktOut</th>
                            <th>Total StocktTransfer</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $slNo = 1;
                        ?>
                        @foreach($stocksBranch as $stockBranch)
                            <?php
                            $branchs = \App\Branch::find($stockBranch->branch);
                                    $reports = new \App\Report();
                                    $stockIn = $reports->getStockInTotal($stockBranch->branch);
                                    $stockOut = $reports->getStockOutTotal($stockBranch->branch);
                                    $stockTransfer = $reports->getStockTransferTotal($stockBranch->branch);
                            ?>
                            <tr>
                                <td>{{$slNo}}</td>
                                <td>{{$branchs->name}}</td>
                                <td>{{$stockIn[0]->totalStockIn}}</td>
                                <td>{{$stockOut[0]->totalStockOut}}</td>
                                <td>{{$stockTransfer[0]->totalStockTransfer}}</td>
                            </tr>
                            <?php
                            $slNo++;
                            ?>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="scroller-footer">
                    <div class="pull-right">
                        <a href="{{URL::to('stocks/index')}}">See All Records <i class="m-icon-swapright m-icon-gray"></i></a> &nbsp;
                    </div>
                </div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>

    <div class="col-md-6">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box purple">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-cogs"></i>Order Requisition</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Party</th>
                            <th>Req Quantity</th>
                            <th>Issued</th>
                            <th>Remaining</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $slNo = 1;
                        ?>
                        @foreach($stockRequisitions as $requisition)
                            <?php
                                $branchName = \App\Branch::find($requisition->branch_id);
                                $subCategory = \App\SubCategory::find($requisition->product->sub_category_id);
                                $subCategoryName =  '('.$subCategory->name.')';
                            ?>
                            <tr>
                                <td>{{$slNo}}</td>
                                <td>{{$requisition->product->name."(".$requisition->product->category->name.")".$subCategoryName}}</td>
                                <td>{{$requisition->party->name}}</td>
                                <td>{{$requisition->requisition_quantity}}</td>
                                <td>{{$requisition->issued_quantity}}</td>
                                <td>{{$requisition->requisition_quantity-$requisition->issued_quantity}}</td>
                            </tr>
                            <?php
                            $slNo++;
                            ?>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="scroller-footer">
                    <div class="pull-right">
                        <a href="{{URL::to('requisitions/')}}">See All Records <i class="m-icon-swapright m-icon-gray"></i></a> &nbsp;
                    </div>
                </div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>

@stop
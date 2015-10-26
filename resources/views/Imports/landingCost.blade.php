@extends('baseLayout')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Landing Cost Section
        </h3>
        <?php
        use Illuminate\Support\Facades\URL;

        $url = URL::to('imports/landingcostprint/'.$id);
        ?>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{URL::to('dasboard/')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>

            <li><a href="{{URL::to('landingCost')}}">Landing Cost</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>

<div class="row">

    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-globe"></i>LandingCost</div>
                <div class="actions">
                    <a class="btn dark" href="{{ URL::to('imports/index') }}">Back</a>
                    <a class="btn dark" onclick="javascript: window.open('{{$url}}','MsgWindow', 'width=1100,height=500').print();">Print</a>
                </div>
                <!--   <div class="tools">
                       <a href="javascript:;" class="collapse"></a>
                       <a href="javascript:;" class="reload"></a>
                       <a href="javascript:;" class="remove"></a>
                   </div>-->
            </div>
            <div style="float: left;width: 80%; margin-left: 20px">
                @if (Session::has('message'))
                <div class="alert alert-success">
                    <button data-close="alert" class="close"></button>
                    {{ Session::get('message') }}
                </div>
                @endif
            </div>

            <div class="portlet-body no-more-tables">
                <?php $grandTotalCrf = 0; ?>
                @foreach($imports as $importtt )
                <?php $grandTotalCrf = $grandTotalCrf + ($importtt->total_cfr_price * $importtt->quantity); ?>
                @endforeach

                <table class="table-bordered table-striped table-condensed cf" id="landingCost_table">
                    <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Product Name</th>
                        <th>Booking Price ($)</th>
                        <th>Booking Price (taka)</th>
                        <th>CRF Value ($)</th>
                        <th>CRF Value (taka)</th>
                        <th>Order Quantity</th>
                        <th>Duty</th>
                        <th>LandingCost</th>
                        <th>Total Booking Price($)</th>
                        <th>Total CRF Price($)</th>
                        <th>Total Duty</th>
                        <th>Total LandingCost</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    $totalQuantity = 0;
                    $totalBookingPrice = 0;
                    $totalCrfPrice = 0;
                    $totalDuty = 0;
                    $totalLandingCost = 0;
                    ?>
                    @foreach($imports as $importt )
                    <tr class="odd gradeX">
                        <?php
                        $value = ($importt->po_cash * 100) / ($grandTotalCrf * $importt->dollar_to_bd_rate);
                        $duty = ($importt->total_cfr_price * $importt->dollar_to_bd_rate) * $value / 100;


                        $totalQuantity = $totalQuantity + $importt->quantity;
                        $totalBookingPrice = $totalBookingPrice + ($importt->total_booking_price * $importt->quantity);
                        $totalCrfPrice = $totalCrfPrice + ($importt->total_cfr_price * $importt->quantity);
                        $totalDuty = $totalDuty + ($duty * $importt->quantity);

                        $landingCost = (($ttCharge[0]['tt_charge'] + $totalBankCost[0]['total_bank_cost'] + $totalCnfCost[0]['total_cnf_cost']) / $totalQuantity) + $importt->total_booking_price + $duty;

                        $totalLandingCost = $totalLandingCost + ($landingCost * $importt->quantity);
                        $categoryName = \App\Category::find($importt->category_id);
                        $subCategoryName = \App\SubCategory::find($importt->sub_category_id);
                        ?>
                        <td>{{ $i }}</td>
                        <td>{{ $importt->name.'('.$categoryName->name.')'.'('.$subCategoryName->name.')' }}</td>
                        <td>{{ $importt->total_booking_price }}</td>
                        <td>{{ $importt->total_booking_price * $importt->dollar_to_bd_rate }}</td>
                        <td>{{ $importt->total_cfr_price }}</td>
                        <td>{{ $importt->total_cfr_price * $importt->dollar_to_bd_rate }}</td>
                        <td>{{ $importt->quantity }}</td>
                        <td>{{ round($duty,2) }}</td>
                        <td>{{ round($landingCost,2) }}</td>
                        <td>{{ $importt->quantity * $importt->total_booking_price }}</td>
                        <td>{{ $importt->quantity * $importt->total_cfr_price }}</td>
                        <td>{{ round($duty * $importt->quantity,2) }}</td>
                        <td>{{ round($landingCost * $importt->quantity,2)}}</td>

                    </tr>
                    <?php $i++ ?>
                    @endforeach
                    <tr>
                        <td>Grand Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ $totalQuantity }}</td>
                        <td></td>
                        <td></td>
                        <td>{{ $totalBookingPrice }}</td>
                        <td>{{ $totalCrfPrice }}</td>
                        <td>{{ $totalDuty }}</td>
                        <td>{{ round($totalLandingCost,2) }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>

        </div>
        <div>
            <?php $total = $ttCharge[0]['tt_charge'] + $totalBankCost[0]['total_bank_cost'] + $totalCnfCost[0]['total_cnf_cost'] ?>
            <h3>Additional Cost:</h3>
            <strong>Cnf Bill:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$totalCnfCost[0]['total_cnf_cost']}}<br>
            <strong>Bank Cost:</strong>&nbsp;&nbsp;&nbsp;{{$totalBankCost[0]['total_bank_cost']}}<br>
            <strong>Tt Charge:</strong>&nbsp;&nbsp;&nbsp;&nbsp;{{$ttCharge[0]['tt_charge']}}
            <hr>
            <strong>Total:</strong>&nbsp;&nbsp;&nbsp;&nbsp;{{$total}}<br>
            <strong>Miss Cost Per Product:</strong>{{round($total/$totalQuantity,2)}}

        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>


@stop

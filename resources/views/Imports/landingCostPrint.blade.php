<div class="modal-dialog shape">

<style>
th {
    font-size: 80%;
}
td {
    font-size: 80%;
}
table {
    border-collapse: collapse;
}
table.tabFont {
  font-size: 80%;
}
h3{
text-decoration: underline;
}
</style>
<?php
    $crfTotal=0;
    $bookingTotal=0;
?>
@foreach($imports as $importtr )
    <?php
        $crfTotal = $crfTotal + ($importtr->total_cfr_price * $importtr->quantity);
        $bookingTotal = $bookingTotal + ($importtr->total_booking_price * $importtr->quantity);
    ?>
@endforeach
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
          {{--<center> <h2>Landing Cost CemcoGroup</h2></center>--}}
          <div>
          <table class="tabFont">
            <tr>
                <td><strong>Benificiary Name </strong></td>
                <td>:</strong>  {{ $benificiaryName[0]['beneficiary_name'] }}</td>
            </tr>
            <tr>
                <td><strong>L/C No </td>
                <td>:</strong>  {{ $totalBankCost[0]['lc_no'] }}</td>
            </tr>
            <tr>
                <td><strong>Total L/C Value </td>
                <td>: </strong>  {{ $crfTotal }}</td>
            </tr>
            <tr>
                <td><strong>Actual Goods Value </td>
                <td>: </strong>  {{ $bookingTotal }}</td>
            </tr>
            <tr>
                <td><strong>Clearing Agent</td>
                <td>: </strong>  {{ $totalCnfCost[0]['clearing_agent_name'] }}</td>
            </tr>
            <tr>
                <td><strong>Clearing Date</td>
                <td>: </strong>  {{ $totalCnfCost[0]['clearing_agent_name'] }}</td>
            </tr>
          </table>
          </div>
          <br>
        </div>
        <div class="modal-body">
            <div class="portlet-body no-more-tables">
                <?php $grandTotalCrf = 0; ?>
                @foreach($imports as $importtt )
                    <?php $grandTotalCrf = $grandTotalCrf + ($importtt->total_cfr_price * $importtt->quantity); ?>
                @endforeach

                <table border="1" class="table-bordered table-striped table-condensed cf" id="landingCost_table">
                    <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Product Name</th>
                        <th>Booking ($)</th>
                        <th>Booking (taka)</th>
                        <th>CRF ($)</th>
                        <th>CRF (taka)</th>
                        <th>Order Qty</th>
                        <th>Duty</th>
                        <th>Lan. Cost</th>
                        <th>T. Booking ($)</th>
                        <th>T. CRF ($)</th>
                        <th>T. Duty</th>
                        <th>T. Lan. Cost</th>
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
                            ?>
                            <td>{{ $i }}</td>
                            <td>{{ $importt->name }}</td>
                            <td style="text-align: right;">{{ $importt->total_booking_price }}</td>
                            <td style="text-align: right;">{{ $importt->total_booking_price * $importt->dollar_to_bd_rate }}</td>
                            <td style="text-align: right;">{{ $importt->total_cfr_price }}</td>
                            <td style="text-align: right;">{{ $importt->total_cfr_price * $importt->dollar_to_bd_rate }}</td>
                            <td style="text-align: right;">{{ $importt->quantity }}</td>
                            <td style="text-align: right;">{{ $duty }}</td>
                            <td style="text-align: right;">{{ $landingCost }}</td>
                            <td style="text-align: right;">{{ $importt->quantity * $importt->total_booking_price }}</td>
                            <td style="text-align: right;">{{ $importt->quantity * $importt->total_cfr_price }}</td>
                            <td style="text-align: right;">{{ $duty * $importt->quantity }}</td>
                            <td style="text-align: right;">{{ $landingCost * $importt->quantity}}</td>

                        </tr>
                        <?php $i++ ?>
                    @endforeach
                    <tr>
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right;">{{ $totalQuantity }}</td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right;">{{ $totalBookingPrice }}</td>
                        <td style="text-align: right;">{{ $totalCrfPrice }}</td>
                        <td style="text-align: right;">{{ $totalDuty }}</td>
                        <td style="text-align: right;">{{ $totalLandingCost }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div style="font-size: 80%;">
                <?php $total = $ttCharge[0]['tt_charge'] + $totalBankCost[0]['total_bank_cost'] + $totalCnfCost[0]['total_cnf_cost'] ?>
                <h3>Additional Cost:</h3>
                <strong>Cnf Bill:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$totalCnfCost[0]['total_cnf_cost']}}<br>
                <strong>Bank Cost:</strong>&nbsp;&nbsp;&nbsp;{{$totalBankCost[0]['total_bank_cost']}}<br>
                <strong>Tt Charge:</strong>&nbsp;&nbsp;&nbsp;&nbsp;{{$ttCharge[0]['tt_charge']}}
                <hr>
                <strong>Total:</strong>&nbsp;&nbsp;&nbsp;&nbsp;{{$total}}<br>
                <strong>Miss Cost Per Product:</strong>{{$total/$totalQuantity}}

            </div>
                      {{--  <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn">Close</button>
                            <button type="button" class="btn blue"  onClick="window.print()">Print</button>
                        </div>--}}

        </div>
    </div>
</div>

<div class="modal-dialog shape">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h3>Cemco Stocks Products Report</h3>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover" border="1" id="stock_products_report_table">
                <thead style="background-color:cadetblue">
                <tr>

                    <th>Stock Name</th>
                    <th>Product Name</th>
                    <th  style="background-color:blue">Total Quantity On Hand</th>

                </tr>
                </thead>
                <tbody>
                <?php
                $grandTotal = 0;
                ?>

                @foreach($results as $result )
                    <?php
                    $pName = \App\Product::find($result->product_id);
                    $sName = \App\StockInfo::find($result->stock_info_id);
                    $grandTotal = $grandTotal + $result->product_quantity;
                    ?>

                    <tr class="odd gradeX">
                        <td>{{$sName->name}}</td>
                        <td>{{$pName->name}}</td>
                        <td>{{$result->product_quantity}}</td>


                    </tr>

                @endforeach
                <tr>
                    <td>Total</td>
                    <td></td>
                    <td>{{$grandTotal}}</td>
                </tr>

                </tbody>
            </table>

            {{--  <div class="modal-footer">
                  <button type="button" data-dismiss="modal" class="btn">Close</button>
                  <button type="button" class="btn blue"  onClick="window.print()">Print</button>
              </div>--}}

        </div>
    </div>
</div>

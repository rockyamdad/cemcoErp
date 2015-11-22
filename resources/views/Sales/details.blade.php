<div class="modal-dialog shape">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <?php
            $party = new \App\Party();
            $partyName = \App\Party::find($sale->party_id);
                    ?>
            <h3>Sales Detail for {{$partyName->name}}</h3>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover"  id="saleDetailtable">
                <thead style="background-color: #68bbec">
                <tr>
                    <th>Branch Name</th>
                    <th>Stock Name</th>
                    <th>Product Type</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $total = 0; ?>
                @foreach($saleDetails as $saleDetail )
                    <?php
                    $stocks = new \App\StockInfo();
                    $branch = new \App\Branch();
                    $stockName = \App\StockInfo::find($saleDetail->stock_info_id);
                    $branchName = \App\Branch::find($saleDetail->branch_id);
                    ?>
                    <tr class="odd gradeX">
                        <td>{{$branchName->name}}</td>
                        <td>{{$stockName->name}}</td>
                        <td>{{$saleDetail->product_type}}</td>
                        <td>{{$saleDetail->product->name}}</td>
                        <td>{{$saleDetail->price}}</td>
                        <td>{{$saleDetail->quantity}}</td>
                        <td>{{$saleDetail->quantity * $saleDetail->price }}</td>
                        <td>
                            @if($saleDetail->remarks)
                                {{ $saleDetail->remarks }}
                            @else
                                {{"Not Available"}}
                            @endif

                        </td>
                        <td>
                            @if( Session::get('user_role') == "admin")
                                <a class="btn red btn-sm" href="{{ URL::to('/deleteDetail/'.$saleDetail->id)}}"
                                   onclick="return confirm('Are you sure you want to delete this item?');"><i
                                            class="fa fa-trash-o"></i> Delete</a>
                            @endif

                        </td>
                    <?php $total = $total + ($saleDetail->price*$saleDetail->quantity); ?>

                    </tr>
                @endforeach
                <tr style="background-color:#b2b2b2;">
                   <td>Total Amount</td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td> {{ $total }}</td>
                </tr>

                </tbody>
            </table>
            <br>
            <h3>Sales Transaction</h3>
            <table class="table table-striped table-bordered table-hover"  id="saleTransactiontable">
                <thead style="background-color:darkslateblue">
                <tr>
                    <th class="table-checkbox"><input type="checkbox" class="group-checkable"
                                                      data-set="#user_table .checkboxes"/></th>
                    <th>Account Category</th>
                    <th>Account Name</th>
                    <th>Payment Method</th>
                    <th>Cheque No</th>
                    <th>Amount</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $totalTransaction = 0; ?>
                @foreach($saleTransactions as $saleTransaction )
                    <tr class="odd gradeX">
                        <td><input type="checkbox" class="checkboxes" value="1"/></td>
                        <td>{{$saleTransaction->accountCategory->name}}</td>
                        <td>{{$saleTransaction->accountName->name}}</td>
                        <td>{{$saleTransaction->payment_method}}</td>
                        <td>
                            @if($saleTransaction->cheque_no)
                                {{ $saleTransaction->cheque_no }}
                            @else
                                {{"Not Available"}}
                            @endif
                          </td>
                        <td>{{$saleTransaction->amount}}</td>
                        <td>
                            @if($saleTransaction->remarks)
                                {{ $saleTransaction->remarks }}
                            @else
                                {{"Not Available"}}
                            @endif

                        </td>
                        <td>
                            @if( Session::get('user_role') == "admin")
                                <input type="button"  id="deleteSaleTransaction" style="width:70px;" value="delete" data-ref="{{$saleTransaction->account_name_id}}"   class="btn red deleteSaleTransaction" rel={{$saleTransaction->id}}  />
                            @endif

                        </td>
                        <?php $totalTransaction = $totalTransaction + $saleTransaction->amount; ?>
                    </tr>
                @endforeach
                <tr style="background-color:#b2b2b2">
                    <td></td>
                    <td>Total Amount</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td> {{ $totalTransaction }}</td>
                </tr>
                </tbody>
            </table>
            <?php $result = $total-$totalTransaction;?>
            @if($total == $totalTransaction)
                <h4 style="color: green;margin-left: 260px;">You don't have any due</h4>
            @else
                <h4 style="color: red ;margin-left: 260px;">You have {{$result}} taka Due </h4>
            @endif
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn">Close</button>
            </div>

        </div>
    </div>
</div>

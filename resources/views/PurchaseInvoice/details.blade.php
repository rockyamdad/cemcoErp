<div class="modal-dialog shape">
    <div class="modal-content">
        <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>

            <h3>Purchase Invoice Detail</h3>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover"  id="PurchaseDetailtable">
                <thead style="background-color: #68bbec">
                <tr>
                    <th class="table-checkbox"><input type="checkbox" class="group-checkable"
                                                      data-set="#user_table .checkboxes"/></th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $total = 0; ?>
                @foreach($purchaseInvoiceDetails as $purchaseInvoiceDetail )
                    <tr class="odd gradeX">
                        <td><input type="checkbox" class="checkboxes" value="1"/></td>
                        <td>{{$purchaseInvoiceDetail->product->name}}</td>
                        <td>{{$purchaseInvoiceDetail->price}}</td>
                        <td>{{$purchaseInvoiceDetail->quantity}}</td>
                        <td>
                            @if($purchaseInvoiceDetail->remarks)
                                {{ $purchaseInvoiceDetail->remarks }}
                            @else
                                {{"Not Available"}}
                            @endif

                        </td>
                        <td>
                            @if( Session::get('user_role') == "admin")
                                <input type="button"  id="deletePurchaseDetail" style="width:127px;" value="delete"  onclick="return confirm('Are you sure you want to delete this item?');" class="btn red deletePurchaseDetail" rel={{$purchaseInvoiceDetail->id}}  />
                            @endif

                        </td>
                    <?php $total = $total + ($purchaseInvoiceDetail->price * $purchaseInvoiceDetail->quantity); ?>
                    </tr>
                @endforeach
                <tr style="background-color:#b2b2b2">
                    <td></td>
                    <td>Total Amount</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td> {{ $total }}</td>
                </tr>
                </tbody>
            </table>
            <br>
            <h3>Purchase Invoice Transaction</h3>
            <table class="table table-striped table-bordered table-hover"  id="PurchaseTransactiontable">
                <thead style="background-color:lightskyblue">
                <tr>
                    <th class="table-checkbox"><input type="checkbox" class="group-checkable"
                                                      data-set="#user_table .checkboxes"/></th>
                    <th>Account Category</th>
                    <th>Account Name</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $totalTransaction = 0; ?>
                @foreach($purchaseInvoiceTransactions as $purchaseInvoiceTransaction )
                    <tr class="odd gradeX">
                        <td><input type="checkbox" class="checkboxes" value="1"/></td>
                        <td>{{$purchaseInvoiceTransaction->accountCategory->name}}</td>
                        <td>{{$purchaseInvoiceTransaction->accountName->name}}</td>
                        <td>{{$purchaseInvoiceTransaction->payment_method}}</td>
                        <td>{{$purchaseInvoiceTransaction->amount}}</td>
                        <td>
                            @if($purchaseInvoiceTransaction->remarks)
                                {{ $purchaseInvoiceTransaction->remarks }}
                            @else
                                {{"Not Available"}}
                            @endif

                        </td>
                        <td>
                            @if( Session::get('user_role') == "admin")
                                <input type="button"  id="deletePurchaseTransaction" style="width:127px;" value="delete"  onclick="return confirm('Are you sure you want to delete this item?');" class="btn red deletePurchaseTransaction" rel={{$purchaseInvoiceTransaction->id}}  />
                            @endif

                        </td>
                        <?php $totalTransaction = $totalTransaction + $purchaseInvoiceTransaction->amount; ?>
                    </tr>
                @endforeach

                <tr style="background-color:#b2b2b2">
                    <td></td>
                    <td>Total Amount</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td> {{ $totalTransaction }}</td>
                </tr>

                </tbody>
            </table>
            <?php $result = $total-$totalTransaction;?>
            @if($result <=0)
                <h4 style="color: green;margin-left: 260px;">You don't have any due</h4>
            @else
                <h4 style="color: red ;margin-left: 260px;">You have {{$result}} taka Due </h4>
            @endif

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn">Close</button>
                <button type="button" class="btn blue">Save changes</button>
            </div>

        </div>
    </div>
</div>

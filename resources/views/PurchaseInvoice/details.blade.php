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

                    </tr>
                @endforeach

                </tbody>
            </table>
            <br>
            <h3>Purchase Invoice Transaction</h3>
            <table class="table table-striped table-bordered table-hover"  id="PurchaseTransactiontable">
                <thead style="background-color:darkslateblue">
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

                    </tr>
                @endforeach

                </tbody>
            </table>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn">Close</button>
                <button type="button" class="btn blue">Save changes</button>
            </div>

        </div>
    </div>
</div>

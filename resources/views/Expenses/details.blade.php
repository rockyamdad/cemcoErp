<div class="modal-dialog shape">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h3>Expense Transaction</h3>
        </div>
        <div class="modal-body">

            <table class="table table-striped table-bordered table-hover"  id="expenseTransactiontable">
                <thead style="background-color:dodgerblue">
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
                @foreach($expenseTransactions as $expenseTransaction )
                    <tr class="odd gradeX">
                        <td><input type="checkbox" class="checkboxes" value="1"/></td>
                        <td>{{$expenseTransaction->accountCategory->name}}</td>
                        <td>{{$expenseTransaction->accountName->name}}</td>
                        <td>{{$expenseTransaction->payment_method}}</td>
                        <td>{{$expenseTransaction->amount}}</td>
                        <td>
                            @if($expenseTransaction->remarks)
                                {{ $expenseTransaction->remarks }}
                            @else
                                {{"Not Available"}}
                            @endif

                        </td>
                        <td>
                            @if( Session::get('user_role') == "admin")

                                <input type="button"  id="deleteExpenseTransaction" style="width:127px;" value="delete"  onclick="return confirm('Are you sure you want to delete this item?');" class="btn red deleteExpenseTransaction" rel={{$expenseTransaction->id}}  />
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

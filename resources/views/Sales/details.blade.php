<div class="modal-dialog sale-modal">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" onclick="closeModal()" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <?php
            $party = new \App\Party();
            $partyName = \App\Party::find($sale->party_id);
                    ?>
            @if($partyName)
                <h3>Sales Detail for {{$partyName->name}}</h3>
            @else
                <h3>Sales Detail for {{$sale->cash_sale}}</h3>
            @endif
            <?php
                $saledetails = \App\SaleDetail::where('invoice_id','=',$sale->invoice_id)->first();
                $branchname= \App\Branch::find($saledetails->branch_id);
            ?>
            <h3>Branch Name : <?php echo $branchname->name; ?></h3>
            <p>Invoice Id : <?php echo $sale->invoice_id; ?></p>
            <p>Date : <?php echo \App\Transaction::convertDate($sale->created_at); ?></p>

        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover"  id="saleDetailtable">
                <thead style="background-color: #68bbec">
                <tr>
                    <th>Stock Name</th>
                    <th>Product Type</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                    <th>Remarks</th>
                    @if( Session::get('user_role') == "admin" && ($sale->is_sale !=1))
                     <th>Action</th>
                        @endif
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

                    $categoryName = \App\Category::find($saleDetail->product->category_id);
                    $subCategory = \App\SubCategory::find($saleDetail->product->sub_category_id);
                            if ($subCategory) {
                                $name = '('.$subCategory->name.')';
                            } else {
                                $name = '';
                            }
                    ?>
                    <tr class="odd gradeX">
                        <td>{{$stockName->name}}</td>
                        <td>{{$saleDetail->product_type}}</td>
                        <td>{{$saleDetail->product->name.'('.$categoryName->name.')'.$name}}</td>
                        <td>{{$saleDetail->price}}</td>
                        <td>{{$saleDetail->quantity}}</td>
                        <?php $amount = $saleDetail->quantity * $saleDetail->price;  ?>
                        <td class="text-right">{{$amount }}</td>
                        <td>
                            @if($saleDetail->remarks)
                                {{ $saleDetail->remarks }}
                            @else
                                {{"Not Available"}}
                            @endif

                        </td>
                        @if( Session::get('user_role') == "admin" && ($sale->is_sale !=1))
                        <td>

                                <a class="btn red btn-sm deleteDetail" rel="{{$saleDetail->id}}"
                                  ><i
                                            class="fa fa-trash-o"></i> Delete</a>

                        </td>
                        @endif
                    <?php $total = $total + ($amount);

                        ?>

                    </tr>
                @endforeach
                <tr style="background-color:#b2b2b2;">
                    <td>Total Amount</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right" >{{ $total }}</td>
                    <td></td>
                    @if( Session::get('user_role') == "admin" && ($sale->is_sale !=1))
                        <td></td>
                    @endif

                </tr>

                <tr style="background-color:#b2b2b2;">
                    <td>Discount Percentage</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td  class="text-right"><input type="text"  id="discount_percentage" class="form-control text-right" onchange="calculate_amount();" value="{{$sale->discount_percentage_per}}"></td>
                    <td></td>
                    @if( Session::get('user_role') == "admin" && ($sale->is_sale !=1))
                        <td></td>
                    @endif

                </tr>

                <tr style="background-color:#b2b2b2;">
                    <td>Discount Amount</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td  class="text-right"><input class="text-right" type="text" readonly id="discount_amount" value="{{$sale->discount_percentage - $sale->discount_special}}" onchange="calculate_grand_amount()"></td>
                    <td></td>
                    @if( Session::get('user_role') == "admin" && ($sale->is_sale !=1))
                        <td></td>
                    @endif

                </tr>

                <tr style="background-color:#b2b2b2;">
                    <td>Special Discount</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td  class="text-right"><input type="text" id="discount_special" class="form-control text-right" onchange="calculate_amount2();"  value="{{$sale->discount_special}}"></td>
                    <td></td>
                    @if( Session::get('user_role') == "admin" && ($sale->is_sale !=1))
                        <td></td>
                    @endif

                </tr>



                <tr style="background-color:#b2b2b2;">
                    <td>Grand Total Amount</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td  id="amount" class="text-right">{{ $total - $sale->discount_percentage }}</td>
                    <td>@if(Session::get('user_role') == "admin" || $sale->is_sale != 1)<a href="#" onclick="save_da();" class="btn btn-success">Save</a>@endif</td>
                    @if( Session::get('user_role') == "admin" && ($sale->is_sale !=1))
                        <td></td>
                    @endif

                </tr>

                </tbody>
            </table>
            @if($sale->is_sale != 1)
            <div class="col-xs-16 remarks-section">
                <b>Remarks:</b><br>
                <div id="remrks"></div>
                <div id="remrksForm">
                    <textarea class="col-xs-16" rows="5" cols="80" id="remIn">
                       {{ $sale->remarks }}
                    </textarea>
                    <button class="btn btn-success" value="{{$sale->id}}" id="confirmRemarks">Confirm</button>
                </div>

            </div>
            @endif
            <p class="remarks-msg" style="color:green"></p>
            <br>
            <h3>Sales Transaction Details</h3>
            <table class="table table-striped table-bordered table-hover"  id="saleTransactiontable">
                <thead style="background-color:darkslateblue">
                <tr>

                    <th>Date</th>
                    <th>Account Category</th>
                    <th>Account Name</th>
                    <th>Payment Method</th>
                    <th>Cheque No</th>
                    <th>Amount</th>
                    <th>Remarks</th>
                    <th style="width: 160px">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $totalTransaction = 0; ?>
                @foreach($saleTransactions as $saleTransaction )
                    <tr class="odd gradeX">

                        <td>{{\App\Transaction::convertDate($saleTransaction->created_at)}}</td>
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
                            {{--<a class="btn green" href="{{URL::to('sales/vouchershow/'.$saleTransaction->voucher_id)}}">Voucher</a>--}}
                            <a class="btn green btn-sm"  href="{{URL::to('sales/voucher/'.$saleTransaction->voucher_id)}}">Voucher</a>
                            @if( Session::get('user_role') == "admin")
                                <input type="button"  id="deleteSaleTransaction" style="width:70px;" value="delete" data-ref="{{$saleTransaction->account_name_id}}"   class="btn red btn-sm deleteSaleTransaction" rel={{$saleTransaction->id}}  />
                            @endif

                        </td>
                        <?php $totalTransaction = $totalTransaction + $saleTransaction->amount; ?>
                    </tr>
                @endforeach
                <tr style="background-color:#b2b2b2">

                    <td>Total Amount</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ $totalTransaction }}</td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
            <?php $result = $total-$totalTransaction;?>
            <div style="color: green;margin-left: 260px; overflow: hidden;">{{$due}}</div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal()" data-dismiss="modal" class="btn">Close</button>
            </div>

        </div>
    </div>
    <script>

        function closeModal() {
            /*$('#sale').modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').hide();*/
            $("#sale").modal('hide').on('hidden.bs.modal');
            $('.modal-backdrop').hide();
        }
        function calculate_amount(){
            var discount_percentage = $('#discount_percentage').val();
            var discount_special = $('#discount_special').val();
            var amount = <?=$total?>;
            var discount_amount = ((amount*discount_percentage)/100);
            amount -= discount_amount;
            amount -= discount_special;
            $('#amount').html(amount);
            $('#discount_amount').val(discount_amount);

        }
        function calculate_amount2(){
            var discount_percentage = $('#discount_percentage').val();
            var discount_special = $('#discount_special').val();
            var amount = <?=$total?>;
            var discount_amount = ((amount*discount_percentage)/100);
            amount -= discount_amount;
            amount -= discount_special;
            $('#amount').html(amount);

        }

        function calculate_grand_amount(){
            var discount_amount = $('#discount_amount').val();
            var amount = <?=$total?>;
            amount -= discount_amount;
            $('#amount').html(amount);

        }

        function save_da(){
            var discount_amount = $('#discount_amount').val();
            var discount_special  = $('#discount_special').val();
            var discount_percentage  = $('#discount_percentage').val();

            $.ajax({
                type: "get",
                url: "savediscount/{{$sale->id}}",
                data:{'data': discount_amount, 'discount_special': discount_special, 'discount_percentage': discount_percentage },
                success: function (html) {
                    alert('Your discount is saved');

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("Status: " + textStatus); alert("Error: " + errorThrown);
                }
            });
        }

        $('#confirmRemarks').live("click", function () {
            var salesId = $(this).val();
            var remarks = $('#remIn').val();
            $.ajax({
                type: "get",
                url: "confirm/"+salesId,
                data:{'data':remarks},
                success: function (html) {
                    var html =[];
                    html.push('Remarks updated');
                    $('.remarks-msg').html(html);
                    $('.remarks-section').hide();

                }
            });
        });
    </script>
</div>

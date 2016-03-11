<?php

$account = \App\NameOfAccount::find($transaction->account_name_id);

?>
{{$account->name}}
<br>
@if($transaction->type == "Receive")
    DV-{{$transaction->id}}
@else
    CV-{{$transaction->id}}
@endif
<br>
{{$transaction->payment_method}}
<br>
@if($transaction->payment_method == "Check")
    chck no: {{$transaction->cheque_no}}
    <br>
    bank: {{$transaction->cheque_bank}}
@endif
<br>
amount: {{$transaction->amount}}
<br>

recieved by: {{$transaction->user->name}}
<br>

@if($transaction->type == "Receive")
    <?php
        $sales = \App\Sale::where('invoice_id', '=' ,$transaction->invoice_id)->first();
    ?>
    Party: {{$sales->party->name}}
@elseif($transaction->type == "Payment")
    <?php
    $sales = \App\PurchaseInvoice::where('invoice_id', '=' ,$transaction->invoice_id)->first();
    ?>
    Party: {{$sales->party->name}}
@endif
<br>
<br>
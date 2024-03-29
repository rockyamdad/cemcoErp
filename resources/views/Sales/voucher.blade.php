<?php
$account = \App\NameOfAccount::find($transactions[0]->account_name_id);
?>

@extends('baseLayout')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}"/>

@stop
@section('content')
    <div class="row">

        <div class="col-xs-2 invoice-block" style="margin-left: 880px;">
            <a class="btn btn-sm btn-success" onclick="javascript:history.back();">Back</a>
            <a class="btn btn-sm blue hidden-print" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
        </div>
    </div>
    <div class="invoice">
        <div class="row invoice-logo">
            <div class="col-md-12 invoice-logo-space">
                <?php
                    $sale22 = \App\SaleDetail::where('invoice_id', '=', $transactions[0]->invoice_id)->first();
                    if ($sale22 == null){
                        $sale22 = \App\PurchaseInvoiceDetail::where('detail_invoice_id', '=', $transactions[0]->invoice_id)->first();
                    }
                    if ($sale22 != null) {
                ?>
           {{--     @if($sale22->branch_id == 1)
                    <img src="../../assets/img/pad/cemon-vc.jpg" style="width: 100%;"  alt="" />
                @elseif($sale22->branch_id == 2)
                    <img src="../../assets/img/pad/fst-vc.jpg" style="width: 100%;"  alt="" />
                @elseif($sale22->branch_id == 3)
                    <img src="../../assets/img/pad/cemon-vc.jpg" style="width: 100%;"  alt="" />
                @elseif($sale22->branch_id == 4)
                    <img src="../../assets/img/pad/cemon-vc.jpg" style="width: 100%;"  alt="" />
                @elseif($sale22->branch_id == 5)
                    <img src="../../assets/img/pad/sun-vc.jpg" style="width: 100%;"  alt="" />
                @elseif($sale22->branch_id == 6)
                    <img src="../../assets/img/pad/olympic-vc.jpg" style="width: 100%;"  alt="" />
                @elseif($sale22->branch_id == 7)
                    <img src="../../assets/img/pad/keyon1.jpg" style="width: 100%;"  alt="" />
                @elseif($sale22->branch_id == 8)
                    <img src="../../assets/img/pad/cemon-vc.jpg" style="width: 100%;"  alt="" />
                @elseif($sale22->branch_id == 9)
                    <img src="../../assets/img/pad/me-vc.jpg" style="width: 100%;"  alt="" />
                @endif--}}
                    <?php
                    $branch = \App\Branch::find($sale22->branch_id);
                    ?>

                    <div class="col-md-8">
                        <h2>{{$branch->name}}</h2>
                        <p1>{{$branch->location}}</p1>
                    </div>
                    <div class="col-md-4">
                        <img src="../../assets/img/cemco.jpg" alt="" />
                    </div>

            </div>
            <?php } ?>
            <hr />
            {{--<div class="col-xs-6">
               <p># {{$sale->invoice_id}} <span class="muted">--{{$sale->created_at}}</span></p>
            </div>--}}
        </div>
        <br>
        <br>
        <table style="width: 100%;" class="col-md-12">
            <tr>
                <td>
                    <table class="text-center">
                        <tr>
                            <td style="background-color: #C0DDEF; border: 1px solid #000;"><?php echo date("d", strtotime($transactions[0]->created_at)); ?></td>
                            <td style="background-color: #C0DDEF; border: 1px solid #000;"><?php echo date("m", strtotime($transactions[0]->created_at)); ?></td>
                            <td style="background-color: #C0DDEF; border: 1px solid #000;"><?php echo date("y", strtotime($transactions[0]->created_at)); ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 5px;">Day</td>
                            <td style="padding: 5px;">Month</td>
                            <td style="padding: 5px;">Year</td>
                        </tr>
                    </table>
                </td>
                <td>Head of A/C: {{$account->name}}</td>
                <td class="pull-right">
                    <table>
                        <tr>
                            <td>Voucher no:
                                @if($transactions[0]->type == "Receive")
                                    {{$transactions[0]->voucher_id}}
                                @else
                                    {{$transactions[0]->voucher_id}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Recieved by:
                            <?php
                                $user = \App\User::find($transactions[0]->user_id);
                                ?>
                                {{$user->name}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>


        <table class="col-md-12"  style="width: 100%;" >

            <tr style="border:1px solid black; background: url('../../assets/img/lightBlueBackground.jpg');">
                <td colspan="2">Received from:
                    @if($transactions[0]->type == "Receive")
                        <?php
                        $party = \App\Party::find($transactions[0]->party);
                        ?>
                        @if($party)
                            {{$party->name}}
                        @else
                            <?php
                                    $sale = \App\Sale::where('invoice_id','=',$transactions[0]->invoice_id)->get();
                            ?>
                            {{$sale[0]->cash_sale}}
                        @endif
                    @elseif($transactions[0]->type == "Payment")
                        <?php
                        $party = \App\Party::find($transactions[0]->party);
                        ?>
                            @if($party)
                                {{$party->name}}
                            @else
                                <?php
                                $sale = \App\PurchaseInvoice::where('invoice_id','=',$transactions[0]->invoice_id)->get();
                                ?>
                                {{$sale[0]->cash_sale}}
                            @endif
                    @endif
                </td>
                <td style="border:1px solid black;" class="text-center">
                    Amount
                </td>
            </tr>
            <tr style="border:1px solid black;">
                <td colspan="2">
                    Being: {{$transactions[0]->remarks}}<br>
                    Payment Mode: <?php if($transactions[0]->payment_method == 'Check') { echo 'Cheque'; } else { echo $transactions[0]->payment_method; }?><?php if($transactions[0]->payment_method == 'Sales Return') { echo ' for invoice - '.$transactions[0]->remarks; } ?>
                    <br>
                    @if($transactions[0]->payment_method == "Check")
                        Cheque no: {{$transactions[0]->cheque_no}}
                        <br>
                        Bank: {{$transactions[0]->cheque_bank}}
                        <br>
                        Cheque Date: {{$transactions[0]->cheque_date}}
                    @endif
                </td>
                <?php $total = 0; ?>

                @foreach($transactions as  $transaction)
                    <?php $total = $total + $transaction->amount; ?>
                 @endforeach
                <td style="border:1px solid black;  background-color: #E4F1F9;"  class="text-center">{{$total}}</td>
            </tr>
            <tr>
                <td>
                    <span class="col-md-3">Amount in words</span>
                    <span class="col-md-8" style="border-bottom:1px dotted black; "><?php echo number_to_word($total); ?> Taka Only</span>
                </td>
                <td style="border:1px solid black; background-color: #E4F1F9;">Total</td>
                <td style="border:1px solid black; background-color: #C0DDEF;"   class="text-center">{{$total}}</td>
            </tr>
        </table>
        <br><br>
        <div class="row">
            <div class="col-xs-8">
            </div>
            <div style="border-top: 1px solid #000;" class="col-xs-4 invoice-payment text-right">
                <center>
                    <b>Authorized By</b>
                </center>
            </div>

        </div>

    </div>
<?php
function number_to_word( $num = '' )
{
    $num    = ( string ) ( ( int ) $num );

    if( ( int ) ( $num ) && ctype_digit( $num ) )
    {
        $words  = array( );

        $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );

        $list1  = array('','one','two','three','four','five','six','seven',
                'eight','nine','ten','eleven','twelve','thirteen','fourteen',
                'fifteen','sixteen','seventeen','eighteen','nineteen');

        $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
                'seventy','eighty','ninety','hundred');

        $list3  = array('','thousand','million','billion','trillion',
                'quadrillion','quintillion','sextillion','septillion',
                'octillion','nonillion','decillion','undecillion',
                'duodecillion','tredecillion','quattuordecillion',
                'quindecillion','sexdecillion','septendecillion',
                'octodecillion','novemdecillion','vigintillion');

        $num_length = strlen( $num );
        $levels = ( int ) ( ( $num_length + 2 ) / 3 );
        $max_length = $levels * 3;
        $num    = substr( '00'.$num , -$max_length );
        $num_levels = str_split( $num , 3 );

        foreach( $num_levels as $num_part )
        {
            $levels--;
            $hundreds   = ( int ) ( $num_part / 100 );
            $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
            $tens       = ( int ) ( $num_part % 100 );
            $singles    = '';

            if( $tens < 20 )
            {
                $tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
            }
            else
            {
                $tens   = ( int ) ( $tens / 10 );
                $tens   = ' ' . $list2[$tens] . ' ';
                $singles    = ( int ) ( $num_part % 10 );
                $singles    = ' ' . $list1[$singles] . ' ';
            }
            $words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );

        }

        $commas = count( $words );

        if( $commas > 1 )
        {
            $commas = $commas - 1;
        }

        $words  = implode( ', ' , $words );

        //Some Finishing Touch
        //Replacing multiples of spaces with one space
        $words  = trim( str_replace( ' ,' , ',' , trim_all( ucwords( $words ) ) ) , ', ' );
        if( $commas )
        {
            $words  = str_replace_last( ',' , ' and' , $words );
        }

        return $words;
    }
    else if( ! ( ( int ) $num ) )
    {
        return 'Zero';
    }
    return '';
}

function trim_all( $str , $what = NULL , $with = ' ' )
{
    if( $what === NULL )
    {
        //  Character      Decimal      Use
        //  "\0"            0           Null Character
        //  "\t"            9           Tab
        //  "\n"           10           New line
        //  "\x0B"         11           Vertical Tab
        //  "\r"           13           New Line in Mac
        //  " "            32           Space

        $what   = "\\x00-\\x20";    //all white-spaces and control chars
    }

    return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
}

function str_replace_last( $search , $replace , $str ) {
    if( ( $pos = strrpos( $str , $search ) ) !== false ) {
        $search_length  = strlen( $search );
        $str    = substr_replace( $str , $replace , $pos , $search_length );
    }
    return $str;
}
?>
    <style>
        body{
            -webkit-print-color-adjust:exact;
        }
    </style>
@stop
@section('javascript')
    {!! HTML::script('js/sales.js') !!}
    {!! HTML::script('assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js') !!}
    {!! HTML::script('assets/plugins/select2/select2.min.js') !!}
@stop



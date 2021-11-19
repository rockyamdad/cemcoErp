@extends('baseLayout')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}"/>
    <style tyle="text/css">
        <!--
        @media print
        {
           .companyLogo{
               width: 30%;
               float: right;

           }
            .branchInfo{
                width: 40%;
                float: left;
            }
        }

        -->
    </style>
@stop
@section('content')
        <div class="row">
            <div class="col-xs-offset-4 invoice-block" style="margin-left: 800px;">
                <a class="btn btn-sm btn-success" onclick="javascript:history.back();">Back</a>
                <a class="btn btn-sm blue hidden-print" href="{{\Illuminate\Support\Facades\URL::to('sales/showinvoice2/'.$invoiceId)}}">Chalan <i class="fa fa-file"></i></a>
                <a class="btn btn-sm blue hidden-print" onclick="getConfirmation();">Print <i class="fa fa-print"></i></a>
            </div>
        </div>
    <div class="invoice">
        <div class="row invoice-logo">
            <div class="col-md-12 invoice-logo-space">
                <?php
                    $branch = \App\Branch::find($saleDetails[0]->branch_id);
                ?>
              {{--  @if($saleDetails[0]->branch_id == 1)
                    <img src="../../assets/img/pad/cemon-invoice.jpg" style="width: 100%;"  alt="" />
                @elseif($saleDetails[0]->branch_id == 2)
                    <img src="../../assets/img/pad/fst-invoice.jpg" style="width: 100%;"  alt="" />
                @elseif($saleDetails[0]->branch_id == 3)
                    <img src="../../assets/img/pad/cemon-invoice.jpg" style="width: 100%;"  alt="" />
                @elseif($saleDetails[0]->branch_id == 4)
                    <img src="../../assets/img/pad/cemon-invoice.jpg" style="width: 100%;"  alt="" />
                @elseif($saleDetails[0]->branch_id == 5)
                    <img src="../../assets/img/pad/sun-invoice.jpg" style="width: 100%;"  alt="" />
                @elseif($saleDetails[0]->branch_id == 6)
                    <img src="../../assets/img/pad/olympic-invoice.jpg" style="width: 100%;"  alt="" />
                @elseif($saleDetails[0]->branch_id == 7)
                    <img src="../../assets/img/pad/keyon1.jpg" style="width: 100%;"  alt="" />
                @elseif($saleDetails[0]->branch_id == 8)
                <img src="../../assets/img/pad/cemon-invoice.jpg" style="width: 100%;"  alt="" />
                @elseif($saleDetails[0]->branch_id == 9)
                    <img src="../../assets/img/pad/me-invoice.jpg" style="width: 100%;"  alt="" />
                @endif--}}
                    <div class="col-md-8 " >
                        <h3>{{$branch->name}}</h3>
                        <span style="width: 20px;">{{$branch->location}}</span>
                    </div>
                    <div  class="col-md-4 companyLogo">
                        <img style="margin-left: -250px;width: 120px" src="../../assets/img/cemco.jpg"  alt="" />
                    </div>

           </div>

        </div>
<br>
<br>
        <div class="row" >

            <div class="col-xs-4">
               {{-- <h4>Client:</h4>--}}
                <?php
                    $party = \App\Party::find($sale->party_id);
                    ?>

                <table style="width: 500px;">
                <tr>
                    <td><b>Customer</b>

                    :
                        @if($party)
                            {{$party->name}}
                        @else
                            {{$sale->cash_sale}}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><b>Address</b>
                    :  @if($party)
                            {{$party->address}}
                        @else

                        @endif
                    </td>
                </tr>
                <tr>
                    <td><b>Contact</b>
                    :
                        @if($party)
                            {{$party->phone}}
                        @else

                        @endif
                    </td>
                </tr>
                </table>
            </div>

            <div class="col-xs-4 invoice-payment">
                <table>
                <tr>
                    <td><b>Invoice #</b>
                    : {{$sale->invoice_id}}</td>
                </tr>
                <tr>
                    <td><b>Date</b>: {{\App\Transaction::convertDate($sale->created_at)}}</td>
                </tr>
                <tr>
                    <td><b>Print Date</b>: {{date("d/m/Y")}}</td>
                </tr>
                </table>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-xs-12" style="width:780px;">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th style="text-align: right;" class="hidden-480">Quantity</th>
                        <th style="text-align: right;" class="hidden-480">Unit Price</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    $total = 0;
                    ?>
                    @foreach($saleDetails as $saleDetail )
                        <?php

                        $categoryName = \App\Category::find($saleDetail->product->category_id);
                        $subCategoryName = \App\SubCategory::find($saleDetail->product->sub_category_id);

                                $products = \App\Product::find($saleDetail->product_id);
                                ?>
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$saleDetail->product->name.'('.$categoryName->name.')'.'('.$subCategoryName->name.')'}}</td>
                            <td style="text-align: right;">{{$saleDetail->quantity}}</td>
                            <td style="text-align: right;">{{$saleDetail->price}}</td>
                            <td style="text-align: right;">{{$saleDetail->price * $saleDetail->quantity}}</td>
                        </tr>
                        <?php
                        $total = $total + ($saleDetail->price * $saleDetail->quantity);
                        $i++;
                        ?>
                    @endforeach
                    <tr>
                        <td>Total:</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right;">{{$total}}</td>
                    </tr>
                    <tr>
                        <td>Discount:</td>
                        <td></td>
                        <td></td>
                        <td class="text-right">{{round(($sale->discount_percentage_per))}}%</td>
                        <td style="text-align: right;">{{($sale->discount_percentage_per*$total)/100}}</td>
                    </tr>

                    <tr>
                        <td>Special Discount:</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right;">{{$sale->discount_special}}</td>
                    </tr>

                    <tr style="font-weight: bold;">
                        <td>Grand Total:</td>
                        <td>
                            <span class="col-md-3">Amount in words</span>
                            <span class="col-md-8" style="border-bottom:1px dotted black; "><?php echo number_to_word($total-$sale->discount_percentage); ?> Taka Only</span>
                        </td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right;">{{$total-$sale->discount_percentage}}</td>
                    </tr>

                    </tbody>
                </table>


                <div class="row">

                    <div class="col-xs-12">
                        <b>Remarks:</b><br>
                        <div id="remrks">
                            <?php if ($sale->is_sale == 1) { ?>
                                <?php echo nl2br($sale->remarks); ?>
                            <?php }?>
                        </div>
                        <div id="remrksForm">
                            <?php if ($sale->is_sale != 1) { ?>
                            {!!Form::open(array('url' => 'http://cemcoerp.dev/sales/confirm/'.$sale->id, 'method' => 'post', 'class'=>'form-horizontal',
                            'id'=>'confirm_form'))!!}

                            <textarea class="col-xs-6" id="remIn" name="remIn">{{$sale->remarks}}

                        </textarea>
                            </form>
                            <button class="btn btn-danger" id="click" onclick="conirm({{$sale->id}});">Confirm</button>
                            <?php }?>
                        </div>

                    </div>



                </div>
                <br><br>
                <div class="row">

                    <div style="text-decoration: underline" class="col-xs-4">
                        <center>
                            <b>Received By</b>
                        </center>
                    </div>
                    <div class="col-xs-4">

                    </div>
                    <div style="text-decoration: underline" class="col-xs-4 invoice-payment">
                        <center>
                            <b>Delivered By</b>
                        </center>
                    </div>

                </div>


            </div>
        </div>
        <br>
        <br>
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
    </div>

        <style>
            table {font-size: 12px;}
            table tr th {font-size: 12px;}
        </style>


        <script>
            function getConfirmation(){
                var retVal = $('#remrks').html();
                //alert(retVal);
                if( retVal != '' ){
                    window.print();
                    return true;
                }
                else{
                    alert('Please confirm terms & conditions');
                    //document.write ("User does not want to continue!");
                    return false;
                }
            }

            function conirm(product_id) {
                var remarks = $('#remIn').val();
                $('#remrks').html($('#remIn').val().replace(/\n/g, "<br>"));
                $('#remrksForm').hide();
                $.ajax({
                    type: "post",
                    url: "../confirm/"+product_id,
                    data: $('#confirm_form').serialize(),
                    dataType:'json',
                    headers:
                    {
                        'X-CSRF-Token': $('input[name="_token"]').val()
                    },
                    success: function (html) {
                        alert(html)
                    }
                });
            }

        </script>

@stop
@section('javascript')
    {!! HTML::script('js/sales.js') !!}
    {!! HTML::script('assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js') !!}
    {!! HTML::script('assets/plugins/select2/select2.min.js') !!}
@stop



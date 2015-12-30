@extends('baseLayout')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" />

@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                <?php
                $branches = \App\Branch::find($branch_id);
                ?>
            <center>
            <h3 class="page-title">
             Purchase Details Report For {{$branches->name}}
            </h3>
            </center>
         </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <div class="portlet box light-grey">

                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-reorder"></i>Date : From to To</div>

                     <div class="actions">
                         <a class="btn btn-sm blue hidden-print" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
                       </div>

                </div>

                <div class="portlet-body">

                    <table class="table table-striped table-bordered table-hover" id="stock_products_report_table">
                        <thead style="background-color:cadetblue">
                        <tr>

                            <th>Invoice No</th>
                            {{--<th>Date</th>--}}
                            <th>Product Name</th>
                            <th>Stock Name</th>
                            <th style="text-align: right;">Unit Price</th>
                            <th style="text-align: right;">Quantity</th>
                            <th>Remarks</th>
                            <th style="text-align: right;">Total Amount</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $totalSale = 0;
                        $invoiceSave = array();
                        ?>

                        @foreach($results as $result )
                            <?php
                         $products = \App\Product::find($result->product_id);
                         $stocks = \App\StockInfo::find($result->stock);
                         $categories = \App\Category::find($result->category_id);
                         $subCategories = \App\SubCategory::find($result->sub_category_id);
                            if($result->sub_category_id)
                            {
                                $subCategoryName = '('.$subCategories->name.')';
                            }else{
                                $subCategoryName = '';
                            }
                                    ?>

                            <tr class="odd gradeX">
                                <td>
                                @if(in_array($result->invoice,$invoiceSave))

                                @else
                                        {{$result->invoice}}
                                @endif
                                </td>
                                {{--<td>{{$result->date}}</td>--}}
                                <td>{{$products->name.'('.$categories->name.')'.$subCategoryName}}</td>
                                <td>{{$stocks->name}}</td>
                                <td style="text-align: right;">{{$result->price}}</td>
                                <td style="text-align: right;">{{$result->quantity}}</td>
                                <td>{{$result->remarks}}</td>
                                <td style="text-align: right;">{{$result->quantity * $result->price}}</td>



                            </tr>
                            <?php

                             $invoiceSave[] = $result->invoice;
                            $totalSale = $totalSale + ($result->quantity * $result->price);
                            ?>
                        @endforeach
                        <tr>
                            <td><b>Grand Total</b></td>
                            <td></td>
                            {{--<td></td>--}}
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align: right;">{{$totalSale}}</td>


                        </tr>

                        </tbody>
                    </table>

                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>

@stop
@section('javascript')
    {!! HTML::script('js/report.js') !!}
    {!! HTML::script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::script('assets/plugins/select2/select2.min.js') !!}
@stop

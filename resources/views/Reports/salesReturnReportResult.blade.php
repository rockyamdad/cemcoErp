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
             Sales Return  Report For {{$branches->name}}
            </h3>
            </center>
         </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <div class="portlet box light-grey">

                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-reorder"></i>  Date : From to To </div>

                     <div class="actions">
                         <a class="btn btn-sm blue hidden-print" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
                       </div>

                </div>

                <div class="portlet-body">

                    <table class="table table-striped table-bordered table-hover" id="stock_products_report_table">
                        <thead style="background-color:cadetblue">
                        <tr>

                            <th>Date</th>
                            <th>Party Name</th>
                            <th>Product Name</th>
                            <th>Customer Ref No</th>
                            <th>Consignment Name</th>
                            <th style="text-align: right;">Quantity</th>
                            <th style="text-align: right;">Return Amount</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total = 0;
                        ?>

                        @foreach($results as $result )
                            <?php
                         $products = \App\Product::find($result->product_id);
                         $party = \App\Party::find($result->party_id);
                         $categories = \App\Category::find($products->category_id);
                         $subCategories = \App\SubCategory::find($products->sub_category_id);
                                    if($products->sub_category_id)
                                    {
                                        $subCategoryName = '('.$subCategories->name.')';
                                    }else{
                                        $subCategoryName = '';
                                    }
                                    ?>

                            <tr class="odd gradeX">
                                <td>{{$result->date}}</td>
                                <td>{{$party->name}}</td>
                                <td>{{$products->name.'('.$categories->name.')'.$subCategoryName}}</td>
                                <td>{{$result->cus_ref_no}}</td>
                                <td>{{$result->consignment_name}}</td>
                                <td style="text-align: right;">{{$result->quantity}}</td>
                                <td style="text-align: right;">{{$result->return_amount}}</td>
                            </tr>
                            <?php
                            $total = $total + ($result->return_amount );
                            ?>
                        @endforeach
                        <tr>
                            <td><b>Grand Total</b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align: right;">{{$total}}</td>


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

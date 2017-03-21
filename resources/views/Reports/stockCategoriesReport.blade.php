@extends('baseLayout')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" />

@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="page-title">  Stock Report of Categories  </h3>
         </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <?php

                    $curent_url = $_SERVER['REQUEST_URI'];

                    if($curent_url == '/reports/stocksproductsresult')
                        {
                            $url = URL::to('reports/printstocksproductsresult/'.$branch_id.'/'.$category_id);
                        }else{
                            $url = URL::to('reports/printstocksproducts');
                    }
            ?>
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box light-grey">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-reorder"></i>   Stock Report of Categories</div>
                    <div class="actions">
                        <a class="btn blue" href="/reports/stockscategories">Back</a>
                        <a class="btn dark" onclick="javascript: window.open('{{$url}}','MsgWindow', 'width=1100,height=500').print();">Print</a>
                    </div>

                </div>

                <div class="portlet-body">

                    {!!Form::open(array('url' => 'reports/stockscategoriesresult', 'method' => 'post', 'class'=>'form-horizontal',
                    'id'=>'stock_search_form'))!!}
                    <div class="form-group">
                        <input type="hidden" name="branch_session" id="branch_session" value="{{Session::get('user_branch')}}">
                        <input type="hidden" name="role_session" id="role_session" value="{{Session::get('user_role')}}">
                        @if(Session::get('user_role')=='admin')
                            <div class="col-md-3">
                                {!!Form::select('branch_id',[null=>'Please Select Branch'] +$branchAll,'null',
                                array('class'=>'form-control ','id'=>'branch_id') )!!}
                            </div>
                        @endif

                        <div class="col-md-3">
                            {!!Form::select('category_id',[null=>'Please Select Category'] +$categoriesAll,'null', array('class'=>'form-control ','id'=>'category_id') )!!}
                        </div>

                        <div class=" fluid">
                            <div class=" col-md-3">
                                <button type="submit" class="btn blue btn-block margin-top-10" style="margin-left: 15px;">SEARCH <i class="m-icon-swapright m-icon-white"></i></button>
                            </div>
                        </div>

                    </div>

                    {!!Form::close()!!}


                    <table class="table table-striped table-bordered table-hover" id="stock_products_report_table">
                        <thead style="background-color:cadetblue">
                        <tr>
                            <th>SL</th>
                            <th>Product Name</th>
                            <th>Price</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        $grandTotalPrice = 0;
                        ?>

                        @foreach($results as $result )
                            <?php
                            $pName = \App\Product::find($result->product_id);
                            $categoryName = \App\Category::find($pName->category_id);
                               if($pName->sub_category_id){
                                   $subCategory = \App\SubCategory::find($pName->sub_category_id);
                                   $subCategoryName = $subCategory->name;
                               }
                                 else{
                                     $subCategoryName = '';
                                 }

                            $grandTotalPrice = $grandTotalPrice + $result->total_price;
                                    ?>

                            <tr class="odd gradeX">
                                <td>{{$i++}}</td>
                                <td>{{$pName->name}} ({{$categoryName->name}}) ({{$subCategoryName}})</td>
                                <td class="text-right">{{$result->total_price}}</td>


                            </tr>

                        @endforeach
                        <tr>
                            <td><b>Total</b></td>
                            <td></td>
                            <td class="text-right">{{$grandTotalPrice}}</td>
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

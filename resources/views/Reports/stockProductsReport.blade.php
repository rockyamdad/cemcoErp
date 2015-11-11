@extends('baseLayout')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" />

@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
             Stock Products Report
            </h3>

         </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <?php
            use Illuminate\Support\Facades\URL;

            $url = URL::to('reports/printstocksproducts');
            ?>
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box light-grey">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-reorder"></i>   Stock Report of Products</div>
                    <div class="actions">

                        <a class="btn dark" onclick="javascript: window.open('{{$url}}','MsgWindow', 'width=1100,height=500').print();">Print</a>
                    </div>

                </div>

                <div class="portlet-body">

                    {!!Form::open(array('url' => 'reports/stocksproductsresult', 'method' => 'post', 'class'=>'form-horizontal',
                    'id'=>'stock_search_form'))!!}
                    <div class="form-group">
                        {!!HTML::decode(Form::label('stock_info_id','Select Stocks<span class="required">*</span>',array('class' =>
                        'control-label col-md-3')))!!}
                        <div class="col-md-4">
                            {!! Form::select('stock_info_id',[null=>'Please Select Stocks'] +$allStockInfos,'null', array('class'=>'form-control','id'=>'stock_info_id'))!!}
                        </div>
                        {!!Form::button('Search',array('type' => 'submit','class' => 'btn blue','id' => 'save'))!!}
                    </div>

                    {!!Form::close()!!}


                    <table class="table table-striped table-bordered table-hover" id="stock_products_report_table">
                        <thead style="background-color:cadetblue">
                        <tr>

                            <th>Stock Name</th>
                            <th>Product Name</th>
                            <th>Total Quantity On Hand</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $grandTotal = 0;
                        ?>

                        @foreach($results as $result )
                            <?php
                            $pName = \App\Product::find($result->product_id);
                            $sName = \App\StockInfo::find($result->stock_info_id);
                            $grandTotal = $grandTotal + $result->product_quantity;
                                    ?>

                            <tr class="odd gradeX">
                                <td>{{$sName->name}}</td>
                                <td>{{$pName->name}}</td>
                                <td>{{$result->product_quantity}}</td>


                            </tr>

                        @endforeach
                        <tr>
                            <td>Total</td>
                            <td></td>
                            <td>{{$grandTotal}}</td>
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

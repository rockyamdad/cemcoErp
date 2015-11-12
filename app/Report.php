<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Report extends Eloquent
{
    //stock main Report
    public function getStockReport($product_type,$date1,$date2)
    {
            return DB::table('stocks')
                ->join('products', 'stocks.product_id', '=', 'products.id')
                ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
                ->join('stock_infos', 'stocks.stock_info_id', '=', 'stock_infos.id')
                ->where('stocks.product_type', '=', $product_type)
                ->whereBetween('stocks.created_at', array(new \DateTime($date1), new \DateTime($date2)))
                ->groupBy('stocks.product_id')
                ->select('products.name AS pName',
                    'product_categories.name AS category',
                    'stocks.created_at',
                    'stocks.product_id',
                    'stocks.product_type',
                    'stock_infos.name AS sName'

                )
                ->get();
    }


    public function getStockBf($product_type,$date1,$product_id)
    {
        return DB::table('stocks')
            ->where('created_at', '<',new \DateTime($date1))
            ->where('product_type', '=',$product_type)
            ->where('entry_type', '=', 'StockIn')
            ->where('product_id', '=',$product_id)
            ->select(
                DB::raw('SUM(product_quantity) as stockBf')

            )
            ->get();
    }
    public function getStockIn($product_type,$date1,$date2,$product_id)
    {
        return DB::table('stocks')
            ->whereBetween('stocks.created_at', array(new \DateTime($date1), new \DateTime($date2)))
            ->where('product_type', '=',$product_type)
            ->where('entry_type', '=', 'StockIn')
            ->where('product_id', '=',$product_id)
            ->select(
                DB::raw('SUM(product_quantity) as stockIn')

            )
            ->get();
    }
    public function getStockOut($product_type,$date1,$date2,$product_id)
    {
        return DB::table('stocks')
            ->whereBetween('stocks.created_at', array(new \DateTime($date1), new \DateTime($date2)))
            ->where('product_type', '=',$product_type)
            ->where('entry_type', '=', 'StockOut')
            ->where('product_id', '=',$product_id)
            ->select(
                DB::raw('SUM(product_quantity) as stockOut')

            )
            ->get();
    }
    public function getStockWastage($product_type,$date1,$date2,$product_id)
    {
        return DB::table('stocks')
            ->whereBetween('stocks.created_at', array(new \DateTime($date1), new \DateTime($date2)))
            ->where('product_type', '=',$product_type)
            ->where('entry_type', '=', 'Wastage')
            ->where('product_id', '=',$product_id)
            ->select(
                DB::raw('SUM(product_quantity) as stockWastage')

            )
            ->get();
    }
    //stock Products Report

    public function getStockProductsReport()
    {
        return DB::table('stock_counts')
            ->selectRaw('product_id,stock_info_id, sum(product_quantity) as product_quantity')
            ->groupBy('stock_counts.product_id')
            ->get();
    }
    public function getStockReportResult($stock)
    {
        return DB::table('stock_counts')
            ->selectRaw('product_quantity,product_id,stock_info_id')
            ->where('stock_info_id', '=', $stock)
            ->get();
    }






}

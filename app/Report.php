<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Report extends Eloquent
{
    public function getStockReport($date1,$date2)
    {
            return DB::table('stocks')
                ->join('products', 'stocks.product_id', '=', 'products.id')
                ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
                ->join('stock_infos', 'stocks.stock_info_id', '=', 'stock_infos.id')
                ->whereBetween('stocks.created_at', array(new \DateTime($date1), new \DateTime($date2)))
               // ->where('entry_type', '=', 'StockIn')
                ->groupBy('stocks.product_id')
                ->select('products.name AS pName',
                    'product_categories.name AS category',
                    'stocks.created_at',
                    'stocks.product_id',
                    //DB::raw('SUM(stocks.product_quantity) as stockIn'),
                    'stock_infos.name AS sName'

                )
                ->get();
    }

    public function getStockBf($date1,$product_id)
    {
        return DB::table('stocks')
            ->where('created_at', '<',new \DateTime($date1))
            ->where('entry_type', '=', 'StockIn')
            ->where('product_id', '=',$product_id)
            ->select(
                DB::raw('SUM(product_quantity) as stockBf')

            )
            ->get();
    }
    public function getStockIn($date1,$date2,$product_id)
    {
        return DB::table('stocks')
            ->whereBetween('stocks.created_at', array(new \DateTime($date1), new \DateTime($date2)))
            ->where('entry_type', '=', 'StockIn')
            ->where('product_id', '=',$product_id)
            ->select(
                DB::raw('SUM(product_quantity) as stockIn')

            )
            ->get();
    }
    public function getStockOut($date1,$date2,$product_id)
    {
        return DB::table('stocks')
            ->whereBetween('stocks.created_at', array(new \DateTime($date1), new \DateTime($date2)))
            ->where('entry_type', '=', 'StockOut')
            ->where('product_id', '=',$product_id)
            ->select(
                DB::raw('SUM(product_quantity) as stockOut')

            )
            ->get();
    }
    public function getStockWastage($date1,$date2,$product_id)
    {
        return DB::table('stocks')
            ->whereBetween('stocks.created_at', array(new \DateTime($date1), new \DateTime($date2)))
            ->where('entry_type', '=', 'Wastage')
            ->where('product_id', '=',$product_id)
            ->select(
                DB::raw('SUM(product_quantity) as stockWastage')

            )
            ->get();
    }






}
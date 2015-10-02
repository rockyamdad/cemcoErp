<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Search extends Eloquent
{
    public function getResultSearchType($type,$date1,$date2)
    {
        return DB::table('stocks')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->join('stock_infos', 'stocks.stock_info_id', '=', 'stock_infos.id')
            ->join('users', 'stocks.user_id', '=', 'users.id')
            ->whereBetween('stocks.created_at',array(new \DateTime($date1),new \DateTime($date2)))
            ->where('entry_type','=',$type)
            ->select('products.name AS pName',
                     'stocks.product_quantity',
                     'stocks.entry_type',
                     'stocks.consignment_name',
                     'stocks.remarks',
                     'users.name AS uName',
                     'stock_infos.name AS sName'
            )
            ->get();
    }

}

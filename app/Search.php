<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Search extends Eloquent
{
    public function getResultSearchType($type,$date1,$date2)
    {
        if(($type=='')) {
            return DB::table('stocks')
                ->join('products', 'stocks.product_id', '=', 'products.id')
                ->join('stock_infos', 'stocks.stock_info_id', '=', 'stock_infos.id')
                ->join('users', 'stocks.user_id', '=', 'users.id')
                ->whereBetween('stocks.created_at', array(new \DateTime($date1), new \DateTime($date2)))
                ->select('products.name AS pName',
                    'stocks.product_quantity',
                    'stocks.entry_type',
                    'stocks.consignment_name',
                    'stocks.remarks',
                    'stocks.created_at',
                    'users.name AS uName',
                    'stock_infos.name AS sName'

                )
                ->get();
        }else{
            return DB::table('stocks')
                ->join('products', 'stocks.product_id', '=', 'products.id')
                ->join('stock_infos', 'stocks.stock_info_id', '=', 'stock_infos.id')
                ->join('users', 'stocks.user_id', '=', 'users.id')
                ->where('entry_type', '=', $type)
                ->groupBy('stocks.product_id')
                ->whereBetween('stocks.created_at', array(new \DateTime($date1), new \DateTime($date2)))
                ->select('products.name AS pName',
                    'stocks.entry_type',
                    'stocks.consignment_name',
                    'stocks.remarks',
                    'stocks.created_at',
                    'users.name AS uName',
                    'stock_infos.name AS sName',
                    DB::raw('SUM(stocks.product_quantity) as product_quantity')
                )
                ->get();
        }
    }
    public function getResultRequisition($party,$date1,$date2)
    {
        if(($party=='')) {
            return DB::table('stock_requisitions')
                ->join('parties', 'stock_requisitions.party_id', '=', 'parties.id')
                ->join('products', 'stock_requisitions.product_id', '=', 'products.id')
                ->join('users', 'stock_requisitions.user_id', '=', 'users.id')
                ->whereBetween('stock_requisitions.created_at', array(new \DateTime($date1), new \DateTime($date2)))
                ->select('products.name AS pName',
                    'products.category_id AS cId',
                    'products.sub_category_id AS sId',
                    'parties.name AS partyName',
                    'stock_requisitions.requisition_quantity',
                    'stock_requisitions.issued_quantity',
                    'stock_requisitions.remarks',
                    'stock_requisitions.created_at',
                    'users.name AS uName'
                )
                ->get();
        }else{
            return DB::table('stock_requisitions')
                ->join('parties', 'stock_requisitions.party_id', '=', 'parties.id')
                ->join('products', 'stock_requisitions.product_id', '=', 'products.id')
                ->join('users', 'stock_requisitions.user_id', '=', 'users.id')
                ->where('party_id', '=', $party)
                ->whereBetween('stock_requisitions.created_at', array(new \DateTime($date1), new \DateTime($date2)))
                ->select('products.name AS pName',
                    'products.category_id AS cId',
                    'products.sub_category_id AS sId',
                    'parties.name AS partyName',
                    'stock_requisitions.requisition_quantity',
                    'stock_requisitions.issued_quantity',
                    'stock_requisitions.remarks',
                    'stock_requisitions.created_at',
                    'users.name AS uName'
                )
                ->get();
        }
    }
    public function getResultStockProducts($category,$product,$date1,$date2)
    {
        if(($category=='') && ($product==''))
        {
            return DB::table('stocks')
                ->join('products', 'stocks.product_id', '=', 'products.id')
                ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
                ->join('stock_infos', 'stocks.stock_info_id', '=', 'stock_infos.id')
                ->join('users', 'stocks.user_id', '=', 'users.id')
                ->whereBetween('stocks.created_at',array(new \DateTime($date1),new \DateTime($date2)))
                ->select('products.name AS pName',
                    'product_categories.name AS category',
                    'stocks.product_quantity',
                    'stocks.entry_type',
                    'stocks.consignment_name',
                    'stocks.remarks',
                    'stocks.created_at',
                    'users.name AS uName',
                    'stock_infos.name AS sName'
                )
                ->get();
        }elseif($category=='')
        {
            return DB::table('stocks')
                ->join('products', 'stocks.product_id', '=', 'products.id')
                ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
                ->join('stock_infos', 'stocks.stock_info_id', '=', 'stock_infos.id')
                ->join('users', 'stocks.user_id', '=', 'users.id')
                ->where('product_id','=',$product)
                ->whereBetween('stocks.created_at',array(new \DateTime($date1),new \DateTime($date2)))
                ->select('products.name AS pName',
                    'product_categories.name AS category',
                    'stocks.product_quantity',
                    'stocks.entry_type',
                    'stocks.consignment_name',
                    'stocks.remarks',
                    'stocks.created_at',
                    'users.name AS uName',
                    'stock_infos.name AS sName'
                )
                ->get();
        }
        elseif($product=='')
        {
            return DB::table('stocks')
                ->join('products', 'stocks.product_id', '=', 'products.id')
                ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
                ->join('stock_infos', 'stocks.stock_info_id', '=', 'stock_infos.id')
                ->join('users', 'stocks.user_id', '=', 'users.id')
                ->where('products.category_id','=',$category)
                ->whereBetween('stocks.created_at',array(new \DateTime($date1),new \DateTime($date2)))
                ->select('products.name AS pName',
                    'product_categories.name AS category',
                    'stocks.product_quantity',
                    'stocks.entry_type',
                    'stocks.consignment_name',
                    'stocks.remarks',
                    'stocks.created_at',
                    'users.name AS uName',
                    'stock_infos.name AS sName'
                )
                ->get();
        }elseif(($category!='') && ($product!='') && ($date1 != '') && ($date2!= ''))
        {
            return DB::table('stocks')
                ->join('products', 'stocks.product_id', '=', 'products.id')
                ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
                ->join('stock_infos', 'stocks.stock_info_id', '=', 'stock_infos.id')
                ->join('users', 'stocks.user_id', '=', 'users.id')
                ->where('product_id','=',$product)
                ->where('products.category_id','=',$category)
                ->whereBetween('stocks.created_at',array(new \DateTime($date1),new \DateTime($date2)))
                ->select('products.name AS pName',
                    'product_categories.name AS category',
                    'stocks.product_quantity',
                    'stocks.entry_type',
                    'stocks.consignment_name',
                    'stocks.remarks',
                    'stocks.created_at',
                    'users.name AS uName',
                    'stock_infos.name AS sName'
                )
                ->get();
        }

    }

}

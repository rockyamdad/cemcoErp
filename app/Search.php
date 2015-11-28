<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Search extends Eloquent
{
    public function getResultSearchType($type,$date1,$date2,$branch)
    {
        if(($type=='')) {
            return DB::table('stocks')
                ->join('products', 'stocks.product_id', '=', 'products.id')
                ->join('stock_infos', 'stocks.stock_info_id', '=', 'stock_infos.id')
                ->join('users', 'stocks.user_id', '=', 'users.id')
                ->where('stocks.branch_id', '=', $branch)
                ->whereBetween('stocks.created_at', array(new \DateTime($date1), new \DateTime($date2)))
                ->select('products.name AS pName',
                    'products.category_id AS cid',
                    'products.sub_category_id AS sid',
                    'stocks.product_quantity',
                    'stocks.entry_type',
                    'stocks.branch_id',
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
                ->where('stocks.branch_id', '=', $branch)
                ->groupBy('stocks.product_id')
                ->whereBetween('stocks.created_at', array(new \DateTime($date1), new \DateTime($date2)))
                ->select('products.name AS pName',
                    'products.category_id AS cid',
                    'products.sub_category_id AS sid',
                    'stocks.entry_type',
                    'stocks.branch_id',
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
    public function getResultRequisition($party,$branch,$date1,$date2)
    {
        if(($party=='' && $branch=='')) {
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
                    'stock_requisitions.branch_id AS branchId',
                    'stock_requisitions.issued_quantity',
                    'stock_requisitions.remarks',
                    'stock_requisitions.created_at',
                    'users.username AS uName'
                )
                ->get();
        }else{
            return DB::table('stock_requisitions')
                ->join('parties', 'stock_requisitions.party_id', '=', 'parties.id')
                ->join('products', 'stock_requisitions.product_id', '=', 'products.id')
                ->join('users', 'stock_requisitions.user_id', '=', 'users.id')
                ->where('stock_requisitions.branch_id', '=', $branch)
                ->where('party_id', '=', $party)
                ->whereBetween('stock_requisitions.created_at', array(new \DateTime($date1), new \DateTime($date2)))
                ->select('products.name AS pName',
                    'products.category_id AS cId',
                    'products.sub_category_id AS sId',
                    'parties.name AS partyName',
                    'stock_requisitions.requisition_quantity',
                    'stock_requisitions.branch_id AS branchId',
                    'stock_requisitions.issued_quantity',
                    'stock_requisitions.remarks',
                    'stock_requisitions.created_at',
                    'users.username AS uName'
                )
                ->get();
        }
    }
    public function getResultStockProducts($category,$product,$date1,$date2,$branch,$stock)
    {
        if(($category=='') && ($product==''))
        {
            return DB::table('stocks')
                ->join('products', 'stocks.product_id', '=', 'products.id')
                ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
                ->join('stock_infos', 'stocks.stock_info_id', '=', 'stock_infos.id')
                ->join('users', 'stocks.user_id', '=', 'users.id')
                ->where('stocks.branch_id','=',$branch)
                ->where('stocks.stock_info_id','=',$stock)
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
                ->where('stocks.branch_id','=',$branch)
                ->where('stocks.stock_info_id','=',$stock)
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
                ->where('stocks.branch_id','=',$branch)
                ->where('stocks.stock_info_id','=',$stock)
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
                ->where('stocks.branch_id','=',$branch)
                ->where('stocks.stock_info_id','=',$stock)
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

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
            ->where('entry_type','=',$type)
            ->orWhereBetween('stocks.created_at',array(new \DateTime($date1),new \DateTime($date2)))
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
    }
    public function getResultRequisition($party,$date1,$date2)
    {
        return DB::table('stock_requisitions')
            ->join('parties', 'stock_requisitions.party_id', '=', 'parties.id')
            ->join('products', 'stock_requisitions.product_id', '=', 'products.id')
            ->join('users', 'stock_requisitions.user_id', '=', 'users.id')
            ->where('party_id','=',$party)
            ->orWhereBetween('stock_requisitions.created_at',array(new \DateTime($date1),new \DateTime($date2)))
            ->select('products.name AS pName',
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

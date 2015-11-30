<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Report extends Eloquent
{
    //stock main Report
    public function getStockReport($product_type,$date1,$date2,$branch_id,$category_id)
    {
            return DB::table('stocks')
                ->join('products', 'stocks.product_id', '=', 'products.id')
                ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
                ->join('stock_infos', 'stocks.stock_info_id', '=', 'stock_infos.id')
                ->where('stocks.branch_id', '=', $branch_id)
                ->where('products.category_id', '=', $category_id)
                ->where('stocks.product_type', '=', $product_type)
                ->whereBetween('stocks.created_at', array(new \DateTime($date1), new \DateTime($date2)))
                ->groupBy('stocks.product_id')
                ->select('products.name AS pName',
                    'product_categories.name AS category',
                    'products.sub_category_id AS subCategory',
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
    public function getStockBfOut($product_type,$date1,$product_id)
    {
        return DB::table('stocks')
            ->where('created_at', '<',new \DateTime($date1))
            ->where('product_type', '=',$product_type)
            ->where('entry_type', '=', 'StockOut')
            ->where('product_id', '=',$product_id)
            ->select(
                DB::raw('SUM(product_quantity) as stockBfOut')

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
    public function getStockReportResult($stock,$product_type,$branch_id,$category_id)
    {
        return DB::table('stock_counts')
            ->join('products', 'stock_counts.product_id', '=', 'products.id')
            ->selectRaw('stock_counts.product_quantity,stock_counts.product_id,stock_counts.stock_info_id,products.branch_id,products.product_type,products.category_id')
            ->where('products.branch_id', '=', $branch_id)
            ->where('stock_counts.stock_info_id', '=', $stock)
            ->where('products.category_id', '=', $category_id)
            ->where('products.product_type', '=', $product_type)
            ->get();
    }
    public function getSalesReport($date1,$date2,$branch_id)
    {
        return DB::table('sale_details')
            ->where('sale_details.branch_id', '=', $branch_id)
            ->whereBetween('sale_details.created_at', array(new \DateTime($date1), new \DateTime($date2)))
            ->groupBy('sale_details.invoice_id')
            ->select('sale_details.created_at AS date',
                'sale_details.branch_id AS branch',
                'sale_details.invoice_id AS invoice',
                DB::raw('SUM(sale_details.price * sale_details.quantity) AS totalSale')
            )
            ->get();
    }
    public function getPaymentForSalesReport($date1,$date2,$invoice_id)
    {
        return DB::table('transactions')
            ->where('transactions.invoice_id', '=', $invoice_id)
            ->where('transactions.type', '=', 'Receive')
            ->whereBetween('transactions.created_at', array(new \DateTime($date1), new \DateTime($date2)))
            ->select(
                DB::raw('SUM(transactions.amount) AS totalPayment')
            )
            ->get();
    }
    public function getSalesDetailsReport($date1,$date2,$branch_id)
    {
        return DB::table('sale_details')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->where('sale_details.branch_id', '=', $branch_id)
            ->whereBetween('sale_details.created_at', array(new \DateTime($date1), new \DateTime($date2)))
            ->select('sale_details.created_at AS date',
                'sale_details.branch_id AS branch',
                'sale_details.stock_info_id AS stock',
                'sale_details.invoice_id AS invoice',
                'sale_details.product_id',
                'sale_details.price',
                'sale_details.quantity',
                'sale_details.remarks',
                'products.category_id',
                'products.sub_category_id'
            )
            ->get();
    }
    public function getSalesDueReport($date1,$date2,$branch_id)
    {
        return DB::table('sale_details')
            ->join('sales','sale_details.invoice_id','=','sales.invoice_id')
            ->where('sale_details.branch_id', '=', $branch_id)
            ->whereBetween('sale_details.created_at', array(new \DateTime($date1), new \DateTime($date2)))
            ->groupBy('sales.party_id')
            ->select('sale_details.created_at AS date',
                'sale_details.branch_id AS branch',
                'sale_details.invoice_id AS invoice',
                'sales.party_id AS party',
                DB::raw('SUM(sale_details.price * sale_details.quantity) AS totalSale')
            )
            ->get();
    }
    public function getPaymentForSalesDueReport($date1,$date2,$party_id)
    {
        return DB::table('transactions')
            ->join('sales','transactions.invoice_id','=','sales.invoice_id')
            ->where('sales.party_id', '=', $party_id)
            ->where('transactions.type', '=', 'Receive')
            ->whereBetween('transactions.created_at', array(new \DateTime($date1), new \DateTime($date2)))
            ->select(
                DB::raw('SUM(transactions.amount) AS totalPayment')
            )
            ->get();
    }
    public function getSalesCollectionReport($date1,$date2,$branch_id)
    {
        return DB::table('transactions')
            ->join('sale_details', 'transactions.invoice_id', '=', 'sale_details.invoice_id')
            ->join('sales', 'transactions.invoice_id', '=', 'sales.invoice_id')
            ->where('sale_details.branch_id', '=', $branch_id)
            ->where('transactions.type', '=', 'Receive')
            ->whereBetween('transactions.created_at', array(new \DateTime($date1), new \DateTime($date2)))
            ->select('transactions.created_at AS date',
                'sale_details.branch_id AS branch',
                'sales.party_id AS party',
                'transactions.amount',
                'transactions.invoice_id AS invoice',
                'transactions.payment_method',
                'transactions.account_name_id',
                'transactions.account_category_id',
                'transactions.cheque_no',
                'transactions.remarks'
            )
            ->get();
    }
//PURCHASE QUERY SECTION
    public function getPurchaseReport($date1,$date2,$branch_id)
    {
        return DB::table('purchase_invoice_details')
            ->where('purchase_invoice_details.branch_id', '=', $branch_id)
            ->whereBetween('purchase_invoice_details.created_at', array(new \DateTime($date1), new \DateTime($date2)))
            ->groupBy('purchase_invoice_details.detail_invoice_id')
            ->select('purchase_invoice_details.created_at AS date',
                'purchase_invoice_details.branch_id AS branch',
                'purchase_invoice_details.detail_invoice_id AS invoice',
                DB::raw('SUM(purchase_invoice_details.price * purchase_invoice_details.quantity) AS totalSale')
            )
            ->get();
    }
    public function getPaymentForPurchaseReport($date1,$date2,$invoice_id)
    {
        return DB::table('transactions')
            ->where('transactions.invoice_id', '=', $invoice_id)
            ->where('transactions.type', '=', 'Payment')
            ->whereBetween('transactions.created_at', array(new \DateTime($date1), new \DateTime($date2)))
            ->select(
                DB::raw('SUM(transactions.amount) AS totalPayment')
            )
            ->get();
    }
}

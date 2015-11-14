<?php namespace App\Http\Controllers;

use App\Category;
use App\Party;
use App\Product;
use App\ProformaInvoice;
use App\Report;
use App\Search;
use App\Stock;
use App\StockInfo;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReportController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getIndex()
    {

    }
    public function getStocks()
    {
        return view('Reports.stockReportSearch');
    }
    public function getStocksproducts()
    {
        $stockInfos = new StockInfo();
        $allStockInfos = $stockInfos->getStockInfoDropDown();
        $report = new Report();
        $results = $report->getStockProductsReport();

        return view('Reports.stockProductsReport')
            ->with('results',$results)
            ->with('allStockInfos',$allStockInfos);
    }
    public function postStocksproductsresult()
    {
        $stockInfos = new StockInfo();
        $allStockInfos = $stockInfos->getStockInfoDropDown();
        $report = new Report();
        $stock_info_id = Input::get('stock_info_id');
        $product_type = Input::get('product_type');
        $results = $report->getStockReportResult($stock_info_id,$product_type);

        return view('Reports.stockProductsReport')
            ->with('stock_info_id',$stock_info_id)
            ->with('product_type',$product_type)
            ->with('results',$results)
            ->with('allStockInfos',$allStockInfos);
    }
    public function getPrintstocksproducts()
    {

        $report = new Report();
        $results = $report->getStockProductsReport();

        return view('Reports.stockProductsReportPrint')
            ->with('results',$results);

    }
    public function getPrintstocksproductsresult($product_type,$stock_info_id)
    {

        $report = new Report();
        $results = $report->getStockReportResult($stock_info_id,$product_type);

        return view('Reports.stockProductsReportPrint')
            ->with('results',$results);

    }
    public function postReportResult()
    {
        $date1 = Input::get('from_date');
        $date2 = Input::get('to_date');
        $product_type = Input::get('product_type');
        $report = new Report();
        $results = $report->getStockReport($product_type,$date1,$date2);
        return view('Reports.stockReport',compact('results'))
            ->with('product_type',$product_type)
            ->with('date1',$date1)
            ->with('date2',$date2);
    }

    public function getPrint($date1,$date2,$type)
    {
        $startDate = date('Y/m/d',strtotime($date1));
        $endDate = date('Y/m/d',strtotime($date2));
        $report = new Report();
        $results = $report->getStockReport($type,$startDate,$endDate);
        return view('Reports.stockReportPrint',compact('results'))
            ->with('product_type',$type)
            ->with('date1',$startDate)
            ->with('date2',$endDate);
    }


}
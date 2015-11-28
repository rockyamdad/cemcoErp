<?php namespace App\Http\Controllers;

use App\Branch;
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
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        $catogories = new Category();
        $categoriesAll = $catogories->getCategoriesDropDown();
        return view('Reports.stockReportSearch')
            ->with('branchAll',$branchAll)
            ->with('categoriesAll',$categoriesAll);
    }
    public function postReportResult()
    {
        $date1 = Input::get('from_date');
        $date2 = Input::get('to_date');
        $branch_id = Input::get('branch_id');
        $category_id = Input::get('category_id');
        $product_type = Input::get('product_type');
        $report = new Report();
        $results = $report->getStockReport($product_type,$date1,$date2,$branch_id,$category_id);
        return view('Reports.stockReport',compact('results'))
            ->with('product_type',$product_type)
            ->with('branch_id',$branch_id)
            ->with('category_id',$category_id)
            ->with('date1',$date1)
            ->with('date2',$date2);
    }

    public function getPrint($date1,$date2,$type,$branch_id,$category_id)
    {
        $startDate = date('Y/m/d',strtotime($date1));
        $endDate = date('Y/m/d',strtotime($date2));
        $report = new Report();
        $results = $report->getStockReport($type,$startDate,$endDate,$branch_id,$category_id);
        return view('Reports.stockReportPrint',compact('results'))
            ->with('product_type',$type)
            ->with('branch_id',$branch_id)
            ->with('category_id',$category_id)
            ->with('date1',$startDate)
            ->with('date2',$endDate);
    }
    public function getStocksproducts()
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        $catogories = new Category();
        $categoriesAll = $catogories->getCategoriesDropDown();
        $stockInfos = new StockInfo();
        $allStockInfos = $stockInfos->getStockInfoDropDown();
        $report = new Report();
        $results = $report->getStockProductsReport();

        return view('Reports.stockProductsReport')
            ->with('results',$results)
            ->with('branchAll',$branchAll)
            ->with('allStockInfos',$allStockInfos)
            ->with('categoriesAll',$categoriesAll);
    }
    public function postStocksproductsresult()
    {
        $stockInfos = new StockInfo();
        $allStockInfos = $stockInfos->getStockInfoDropDown();
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        $catogories = new Category();
        $categoriesAll = $catogories->getCategoriesDropDown();
        $report = new Report();
        $branch_id = Input::get('branch_id');
        $stock_info_id = Input::get('stock_info_id');
        $category_id = Input::get('category_id');
        $product_type = Input::get('product_type');
        $results = $report->getStockReportResult($stock_info_id,$product_type,$branch_id,$category_id);

        return view('Reports.stockProductsReport')
            ->with('stock_info_id',$stock_info_id)
            ->with('branch_id',$branch_id)
            ->with('category_id',$category_id)
            ->with('product_type',$product_type)
            ->with('results',$results)
            ->with('branchAll',$branchAll)
            ->with('allStockInfos',$allStockInfos)
            ->with('categoriesAll',$categoriesAll);
    }
    public function getPrintstocksproducts()
    {

        $report = new Report();
        $results = $report->getStockProductsReport();

        return view('Reports.stockProductsReportPrint')
            ->with('results',$results);

    }
    public function getPrintstocksproductsresult($product_type,$stock_info_id,$branch_id,$category_id)
    {

        $report = new Report();
        $results = $report->getStockReportResult($stock_info_id,$product_type,$branch_id,$category_id);

        return view('Reports.stockProductsReportPrint')
            ->with('results',$results);

    }

    public function getCategory($branch_id)
    {
        $categoriesName = Category::where('branch_id','=',$branch_id)
            ->get();

        foreach ($categoriesName as $categoryName) {

            echo "<option value = $categoryName->id > $categoryName->name</option> ";

        }
    }
    public function getSalesreport()
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('Reports.salesReport')
            ->with('branchAll',$branchAll);

    }
    public function postSalesereportresult()
    {
        $date1 = Input::get('from_date');
        $date2 = Input::get('to_date');
        $branch_id = Input::get('branch_id');
        $report = new Report();
        $results = $report->getSalesReport($date1,$date2,$branch_id);
        return view('Reports.salesReportResult',compact('results'))
            ->with('branch_id',$branch_id)
            ->with('date1',$date1)
            ->with('date2',$date2);
    }


}
<?php namespace App\Http\Controllers;

use App\Branch;
use App\Category;
use App\NameOfAccount;
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
            ->with('branch_id',$branch_id)
            ->with('stock_info_id',$stock_info_id)
            ->with('category_id',$category_id)
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
    public function getSalesdetails()
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('Reports.salesDetailsReport')
            ->with('branchAll',$branchAll);

    }
    public function postSalesDetailsReportResult()
    {
        $date1 = Input::get('from_date');
        $date2 = Input::get('to_date');
        $branch_id = Input::get('branch_id');
        $report = new Report();
        $results = $report->getSalesDetailsReport($date1,$date2,$branch_id);
        return view('Reports.salesDetailsReportResult',compact('results'))
            ->with('branch_id',$branch_id);
    }
    public function getSalesdue()
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('Reports.salesDueReport')
            ->with('branchAll',$branchAll);

    }
    public function postSalesDueReportResult()
    {
        $date1 = Input::get('from_date');
        $date2 = Input::get('to_date');
        $branch_id = Input::get('branch_id');
        $report = new Report();
        $results = $report->getSalesDueReport($date1,$date2,$branch_id);
        return view('Reports.salesDueReportResult',compact('results'))
            ->with('branch_id',$branch_id)
            ->with('date1',$date1)
            ->with('date2',$date2);
    }
    public function getSalescollection()
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('Reports.salesCollectionReport')
            ->with('branchAll',$branchAll);

    }
    public function postSalesCollectionReportResult()
    {
        $date1 = Input::get('from_date');
        $date2 = Input::get('to_date');
        $branch_id = Input::get('branch_id');
        $report = new Report();
        $results = $report->getSalesCollectionReport($date1,$date2,$branch_id);
        return view('Reports.salesCollectionReportResult',compact('results'))
            ->with('branch_id',$branch_id)
            ->with('date1',$date1)
            ->with('date2',$date2);
    }
    public function getPurchasereport()
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('Reports.purchaseReport')
            ->with('branchAll',$branchAll);

    }
    public function postPurchasereportresult()
    {
        $date1 = Input::get('from_date');
        $date2 = Input::get('to_date');
        $branch_id = Input::get('branch_id');
        $report = new Report();
        $results = $report->getPurchaseReport($date1,$date2,$branch_id);
        return view('Reports.purchaseReportResult',compact('results'))
            ->with('branch_id',$branch_id)
            ->with('date1',$date1)
            ->with('date2',$date2);
    }
    public function getPurchasedetails()
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('Reports.purchaseDetailsReport')
            ->with('branchAll',$branchAll);

    }
    public function postPurchaseDetailsReportResult()
    {
        $date1 = Input::get('from_date');
        $date2 = Input::get('to_date');
        $branch_id = Input::get('branch_id');
        $report = new Report();
        $results = $report->getPurchaseDetailsReport($date1,$date2,$branch_id);
        return view('Reports.purchaseDetailsReportResult',compact('results'))
            ->with('branch_id',$branch_id);
    }
    public function getPurchasedue()
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('Reports.purchaseDueReport')
            ->with('branchAll',$branchAll);

    }
    public function postPurchaseDueReportResult()
    {
        $date1 = Input::get('from_date');
        $date2 = Input::get('to_date');
        $branch_id = Input::get('branch_id');
        $report = new Report();
        $results = $report->getPurchaseDueReport($date1,$date2,$branch_id);
        return view('Reports.purchaseDueReportResult',compact('results'))
            ->with('branch_id',$branch_id)
            ->with('date1',$date1)
            ->with('date2',$date2);
    }
    public function getPurchasecollection()
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('Reports.purchaseCollectionReport')
            ->with('branchAll',$branchAll);

    }
    public function postPurchaseCollectionReportResult()
    {
        $date1 = Input::get('from_date');
        $date2 = Input::get('to_date');
        $branch_id = Input::get('branch_id');
        $report = new Report();
        $results = $report->getPurchaseCollectionReport($date1,$date2,$branch_id);
        return view('Reports.purchaseCollectionReportResult',compact('results'))
            ->with('branch_id',$branch_id)
            ->with('date1',$date1)
            ->with('date2',$date2);
    }
    public function getExpensereport()
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('Reports.expenseReport')
            ->with('branchAll',$branchAll);

    }
    public function postExpenseReportResult()
    {
        $date1 = Input::get('from_date');
        $date2 = Input::get('to_date');
        $branch_id = Input::get('branch_id');
        $report = new Report();
        $results = $report->getExpenseReport($date1,$date2,$branch_id);
        return view('Reports.expenseReportResult',compact('results'))
            ->with('branch_id',$branch_id)
            ->with('date1',$date1)
            ->with('date2',$date2);
    }
    public function getExpensepayment()
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('Reports.expensePaymentReport')
            ->with('branchAll',$branchAll);

    }
    public function postExpensePaymentReportResult()
    {
        $date1 = Input::get('from_date');
        $date2 = Input::get('to_date');
        $branch_id = Input::get('branch_id');
        $report = new Report();
        $results = $report->getExpensePaymentReport($date1,$date2,$branch_id);
        return view('Reports.expensePaymentReportResult',compact('results'))
            ->with('branch_id',$branch_id)
            ->with('date1',$date1)
            ->with('date2',$date2);
    }
    public function getBalancetransfer()
    {
        $accounts = new NameOfAccount();
        $accountAll = $accounts->getAccountsDropDown();
        return view('Reports.balanceTransfer')
            ->with('accountAll',$accountAll);

    }
    public function postBalanceTransferReportResult()
    {
        $account1 = Input::get('from_account_id');
        $account2 = Input::get('to_account_id');

        $report = new Report();
        $results = $report->getBalanceTransferReport($account1,$account2);
        $results2 = $report->getBalanceTransferReport2($account1,$account2);
        return view('Reports.balanceTransferReportResult',compact('results'))
            ->with('results2',$results2);

    }
    public function getAccountsreport()
    {
        $accounts = new NameOfAccount();
        $accountAll = $accounts->getAccountsDropDown();
        return view('Reports.accountsReport')
            ->with('accountAll',$accountAll);

    }
    public function postAccountsresult()
    {
        $date1 = Input::get('from_date');
        $date2 = Input::get('to_date');
        $account_id = Input::get('account_id');
        $report = new Report();
        $results = $report->getAccountsReportData($date1,$date2,$account_id);
        $currentBalance = NameOfAccount::find($account_id);

        $balanceIn = $report->getBalanceIn($date1,$date2,$account_id);
        $balanceOut = $report->getBalanceOut($date1,$date2,$account_id);
        $totalBalanceOut = $report->getTotalBalanceOut($account_id);
        $totalBalanceIn = $report->getTotalBalanceIn($account_id);
        return view('Reports.accountsReportResult',compact('results'))
            ->with('account_id',$account_id)
            ->with('totalBalanceIn',$totalBalanceIn)
            ->with('totalBalanceOut',$totalBalanceOut)
            ->with('balanceIn',$balanceIn)
            ->with('currentBalance',$currentBalance)
            ->with('balanceOut',$balanceOut);

    }
    public function getBalancetransferreport()
    {
        $reports = new Report();
        $results = $reports->getBalanceTransferFullReport();

        return view('Reports.balanceTransferFullReport')
            ->with('results',$results);


    }
    public function getSalesreturn()
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('Reports.salesReturnReport')
            ->with('branchAll',$branchAll);

    }
    public function postSalesReturnReportResult()
    {
        $date1 = Input::get('from_date');
        $date2 = Input::get('to_date');
        $branch_id = Input::get('branch_id');
        $report = new Report();
        $results = $report->getSalesReturnReport($date1,$date2,$branch_id);
        return view('Reports.salesReturnReportResult',compact('results'))
            ->with('branch_id',$branch_id)
            ->with('date1',$date1)
            ->with('date2',$date2);
    }

}
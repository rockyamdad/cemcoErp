<?php namespace App\Http\Controllers;

use App\AccountCategory;


use App\Branch;
use App\Import;
use App\NameOfAccount;
use App\Party;
use App\Product;
use App\PurchaseInvoice;
use App\PurchaseInvoiceDetail;
use App\Sale;
use App\SAleDetail;
use App\SalesReturn;
use App\Stock;
use App\StockCount;
use App\StockInfo;
use App\SubCategory;
use App\Transaction;
use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class SalesReturnController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getIndex()
    {
        $salesreturn = SalesReturn::orderBy('id','DESC')->paginate(15);

        return view('SalesReturn.list',compact('salesreturn'));
    }
    public function getCreate()
    {
        $stockInfos = new StockInfo();
        $allStockInfos = $stockInfos->getStockInfoDropDown();

        $parties = new Party();
        $partyAll = $parties->getPartiesDropDown();

        $imports= new Import();
        $consignmentAll = $imports->getConsignmentNameDropDown();

        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('SalesReturn.add',compact('allStockInfos','consignmentAll'))
            ->with('branchAll',$branchAll)
            ->with('partyAll',$partyAll);
    }
    public  function getProduct($type)
    {
        $branch= Input::get('data');
        $productsName = Product::where('product_type','=',$type)
            ->where('branch_id','=',$branch)
            ->get();

        foreach ($productsName as $productName) {

            $category = $productName->category->name;
            if($productName->sub_category_id){
                $subCategory = SubCategory::find($productName->sub_category_id);
                $subCategoryName = $subCategory->name;
            }else{
                $subCategoryName = '';
            }

            echo "<option value = $productName->id > $productName->name ($category) ($subCategoryName)</option> ";

        }
    }
}
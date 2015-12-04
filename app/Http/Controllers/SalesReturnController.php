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
    public function postSaveSalesReturn()
    {
        $ruless = array(
            'party_id' => 'required',
            'cus_ref_no' => 'required',
            'branch_id' => 'required',
            'product_type' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'return_amount' => 'required',
            'consignment_name' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('salesreturn/create')
                ->withErrors($validate);
        }
        else{
            $salesreturn = new SalesReturn();

            $this->setSalesReturnData($salesreturn);
            return Redirect::to('salesreturn/create');
        }
    }
    private function setSalesReturnData($salesreturn)
    {
            $this->insertSalesReturnData($salesreturn);
            $salesreturn->save();
            Session::flash('message', 'Sales has been Returned Successfully!!!');
    }
    private function insertSalesReturnData($salesreturn)
    {
        $salesreturn->branch_id = Input::get('branch_id');
        $salesreturn->party_id = Input::get('party_id');
        $salesreturn->product_id = Input::get('product_id');
        $salesreturn->cus_ref_no = Input::get('cus_ref_no');
        $salesreturn->quantity = Input::get('quantity');
        $salesreturn->return_amount = Input::get('return_amount');
        $salesreturn->consignment_name = Input::get('consignment_name');
        $salesreturn->remarks = Input::get('remarks');
        //$salesreturn->status = "Activate";
        //$salesreturn->user_id = Session::get('user_id');
    }
}
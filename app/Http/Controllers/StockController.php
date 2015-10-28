<?php namespace App\Http\Controllers;

use App\Branch;
use App\Import;
use App\Product;
use App\Stock;
use App\StockInfo;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;

class StockController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getIndex()
    {
        $stocks = Stock::all();
        return view('Stocks.list',compact('stocks'));
    }

    public function getCreate()
    {
        $stockInfos = new StockInfo();
        $allStockInfos = $stockInfos->getStockInfoDropDown();
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('Stocks.addSubtract',compact('allStockInfos'))
            ->with('branchAll',$branchAll);
    }
    public  function getProducts($type)
    {
        $productsName = Product::where('product_type','=',$type)
            ->get();

        foreach ($productsName as $productName) {
            $category = $productName->category->name;
            $subCategory = $productName->subCategory->name;
            echo "<option value = $productName->id > $productName->name ($category) ($subCategory)</option> ";
        }
    }
    public  function getImports()
    {
        $imports = Import::where('status','=','Activate')
            ->get();

       /* echo "<label class='control-label col-md-3'>Choose Consignment Name</label>";
        echo " <div class='col-md-4'> <select class='form-control' name='consignment_name'><option value ='N/A' >N/A </option>";*/
        foreach ($imports as $import) {
            echo "<option value = $import->consignment_name > $import->consignment_name</option> ";
        }
       /* echo "</select> </div>";*/
    }
    public  function getStocks()
    {
        $stocks = StockInfo::where('status','=','Activate')
            ->get();

        echo "<label class='control-label col-md-3'>To Stock </label>";
        echo " <div class='col-md-4'> <select class='form-control' name='to_stock_info_id'>";
        foreach ($stocks as $stock) {
            echo "<option value = $stock->id > $stock->name</option> ";
        }
        echo "</select> </div>";
    }
    public function postSaveStock()
    {
        $ruless = array(
            'product_id' => 'required',
            'branch_id' => 'required',
            'product_quantity' => 'required',
            'entry_type' => 'required',
            'stock_info_id' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('stocks/create')
                ->withErrors($validate);
        }
        else{
            $stock = new Stock();
            $this->setStockData($stock);
            $stock->save();
            return Redirect::to('stocks/create');
        }
    }
    public function getEdit($id)
    {
        $stock = Stock::find($id);
        $stockInfos = new StockInfo();
        $allStockInfos = $stockInfos->getStockInfoDropDown();
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('Stocks.edit',compact('stock'))
            ->with('allStockInfos',$allStockInfos)
            ->with('branchAll',$branchAll);

    }
    public function postUpdateStock($id)
    {
        $ruless = array(
            'product_id' => 'required',
            'branch_id' => 'required',
            'product_quantity' => 'required',
            'entry_type' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('stocks/edit',$id)
                ->withErrors($validate);
        }
        else{
            $stock = Stock::find($id);
            $this->updateStockData($stock);
            $stock->save();
            return Redirect::to('stocks/index');
        }
    }
    private function setStockData($stock)
    {
        $stock->branch_id = Input::get('branch_id');
        $stock->product_id = Input::get('product_id');
        $stock->product_type = Input::get('product_type');
        $stock->product_quantity = Input::get('product_quantity');
        $stock->entry_type = Input::get('entry_type');
        $stock->remarks = Input::get('remarks');
        $stock->user_id = Session::get('user_id');
        $stock->stock_info_id = Input::get('stock_info_id');
        $stock->to_stock_info_id = Input::get('to_stock_info_id');
        $stock->status = "Activate";

        $product = Product::find(Input::get('product_id'));
        if(Input::get('entry_type') == 'StockIn')
        {
            $stock->consignment_name = Input::get('consignment_name');
            $product->total_quantity = $product->total_quantity + Input::get('product_quantity');
            Session::flash('message', 'Stock has been Successfully Created && Product Quantity Added');
        }elseif(Input::get('entry_type') == 'StockOut'){
            if($product->total_quantity >= Input::get('product_quantity'))
            {
                $product->total_quantity = $product->total_quantity - Input::get('product_quantity');
                Session::flash('message', 'Stock has been Successfully Created && Product Quantity Subtracted');
            }else{
                Session::flash('message', 'You Dont have enough products in Stock');
            }

        }  elseif(Input::get('entry_type') == 'Transfer')
            {
                Session::flash('message', 'Product Has been successfully Transfered');
            }
        else{
                Session::flash('message', 'Stock has been Successfully Created && Wastage Product saved');
        }
        $product->save();
    }
    private function updateStockData($stock)
    {
        $stock->branch_id = Input::get('branch_id');
        $stock->product_id = Input::get('product_id');
        $stock->product_type = Input::get('product_type');
        $stock->product_quantity = Input::get('product_quantity');
        $stock->entry_type = Input::get('entry_type');
        $stock->remarks = Input::get('remarks');
        $stock->user_id = Session::get('user_id');
        $stock->stock_info_id = Input::get('stock_info_id');
        $stock->stock_info_id = Input::get('to_stock_info_id');
        $stock->status = "Activate";
        $product = Product::find(Input::get('product_id'));
        if(Input::get('entry_type') == 'StockIn')
        {
            $stock->consignment_name = Input::get('consignment_name');
            $product->total_quantity = ($product->total_quantity - $stock->product_quantity) + Input::get('product_quantity');
            Session::flash('message', 'Stock has been Successfully Updated && Product Quantity Updated');
        }elseif(Input::get('entry_type') == 'StockOut'){
            $product->total_quantity = ($product->total_quantity + $stock->product_quantity) - Input::get('product_quantity');
            Session::flash('message', 'Stock has been Successfully Updated && Product Quantity Updated');

        }elseif(Input::get('entry_type') == 'Transfer')
        {
            Session::flash('message', 'Product Has been successfully Transfered and Updated');
        }
        else{
            Session::flash('message', 'Stock has been Successfully Created && Wastage Product Updated');
        }
        $product->save();
    }
    public function getDelete($id)
    {
        $stock = Stock::find($id);
        $stock->delete();
        Session::flash('message', 'Stock  has been Successfully Deleted.');
        return Redirect::to('stocks/index');
    }

}
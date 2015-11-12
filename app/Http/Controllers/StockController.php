<?php namespace App\Http\Controllers;

use App\Branch;
use App\Import;
use App\Product;
use App\Stock;
use App\StockCount;
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
        $stocks = Stock::orderBy('id','DESC')->paginate(15);
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
        $product_id= Input::get('data');//database theke astece ai product id

        foreach ($productsName as $productName) {

            $category = $productName->category->name;
            $subCategory = $productName->subCategory->name;
            if($productName->id==$product_id)
            {
                echo '<option value = "'.$productName->id.'" selected> '.$productName->name.' ('.$category.') ('.$subCategory.')</option> ';
            }
            else
            {
                echo "<option value = $productName->id > $productName->name ($category) ($subCategory)</option> ";
            }

        }
    }
    public  function getImports()
    {
        $imports = Import::where('status','=','Activate')
            ->get();
        echo "<option value ='N/A' >N/A </option>";
        foreach ($imports as $import) {
            echo "<option value = $import->consignment_name > $import->consignment_name</option> ";
        }
    }
    public  function getQuantity()
    {
        $stock_info_id = Input::get('stock_info_id');
        $product_id = Input::get('product_id');

        $productsQuantity = StockCount::where('product_id', '=', $product_id)
            ->where('stock_info_id', '=', $stock_info_id)
            ->first();
        if ($stock_info_id == '' || $product_id=='')
        {
            echo "<p3 style='color: red;font-size: 150%'>You have to Choose Both</p3>";
        }
        elseif(empty($productsQuantity))
        {
            echo "<p3 style='color: red;font-size: 150%'>Available 0</p3>";
        }elseif(!empty($productsQuantity)){
            echo "<p3 style='color: green;font-size: 150%'>Available $productsQuantity->product_quantity</p3>";
        }

    }
    public  function getStocks()
    {
        $stocks = StockInfo::where('status','=','Activate')
            ->get();

        foreach ($stocks as $stock) {
            echo "<option value = $stock->id > $stock->name</option> ";
        }
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
            $stockCounts = new StockCount();
            $this->setStockData($stock,$stockCounts);


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
    private function setStockData($stock,$stockCounts)
    {
        $this->insertStockData($stock);

        $stockCount = StockCount::where('product_id','=',Input::get('product_id'))
            ->where('stock_info_id','=',Input::get('stock_info_id'))
            ->get();
        if(Input::get('entry_type') == 'StockIn')
        {
            $stock->consignment_name = Input::get('consignment_name');
            if(empty($stockCount[0]))
            {

                $stockCounts->product_id = Input::get('product_id');
                $stockCounts->stock_info_id = Input::get('stock_info_id');
                $stockCounts->product_quantity = Input::get('product_quantity');
                $stock->save();
                $stockCounts->save();
            }else{

                $stockCount[0]->product_quantity = $stockCount[0]->product_quantity + Input::get('product_quantity');
                $stock->save();
                $stockCount[0]->save();
            }
            Session::flash('message', 'Stock has been Successfully Created && Product Quantity Added');
        }elseif(Input::get('entry_type') == 'StockOut'){
            if(!empty($stockCount[0])) {
                if ($stockCount[0]->product_quantity >= Input::get('product_quantity')) {
                    $stockCount[0]->product_quantity = $stockCount[0]->product_quantity - Input::get('product_quantity');
                    $stock->save();
                    $stockCount[0]->save();
                    Session::flash('message', 'Stock has been Successfully Created && Product Quantity Subtracted');
                } else {
                    Session::flash('message', 'You Dont have enough products in Stock');
                }
            }else{
                Session::flash('message', 'You Dont have This products in This Stock');
            }


        }  elseif(Input::get('entry_type') == 'Transfer')
            {
                if(!empty($stockCount[0])) {
                    if ($stockCount[0]->product_quantity >= Input::get('product_quantity')) {
                        $stockCount[0]->product_quantity = $stockCount[0]->product_quantity - Input::get('product_quantity');
                        $stockCount[0]->save();

                        $stockCountTo = StockCount::where('product_id','=',Input::get('product_id'))
                            ->where('stock_info_id','=',Input::get('to_stock_info_id'))
                            ->get();

                        if(!empty($stockCountTo[0])) {
                            $stockCountTo[0]->product_quantity = $stockCountTo[0]->product_quantity + Input::get('product_quantity');
                            $stock->save();
                            $stockCountTo[0]->save();
                        }else{
                            $stockCounts->product_id = Input::get('product_id');
                            $stockCounts->stock_info_id = Input::get('to_stock_info_id');
                            $stockCounts->product_quantity = Input::get('product_quantity');
                            $stock->save();
                            $stockCounts->save();
                        }

                        Session::flash('message', 'Product Has been successfully Transfered');
                    } else {
                        Session::flash('message', 'You Dont have enough products in Stock');
                    }
                }else{
                    Session::flash('message', 'You Dont have This products in This Stock');
                }

            }
        else{
                $stock->save();
                Session::flash('message', 'Stock has been Successfully Created && Wastage Product saved');
        }

    }
    private function updateStockData($stock)
    {
        $this->insertStockData($stock);
        $stockCount = StockCount::where('product_id','=',Input::get('product_id'))
            ->where('stock_info_id','=',Input::get('stock_info_id'))
            ->get();
        if(Input::get('entry_type') == 'StockIn')
        {
            $stock->consignment_name = Input::get('consignment_name');
            if(empty($stockCount[0]))
            {
                $stockCountOld = StockCount::where('product_id','=',$stock->product_id)
                    ->where('stock_info_id','=',$stock->stock_info_id)
                    ->get();
                $stockCountOld[0]->product_quantity = ($stockCountOld[0]->product_quantity - $stock->product_quantity);
                $stockCountOld[0]->save();

                $stockCounts = new StockCount();
                $stockCounts->product_id = Input::get('product_id');
                $stockCounts->stock_info_id = Input::get('stock_info_id');
                $stockCounts->product_quantity = Input::get('product_quantity');
                $stockCounts->save();
            }else{
                $stockCount[0]->product_quantity = ($stockCount[0]->product_quantity - $stock->product_quantity) + Input::get('product_quantity');
                $stockCount[0]->save();
            }
            Session::flash('message', 'Stock has been Successfully Updated && Product Quantity Updated');
        }elseif(Input::get('entry_type') == 'StockOut'){
            if(!empty($stockCount[0])) {
                if ($stockCount[0]->product_quantity >= Input::get('product_quantity')) {
                    $stockCount[0]->product_quantity = ($stockCount[0]->product_quantity+$stock->product_quantity) - Input::get('product_quantity');
                    $stockCount[0]->save();
                    Session::flash('message', 'Stock has been Successfully Updated && Product Quantity Updated');
                } else {
                    Session::flash('message', 'You Dont have enough products in Stock');
                }
            }else{
                Session::flash('message', 'You Dont have This products in This Stock');
            }

        }elseif(Input::get('entry_type') == 'Transfer')
        {
            if(!empty($stockCount[0])) {
                if ($stockCount[0]->product_quantity >= Input::get('product_quantity')) {
                    $stockCount[0]->product_quantity = ($stockCount[0]->product_quantity + $stock->product_quantity) - Input::get('product_quantity');
                    $stockCount[0]->save();

                    $stockCountTo = StockCount::where('product_id','=',Input::get('product_id'))
                        ->where('stock_info_id','=',Input::get('to_stock_info_id'))
                        ->get();

                    if(!empty($stockCountTo[0])) {
                        $stockCountTo[0]->product_quantity = ($stockCountTo[0]->product_quantity - $stock->product_quantity) + Input::get('product_quantity');
                        $stockCountTo[0]->save();
                    }else{
                        $stockCountToOld = StockCount::where('product_id','=',$stock->product_id)
                            ->where('stock_info_id','=',$stock->to_stock_info_id)
                            ->get();
                        $stockCountToOld[0]->product_quantity = ($stockCountToOld[0]->product_quantity - $stock->product_quantity);
                        $stockCountToOld[0]->save();

                        $stockCounts = new StockCount();
                        $stockCounts->product_id = Input::get('product_id');
                        $stockCounts->stock_info_id = Input::get('to_stock_info_id');
                        $stockCounts->product_quantity = Input::get('product_quantity');
                        $stockCounts->save();
                    }
                    Session::flash('message', 'Product Has been successfully Transfered and Updated');
                } else {
                    Session::flash('message', 'You Dont have enough products in Stock');
                }
            }else{
                Session::flash('message', 'You Dont have This products in This Stock');
            }

        }
        else{
            Session::flash('message', 'Stock has been Successfully Created && Wastage Product Updated');
        }
    }
    public function getDelete($id)
    {
        $stock = Stock::find($id);
        $stockCount = StockCount::where('product_id','=',$stock->product_id)
            ->where('stock_info_id','=',$stock->stock_info_id)
            ->get();

        if($stock->entry_type=='StockIn'){
            $stockCount[0]->product_quantity = $stockCount[0]->product_quantity - $stock->product_quantity;
            $stockCount[0]->save();
        }elseif($stock->entry_type=='StockOut')
        {
            $stockCount[0]->product_quantity = $stockCount[0]->product_quantity + $stock->product_quantity;
            $stockCount[0]->save();
        }elseif($stock->entry_type=='Transfer')
        {
            $stockCount[0]->product_quantity = $stockCount[0]->product_quantity + $stock->product_quantity;
            $stockCount[0]->save();

            $stockCountTo = StockCount::where('product_id','=',$stock->product_id)
                ->where('stock_info_id','=',$stock->to_stock_info_id)
                ->get();
            $stockCountTo[0]->product_quantity = $stockCountTo[0]->product_quantity - $stock->product_quantity;
            $stockCountTo[0]->save();
        }
        $stock->delete();
        Session::flash('message', 'Stock  has been Successfully Deleted.');
        return Redirect::to('stocks/index');
    }

    /**
     * @param $stock
     */
    private function insertStockData($stock)
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
    }

}
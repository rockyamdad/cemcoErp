<?php namespace App\Http\Controllers;

use App\Import;
use App\Product;
use App\Stock;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;

class StockController extends Controller{

    public function getCreate()
    {
        return view('Stocks.addSubtract');
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
        $importsNum = Import::where('status','=','Activate')
            ->get();

        echo "<label class='control-label col-md-3'>Choose Import</label>";
        echo " <div class='col-md-4'> <select class='form-control' name='import_num'><option value ='N/A' >N/A </option>";
        foreach ($importsNum as $importNum) {
            echo "<option value = $importNum->import_num > $importNum->import_num</option> ";
        }
        echo "</select> </div>";
    }
    public function postSaveStock()
    {
        $ruless = array(
            'product_id' => 'required',
            'product_quantity' => 'required',
            'entry_type' => 'required',
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
    private function setStockData($stock)
    {
        $stock->product_id = Input::get('product_id');
        $stock->product_quantity = Input::get('product_quantity');
        $stock->entry_type = Input::get('entry_type');
        $stock->created_by = Session::get('user_id');
        $stock->status = "Activate";
        $product = Product::find(Input::get('product_id'));
        if(Input::get('entry_type') == 1)
        {
            $stock->import_num = Input::get('import_num');
            $product->total_quantity = $product->total_quantity + Input::get('product_quantity');
            Session::flash('message', 'Stock has been Successfully Created && Product Quantity Added');
        }else{
            $product->total_quantity = $product->total_quantity - Input::get('product_quantity');
            Session::flash('message', 'Stock has been Successfully Created && Product Quantity Subtracted');

        }
        $product->save();
    }

}
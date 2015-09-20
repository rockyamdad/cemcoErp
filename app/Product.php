<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Product extends Eloquent
{

    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    public function subCategory()
    {
        return $this->belongsTo('App\SubCategory');
    }
    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function getProducts()
    {

        $products = DB::table('products')->where('product_type', 'Foreign')->get();
        return $products;
    }
    public function getProductsWithCategories()
    {
        $products =Product::all();

        $array = array();

        foreach($products as $product){
            $category = $product->category->name;
            $subCategory = $product->subCategory->name;
            $array[$product->id] = $product->name."($category)"."($subCategory)";
        }

        return $array;
    }

    public function getProductsDropDownForeign()
    {
        $products = $this->getProducts();

        $array = array();

        foreach($products as $product){
            $array[$product->id] = $product->name;
        }

        return $array;
    }
    public function getLocalProductsDropDown()
    {
        $localProducts = Product::where('product_type','Local')->get();

        $array = array();

        foreach($localProducts as $product){
            $array[$product->id] = $product->name;
        }

        return $array;
    }
    public function getFinishGoodsDropDown()
    {
        $finishGoods = Product::where('product_type','Finish Goods')->get();

        $array = array();

        foreach($finishGoods as $finishGood){
            $array[$finishGood->id] = $finishGood->name;
        }

        return $array;
    }
    public function importdetails()
    {
        return $this->belongsToMany('App\importDetail');
    }
    public function stock()
    {
        return $this->hasOne('App\Stock');
    }

}
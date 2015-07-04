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
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getProducts()
    {

        $products = DB::table('products')->get();
        return $products;
    }

    public function getProductsDropDown()
    {
        $products = $this->getProducts();

        $array = array();

        foreach($products as $product){
            $array[$product->id] = $product->name;
        }

        return $array;
    }
    public function importdetails()
    {
        return $this->belongsToMany('App\importDetail');
    }

}
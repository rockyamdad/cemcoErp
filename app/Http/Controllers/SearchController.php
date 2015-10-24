<?php namespace App\Http\Controllers;

use App\Category;
use App\Party;
use App\Product;
use App\ProformaInvoice;
use App\Search;
use App\Stock;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;

class SearchController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getIndex()
    {
       var_dump("ss");exit;
        return view('Parties.list',compact('parties'));
    }
    public function getEntry()
    {
        return view('Searches.stockEntryType');
    }
    public function postSearchResult()
    {
            $type= Input::get('entry_type');
            $date1 = Input::get('from_date');
            $date2 = Input::get('to_date');
            $search = new Search();
            $results = $search->getResultSearchType($type,$date1,$date2);

            return view('Searches.stockEntryTypeResult',compact('results'));
    }
    public function getRequisition()
    {
        $parties = new Party();
        $partyAll = $parties->getPartiesDropDown();
        return view('Searches.stockRequisition',compact('partyAll'));
    }
    public function postRequisitionResult()
    {
            $party= Input::get('party_id');
            $date1 = Input::get('from_date');
            $date2 = Input::get('to_date');
            $search = new Search();
            $results = $search->getResultRequisition($party,$date1,$date2);

            return view('Searches.requisitionResult',compact('results'));
    }
    public function getStockProducts()
    {
        $catogories = new Category();
        $categoriesAll = $catogories->getCategoriesDropDown();
        $products = new Product();
        $productAll = $products->getProductsWithCategories();
        return view('Searches.stockProduct',compact('productAll'))
            ->with('categoriesAll',$categoriesAll);
    }
    public function postStockProductResult()
    {
        $category = Input::get('category_id');
        $product = Input::get('product_id');
        $date1 = Input::get('from_date');
        $date2 = Input::get('to_date');
        $search = new Search();
        $results = $search->getResultStockProducts($category,$product,$date1,$date2);

        return view('Searches.stockProductResult',compact('results'));

    }

}
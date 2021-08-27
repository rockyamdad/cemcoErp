<?php namespace App\Http\Controllers;

use App\AccountCategory;
use App\Branch;
use App\Category;
use App\NameOfAccount;
use App\SubCategory;
use App\Transaction;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getIndex()
    {
        if(Session::get('user_role')=='admin'){
            $categories = Category::orderBy('id','DESC')->paginate(15);
        }else{
            $categories = Category::where('branch_id','=',Session::get('user_branch'))
            ->orderBy('id','DESC')->paginate(15);
        }

       //var_dump($categories); die();
        return view('ProductCategory.list', compact('categories'));
    }
    public function getCreate()
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('ProductCategory.add',compact('branchAll'));
    }
    public function postSaveCategory()
    {
        $ruless = array(
            'name' => 'required'
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('create/')
                ->withErrors($validate);
        }
        else{
            $categories = new Category();
            $this->setCategoriesData($categories);
            $categories->save();
            Session::flash('message', 'Product Category has been Successfully Created.');
            return Redirect::to('productCategories/index');
        }
    }
    public function getEdit($id)
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        $category = Category::find($id);
        return view('ProductCategory.edit',compact('branchAll'))
            ->with('category',$category);
    }
    public function postUpdateCategory($id)
    {
        $ruless = array(
            'name' => 'required',
            'branch_id' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('productCategories/edit/'.$id)
                ->withErrors($validate);
        }
        else{
            $categories = Category::find($id);
            $this->setCategoriesData($categories);
            $categories->save();
            Session::flash('message', 'Product Category has been Successfully Updated.');
            return Redirect::to('productCategories/index');
        }
    }
    public function getDelete($id)
    {
        $category = Category::find($id);
        $data = SubCategory::where('category_id', '=', $category->id)->first();
        if($data){
            Session::flash('error', 'This  Category can\'t delete because it  is used in sub category section');

        }else{

            // Deletion Of Account Category (  This Feature is comment out due to client requirement )
            /*
            $accountCategory = AccountCategory::where('name', '=', $category->name)->first();
            $nameOfAccount = NameOfAccount::where('account_category_id', '=', $accountCategory->id)->first();
            if (!$nameOfAccount) {
                $accountCategory->delete();
            }
            */
            $category->delete();
            Session::flash('message', ' Category has been Successfully Deleted.');
        }

        return Redirect::to('productCategories/index');
    }

    private function setCategoriesData($categories)
    {
        $categories->name = Input::get('name');
        if(Session::get('user_role') == 'admin'){
            $categories->branch_id = Input::get('branch_id');
        }else{
            $categories->branch_id = Session::get('user_branch');
        }

        $categories->user_id = Session::get('user_id');

        // Account Category Creation ( This Feature is comment out due to client requirement )
        /*
        $aCategory = new AccountCategory();
        $aCategory->name = Input::get('name');
        $aCategory->user_id = Session::get('user_id');
        $aCategory->save();
        */


    }
}
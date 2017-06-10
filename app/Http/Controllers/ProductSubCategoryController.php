<?php namespace App\Http\Controllers;

use App\AccountCategory;
use App\Branch;
use App\Category;
use App\NameOfAccount;
use App\Product;
use App\SubCategory;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProductSubCategoryController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getIndex()
    {
        if(Session::get('user_role')=='admin'){
            $subCategories = SubCategory::orderBy('id','DESC')->paginate(15);
        }else{
            $subCategories = SubCategory::where('branch_id','=',Session::get('user_branch'))
                ->orderBy('id','DESC')->paginate(15);
        }
        return view('ProductSubCategory.list', compact('subCategories'));
    }
    public function getCreate()
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('ProductSubCategory.add',compact('branchAll'));
    }
    public function postSaveSubCategory()
    {
        $ruless = array(
            'name' => 'required',
            'category_id' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('productsubCategories/create')
                ->withErrors($validate);
        }
        else{
            $subCategories = new SubCategory();
            $this->setSubCategoriesData($subCategories);
            $subCategories->save();
            Session::flash('message', 'Product Category has been Successfully Created.');
            return Redirect::to('productsubcategories/index');
        }
    }
    public function getEdit($id)
    {
        $categories = new Category();
        $allCategory = $categories->getCategoriesDropDown();
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        $subCategory = SubCategory::find($id);
        return view('ProductSubCategory.edit',compact('branchAll'))
            ->with('subCategory',$subCategory)
            ->with('categoryAll',$allCategory);
    }
    public function postUpdateSubCategory($id)
    {
        $ruless = array(
            'name' => 'required',
            'branch_id' => 'required',
            'category_id' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('productsubcategories/edit/'.$id)
                ->withErrors($validate);
        }
        else{
            $subCategories = SubCategory::find($id);
            $this->setSubCategoriesData($subCategories);
            $subCategories->save();
            Session::flash('message', 'Product SubCategory has been Successfully Updated.');
            return Redirect::to('productsubcategories/index');
        }
    }
    public function getCategorybybranch($branch_id)
    {
        $categoriesName = Category::where('branch_id','=',$branch_id)
            ->get();

        foreach ($categoriesName as $categoryName) {
            echo "<option value = $categoryName->id > $categoryName->name</option> ";
        }
    }

    private function setSubCategoriesData($SubCategories)
    {
        $SubCategories->name = Input::get('name');
        if(Session::get('user_role') == 'admin'){
            $SubCategories->branch_id = Input::get('branch_id');
        }else{
            $SubCategories->branch_id = Session::get('user_branch');
        }
        $SubCategories->category_id = Input::get('category_id');
        $SubCategories->user_id = Session::get('user_id');

        $category = Category::find(Input::get('category_id'));
        $accountCat = AccountCategory::where('name','=',$category->name)->get();

        $accountNames = new NameOfAccount();
        $accountNames->name = Input::get('name');
        $accountNames->branch_id = Input::get('branch_id');
        if(count($accountCat)>0){
            $accountNames->account_category_id = $accountCat[0]->id;
        }else{
            $category = Category::find(Input::get('category_id'));
            $aCategory = new AccountCategory();
            $aCategory->name = $category->name;
            $aCategory->user_id = Session::get('user_id');
            $aCategory->save();
            $accountNames->account_category_id = $aCategory->id;
        }

        $accountNames->opening_balance = 0.00;
        $accountNames->user_id = Session::get('user_id');
        $accountNames->save();

    }
    public function getDelete($id)
    {
        $del = SubCategory::find($id);
        $data = Product::where('sub_category_id','=',$del->id)->first();

        if($data){
            Session::flash('error', 'This Sub Category can\'t delete because it  is used to file');
        }else {
            $del->delete();
            Session::flash('message', 'Sub Category has been Successfully Deleted.');
        }

        return Redirect::to('productsubcategories/index');
    }
}
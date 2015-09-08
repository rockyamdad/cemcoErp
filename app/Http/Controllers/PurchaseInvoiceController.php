<?php namespace App\Http\Controllers;

use App\AccountCategory;

use App\NameOfAccount;
use App\Party;
use App\Product;
use App\PurchaseInvoice;
use App\PurchaseInvoiceDetail;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;

class PurchaseInvoiceController extends Controller{

    public function getIndex()
    {
       $purchases = NameOfAccount::all();

        return view('PurchaseInvoice.list',compact('purchases'));
    }
    public function getCreate()
    {
        $suppliers = new Party();
        $suppliersAll = $suppliers->getSuppliersDropDown();
        $products = new Product();
        $localProducts = $products->getLocalProductsDropDown();

        return view('PurchaseInvoice.add',compact('suppliersAll'))
            ->with('localProducts',$localProducts);
    }
    public function postSavePurchaseInvoice()
    {
        $ruless = array(
            'party_id' => 'required',
            'product_id' => 'required',
            'price' => 'required',
            'quantity' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('purchases/index/')
                ->withErrors($validate);
        }
        else{
            $list = $this->setPurchaseInvoiceData();
            return new JsonResponse($list);

        }
    }
    private function setPurchaseInvoiceData()
    {
        $purchases = new PurchaseInvoice();
        $purchaseDetails = new PurchaseInvoiceDetail();
        $purchaseDetails->quantity = Input::get('quantity');
        $purchaseDetails->price = Input::get('price');
        $purchaseDetails->invoice_id = (int)Input::get('invoice_id');
        $purchaseDetails->product_id = Input::get('product_id');
        $purchaseDetails->remarks = Input::get('remarks');
        $purchaseDetails->save();

        $hasInvoice = PurchaseInvoice::where('invoice_id','=',(int)Input::get('invoice_id'))->get();
        if(empty($hasInvoice[0])){
            $purchases->party_id = Input::get('party_id');
            $purchases->status = "Activate";
            $purchases->invoice_id = (int)Input::get('invoice_id');
            $purchases->created_by = Session::get('user_id');
            $purchases->save();
        }

        $purchaseInvoiceDetails = PurchaseInvoiceDetail::find($purchaseDetails->id);
        $list = $this->purchaseInvoiceDetailConvertToArray($purchaseInvoiceDetails);
        return $list;
    }
    private function purchaseInvoiceDetailConvertToArray($purchaseInvoiceDetails)
    {
        $array = array();

        $array['id'] = $purchaseInvoiceDetails->id;
        $array['product_id'] = $purchaseInvoiceDetails->product_id;
        $array['price'] = $purchaseInvoiceDetails->price;
        $array['quantity']   = $purchaseInvoiceDetails->quantity;
        $array['remarks'] = $purchaseInvoiceDetails->remarks;
        return $array;
    }
    public function getDelete($id)
    {
        $puchase = PurchaseInvoiceDetail::find($id);
        $puchase->delete();
        $message = array('Purchase Invoice  Successfully Deleted');
        return new JsonResponse($message);
    }
  /*  public function getEdit($id)
    {
        $account = NameOfAccount::find($id);
        $accountCategories = new AccountCategory();
        $accountCategoriesAll = $accountCategories->getAccountCategoriesDropDown();
        return view('AccountName.edit',compact('account'))
            ->with('accountCategoriesAll',$accountCategoriesAll);

    }
    public function postUpdate($id)
    {
        $ruless = array(
            'name' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('accountnames/index/')
                ->withErrors($validate);
        }
        else{
            $accountNames = NameOfAccount::find($id);
            $this->setAccountNameData($accountNames);
            $accountNames->save();
            Session::flash('message', 'Account Name  has been Successfully Updated.');
            return Redirect::to('accountnames/index');
        }
    }

    private function setAccountNameData($accountNames)
    {
        $accountNames->name = Input::get('name');
        $accountNames->account_category_id = Input::get('account_category_id');
        $accountNames->opening_balance = Input::get('opening_balance');
        $accountNames->created_by = Session::get('user_id');
    }
    public function getDelete($id)
    {
        $accountNames = NameOfAccount::find($id);
        $accountNames->delete();
        Session::flash('message', 'Account Name  has been Successfully Deleted.');
        return Redirect::to('accountnames/index');
    }*/

}
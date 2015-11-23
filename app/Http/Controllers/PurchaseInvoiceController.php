<?php namespace App\Http\Controllers;

use App\AccountCategory;


use App\Branch;
use App\NameOfAccount;
use App\Party;
use App\Product;
use App\PurchaseInvoice;
use App\PurchaseInvoiceDetail;
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

class PurchaseInvoiceController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getIndex()
    {
       $purchases = PurchaseInvoice::orderBy('id','DESC')->paginate(15);

        return view('PurchaseInvoice.list',compact('purchases'));
    }
    public function getCreate()
    {
        $suppliers = new Party();
        $suppliersAll = $suppliers->getSuppliersDropDown();
        $products = new Product();
        $localProducts = $products->getLocalProductsDropDown();
        $stockInfos = new StockInfo();
        $allStockInfos = $stockInfos->getStockInfoDropDown();
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();

        return view('PurchaseInvoice.add',compact('suppliersAll'))
            ->with('localProducts',$localProducts)
            ->with('branchAll',$branchAll)
            ->with('allStockInfos',$allStockInfos);
    }
    public function postSavePurchaseInvoice()
    {
        $ruless = array(
            'branch_id' => 'required',
            'stock_info_id' => 'required',
            'product_type' => 'required',
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
    public function postUpdate($id)
    {

        $ruless = array(
            'branch_id' => 'required',
            'stock_info_id' => 'required',
            'product_type' => 'required',
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
        $purchaseDetails->detail_invoice_id = Input::get('invoice_id');
        $purchaseDetails->product_id = Input::get('product_id');
        $purchaseDetails->branch_id = Input::get('branch_id');
        $purchaseDetails->stock_info_id = Input::get('stock_info_id');
        $purchaseDetails->product_type = Input::get('product_type');
        $purchaseDetails->remarks = Input::get('remarks');
        $purchaseDetails->save();
        $hasInvoice = PurchaseInvoice::where('invoice_id','=',Input::get('invoice_id'))->get();
        if(empty($hasInvoice[0])){
            $purchases->party_id = Input::get('party_id');
            $purchases->status = "Activate";
            $purchases->invoice_id = (int)Input::get('invoice_id');
            $purchases->user_id = Session::get('user_id');
            $purchases->save();
        }

        $purchaseInvoiceDetails = PurchaseInvoiceDetail::find($purchaseDetails->id);
        $list = $this->purchaseInvoiceDetailConvertToArray($purchaseInvoiceDetails);
        return $list;
    }
    private function purchaseInvoiceDetailConvertToArray($purchaseInvoiceDetails)
    {
        $array = array();
        $stockName = StockInfo::find($purchaseInvoiceDetails->stock_info_id);
        $branchName = Branch::find($purchaseInvoiceDetails->branch_id);
        $array['id'] = $purchaseInvoiceDetails->id;
        $array['branch_id'] = $branchName->name;
        $array['stock_info_id'] = $stockName->name;
        $array['product_type'] = $purchaseInvoiceDetails->product_type;
        $array['product_id'] = $purchaseInvoiceDetails->product->name;
        $array['price'] = $purchaseInvoiceDetails->price;
        $array['quantity']   = $purchaseInvoiceDetails->quantity;
        $array['remarks'] = $purchaseInvoiceDetails->remarks;
        return $array;
    }
    public function getDetails($invoiceId)
    {
        $purchaseInvoiceDetails = PurchaseInvoiceDetail::where('detail_invoice_id','=',$invoiceId)->get();
        $purchaseInvoiceTransactions = Transaction::where('invoice_id','=',$invoiceId)->get();
        $purchase = PurchaseInvoice::where('invoice_id','=',$invoiceId)->first();
        return view('PurchaseInvoice.details',compact('purchaseInvoiceDetails'))
            ->with('purchase',$purchase)
            ->with('purchaseInvoiceTransactions',$purchaseInvoiceTransactions);

    }
    public function getMake($invoice_id)
    {
        $accountCategories = new AccountCategory();
        $accountCategoriesAll = $accountCategories->getAccountCategoriesDropDown();
        $purchaseDetails = new PurchaseInvoiceDetail();
        $transactions = new Transaction();
        $purchaseDetailsAmount = $purchaseDetails->getTotalAmount($invoice_id);
        $transactionsPaid = $transactions->getTotalPaidPurchase($invoice_id);
        return view('PurchaseInvoice.paymentAdd',compact('accountCategoriesAll'))
            ->with('purchaseDetailsAmount',$purchaseDetailsAmount)
            ->with('transactionsPaid',$transactionsPaid);
    }
    public function postSaveMake()
    {
        $ruless = array(
            'account_category_id' => 'required',
            'account_name_id' => 'required',
            'amount' => 'required',
            'payment_method' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('purchases/index/')
                ->withErrors($validate);
        }
        else{

            $this->setPurchasePayment();

            return Redirect::to('purchases/index');

        }
    }
    private function setPurchasePayment()
    {
        $accountPayment = NameOfAccount::find(Input::get('account_name_id'));
        if($accountPayment->opening_balance >= Input::get('amount')){

                $purchases[0] = PurchaseInvoice::where('invoice_id','=',Input::get('invoice_id'))->get();
                $purchaseTransaction = new Transaction();
                $purchaseTransaction->account_category_id = Input::get('account_category_id');
                $purchaseTransaction->account_name_id = Input::get('account_name_id');
                $purchaseTransaction->amount = Input::get('amount');
                $purchaseTransaction->remarks = Input::get('remarks');
                $purchaseTransaction->user_id = Session::get('user_id');
                $purchaseTransaction->type = "Payment";
                $purchaseTransaction->payment_method = Input::get('payment_method');
                $purchaseTransaction->invoice_id = Input::get('invoice_id');
                $purchaseTransaction->cheque_no = Input::get('cheque_no');


                $totalAmount = 0;
                $totalPrice = 0;
                $purchaseDetails = PurchaseInvoiceDetail::where('detail_invoice_id','=',$purchaseTransaction->invoice_id)->get();
                $transactions = Transaction::where('invoice_id','=',$purchaseTransaction->invoice_id)->get();
                foreach($purchaseDetails as $purchaseDetail)
                {
                    $totalPrice =$totalPrice + ($purchaseDetail->price * $purchaseDetail->quantity);
                }
                foreach($transactions as $transaction)
                {
                    $totalAmount =$totalAmount + ($transaction->amount);
                }
                $purchaseInvoice = PurchaseInvoice::find( $purchases[0][0]['id']);
                if($totalAmount == $totalPrice)
                {
                    $purchaseInvoice->status = "Completed";
                }else{
                    $purchaseInvoice->status = "Partial";
                }
                $purchaseInvoice->save();
                $purchaseTransaction->save();
                $accountPayment->opening_balance = $accountPayment->opening_balance - Input::get('amount');
                $accountPayment->save();
                Session::flash('message', 'Payment has been Successfully Cleared.');
        }else{
            Session::flash('message', 'You dont have Enough Balance');
        }


    }

    public function getEdit($id)
    {
        $suppliers = new Party();
        $suppliersAll = $suppliers->getSuppliersDropDown();
        $products = new Product();
        $localProducts = $products->getLocalProductsDropDown();
        $stockInfos = new StockInfo();
        $allStockInfos = $stockInfos->getStockInfoDropDown();
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        $purchase[0] = PurchaseInvoice::where('invoice_id','=',$id)->get();
        $var = $purchase[0];
        $purchaseDetails = PurchaseInvoiceDetail::where('detail_invoice_id','=',$id)->get();
        return view('PurchaseInvoice.edit',compact('suppliersAll'))
            ->with('localProducts',$localProducts)
            ->with('purchaseDetails',$purchaseDetails)
            ->with('purchase',$var)
            ->with('branchAll',$branchAll)
            ->with('allStockInfos',$allStockInfos);

    }
    public function getDelete($id)
    {
        $purchase = PurchaseInvoiceDetail::find($id);
        $purchase->delete();

        $message = array('Purchase Invoice  Successfully Deleted');
        return new JsonResponse($message);
    }
    public function getDeletepurchasedetail($id)
    {
        $purchaseDetail = PurchaseInvoiceDetail::find($id);
        $purchaseDetail->delete();
        Session::flash('message', 'Purchase Detail  Successfully Deleted');
        return Redirect::to('purchases/index');
    }
    public function getDeleteTransaction($id)
    {
        $transaction = Transaction::find($id);
        $account_id = Input::get('data');
        $accounts = NameOfAccount::find($account_id);
        $accounts->opening_balance = $accounts->opening_balance + $transaction->amount;
        $accounts->save();
        $transaction->delete();
        $message = array('Transaction Successfully Deleted');
        return new JsonResponse($message);
    }
    public function getDel($id)
    {
        $del = PurchaseInvoice::where('invoice_id','=',$id)->get();
        try {
            $del[0]->deletee();
            Session::flash('message', 'Purchase Invoice has been Successfully Deleted.');
        } catch (Exception $e) {
            Session::flash('message', 'This Purchase Invoice can\'t delete because it  is used to file');
        }
        return Redirect::to('purchases/index');
    }
    public function getCategories($category_id)
    {
        $categoriesName = NameOfAccount::where('account_category_id','=',$category_id)
            ->get();
        foreach ($categoriesName as $categoryName) {
            echo "<option value = $categoryName->id > $categoryName->name</option> ";
        }
    }
    public function getProducts($branch_id)
    {

        $poductsNames = Product::where('branch_id','=',$branch_id)
            ->get();

        foreach ($poductsNames as $product) {

            echo "<option value = $product->id > $product->name</option> ";

        }
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
    public function getProductbalance($product_id)
    {
        $stockCount = StockCount::where('product_id','=',$product_id)
            ->where('stock_info_id','=',Input::get('data'))
            ->first();
        if($stockCount){
            echo "<p3 style='color: blue;font-size: 100%; margin-left: 32px;'>Your product Balance This Stock is $stockCount->product_quantity</p3>";

        }else{
            echo "<p3 style='color: blue;font-size: 100%; margin-left: 32px; '>You Dont have this Product In this Stock</p3>";

        }

    }
    public function getAccountbalance($account_id)
    {
        $accountBalance = NameOfAccount::find($account_id);
        echo "<p3 style='color: blue;font-size: 130%'>Your Current Balance $accountBalance->opening_balance</p3>";
    }



}
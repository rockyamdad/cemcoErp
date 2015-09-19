<?php namespace App\Http\Controllers;

use App\AccountCategory;


use App\NameOfAccount;
use App\Party;
use App\Product;
use App\PurchaseInvoice;
use App\PurchaseInvoiceDetail;
use App\Transaction;
use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class PurchaseInvoiceController extends Controller{

    public function getIndex()
    {
       $purchases = PurchaseInvoice::all();

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
    public function updatePurchaseInvoiceData($id)
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
        $purchaseDetails->detail_invoice_id = Input::get('invoice_id');
        $purchaseDetails->product_id = Input::get('product_id');
        $purchaseDetails->remarks = Input::get('remarks');
        $purchaseDetails->save();
        $hasInvoice = PurchaseInvoice::where('invoice_id','=',Input::get('invoice_id'))->get();
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
        return view('PurchaseInvoice.details',compact('purchaseInvoiceDetails'))
            ->with('purchaseInvoiceTransactions',$purchaseInvoiceTransactions);

    }
    public function getMake()
    {
        $accountCategories = new AccountCategory();
        $accountCategoriesAll = $accountCategories->getAccountCategoriesDropDown();
        return view('PurchaseInvoice.paymentAdd',compact('accountCategoriesAll'));
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
                $purchaseTransaction->type = "Payment";
                $purchaseTransaction->payment_method = Input::get('payment_method');
                $purchaseTransaction->invoice_id = Input::get('invoice_id');
                $purchaseTransaction->save();

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
        $purchase[0] = PurchaseInvoice::where('invoice_id','=',$id)->get();
        $var = $purchase[0];
        $purchaseDetails = PurchaseInvoiceDetail::where('detail_invoice_id','=',$id)->get();
         //var_dump($var);exit;
        return view('PurchaseInvoice.edit',compact('suppliersAll'))
            ->with('localProducts',$localProducts)
            ->with('purchaseDetails',$purchaseDetails)
            ->with('purchase',$var);

    }
    public function getDelete($id)
    {
        $purchase = PurchaseInvoiceDetail::find($id);
        $purchase->delete();
        $message = array('Purchase Invoice  Successfully Deleted');
        return new JsonResponse($message);
    }
    public function getDeleteDetail($id)
    {
        $purchaseDetail = PurchaseInvoiceDetail::find($id);
        $purchaseDetail->delete();
        $message = array('Purchase Invoice  Successfully Deleted');
        return new JsonResponse($message);
    }
    public function getDeleteTransaction($id)
    {
        $transaction = Transaction::find($id);
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



}
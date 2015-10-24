<?php namespace App\Http\Controllers;

use App\AccountCategory;


use App\NameOfAccount;
use App\Party;
use App\Product;
use App\PurchaseInvoice;
use App\PurchaseInvoiceDetail;
use App\Sale;
use App\SaleDetail;
use App\Transaction;
use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class SaleController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getIndex()
    {
       $sales = Sale::all();

        return view('Sales.list',compact('sales'));
    }
    public function getCreate()
    {
        $buyers = new Party();
        $buyersAll = $buyers->getBuyersDropDown();
        $products = new Product();
        $finishGoods = $products->getFinishGoodsDropDown();

        return view('Sales.add',compact('buyersAll'))
            ->with('finishGoods',$finishGoods);
    }
    public function postSaveSales()
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
            return Redirect::to('sales/index/')
                ->withErrors($validate);
        }
        else{
            $list = $this->setSaleData();
            return new JsonResponse($list);

        }
    }
    public function getEdit($id)
    {
        $buyers = new Party();
        $buyersAll = $buyers->getBuyersDropDown();
        $products = new Product();
        $finishGoods = $products->getFinishGoodsDropDown();
        $sale[0] = Sale::where('invoice_id','=',$id)->get();
        $var = $sale[0];
        $saleDetails = SAleDetail::where('invoice_id','=',$id)->get();

        return view('Sales.edit',compact('buyersAll'))
            ->with('finishGoods',$finishGoods)
            ->with('saleDetails',$saleDetails)
            ->with('sale',$var);

    }
    public function updateSaleData($id)
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
            return Redirect::to('sales/index/')
                ->withErrors($validate);
        }
        else{
            $list = $this->setSaleData();
            return new JsonResponse($list);

        }
    }
    private function setSaleData()
    {
        $sale = new Sale();
        $saleDetails = new SaleDetail();
        $saleDetails->quantity = Input::get('quantity');
        $saleDetails->price = Input::get('price');
        $saleDetails->invoice_id = Input::get('invoice_id');
        $saleDetails->product_id = Input::get('product_id');
        $saleDetails->remarks = Input::get('remarks');
        $saleDetails->save();
        $hasSale = Sale::where('invoice_id','=',Input::get('invoice_id'))->get();
        if(empty($hasSale[0])){
            $sale->party_id = Input::get('party_id');
            $sale->status = "Activate";
            $sale->invoice_id = Input::get('invoice_id');
            $sale->user_id = Session::get('user_id');
            $sale->save();
        }

        $salesDetails = SaleDetail::find($saleDetails->id);
        $list = $this->saleDetailConvertToArray($salesDetails);
        return $list;
    }
    private function saleDetailConvertToArray($salesDetails)
    {
        $array = array();

        $array['id'] = $salesDetails->id;
        $array['product_id'] = $salesDetails->product->name;
        $array['price'] = $salesDetails->price;
        $array['quantity']   = $salesDetails->quantity;
        $array['remarks'] = $salesDetails->remarks;
        return $array;
    }
    public function getDelete($id)
    {
        $del = Sale::where('invoice_id','=',$id)->get();
        try {
            $del[0]->deletee();
            Session::flash('message', 'Sale has been Successfully Deleted.');
        } catch (Exception $e) {
            Session::flash('message', 'This Sale can\'t delete because it  is used to file');
        }
        return Redirect::to('sales/index');


    }
    public function getDetails($invoiceId)
    {
        $saleDetails = SaleDetail::where('invoice_id','=',$invoiceId)->get();
        $saleTransactions = Transaction::where('invoice_id','=',$invoiceId)->get();
        return view('Sales.details',compact('saleDetails'))
            ->with('saleTransactions',$saleTransactions);

    }
    public function getMake()
    {
        $accountCategories = new AccountCategory();
        $accountCategoriesAll = $accountCategories->getAccountCategoriesDropDown();
        return view('Sales.paymentAdd',compact('accountCategoriesAll'));
    }
    public function postSaveReceive()
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
            return Redirect::to('sales/index/')
                ->withErrors($validate);
        }
        else{

            $this->setReceiveSalePayment();

            return Redirect::to('sales/index');

        }
    }
    private function setReceiveSalePayment()
    {
        $sales[0] = Sale::where('invoice_id','=',Input::get('invoice_id'))->get();
        $saleTransaction = new Transaction();
        $saleTransaction->account_category_id = Input::get('account_category_id');
        $saleTransaction->account_name_id = Input::get('account_name_id');
        $saleTransaction->amount = Input::get('amount');
        $saleTransaction->remarks = Input::get('remarks');
        $saleTransaction->type = "Payment";
        $saleTransaction->user_id = Session::get('user_id');
        $saleTransaction->payment_method = Input::get('payment_method');
        $saleTransaction->invoice_id = Input::get('invoice_id');


        $totalAmount = 0;
        $totalPrice = 0;
        $saleDetails = SaleDetail::where('invoice_id','=',$saleTransaction->invoice_id)->get();
        $transactions = Transaction::where('invoice_id','=',$saleTransaction->invoice_id)->get();
        foreach($saleDetails as $saleDetail)
        {
            $totalPrice =$totalPrice + ($saleDetail->price * $saleDetail->quantity);
        }
        foreach($transactions as $transaction)
        {
            $totalAmount =$totalAmount + ($transaction->amount);
        }
        $sale = Sale::find( $sales[0][0]['id']);
        if($totalAmount == $totalPrice)
        {
            $sale->status = "Completed";
        }else{
            $sale->status = "Partial";
        }
        $accountPayment = NameOfAccount::find(Input::get('account_name_id'));

        $accountPayment->opening_balance = $accountPayment->opening_balance + Input::get('amount');
        $saleTransaction->save();
        $sale->save();
        $accountPayment->save();
        Session::flash('message', 'Sales Due  has been Successfully Received.');
    }



    public function getDeleteDetail($id)
    {
        $saleDetail = SaleDetail::find($id);
        $saleDetail->delete();
        $message = array('Sale Detail   Successfully Deleted');
        return new JsonResponse($message);
    }
    public function getDeleteTransaction($id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete();
        $message = array('Transaction Successfully Deleted');
        return new JsonResponse($message);
    }
   /* public function getDel($id)
    {
        $purchase = SaleDetail::find($id);
        $purchase->delete();
        $message = array('Sale Detail  Successfully Deleted');
        return new JsonResponse($message);
    }*/
    public function getCategories($category_id)
    {
        $categoriesName = NameOfAccount::where('account_category_id','=',$category_id)
            ->get();
        foreach ($categoriesName as $categoryName) {
            echo "<option value = $categoryName->id > $categoryName->name</option> ";
        }
    }



}
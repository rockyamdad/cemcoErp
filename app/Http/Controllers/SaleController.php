<?php namespace App\Http\Controllers;

use App\AccountCategory;


use App\Branch;
use App\NameOfAccount;
use App\Party;
use App\Product;
use App\PurchaseInvoice;
use App\PurchaseInvoiceDetail;
use App\Sale;
use App\SAleDetail;
use App\Stock;
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

class SaleController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getIndex()
    {
        $sales = Sale::orderBy('id','DESC')->paginate(15);
        $sale = new Sale();
        $allInvoices = $sale->getSalesInvoiceDropDown();
        $invoice = Input::get('invoice_id');
        $saleInvoice = Sale::where('invoice_id',$invoice)->get();
        return view('Sales.list',compact('sales'))
            ->with('allInvoices',$allInvoices)
            ->with('saleInvoice',$saleInvoice);
    }
    public function getCreate()
    {
        $buyers = new Party();
        $buyersAll = $buyers->getBuyersDropDown();
        $products = new Product();
        $finishGoods = $products->getFinishGoodsDropDown();
        $stockInfos = new StockInfo();
        $allStockInfos = $stockInfos->getStockInfoDropDown();
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
    // Invoice Id Generation Starts
        $invoiceid =$this->generateInvoiceId();
        //var_dump($invoiceid);

        return view('Sales.add',compact('buyersAll'))
            ->with('finishGoods',$finishGoods)
            ->with('branchAll',$branchAll)
            ->with('invoiceid',$invoiceid)
            ->with('allStockInfos',$allStockInfos);
    }
    public function postSaveSales()
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
        $stockInfos = new StockInfo();
        $allStockInfos = $stockInfos->getStockInfoDropDown();
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        $sale[0] = Sale::where('invoice_id','=',$id)->get();
        $var = $sale[0];
        $saleDetails = SAleDetail::where('invoice_id','=',$id)->get();


        return view('Sales.edit',compact('buyersAll'))
            ->with('finishGoods',$finishGoods)
            ->with('saleDetails',$saleDetails)
            ->with('sale',$var)
            ->with('branchAll',$branchAll)
            ->with('allStockInfos',$allStockInfos);

    }
    public function updateSaleData($id)
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
        $saleDetails = new SAleDetail();
        $saleDetails->quantity = Input::get('quantity');
        $saleDetails->price = Input::get('price');
        $saleDetails->invoice_id = Input::get('invoice_id');
        $saleDetails->product_id = Input::get('product_id');
        $saleDetails->branch_id = Input::get('branch_id');
        $saleDetails->stock_info_id = Input::get('stock_info_id');
        $saleDetails->product_type = Input::get('product_type');
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

        $salesDetails = SAleDetail::find($saleDetails->id);
        $list = $this->saleDetailConvertToArray($salesDetails);
        return $list;
    }
    private function saleDetailConvertToArray($salesDetails)
    {
        $array = array();
        $stockName = StockInfo::find($salesDetails->stock_info_id);
        $branchName = Branch::find($salesDetails->branch_id);

        $array['id'] = $salesDetails->id;
        $array['branch_id'] = $branchName->name;
        $array['stock_info_id'] = $stockName->name;
        $array['product_type'] = $salesDetails->product_type;
        $array['product_id'] = $salesDetails->product->name;
        $array['price'] = $salesDetails->price;
        $array['quantity']   = $salesDetails->quantity;
        $array['remarks'] = $salesDetails->remarks;
        return $array;
    }
    public function getDelete($id)
    {
        Sale::where('invoice_id','=',$id)->delete();
        SAleDetail::where('invoice_id','=',$id)->delete();

        Session::flash('message', 'Sale has been Successfully Deleted.');
        return Redirect::to('sales/index');
    }
    public function getDetails($invoiceId)
    {
        $saleDetails = SAleDetail::where('invoice_id','=',$invoiceId)->get();
        $saleTransactions = Transaction::where('invoice_id','=',$invoiceId)->get();
        $sale = Sale::where('invoice_id','=',$invoiceId)->first();
        return view('Sales.details',compact('saleDetails'))
            ->with('saleTransactions',$saleTransactions)
            ->with('sale',$sale);

    }
    public function getShowinvoice($invoiceId)
    {
        $saleDetails = SAleDetail::where('invoice_id','=',$invoiceId)->get();
        $sale = Sale::where('invoice_id','=',$invoiceId)->first();
        return view('Sales.showInvoice',compact('saleDetails'))
            ->with('sale',$sale);

    }
    public function getMake($invoice_id)
    {
        $accountCategories = new AccountCategory();
        $saleDetails = new SAleDetail();
        $transactions = new Transaction();
        $accountCategoriesAll = $accountCategories->getAccountCategoriesDropDown();
        $saleDetailsAmount = $saleDetails->getTotalAmount($invoice_id);
        $transactionsPaid = $transactions->getTotalPaid($invoice_id);
        $saleDetailsBranch = SAleDetail::where('invoice_id','=',$invoice_id)->first();
        return view('Sales.paymentAdd',compact('accountCategoriesAll'))
            ->with('saleDetailsAmount',$saleDetailsAmount)
            ->with('saleDetailsBranch',$saleDetailsBranch->branch_id)
            ->with('invoice_id',$invoice_id)
            ->with('transactionsPaid',$transactionsPaid);
    }
    public function getMakeall()
    {
        $accountCategories = new AccountCategory();
        $saleDetails = new SAleDetail();
        $transactions = new Transaction();
        $accountCategoriesAll = $accountCategories->getAccountCategoriesDropDown();
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        $buyers = new Party();
        $partyAll = $buyers->getPartiesDropDown();
        return view('Sales.paymentAddAll',compact('accountCategoriesAll'))
            ->with('branchAll',$branchAll)
            ->with('partyAll',$partyAll);
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
        $sales = Sale::where('invoice_id','=',Input::get('invoice_id'))->first();
        $saleTransaction = new Transaction();
        $saleTransaction->account_category_id = Input::get('account_category_id');
        $saleTransaction->account_name_id = Input::get('account_name_id');
        $saleTransaction->amount = Input::get('amount');
        $saleTransaction->remarks = Input::get('remarks');
        $saleTransaction->type = "Receive";
        $saleTransaction->user_id = Session::get('user_id');
        $saleTransaction->payment_method = Input::get('payment_method');
        $saleTransaction->cheque_no = Input::get('cheque_no');
        $saleTransaction->cheque_date=Input::get('cheque_date');
        $saleTransaction->cheque_bank=Input::get('cheque_bank');
        $saleTransaction->invoice_id = Input::get('invoice_id');


        $totalAmount = 0;
        $totalPrice = 0;
        $saleDetails = SAleDetail::where('invoice_id','=',$saleTransaction->invoice_id)->get();
        $transactions = Transaction::where('invoice_id','=',$saleTransaction->invoice_id)->get();
        foreach($saleDetails as $saleDetail)
        {
            $totalPrice = $totalPrice + ($saleDetail->price * $saleDetail->quantity);
        }
        foreach($transactions as $transaction)
        {
            $totalAmount =$totalAmount + ($transaction->amount);
        }
        $sale = Sale::find( $sales->id);
        if($totalAmount == $totalPrice)
        {
            $sale->status = "Completed";
        }else{
            $sale->status = "Partial";
        }
        $accountPayment = NameOfAccount::find(Input::get('account_name_id'));

        $accountPayment->opening_balance = $accountPayment->opening_balance + Input::get('amount');

        $saleTransaction->branch_id = Input::get('branch_id');
        $saleTransaction->save();
        $sale->save();
        $accountPayment->save();
        Session::flash('message', 'Sales Due  has been Successfully Received.');
    }



    public function getDeleteDetail($id)
    {
        $saleDetail = SAleDetail::find($id);
        $saleDetail->delete();
        Session::flash('message', 'Sale Detail   Successfully Deleted');
        return Redirect::to('sales/index');
    }
    public function getSale($invoice_id)
    {
        $saleDetails = SAleDetail::where('invoice_id','=',$invoice_id)->get();
        $sale = Sale::where('invoice_id','=',$invoice_id)->get();
       foreach($saleDetails as $saleDetail)
       {
           $stock = new Stock();
           $stock->branch_id = $saleDetail->branch_id;
           $stock->product_id = $saleDetail->product_id;
           $stock->product_type = $saleDetail->product_type;
           $stock->product_quantity = $saleDetail->quantity;
           $stock->entry_type = "StockOut";
           $stock->remarks = $saleDetail->remarks;
           $stock->user_id = Session::get('user_id');
           $stock->stock_info_id = $saleDetail->stock_info_id;
           $stock->status = "Activate";

           $stockCount = StockCount::where('product_id','=',$saleDetail->product_id)
               ->where('stock_info_id','=',$saleDetail->stock_info_id)
               ->get();

           if(!empty($stockCount[0])) {
               if ($stockCount[0]->product_quantity >= $saleDetail->quantity) {
                   $stockCount[0]->product_quantity = $stockCount[0]->product_quantity - $saleDetail->quantity;
                   $stock->save();
                   $stockCount[0]->save();
                   $sale[0]->is_sale=1;
                   $sale[0]->save();
                   Session::flash('message', 'Stock  has been Successfully Balanced.');
               } else {
                   Session::flash('message', 'You Dont have enough products in Stock');
               }
           }else{
               Session::flash('message', 'You Dont have This products in This Stock');
           }
       }
        return Redirect::to('sales/index');

    }
    public function getDeleteTransaction($id)
    {
        $transaction = Transaction::find($id);
        $account_id = Input::get('data');
        $accounts = NameOfAccount::find($account_id);
        $accounts->opening_balance = $accounts->opening_balance - $transaction->amount;
        $accounts->save();
        $transaction->delete();

        $message = array('Transaction Successfully Deleted');
        return new JsonResponse($message);
    }
   /* public function getDel($id)
    {
        $purchase = SAleDetail::find($id);
        $purchase->delete();
        $message = array('Sale Detail  Successfully Deleted');
        return new JsonResponse($message);
    }*/
    public function getCategories($category_id)
    {
        $categoriesName = NameOfAccount::where('account_category_id','=',$category_id)
            ->where('branch_id','=',Input::get('data'))
            ->get();
        foreach ($categoriesName as $categoryName) {
            echo "<option value = $categoryName->id > $categoryName->name</option> ";
        }
    }
    public function getAccountbalance($account_id)
    {
        $accountBalance = NameOfAccount::find($account_id);
        echo "<p3 style='color: blue;font-size: 110%'>Your Current Balance $accountBalance->opening_balance</p3>";
    }

    private function generateInvoiceId()
    {
        $invdesc = Sale::orderBy('id', 'DESC')->first();
        if ($invdesc != null) {
            $invDescId = $invdesc->invoice_id;
            $invDescIdNo = substr($invDescId, 7);

            $subinv1 = substr($invDescId, 6);
            $dd = substr($invDescId, 1, 2);
            $mm = substr($invDescId, 3,2);
            $yy = substr($invDescId, 5, 2);
            //var_dump($invDescId." ".$dd." ".$mm." ".$yy);
            //echo "d1 ".$yy;


            $tz = 'Asia/Dhaka';
            $timestamp = time();
            $dt = new \DateTime("now", new \DateTimeZone($tz)); //first argument "must" be a string
            $dt->setTimestamp($timestamp); //adjust the object to correct timestamp
            $Today = $dt->format('d.m.Y');

            $explodToday = explode(".", $Today);
            $dd2 = $explodToday[0];
            $mm2 = $explodToday[1];
            $yy1 = $explodToday[2];
            $yy2 = substr($yy1, 2);
            //var_dump($dd2." ".$mm2." ".$yy2);


            if ($dd == $dd2 && $yy == $yy2 && $mm == $mm2) {
                $invoiceidd = "S".$dd2 . $mm2 . $yy2 . ($invDescIdNo + 1);
                //var_dump($invoiceidd);
                return $invoiceidd;
            } else {
                $invoiceidd = "S".$dd2 . $mm2 . $yy2 . "1";
                return $invoiceidd;
            }
        } else {
            $tz = 'Asia/Dhaka';
            $timestamp = time();
            $dt = new \DateTime("now", new \DateTimeZone($tz)); //first argument "must" be a string
            $dt->setTimestamp($timestamp); //adjust the object to correct timestamp
            $Today = $dt->format('d.m.Y');

            $explodToday = explode(".", $Today);
            $mm2 = $explodToday[1];
            $dd2 = $explodToday[0];
            $yy1 = $explodToday[2];
            $yy2 = substr($yy1, 2);


            $invoiceidd = "S".$dd2 . $mm2 . $yy2 . "1";
            //var_dump($invoiceidd);
            return $invoiceidd;
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
                $subCategoryName = '('.$subCategory->name.')';
            }else{
                $subCategoryName = '';
            }

            echo "<option value = $productName->id > $productName->name ($category) $subCategoryName</option> ";

        }
    }
    public function getProductbalance($product_id)
    {
        $stockCount = StockCount::where('product_id','=',$product_id)
            ->where('stock_info_id','=',Input::get('data'))
            ->first();
        if($stockCount){
            echo "<p3 style='color: blue;font-size: 114%; margin-left: 32px;'>Your product Balance This Stock is $stockCount->product_quantity</p3>";

        }else{
            echo "<p3 style='color: blue;font-size: 114%; margin-left: 32px; '>You Dont have this Product In this Stock</p3>";

        }

    }
    public function getPartydue($party_id)
    {
        $partySales = Sale::where('party_id','=',$party_id)
            ->where('status','!=','Completed')
            ->get();
        if(count($partySales)>0){
            $totalAmount = 0;
            $totalPrice = 0;
            foreach ($partySales as $sale) {

                $saleDetails = SAleDetail::where('invoice_id','=',$sale->invoice_id)->get();
                $transactions = Transaction::where('invoice_id','=',$sale->invoice_id)->get();
                foreach($saleDetails as $saleDetail)
                {
                    $totalPrice = $totalPrice + ($saleDetail->price * $saleDetail->quantity);
                }
                foreach($transactions as $transaction)
                {
                    $totalAmount =$totalAmount + ($transaction->amount);
                }
            }
            $due = $totalPrice - $totalAmount;
            echo "<p3 style='color: red;font-size: 114%; margin-left: 32px;'>Due is $due</p3>";

        }else{
            echo "<p3 style='color: blue;font-size: 114%; margin-left: 32px; '>No Due</p3>";

        }

    }


    public function postSaveReceiveAll()
    {
        $ruless = array(
            'party_id' => 'required',
            //'cus_ref_no' => 'required',
            'branch_id' => 'required',
            'account_category_id' => 'required',
            'account_name_id' => 'required',
            'payment_method' => 'required',
            'amount' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('sales/index/')
                ->withErrors($validate);
        }
        else{
            //$this->setReceiveSalePaymentAll();

            //return Redirect::to('sales/index');



            //$salesreturn = new SalesReturn();

            //$this->setSalesReturnData($salesreturn);

            //automatically reduce sales payment starts
            $return_amount=Input::get('amount');
            $remaining_amount=$return_amount;
            //var_dump($remaining_amount);
            $partyId=Input::get('party_id');
            if($remaining_amount>0)
            {
                $invoiceId = Sale::where('party_id','=',$partyId)->get();
                foreach($invoiceId as $invid)
                {
                    $price = SAleDetail::where('invoice_id','=',$invid->invoice_id)->get();
                    $detailsPrice=0;
                    foreach($price as $prc)
                    {
                        $detailsPrice=$detailsPrice+($prc->price*$prc->quantity);
                    }
                    //var_dump($detailsPrice);
                    $amount=Transaction::where('invoice_id','=',$invid->invoice_id)
                        ->where('type','=','Receive')
                        ->get();
                    $paid=0;
                    foreach($amount as $amnt)
                    {
                        $paid=$paid+$amnt->amount;
                    }
                    $difference=$detailsPrice-$paid;
                    if($difference>0)
                    {

                        //echo 'greater than 0 difference';
                        if($remaining_amount<=$difference)
                        {
                            if($remaining_amount>0) {
                                $sale = Sale::find( $invid->id);
                                if($remaining_amount<$difference)
                                {
                                    $sale->status = "Partial";
                                }
                                elseif($remaining_amount==$difference)
                                {
                                    $sale->status = "Completed";
                                }

                                $transaction = new Transaction();

                                $transaction->invoice_id = $invid->invoice_id;
                                $transaction->amount = $remaining_amount;
                                $transaction->type = 'Receive';
                                $transaction->payment_method = Input::get('payment_method');
                                $transaction->account_category_id = Input::get('account_category_id');
                                $transaction->remarks = Input::get('remarks');
                                $transaction->account_name_id = Input::get('account_name_id');
                                $transaction->user_id = Session::get('user_id');
                                $transaction->cheque_no = Input::get('cheque_no');
                                $branch = SAleDetail::where('invoice_id', '=', $invid->invoice_id)->first();
                                $transaction->branch_id = $branch->branch_id;
                                $transaction->cheque_date = Input::get('cheque_date');
                                $transaction->cheque_bank = Input::get('cheque_bank');

                                $accountPayment = NameOfAccount::find(Input::get('account_name_id'));
                                $accountPayment->opening_balance = $accountPayment->opening_balance + $remaining_amount;

                                $accountPayment->save();
                                $transaction->save();
                                $remaining_amount = 0;

                            }

                        }
                        elseif($remaining_amount>$difference)
                        {
                            if($remaining_amount>0) {
                                $sale = Sale::find( $invid->id);

                                $sale->status = "Completed";

                                $toBePaid=$remaining_amount-$difference;


                                $transaction = new Transaction();

                                $transaction->invoice_id = $invid->invoice_id;
                                $transaction->amount = $difference;
                                $transaction->type = 'Receive';
                                $transaction->payment_method = Input::get('payment_method');
                                $transaction->account_category_id = Input::get('account_category_id');
                                $transaction->remarks = Input::get('remarks');
                                $transaction->account_name_id = Input::get('account_name_id');
                                $transaction->user_id = Session::get('user_id');
                                $transaction->cheque_no = Input::get('cheque_no');
                                $branch = SAleDetail::where('invoice_id', '=', $invid->invoice_id)->first();
                                $transaction->branch_id = $branch->branch_id;
                                $transaction->cheque_date = Input::get('cheque_date');
                                $transaction->cheque_bank = Input::get('cheque_bank');

                                $accountPayment = NameOfAccount::find(Input::get('account_name_id'));
                                $accountPayment->opening_balance = $accountPayment->opening_balance + $difference;

                                $accountPayment->save();
                                $transaction->save();
                                $remaining_amount = $toBePaid;
                            }



                        }
                        $sale->save();
                    }
                }
            }
            /*if($remaining_amount>0)
            {
                echo "How come its possible! Consult with DEVELOPERS!!!";
            }*/
            //automatically reduce sales payment ends

            return Redirect::to('sales/index');
        }
    }



}
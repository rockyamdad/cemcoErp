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
use App\StockDetail;
use App\StockInfo;
use App\StockInvoice;
use App\SubCategory;
use App\Transaction;
use App\User;
use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
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

        if(Session::get('user_role')=='admin'){
            $sales = Sale::orderBy('id','DESC')->paginate(10);
        }else{
            $sale = new Sale();
            $sales = $sale->getSalesList(Session::get('user_branch'));
        }


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
        $user = new User();
        $salesMan = $user->getSalesManDropDown();
        $products = new Product();
        $finishGoods = $products->getFinishGoodsDropDown();
        $stockInfos = new StockInfo();
        $allStockInfos = $stockInfos->getStockInfoDropDown();
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
    // Invoice Id Generation Starts
        $invoiceid =$this->generateInvoiceId();

        return view('Sales.add',compact('buyersAll'))
            ->with('finishGoods',$finishGoods)
            ->with('branchAll',$branchAll)
            ->with('salesMan',$salesMan)
            ->with('invoiceid',$invoiceid)
            ->with('allStockInfos',$allStockInfos);
    }
    public function postSaveSales()
    {
        $ruless = array(
            'stock_info_id' => 'required',
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
        $user = new User();
        $salesMan = $user->getSalesManDropDown();
        $allStockInfos = $stockInfos->getStockInfoDropDown();
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        $sale[0] = Sale::where('invoice_id','=',$id)->get();
        $var = $sale[0];
        $saleDetails = SAleDetail::where('invoice_id','=',$id)->get();
        if(count($saleDetails) < 1){
            return Redirect::to('sales/index');
        }


        return view('Sales.edit',compact('buyersAll'))
            ->with('finishGoods',$finishGoods)
            ->with('saleDetails',$saleDetails)
            ->with('sale',$var)
            ->with('salesMan',$salesMan)
            ->with('branchAll',$branchAll)
            ->with('allStockInfos',$allStockInfos);

    }
    public function updateSaleData($id)
    {
        $ruless = array(
            'stock_info_id' => 'required',
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
            $saleFound = Sale::where('invoice_id','=',$id)->get();
            if(!empty($saleFound[0])) {
                $saleFound[0]->discount_percentage = 0.00;
                $saleFound[0]->discount_percentage_per = 0.00;
                $saleFound[0]->discount_special = 0.00;
                $saleFound[0]->save();
            }
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


        if(Session::get('user_role') == 'admin'){
            $saleDetails->branch_id = Input::get('branch_id');
        }else{
            $saleDetails->branch_id = Session::get('user_branch');
        }
        $product = Product::find(Input::get('product_id'));
        $saleDetails->stock_info_id = Input::get('stock_info_id');
        $saleDetails->product_type = $product->product_type;
        $saleDetails->remarks = Input::get('remarks');
        $saleDetails->save();
        $hasSale = Sale::where('invoice_id','=',Input::get('invoice_id'))->get();
        if(empty($hasSale[0])){

            if(Input::get('party_id')){
                $sale->party_id = Input::get('party_id');
            }else{
                $sale->cash_sale = Input::get('cash_sale');
                $sale->address = Input::get('address');
            }
            if(Input::get('sales_man_id')){
                $sale->sales_man_id = Input::get('sales_man_id');
            }

            $sale->status = "Activate";
            $sale->invoice_id = Input::get('invoice_id');
            $sale->remarks = '1. PAYMENT MUST BE MAID WITHIN 15 DAYS BY CHEQUE OR CASH
2. NO REPLACEMENT WARANTY';
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
       // $sales =  SAleDetail::where('invoice_id','=',$id)->get();
        Sale::where('invoice_id','=',$id)->delete();
        SAleDetail::where('invoice_id','=',$id)->delete();

  /*      foreach($sales as $sale)
        {
            $stock_Count = StockCount::where('product_id','=', $sale->product_id)
                ->where('stock_info_id','=',$sale->stock_info_id)
                ->get();

            $stock_Count[0]->product_quantity = $stock_Count[0]->product_quantity + $sale->quantity;
            $stock_Count[0]->total_price = $stock_Count[0]->total_price + ($sale->quantity * $sale->price);
            $stock_Count[0]->save();
        }*/
        Session::flash('error', 'Sale has been Successfully Deleted.');
        return Redirect::to('sales/index');
    }
    public function getDetails($invoiceId)
    {
        $saleDetails = SAleDetail::where('invoice_id','=',$invoiceId)->get();
        $saleTransactions = Transaction::where('invoice_id','=',$invoiceId)->get();
        $sale = Sale::where('invoice_id','=',$invoiceId)->first();
        $s = new Sale();
        $due = $s->getPartydue($invoiceId);
        return view('Sales.details',compact('saleDetails', 'due'))
            ->with('saleTransactions',$saleTransactions)
            ->with('sale',$sale);

    }
    public function getShowinvoice($invoiceId)
    {
        $saleDetails = SAleDetail::where('invoice_id','=',$invoiceId)->get();
        $sale = Sale::where('invoice_id','=',$invoiceId)->first();
        return view('Sales.showInvoice',compact('saleDetails', 'invoiceId'))
            ->with('sale',$sale);

    }

    public function getShowinvoice2($invoiceId)
    {
        $saleDetails = SAleDetail::where('invoice_id','=',$invoiceId)->get();
        $sale = Sale::where('invoice_id','=',$invoiceId)->first();
        return view('Sales.showInvoice2',compact('saleDetails'))
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
        $s = new Sale();
        $due = $s->getPartydue($invoice_id);
        return view('Sales.paymentAdd',compact('accountCategoriesAll','due'))
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
            $id =$this->setReceiveSalePayment();

            return Redirect::to('sales/voucher/'.$id);

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
        $totalPrice -= $sales->discount_percentage;
        $sale = Sale::find( $sales->id);

        $amountTobePaid = $totalAmount+Input::get('amount');

        if(sprintf($amountTobePaid) == sprintf($totalPrice))
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
        return $saleTransaction->id;
    }



    public function getDeleteDetail($id)
    {
        $saleDetail = SAleDetail::find($id);
        $saleDetail->delete();

    /*    $stock_Count = StockCount::where('product_id','=', $saleDetail->product_id)
            ->where('stock_info_id','=',$saleDetail->stock_info_id)
            ->get();

        $stock_Count[0]->product_quantity = $stock_Count[0]->product_quantity + $saleDetail->quantity;
        $stock_Count[0]->total_price = $stock_Count[0]->total_price + ($saleDetail->quantity*$saleDetail->price);
        $stock_Count[0]->save();*/
        $message = array('Sale Detail   Successfully Deleted');
        return new JsonResponse($message);
    }
    public function getSale($invoice_id)
    {
        $saleDetails = SAleDetail::where('invoice_id','=',$invoice_id)->get();
        $sale = Sale::where('invoice_id','=',$invoice_id)->get();
        $temp = 0;

        $stockInvoiceId= StockInvoice::generateInvoiceId();

        foreach($saleDetails as $saleDetail)
        {

            $stock_Count = StockCount::where('product_id','=',$saleDetail->product_id)
                ->where('stock_info_id','=',$saleDetail->stock_info_id)
                ->get();

            if(!empty($stock_Count[0])) {
                if ($stock_Count[0]->product_quantity >=$saleDetail->quantity) {
                } else {
                    $temp++;
                    Session::flash('message', 'You Dont have enough products in Stock');
                }
            }else{
                $temp++;
                Session::flash('message', 'You Dont have This products in This Stock');
            }

        }

        if ($temp == 0) {

            foreach ($saleDetails as $saleDetail) {
                $stockInvoces = new StockInvoice();

                $stockInvoces->branch_id = $saleDetail->branch_id;
                $stockInvoces->status = 'Activate';
                $stockInvoces->remarks = '';
                $stockInvoces->user_id = Session::get('user_id');
                $stockInvoces->invoice_id = $stockInvoiceId;

                $stock_invoices_check = StockInvoice::where('invoice_id', '=', $stockInvoiceId)
                    ->get();
                if (empty($stock_invoices_check[0]))
                    $stockInvoces->save();


                $stock = new StockDetail();
                $stock->branch_id = $saleDetail->branch_id;
                $stock->product_id = $saleDetail->product_id;
                $stock->product_type = $saleDetail->product_type;
                $stock->quantity = $saleDetail->quantity;
                $stock->price = $saleDetail->price;
                $stock->entry_type = "StockOut";
                $stock->remarks = $saleDetail->remarks;
                $stock->invoice_id = $stockInvoiceId;
                $stock->stock_info_id = $saleDetail->stock_info_id;

                $stock_Count = StockCount::where('product_id', '=', $saleDetail->product_id)
                    ->where('stock_info_id', '=', $saleDetail->stock_info_id)
                    ->get();

                if (!empty($stock_Count[0])) {
                    if ($stock_Count[0]->product_quantity >= $stock->quantity) {
                        $stock_Count[0]->product_quantity = $stock_Count[0]->product_quantity - $stock->quantity;
                        $stock_Count[0]->total_price = $stock_Count[0]->total_price - ($stock->price*$stock->quantity);
                        //$stock->save();
                        $stock_Count[0]->save();
                        $stock->save();

                        $sale[0]->is_sale = 1;
                        $sale[0]->save();
                        //Session::flash('message', 'Stock has been Successfully Created && Product Quantity Subtracted');
                    } else {
                        Session::flash('message', 'You Dont have enough products in Stock');
                    }
                } else {
                    Session::flash('message', 'You Dont have This products in This Stock');
                }

            }
        }
//        if($temp == 0) {
//            foreach($saleDetails as $saleDetail)
//            {
//                $stock = new Stock();
//                $stock->branch_id = $saleDetail->branch_id;
//                $stock->product_id = $saleDetail->product_id;
//                $stock->product_type = $saleDetail->product_type;
//                $stock->product_quantity = $saleDetail->quantity;
//                $stock->entry_type = "StockOut";
//                $stock->remarks = $saleDetail->remarks;
//                $stock->user_id = Session::get('user_id');
//                $stock->stock_info_id = $saleDetail->stock_info_id;
//                $stock->status = "Activate";
//
//                $stockCount = StockCount::where('product_id','=',$saleDetail->product_id)
//                    ->where('stock_info_id','=',$saleDetail->stock_info_id)
//                    ->get();
//
//
//
//                if(!empty($stockCount[0])) {
//
//                    if ($stockCount[0]->product_quantity >= $saleDetail->quantity) {
//
//                        $stockCount[0]->product_quantity = $stockCount[0]->product_quantity - $saleDetail->quantity;
//                        $stock->save();
//                        $stockCount[0]->save();
//                        $sale[0]->is_sale=1;
//                        $sale[0]->save();
//                        //Session::flash('message', 'Stock  has been Successfully Balanced.');
//                    }
//                }
//            }
//
//        }
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
            $invDescIdNo = substr($invDescId, 8);

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
                $invoiceidd = "S".$dd2 . $mm2 . $yy2 . "-".($invDescIdNo + 1);
                //var_dump($invoiceidd);
                return $invoiceidd;
            } else {
                $invoiceidd = "S".$dd2 . $mm2 . $yy2 . "-1";
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


            $invoiceidd = "S".$dd2 . $mm2 . $yy2 . "-1";
            //var_dump($invoiceidd);
            return $invoiceidd;
        }
    }
    private function generateVoucherId()
    {
        $invdesc = Transaction::orderBy('id', 'DESC')->first();
        if ($invdesc != null) {
            $invDescId = $invdesc->voucher_id;
            $invDescIdNo = substr($invDescId, 8);

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
                $invoiceidd = "S".$dd2 . $mm2 . $yy2 . "-".($invDescIdNo + 1);
                //var_dump($invoiceidd);
                return $invoiceidd;
            } else {
                $invoiceidd = "V".$dd2 . $mm2 . $yy2 . "-1";
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


            $invoiceidd = "V".$dd2 . $mm2 . $yy2 . "-1";
            //var_dump($invoiceidd);
            return $invoiceidd;
        }
    }
    public function getProducts($branch_id)
    {

        $productsName = Product::where('branch_id','=',$branch_id)
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
            echo "<p3 class='msg' style='color: blue;font-size: 114%; margin-left: 32px;'>Your product Balance This Stock is $stockCount->product_quantity</p3>";

        }else{
            echo "<p3 class='msg' style='color: blue;font-size: 114%; margin-left: 32px; '>You Dont have this Product In this Stock</p3>";

        }

    }
    public function getPartydue($party_id)
    {
        $partySales = Sale::where('party_id','=',$party_id)
            ->where('status','!=','Completed')
            ->where('is_sale','=',1)
            ->get();
        $party = Party::find($party_id);
        $totalAmount = 0;
        $totalPrice = 0;
        if(count($partySales)>0){

            foreach ($partySales as $sale) {

                $saleDetails = SAleDetail::where('invoice_id','=',$sale->invoice_id)->get();
                $transactions = Transaction::where('invoice_id','=',$sale->invoice_id)
                    ->where('payment_method', '=', 'Check')
                    ->where('type', '=', 'Receive')
                    ->where('cheque_status', '=', 1)->get();
                foreach($saleDetails as $saleDetail)
                {
                    $totalPrice = $totalPrice + ($saleDetail->price * $saleDetail->quantity);
                }
                $totalPrice = $totalPrice - $sale->discount_percentage_per;
                foreach($transactions as $transaction)
                {
                    $totalAmount =$totalAmount + ($transaction->amount);
                }

                $transactions2 = Transaction::where('invoice_id','=',$sale->invoice_id)
                    ->where('type', '=',     'Receive')
                    ->where('payment_method', '!=', 'Check')->get();
                foreach($transactions2 as $transaction)
                {
                    $totalAmount =$totalAmount + ($transaction->amount);
                }

            }
        }
        $due = $totalPrice  + $party->balance - $totalAmount;
        if($due > 0) {
            echo "<p3 style='color: red;font-size: 114%; margin-left: 32px;'>Due is $due</p3>";
        }else{

            echo "<p3 style='color: blue;font-size: 114%; margin-left: 32px; '>No Due</p3>";

        }

    }


    public function postSaveReceiveAll()
    {
        $transactionId = 0;

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

            $return_amount=Input::get('amount');
            $remaining_amount=$return_amount;
            $partyId=Input::get('party_id');
            if($remaining_amount>0)
            {
                $voucherId = $this->generateVoucherId();

                $invoiceId = Sale::where('party_id','=',$partyId)
                    ->where('is_sale','=',1)
                    ->get();
                if(count($invoiceId)>0){
                    foreach($invoiceId as $invid)
                    {
                        $detailsPrice = 0;
                        $paid = 0;
                        $saleDetails = SAleDetail::where('invoice_id','=',$invid->invoice_id)->get();
                        $transactions = Transaction::where('invoice_id','=',$invid->invoice_id)
                            ->where('payment_method', '=', 'Check')
                            ->where('type', '=', 'Receive')
                            ->where('cheque_status', '=', 1)->get();
                        $salef = Sale::find( $invid->id);
                        foreach($saleDetails as $saleDetail)
                        {
                            $salePriceCalculated = ($saleDetail->price * $saleDetail->quantity);
                            $detailsPrice = $detailsPrice + $salePriceCalculated;
                        }
                        $detailsPrice -= $invid->discount_percentage;
                        foreach($transactions as $transaction)
                        {
                            $paid =$paid + ($transaction->amount);
                        }
                        $transactions2 = Transaction::where('invoice_id','=',$invid->invoice_id)
                            ->where('type', '=', 'Receive')
                            ->where('payment_method', '!=', 'Check')->get();
                        foreach($transactions2 as $transaction)
                        {
                            $paid =$paid + ($transaction->amount);
                        }

                        $difference=$detailsPrice-$paid;
                        //echo $difference; die();
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
                                    $transaction->voucher_id = $voucherId;
                                    $branch = SAleDetail::where('invoice_id', '=', $invid->invoice_id)->first();
                                    $transaction->branch_id = $branch->branch_id;
                                    $transaction->cheque_date = Input::get('cheque_date');
                                    $transaction->cheque_bank = Input::get('cheque_bank');

                                    if ($transaction->payment_method != "Check") {
                                        $accountPayment = NameOfAccount::find(Input::get('account_name_id'));
                                        $accountPayment->opening_balance = $accountPayment->opening_balance + $remaining_amount;
                                        $accountPayment->save();
                                    }
                                    $transaction->save();
                                    $transactionId = $transaction->id;
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
                                    $transaction->voucher_id = $voucherId;
                                    $branch = SAleDetail::where('invoice_id', '=', $invid->invoice_id)->first();
                                    $transaction->branch_id = $branch->branch_id;
                                    $transaction->cheque_date = Input::get('cheque_date');
                                    $transaction->cheque_bank = Input::get('cheque_bank');

                                    if ($transaction->payment_method != "Check") {
                                        $accountPayment = NameOfAccount::find(Input::get('account_name_id'));
                                        $accountPayment->opening_balance = $accountPayment->opening_balance + $difference;
                                        $accountPayment->save();
                                    }
                                    $transaction->save();
                                    $transactionId = $transaction->id;
                                    $remaining_amount = $toBePaid;
                                }

                            }
                            $sale->save();

                        }
                    }
                }else{
                    $party = Party::find($partyId);

                    if($remaining_amount < $party->balance){
                        $party->balance = $party->balance - $remaining_amount;
                        $party->save();

                        $transaction = new Transaction();

                        $transaction->invoice_id = $this->generateInvoiceId();
                        $transaction->amount = $remaining_amount;
                        $transaction->type = 'Receive';
                        $transaction->payment_method = Input::get('payment_method');
                        $transaction->account_category_id = Input::get('account_category_id');
                        $transaction->remarks = Input::get('remarks');
                        $transaction->account_name_id = Input::get('account_name_id');
                        $transaction->user_id = Session::get('user_id');
                        $transaction->cheque_no = Input::get('cheque_no');
                        $transaction->voucher_id = $voucherId;
                        $transaction->branch_id = Input::get('branch_id');
                        $transaction->cheque_date = Input::get('cheque_date');
                        $transaction->cheque_bank = Input::get('cheque_bank');

                        if ($transaction->payment_method != "Check") {
                            $accountPayment = NameOfAccount::find(Input::get('account_name_id'));
                            $accountPayment->opening_balance = $accountPayment->opening_balance + $remaining_amount;
                            $accountPayment->save();
                        }
                        $transaction->save();
                        $transactionId = $transaction->id;
                    }else{
                        Session::flash('error', 'Sorry!!  Amount is bigger than  loan');
                        return Redirect::to('sales/index');
                    }

                }

            }
            /*if($remaining_amount>0)
            {
                echo "How come its possible! Consult with DEVELOPERS!!!";
            }*/
            //automatically reduce sales payment ends
            return Redirect::to('sales/voucher/'.$transaction->id);
        }
    }
    public function getProductprice($id){
        $product = Product::find($id);
        return new JsonResponse($product->price);
    }

    public function getStocks($id){
        $stocks = StockCount::where('product_id', '=', $id)->get();
        $stockArray =array();
        $x = "";
        foreach($stocks as $row){
            $stockInfo = StockInfo::find($row->stock_info_id);
            $x .= "<option value='".$row->stock_info_id."'>".$stockInfo->name." (".$row->product_quantity.")</option>";
            array_push($stockArray, $row->stock_info_id);
        }

        $stocks = StockInfo::whereNotIn('id', $stockArray)->get();

        foreach($stocks as $row){
            $x .= "<option value='".$row->id."'>".$row->name." (0)</option>";
        }

        $product = Product::find($id);
        $data = array(
            'list' => $x,
            'price' => $product->price
        );
        return json_encode($data);
    }

    public function getVoucher($transactionId){
        $transaction = Transaction::find($transactionId);
        return view('Sales.voucher',compact('transaction'));

    }
    public function getVouchershow($voucherId){
        $transaction = new Transaction();
        $transaction = $transaction->getVoucher($voucherId);

        return view('Sales.voucher')
            ->with('transaction',$transaction[0]);

    }
    public function getVoucherlist(){
        $buyers = new Party();
        $buyersAll = $buyers->getBuyersDropDown();
        $transaction = new Transaction();
        $vouchers = $transaction->getVoucherList();
        return view('Sales.vouchers',compact('vouchers'))
            ->with('buyersAll',$buyersAll);

    }
    public function postVoucher(){
        $date1 = Input::get('from_date');
        $buyers = new Party();
        $buyersAll = $buyers->getBuyersDropDown();
        $date2 = Input::get('to_date');
        $party_id = Input::get('party_id');
        $transaction = new Transaction();
        $vouchers = $transaction->getVoucherListByParty($date1,$date2,$party_id);
        return view('Sales.vouchers',compact('vouchers'))
            ->with('party_id',$party_id)
            ->with('date1',$date1)
            ->with('buyersAll',$buyersAll)
            ->with('date2',$date2);
    }

    public function getDiscount($saleId){
        $sales = Sale::find($saleId);
        return view('Sales.discountPercentage',compact('sales'));
    }

    public function getSavediscount($salesId){
        $sales = Sale::find($salesId);
        $sales->discount_percentage = Input::get('data') +  Input::get('discount_special');
        $sales->discount_special = Input::get('discount_special');
        $sales->discount_percentage_per = Input::get('discount_percentage');
        //echo Input::get('data');
        $sales->save();
    }

    public function postConfirm($saleId){
        $sales = Sale::find($saleId);
        echo $sales->remarks = Input::get('remIn');
        $sales->save();
    }



}
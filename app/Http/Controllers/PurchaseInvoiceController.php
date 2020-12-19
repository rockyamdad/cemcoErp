<?php namespace App\Http\Controllers;

use App\AccountCategory;


use App\Branch;
use App\NameOfAccount;
use App\Party;
use App\Product;
use App\PurchaseInvoice;
use App\PurchaseInvoiceDetail;
use App\StockCount;
use App\StockDetail;
use App\StockInfo;
use App\StockInvoice;
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
        if(Session::get('user_role')=='admin'){
            $purchases = PurchaseInvoice::orderBy('id','DESC')->paginate(15);
        }else{
            $purchases = PurchaseInvoice::orderBy('id','DESC')->paginate(15);
        }

        $purchase = new PurchaseInvoice();
        $allInvoices = $purchase->getPurchaseInvoiceDropDown();
        $invoice = Input::get('invoice_id');
        $purchaseInvoice = PurchaseInvoice::where('invoice_id',$invoice)->get();
        return view('PurchaseInvoice.list',compact('purchases'))
            ->with('allInvoices',$allInvoices)
            ->with('purchaseInvoice',$purchaseInvoice);
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

        // Invoice Id Generation Starts
        $invoiceid =$this->generateInvoiceId();
        //var_dump($invoiceid);
        // Invoice Id Generation Starts

        return view('PurchaseInvoice.add',compact('suppliersAll'))
            ->with('localProducts',$localProducts)
            ->with('branchAll',$branchAll)
            ->with('invoiceid',$invoiceid)
            ->with('allStockInfos',$allStockInfos);
    }
    public function postSavePurchaseInvoice()
    {
        $ruless = array(
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

    private function generateInvoiceId()
    {
        $invdesc = PurchaseInvoice::orderBy('id', 'DESC')->first();
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
                $invoiceidd = "P".$dd2 . $mm2 . $yy2 . "-".($invDescIdNo + 1);
                //var_dump($invoiceidd);
                return $invoiceidd;
            } else {
                $invoiceidd = "P".$dd2 . $mm2 . $yy2 . "-1";
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


            $invoiceidd = "P".$dd2 . $mm2 . $yy2 . "-1";
            //var_dump($invoiceidd);
            return $invoiceidd;
        }
    }

    public function postUpdate($id)
    {

        $ruless = array(
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
        if(Session::get('user_role') == 'admin'){
            $purchaseDetails->branch_id = Input::get('branch_id');
        }else{
            $purchaseDetails->branch_id = Session::get('user_branch');
        }

        $purchaseDetails->stock_info_id = Input::get('stock_info_id');
        $purchaseDetails->product_type = Input::get('product_type');
        $purchaseDetails->remarks = Input::get('remarks');
        $purchaseDetails->save();

        $stock_Count = StockCount::where('product_id','=', $purchaseDetails->product_id)
            ->where('stock_info_id','=',$purchaseDetails->stock_info_id)
            ->get();

        if(empty($stock_Count[0]))
        {
            $stock_Count = new StockCount();
            $stock_Count->product_id = Input::get('product_id');
            $stock_Count->stock_info_id = Input::get('stock_info_id');
            $stock_Count->product_quantity = Input::get('quantity');
            $stock_Count->total_price = Input::get('quantity')*Input::get('price') ;
            $stock_Count->save();
        }else{
            $stock_Count[0]->product_quantity = $stock_Count[0]->product_quantity + Input::get('quantity');
            $stock_Count[0]->total_price = $stock_Count[0]->total_price + (Input::get('quantity')*Input::get('price'));
            $stock_Count[0]->save();
        }

        $hasInvoice = PurchaseInvoice::where('invoice_id','=',Input::get('invoice_id'))->get();
        if(empty($hasInvoice[0])){
            $purchases->party_id = Input::get('party_id');
            $purchases->status = "Activate";
            $purchases->invoice_id = \Input::get('invoice_id');
            $purchases->user_id = Session::get('user_id');
            $purchases->save();
        }

        $stockInvoces = new StockInvoice();
        $stockInvoiceId= StockInvoice::generateInvoiceId();
        if(Session::get('user_role') == 'admin'){
            $stockInvoces->branch_id = Input::get('branch_id');
        }else{
            $stockInvoces->branch_id = Session::get('user_branch');
        }

        $stockInvoces->status = 'Activate';
        $stockInvoces->remarks = '';
        $stockInvoces->user_id = Session::get('user_id');
        $stockInvoces->invoice_id = $stockInvoiceId;

        $stock_invoices_check = StockInvoice::where('invoice_id', '=', $stockInvoiceId)
            ->get();
        if (empty($stock_invoices_check[0]))
            $stockInvoces->save();


        $stock = new StockDetail();
        if(Session::get('user_role') == 'admin'){
            $stock->branch_id = Input::get('branch_id');
        }else{
            $stock->branch_id = Session::get('user_branch');
        }

        $stock->product_id =Input::get('product_id');
        $stock->product_type =  Input::get('product_type');
        $stock->quantity = Input::get('quantity');
        $stock->price = Input::get('price');
        $stock->entry_type = "StockIn";
        $stock->remarks = Input::get('remarks');
        $stock->invoice_id = $stockInvoiceId;
        $stock->stock_info_id = Input::get('stock_info_id');
        $stock->to_stock_info_id = Input::get('stock_info_id');
        $stock->save();

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

        $categoryName = \App\Category::find($purchaseInvoiceDetails->product->category_id);
        $subCategoryName = \App\SubCategory::find($purchaseInvoiceDetails->product->sub_category_id);

        $array['product_id'] = $purchaseInvoiceDetails->product->name.'('.$categoryName->name.')'.'('.$subCategoryName->name.')';
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
        $due = $purchaseDetails->getPurchasedue($invoice_id);
        $purchaseDetailsBranch = PurchaseInvoiceDetail::where('detail_invoice_id','=',$invoice_id)->first();
        return view('PurchaseInvoice.paymentAdd',compact('accountCategoriesAll', 'due'))
            ->with('purchaseDetailsAmount',$purchaseDetailsAmount)
            ->with('invoice_id',$invoice_id)
            ->with('purchaseDetailsBranch',$purchaseDetailsBranch->branch_id)
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

            $voucherId = $this->setPurchasePayment();
            if ($voucherId) {
                return Redirect::to('purchases/voucher/'.$voucherId);
            } else {
                return Redirect::to('purchases/index');
            }
        }
    }
    private function setPurchasePayment()
    {
        $accountPayment = NameOfAccount::find(Input::get('account_name_id'));
        $voucherId = '';
        if($accountPayment->opening_balance >= Input::get('amount')){
            $voucherId = $this->generateVoucherId();
            $purchases = PurchaseInvoice::where('invoice_id','=',Input::get('invoice_id'))->first();
            $purchaseTransaction = new Transaction();
            $purchaseTransaction->account_category_id = Input::get('account_category_id');
            $purchaseTransaction->account_name_id = Input::get('account_name_id');
            $purchaseTransaction->amount = Input::get('amount');
            $purchaseTransaction->remarks = Input::get('remarks');
            $purchaseTransaction->user_id = Session::get('user_id');
            $purchaseTransaction->type = "Payment";
            $purchaseTransaction->voucher_id = $voucherId;
            $purchaseTransaction->payment_method = Input::get('payment_method');
            $purchaseTransaction->invoice_id = Input::get('invoice_id');
            $purchaseTransaction->cheque_no = Input::get('cheque_no');
            $purchaseTransaction->cheque_date=Input::get('cheque_date');
            $purchaseTransaction->cheque_bank=Input::get('cheque_bank');


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
            $purchaseInvoice = PurchaseInvoice::find( $purchases->id);
            if($totalAmount == $totalPrice)
            {
                $purchaseInvoice->status = "Completed";
            }else{
                $purchaseInvoice->status = "Partial";
            }


            if($purchaseTransaction->payment_method != "Check") {
                $accountPayment->opening_balance = $accountPayment->opening_balance - Input::get('amount');
                $accountPayment->save();
            }

            $purchaseInvoice->save();
            $purchaseTransaction->save();
            Session::flash('message', 'Payment has been Successfully Cleared.');

        }else{
            Session::flash('message', 'You dont have Enough Balance');
        }
        return $voucherId;

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
        if(count($purchaseDetails) < 1){
            return Redirect::to('purchases/index');
        }
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

        $stock_Count = StockCount::where('product_id','=', $purchase->product_id)
            ->where('stock_info_id','=',$purchase->stock_info_id)
            ->get();

        $stock_Count[0]->product_quantity = $stock_Count[0]->product_quantity - $purchase->quantity;
        $stock_Count[0]->total_price = $stock_Count[0]->total_price - ($purchase->quantity*$purchase->price);
        $stock_Count[0]->save();

        $message = array('Purchase Invoice  Successfully Deleted');
        return new JsonResponse($message);
    }
    public function getDeletepurchasedetail($id)
    {
        $purchaseDetail = PurchaseInvoiceDetail::find($id);
        $purchaseDetail->delete();

        $stock_Count = StockCount::where('product_id','=', $purchaseDetail->product_id)
            ->where('stock_info_id','=',$purchaseDetail->stock_info_id)
            ->get();

        $stock_Count[0]->product_quantity = $stock_Count[0]->product_quantity - $purchaseDetail->quantity;
        $stock_Count[0]->total_price = $stock_Count[0]->total_price - ($purchaseDetail->quantity*$purchaseDetail->price);
        $stock_Count[0]->save();

        Session::flash('error', 'Purchase Detail  Successfully Deleted');
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
        $purchases =  PurchaseInvoiceDetail::where('detail_invoice_id','=',$id)->get();
        PurchaseInvoice::where('invoice_id','=',$id)->delete();
        PurchaseInvoiceDetail::where('detail_invoice_id','=',$id)->delete();

        foreach($purchases as $purchase)
        {
            $stock_Count = StockCount::where('product_id','=', $purchase->product_id)
                ->where('stock_info_id','=',$purchase->stock_info_id)
                ->get();

            $stock_Count[0]->product_quantity = $stock_Count[0]->product_quantity - $purchase->quantity;
            $stock_Count[0]->total_price = $stock_Count[0]->total_price - ($purchase->quantity*$purchase->price);
            $stock_Count[0]->save();
        }
        Session::flash('error', 'Purchase Invoice has been Successfully Deleted.');

        return Redirect::to('purchases/index');
    }
    /*public function getCategories($category_id)
    {
        $categoriesName = NameOfAccount::where('account_category_id','=',$category_id)
            ->get();
        foreach ($categoriesName as $categoryName) {
            echo "<option value = $categoryName->id > $categoryName->name</option> ";
        }
    }*/
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
            echo "<p3 class='msg' style='color: blue;font-size: 100%; margin-left: 32px;'>Your product Balance This Stock is $stockCount->product_quantity</p3>";

        }else{
            echo "<p3 class='msg' style='color: blue;font-size: 100%; margin-left: 32px; '>You Dont have this Product In this Stock</p3>";

        }

    }
    public function getAccountbalance($account_id)
    {
        $accountBalance = NameOfAccount::find($account_id);
        echo "<p3 style='color: blue;font-size: 130%'>Your Current Balance $accountBalance->opening_balance</p3>";
    }
    public function getCategories($category_id)
    {
        $categoriesName = NameOfAccount::where('account_category_id','=',$category_id)
            ->where('branch_id','=',Input::get('data'))
            ->get();
        foreach ($categoriesName as $categoryName) {
            echo "<option value = $categoryName->id > $categoryName->name</option> ";
        }
    }
    public function getProductprice($id){
        $product = Product::find($id);
        return new JsonResponse($product->price);
    }


    public function getMakeall()
    {
        $accountCategories = new AccountCategory();
        $accountCategoriesAll = $accountCategories->getAccountCategoriesDropDown();
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        $buyers = new Party();
        $partyAll = $buyers->getSuppliersDropDown();
        return view('PurchaseInvoice.paymentAddAll',compact('accountCategoriesAll'))
            ->with('branchAll',$branchAll)
            ->with('partyAll',$partyAll);
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
            //var_dump($remaining_amount);
            $partyId=Input::get('party_id');
            if($remaining_amount>0)
            {
                $voucherId = $this->generateVoucherId();
                $invoiceId = PurchaseInvoice::where('party_id','=',$partyId)
                    ->get();
                if(count($invoiceId)>0) {
                    foreach ($invoiceId as $invid) {


                        $detailsPrice = 0;
                        $paid = 0;
                        $saleDetails = PurchaseInvoiceDetail::where('detail_invoice_id', '=', $invid->invoice_id)->get();
                        $transactions = Transaction::where('invoice_id', '=', $invid->invoice_id)
                            ->where('payment_method', '=', 'Check')
                            ->where('type', '=', 'Payment')
                            ->where('cheque_status', '=', 1)->get();
                        foreach ($saleDetails as $saleDetail) {
                            $detailsPrice = $detailsPrice + ($saleDetail->price * $saleDetail->quantity);
                        }
                        foreach ($transactions as $transaction) {
                            $paid = $paid + ($transaction->amount);
                        }
                        $transactions2 = Transaction::where('invoice_id', '=', $invid->invoice_id)
                            ->where('type', '=', 'Payment')
                            ->where('payment_method', '!=', 'Check')->get();
                        foreach ($transactions2 as $transaction) {
                            $paid = $paid + ($transaction->amount);
                        }

                        $difference = $detailsPrice - $paid;
                        if ($difference > 0) {

                            //echo 'greater than 0 difference';
                            if ($remaining_amount <= $difference) {
                                if ($remaining_amount > 0) {

                                    $sale = PurchaseInvoice::find($invid->id);
                                    if ($remaining_amount < $difference) {
                                        $sale->status = "Partial";
                                    } elseif ($remaining_amount == $difference) {
                                        $sale->status = "Completed";
                                    }

                                    $transaction = new Transaction();

                                    $transaction->invoice_id = $invid->invoice_id;
                                    $transaction->amount = $remaining_amount;
                                    $transaction->type = 'Payment';
                                    $transaction->payment_method = Input::get('payment_method');
                                    $transaction->account_category_id = Input::get('account_category_id');
                                    $transaction->remarks = Input::get('remarks');
                                    $transaction->account_name_id = Input::get('account_name_id');
                                    $transaction->user_id = Session::get('user_id');
                                    $transaction->cheque_no = Input::get('cheque_no');
                                    $transaction->voucher_id = $voucherId;
                                    $branch = PurchaseInvoiceDetail::where('detail_invoice_id', '=', $invid->invoice_id)->first();
                                    $transaction->branch_id = $branch->branch_id;
                                    $transaction->cheque_date = Input::get('cheque_date');
                                    $transaction->cheque_bank = Input::get('cheque_bank');

                                    if ($transaction->payment_method != "Check") {
                                        $accountPayment = NameOfAccount::find(Input::get('account_name_id'));
                                        $accountPayment->opening_balance = $accountPayment->opening_balance - $remaining_amount;
                                        $accountPayment->save();
                                    }
                                    $transaction->save();
                                    $transactionId = $transaction->id;
                                    $remaining_amount = 0;

                                }

                            } elseif ($remaining_amount > $difference) {
                                if ($remaining_amount > 0) {

                                    $sale = PurchaseInvoice::find($invid->id);

                                    $sale->status = "Completed";

                                    $toBePaid = $remaining_amount - $difference;


                                    $transaction = new Transaction();

                                    $transaction->invoice_id = $invid->invoice_id;
                                    $transaction->amount = $difference;
                                    $transaction->type = 'Payment';
                                    $transaction->payment_method = Input::get('payment_method');
                                    $transaction->account_category_id = Input::get('account_category_id');
                                    $transaction->remarks = Input::get('remarks');
                                    $transaction->account_name_id = Input::get('account_name_id');
                                    $transaction->user_id = Session::get('user_id');
                                    $transaction->cheque_no = Input::get('cheque_no');
                                    $branch = PurchaseInvoiceDetail::where('detail_invoice_id', '=', $invid->invoice_id)->first();
                                    $transaction->branch_id = $branch->branch_id;
                                    $transaction->voucher_id = $voucherId;
                                    $transaction->cheque_date = Input::get('cheque_date');
                                    $transaction->cheque_bank = Input::get('cheque_bank');

                                    if ($transaction->payment_method != "Check") {
                                        $accountPayment = NameOfAccount::find(Input::get('account_name_id'));
                                        $accountPayment->opening_balance = $accountPayment->opening_balance - $difference;
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
                        $transaction->type = 'Payment';
                        $transaction->payment_method = Input::get('payment_method');
                        $transaction->account_category_id = Input::get('account_category_id');
                        $transaction->remarks = Input::get('remarks');
                        $transaction->account_name_id = Input::get('account_name_id');
                        $transaction->user_id = Session::get('user_id');
                        $transaction->cheque_no = Input::get('cheque_no');
                        $transaction->branch_id = Input::get('branch_id');
                        $transaction->voucher_id = $voucherId;
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
                        Session::flash('error', 'Sorry!! Your amount is bigger than your loan');
                        return Redirect::to('purchases/index');
                    }
                }
            }
            return Redirect::to('purchases/voucher/'.$voucherId);
        }
    }

    public function getPartydue($party_id)
    {
        $partySales = PurchaseInvoice::where('party_id','=',$party_id)
            ->where('status','!=','Completed')
            ->get();
        $party = Party::find($party_id);

        $totalAmount = 0;
        $totalPrice = 0;

        if(count($partySales)>0){
            foreach ($partySales as $sale) {

                $saleDetails = PurchaseInvoiceDetail::where('detail_invoice_id','=',$sale->invoice_id)->get();
                $transactions = Transaction::where('invoice_id','=',$sale->invoice_id)
                    ->where('payment_method', '=', 'Check')
                    ->where('type', '=', 'Payment')
                    ->where('cheque_status', '=', 1)->get();
                foreach($saleDetails as $saleDetail)
                {
                    $totalPrice = $totalPrice + ($saleDetail->price * $saleDetail->quantity);
                }

                foreach($transactions as $transaction)
                {
                    $totalAmount =$totalAmount + ($transaction->amount);
                }

                $transactions2 = Transaction::where('invoice_id','=',$sale->invoice_id)
                    ->where('type', '=', 'Payment')
                    ->where('payment_method', '!=', 'Check')->get();
                foreach($transactions2 as $transaction)
                {
                    $totalAmount =$totalAmount + ($transaction->amount);
                }

            }
        }
        $due = $totalPrice + $party->balance - $totalAmount;
        if($due > 0) {

            echo "<p3 style='color: red;font-size: 114%; margin-left: 32px;'>Due is $due</p3>";
        }else{

            echo "<p3 style='color: blue;font-size: 114%; margin-left: 32px; '>No Due</p3>";

        }

    }

    private function generateVoucherId()
    {
        $invdesc = Transaction::orderBy('id', 'DESC')->first();
        if ($invdesc != null) {
            $invDescId = $invdesc->voucher_id;
            $invDescIdNo = substr($invDescId, 9);

            $subinv1 = substr($invDescId, 6);
            $dd = substr($invDescId, 2, 2);
            $mm = substr($invDescId, 4,2);
            $yy = substr($invDescId, 6, 2);

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

            if ($dd == $dd2 && $yy == $yy2 && $mm == $mm2) {
                $invoiceidd = "CV".$dd2 . $mm2 . $yy2 . "-".($invDescIdNo + 1);
                return $invoiceidd;
            } else {
                $invoiceidd = "CV".$dd2 . $mm2 . $yy2 . "-1";

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

            $invoiceidd = "CV".$dd2 . $mm2 . $yy2 . "-1";

            return $invoiceidd;
        }
    }
    public function getDue($party_id)
    {
        $partySales = PurchaseInvoice::where('party_id','=',$party_id)
            ->where('status','!=','Completed')
            ->get();
        $party = Party::find($party_id);

        $totalAmount = 0;
        $totalPrice = 0;

        if(count($partySales)>0){
            foreach ($partySales as $sale) {

                $saleDetails = PurchaseInvoiceDetail::where('detail_invoice_id','=',$sale->invoice_id)->get();
                $transactions = Transaction::where('invoice_id','=',$sale->invoice_id)
                    ->where('payment_method', '=', 'Check')
                    ->where('type', '=', 'Payment')
                    ->where('cheque_status', '=', 1)->get();
                foreach($saleDetails as $saleDetail)
                {
                    $totalPrice = $totalPrice + ($saleDetail->price * $saleDetail->quantity);
                }

                foreach($transactions as $transaction)
                {
                    $totalAmount =$totalAmount + ($transaction->amount);
                }

                $transactions2 = Transaction::where('invoice_id','=',$sale->invoice_id)
                    ->where('type', '=', 'Payment')
                    ->where('payment_method', '!=', 'Check')->get();
                foreach($transactions2 as $transaction)
                {
                    $totalAmount =$totalAmount + ($transaction->amount);
                }

            }
        }
        $due = $totalPrice + $party->balance - $totalAmount;
        return new JsonResponse($due);

    }

    public function getVoucher($voucherId){
        $transactions = Transaction::where('voucher_id','=',$voucherId)->get();
        return view('Sales.voucher',compact('transactions'));

    }


}
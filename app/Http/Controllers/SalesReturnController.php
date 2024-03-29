<?php namespace App\Http\Controllers;

use App\AccountCategory;


use App\Branch;
use App\Import;
use App\NameOfAccount;
use App\Party;
use App\Product;
use App\PurchaseInvoice;
use App\PurchaseInvoiceDetail;
use App\Sale;
use App\SaleDetail;
use App\SalesReturn;
use App\SalesReturnInvoice;
use App\SalesReturnDetail;
use App\Stock;
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

class SalesReturnController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getIndex()
    {
        if(Session::get('user_role')=='admin'){
            $salesreturn = SalesReturnInvoice::orderBy('id','DESC')->paginate(15);
        }else{
            $salesreturn = SalesReturnInvoice::where('branch_id','=',Session::get('user_branch'))
                ->orderBy('id','DESC')
                ->paginate(15);
        }

        return view('SalesReturn.list2',compact('salesreturn'));
    }
    public function getCreate()
    {
        $stockInfos = new StockInfo();
        $allStockInfos = $stockInfos->getStockInfoDropDown();

        $parties = new Party();
        $partyAll = $parties->getBuyersDropDown();

        $imports= new Import();
        $consignmentAll = $imports->getConsignmentNameDropDown();

        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        $imports = Import::where('status','=','Activate')
            ->get();
        $invoiceId = $this->generateInvoiceId();
        return view('SalesReturn.add2',compact('allStockInfos','consignmentAll', 'imports', 'invoiceId'))
            ->with('branchAll',$branchAll)
            ->with('partyAll',$partyAll);
    }
    public  function getProduct($type)
    {
        $product_type = Input::get('product_type');
        $branch= Input::get('branch');
        $productsName = Product::where('product_type','=',$product_type)
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

    private function saleReturnDetailConvertToArray($saleRetrnDetails)
    {
        $array = array();
        if(Input::get('product_status') == 'Intact'){
            $stock = StockInfo::find(Input::get('stock_info_id'));
            $array['stock_name'] = $stock->name;
        }

        $array['id'] = $saleRetrnDetails->id;
        $array['product_type'] = $saleRetrnDetails->product_type;
        $array['product_status'] = Input::get('product_status');
        $array['product_id'] = $saleRetrnDetails->product->name;
        $array['quantity']   = $saleRetrnDetails->quantity;
        $array['unit_price']   = $saleRetrnDetails->unit_price;
        $array['remarks'] = $saleRetrnDetails->remarks;
        $array['consignment_name'] = $saleRetrnDetails->consignment_name;
        return $array;
    }


    public function postSaveSalesReturn()
    {
        $ruless = array(
            'party_id' => 'required'

        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return json_encode(($validate));
        }
        else{
            $invoiceId = $this->generateInvoiceId();
            $salesReturnInvoice = new SalesReturnInvoice();
            $this->setSalesReturnInvoiceData($salesReturnInvoice, $invoiceId);

            $salesReturnDetails = new SalesReturnDetail();
            $salesReturnDetails->product_type = Input::get('product_type');
            if(Input::get('stock_info_id')){
                $salesReturnDetails->stock_info_id = Input::get('stock_info_id');
            }

            $salesReturnDetails->product_id = Input::get('product_id');
            $salesReturnDetails->quantity = Input::get('quantity');
            $salesReturnDetails->unit_price = Input::get('unit_price');
            $salesReturnDetails->return_amount = ($salesReturnDetails->quantity * $salesReturnDetails->unit_price) - ($salesReturnDetails->quantity*$salesReturnDetails->unit_price)*((double)Input::get('discount_percentage')/100);
            $salesReturnDetails->consignment_name = Input::get('consignment_name');

            $salesReturnDetails->invoice_id = $invoiceId;
            $salesReturnDetails->save();

            //automatically reduce sales payment starts
            $unit_price = Input::get('unit_price');
            $remaining_amount = $salesReturnDetails->return_amount;
            $partyId=Input::get('party_id');
            if($remaining_amount > 0)
            {
                $invoiceId = Sale::where('party_id','=',$partyId)
                    ->where('is_sale','=',1)
                    ->get();
                foreach($invoiceId as $invid)
                {
                    $price = SaleDetail::where('invoice_id','=',$invid->invoice_id)->get();
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
                        $accountCategory = AccountCategory::where('name','=','Sales Return Category')->first();
                        $accountname = NameOfAccount::where('name','=','Sales Return Account')->first();
                        $voucherId = $this->generateVoucherId();
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
                            $transaction->payment_method = 'Sales Return';
                            $transaction->account_category_id = $accountCategory?$accountCategory->id:null;
                            $transaction->remarks = Input::get('remarks');
                            $transaction->account_name_id = $accountname->id;
                            $transaction->user_id = Session::get('user_id');
                            $transaction->cheque_no = '';
                            $transaction->voucher_id = $voucherId;
                            $branch = SaleDetail::where('invoice_id', '=', $invid->invoice_id)->first();
                            $transaction->branch_id = $branch->branch_id;

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
                                $transaction->payment_method = 'Sales Return';
                                $transaction->account_category_id = $accountCategory->id;
                                $transaction->remarks = Input::get('remarks')?Input::get('remarks'):'N/A';
                                $transaction->account_name_id = $accountname->id;
                                $transaction->user_id = Session::get('user_id');
                                $transaction->cheque_no = '';
                                $transaction->voucher_id = $voucherId;
                                $branch = SaleDetail::where('invoice_id', '=', $invid->invoice_id)->first();
                                $transaction->branch_id = $branch->branch_id;

                                $transaction->save();
                                $remaining_amount = $toBePaid;
                            }



                        }
                        $sale->save();

                        if(Input::get('product_status') == 'Intact'){
                            $stock_Count = StockCount::where('product_id','=', Input::get('product_id'))
                                ->where('stock_info_id','=',Input::get('stock_info_id'))
                                ->get();

                            if(empty($stock_Count[0]))
                            {
                                $stock_Count = new StockCount();
                                $stock_Count->product_id = Input::get('product_id');
                                $stock_Count->stock_info_id = Input::get('stock_info_id');
                                $stock_Count->product_quantity = Input::get('quantity');
                                $stock_Count->total_price = Input::get('quantity')*Input::get('unit_price') ;
                                $stock_Count->save();
                            }else{
                                $stock_Count[0]->product_quantity = $stock_Count[0]->product_quantity + Input::get('quantity');
                                $stock_Count[0]->total_price = $stock_Count[0]->total_price + (Input::get('quantity')*Input::get('unit_price'));
                                $stock_Count[0]->save();
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
                            $stock->price = Input::get('unit_price');
                            $stock->entry_type = "StockIn";
                            $stock->remarks = Input::get('remarks');
                            $stock->invoice_id = $stockInvoiceId;
                            $stock->stock_info_id = Input::get('stock_info_id');
                            $stock->save();
                        }

                    }
                }
            }

            Session::flash('message', 'Sales has been Returned Successfully!!!');
            /*if($remaining_amount>0)
            {
                echo "How come its possible! Consult with DEVELOPERS!!!";
            }*/
            //automatically reduce sales payment ends

            return $this->saleReturnDetailConvertToArray($salesReturnDetails);
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
    private function setSalesReturnInvoiceData($salesReturnInvoice, $invoiceId)
    {
            $this->insertSalesReturnData($salesReturnInvoice, $invoiceId);
            $stock_invoices_check = SalesReturnInvoice::where('invoice_id','=',Input::get('invoice_id'))
                ->get();
            if(empty($stock_invoices_check[0]))
                $salesReturnInvoice->save();
    }
    private function insertSalesReturnData($salesreturn, $invoiceId)
    {
        if(Session::get('user_role') == 'admin'){
            $salesreturn->branch_id = Input::get('branch_id');
        }else{
            $salesreturn->branch_id = Session::get('user_branch');
        }
        $salesreturn->party_id = Input::get('party_id');
        $salesreturn->product_status = Input::get('product_status');
        $salesreturn->ref_no = Input::get('ref_no');
        $salesreturn->discount_percentage = Input::get('discount_percentage');
        $salesreturn->invoice_id = $invoiceId;
        //$salesreturn->status = "Activate";
        $salesreturn->user_id = Session::get('user_id');
    }

    private function generateInvoiceId()
    {
        //needs recheck
        $invdesc = SalesReturnInvoice::orderBy('id', 'DESC')->first();
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
                $invoiceidd = "R".$dd2 . $mm2 . $yy2 . "-".($invDescIdNo + 1);
                //var_dump($invoiceidd);
                return $invoiceidd;
            } else {
                $invoiceidd = "R".$dd2 . $mm2 . $yy2 . "-1";
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


            $invoiceidd = "R".$dd2 . $mm2 . $yy2 . "-1";
            //var_dump($invoiceidd);
            return $invoiceidd;
        }
    }

    public function getDetails($invoice_id = null){
        if($invoice_id != null) {
            $srDetails = SalesReturnDetail::where('invoice_id','=',$invoice_id)->get();
            //$saleTransactions = Transaction::where('invoice_id','=',$invoice_id)->get();
            $sr = SalesReturnInvoice::where('invoice_id','=',$invoice_id)->first();
            return view('SalesReturn.details',compact('srDetails'))
                ->with('sr',$sr);
        }
    }

    public function getDelete($id)
    {

        $salesreturn=SalesReturnDetail::find($id);
        $saleReturnInvoice = SalesReturnInvoice::where('invoice_id', '=', $salesreturn->invoice_id)->first();
        $remaining_amount=$salesreturn->return_amount;
        $partyId=$saleReturnInvoice->party_id;

        if($remaining_amount>0)
        {
            $invoiceId = Sale::where('party_id','=',$partyId)
                ->where('is_sale','=',1)
                ->get();
            foreach($invoiceId as $invid)
            {

                $amount=Transaction::where('invoice_id','=',$invid->invoice_id)
                    ->where('type','=','Receive')
                    ->where('account_category_id','=',7)
                    ->get();

                foreach($amount as $amnt)
                {
                    if($amnt->amount>$remaining_amount)
                    {
                        $transaction=Transaction::find($amnt->id);
                        $transaction->amount=$transaction->amount-$remaining_amount;
                        $transaction->save();

                        $sale = Sale::find( $invid->id);
                        $sale->status='Partial';
                        $sale->save();

                        $remaining_amount=0;
                    }
                    elseif($amnt->amount<$remaining_amount)
                    {
                        $transaction=Transaction::find($amnt->id);
                        $transacamount=$transaction->amount;
                        $transaction->delete();

                        $sale = Sale::find( $invid->id);
                        $sale->status='Partial';
                        $sale->save();

                        $remaining_amount=$remaining_amount-$transacamount;
                    }
                    elseif($amnt->amount==$remaining_amount)
                    {
                        $transaction=Transaction::find($amnt->id);
                        $transaction->delete();

                        $sale = Sale::find( $invid->id);
                        $sale->status='Partial';
                        $sale->save();

                        $remaining_amount=0;
                    }

                }

            }
        }
        $salesreturn->delete();
        $saleReturnDetail = SalesReturnDetail::where('invoice_id', '=', $saleReturnInvoice->invoice_id)->get();
        if(empty($saleReturnDetail[0])) {
            echo 'deleted';
            $saleReturnInvoice->delete();
        }


    }

    function delsalesreturn($srInvoiceId = null){
        if($srInvoiceId != null) {
            $srInvoice = SalesReturnInvoice::find($srInvoiceId);
            $srDetails = SalesReturnDetail::where('invoice_id','=',$srInvoice->invoice_id)
                ->get();
            foreach($srDetails  as $row) {
                $this->getDelete($row->id);
            }
            return Redirect::to('salesreturn/index');
        }
    }

    public function getShowinvoice($invoiceId)
    {
        $srDetails = SalesReturnDetail::where('invoice_id','=',$invoiceId)->get();
        $srInvoice = SalesReturnInvoice::where('invoice_id','=',$invoiceId)->first();
        return view('SalesReturn.showInvoice',compact('srDetails'))
            ->with('srInvoice',$srInvoice);

    }

    public function getEdit($id)
    {
        $stockInvoices = SalesReturnInvoice::find($id);
        $branches = new Branch();

        $stockDetails = SalesReturnDetail::where('invoice_id', '=', $stockInvoices->invoice_id)->first();
        $stockDetails2 = SalesReturnDetail::where('invoice_id', '=', $stockInvoices->invoice_id)->get();

        // get all stocks

        $invoiceId = $stockInvoices->invoice_id;
        //$invoiceId = 's121513';
        $buyersAll = 0;

        $imports = Import::where('status','=','Activate')
            ->get();

        $productsName = Product::where('product_type','=',$stockDetails->product_type)
            ->get();

        $stockInfos = new StockInfo();
        $allStockInfos = $stockInfos->getStockInfoDropDown();

        $parties = new Party();
        $partyAll = $parties->getPartiesDropDown();

        $imports= new Import();
        $consignmentAll = $imports->getConsignmentNameDropDown();

        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        $imports = Import::where('status','=','Activate')
            ->get();



        return view('SalesReturn.edit2',compact('buyersAll','invoiceId', 'stockInvoices', 'stockDetails', 'productsName', 'stockDetails2', 'imports', 'partyAll', 'consignmentAll'))
            ->with('branchAll',$branchAll)
            ->with('allStockInfos',$allStockInfos);

//        return view('Stocks.edit',compact('stock'))
//            ->with('allStockInfos',$allStockInfos)
//            ->with('branchAll',$branchAll);

    }
}
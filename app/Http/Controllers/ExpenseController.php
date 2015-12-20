<?php namespace App\Http\Controllers;

use App\AccountCategory;


use App\Branch;
use App\Expense;
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

class ExpenseController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getIndex()
    {
        $expenseAll = Expense::orderBy('id','DESC')->paginate(15);
        $expense = new Expense();
        $allInvoices = $expense->getExpenseInvoiceDropDown();
        $invoice = Input::get('invoice_id');
        $expenseInvoice = Expense::where('invoice_id',$invoice)->get();
        return view('Expenses.list',compact('expenseAll'))
            ->with('allInvoices',$allInvoices)
            ->with('expenseInvoice',$expenseInvoice);
    }
    public function getCreate()
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();

        // Invoice Id Generation Starts
        $invoiceid =$this->generateInvoiceId();
        //var_dump($invoiceid);
        // Invoice Id Generation Starts

        return view('Expenses.add')
            ->with('branchAll',$branchAll)
            ->with('invoiceid',$invoiceid);
    }

    private function generateInvoiceId()
    {
        $invdesc = Expense::orderBy('id', 'DESC')->first();
        if ($invdesc != null) {
            $invDescId = $invdesc->invoice_id;
            $invDescIdNo = substr($invDescId, 7);

            $subinv1 = substr($invDescId, 6);
            $dd = substr($invDescId, 1, 2);
            $mm = substr($invDescId, -7, -5);
            $yy = substr($invDescId, -5, -3);
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



            if ($dd == $dd2 && $yy == $yy2 && $mm == $mm2) {
                $invoiceidd = "E".$dd2 . $mm2 . $yy2 . $invDescIdNo + 1;
                return $invoiceidd;
            } else {
                $invoiceidd = "E".$dd2 . $mm2 . $yy2 . "0001";
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


            $invoiceidd = "E".$dd2 . $mm2 . $yy2 . "0001";
            return $invoiceidd;
        }
    }

    public function postSaveExpense()
    {
        $ruless = array(
            'branch_id' => 'required',
            'category' => 'required',
            'amount' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('expenses/index/')
                ->withErrors($validate);
        }
        else{
            $this->setExpenseData();
            Session::flash('message', 'Expense has been Successfully Saved.');
            return Redirect::to('expenses/index/');

        }
    }

    public function getEdit($id)
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        $expense = Expense::find($id);
        return view('Expenses.edit',compact('expense'))
            ->with('branchAll',$branchAll);

    }
    public function postUpdateExpense($id)
    {
        $ruless = array(
            'branch_id' => 'required',
            'category' => 'required',
            'amount' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('purchases/index/')
                ->withErrors($validate);
        }
        else{
            $this->updateExpenseData($id);
            Session::flash('message', 'Expense has been Successfully Updated.');
            return Redirect::to('expenses/index/');

        }
    }
    private function updateExpenseData($id)
    {
        $expense =Expense::find($id);
        $expense->invoice_id = Input::get('invoice_id');
        $expense->branch_id = Input::get('branch_id');
        $expense->category = Input::get('category');
        $expense->particular = Input::get('particular');
        $expense->purpose = Input::get('purpose');
        $expense->amount = Input::get('amount');
        $expense->status = "Activate";
        $expense->user_id = Session::get('user_id');
        $expense->remarks = Input::get('remarks');
        $expense->save();
    }
    private function setExpenseData()
    {
        $expense = new Expense();
        $expense->invoice_id = Input::get('invoice_id');
        $expense->branch_id = Input::get('branch_id');
        $expense->category = Input::get('category');
        $expense->particular = Input::get('particular');
        $expense->purpose = Input::get('purpose');
        $expense->amount = Input::get('amount');
        $expense->status = "Activate";
        $expense->user_id = Session::get('user_id');
        $expense->remarks = Input::get('remarks');
        $expense->save();
    }
    public function getDelete($id)
    {
        $del = Expense::find($id);
        try {
            $del->delete();
            Session::flash('message', 'Expense  has been Successfully Deleted.');
        } catch (Exception $e) {
            Session::flash('message', 'This Expense can\'t delete because it  is used to file');
        }
        return Redirect::to('expenses/index');
    }

     public function getDetails($invoiceId)
    {
        $expense =  Expense::where('invoice_id','=',$invoiceId)->get();
        $expenseTransactions = Transaction::where('invoice_id','=',$invoiceId)->get();
        return view('Expenses.details',compact('expenseTransactions'))
            ->with('expense',$expense[0]['amount']);

    }
    public function getMake()
    {
        $accountCategories = new AccountCategory();
        $accountCategoriesAll = $accountCategories->getAccountCategoriesDropDown();
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('Expenses.paymentAdd',compact('accountCategoriesAll'))
            ->with('branchAll',$branchAll);
    }
    public function postSaveMake()
    {
        $ruless = array(
            'branch_id' => 'required',
            'account_category_id' => 'required',
            'account_name_id' => 'required',
            'amount' => 'required',
            'payment_method' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('expenses/index/')
                ->withErrors($validate);
        }
        else{

            $this->setPurchasePayment();

            return Redirect::to('expenses/index');

        }
    }
    private function setPurchasePayment()
    {
        $accountPayment = NameOfAccount::find(Input::get('account_name_id'));
        if($accountPayment->opening_balance >= Input::get('amount')){

            $expense[0] = Expense::where('invoice_id','=',Input::get('invoice_id'))->get();
            $expenseTransaction = new Transaction();
            $expenseTransaction->branch_id = Input::get('branch_id');
            $expenseTransaction->account_category_id = Input::get('account_category_id');
            $expenseTransaction->account_name_id = Input::get('account_name_id');
            $expenseTransaction->amount = Input::get('amount');
            $expenseTransaction->remarks = Input::get('remarks');
            $expenseTransaction->type = "Expense";
            $expenseTransaction->user_id = Session::get('user_id');
            $expenseTransaction->payment_method = Input::get('payment_method');
            $expenseTransaction->invoice_id = Input::get('invoice_id');
            $expenseTransaction->cheque_no = Input::get('cheque_no');
            $expenseTransaction->save();

            $totalAmount = 0;

            $transactions = Transaction::where('invoice_id','=',$expenseTransaction->invoice_id)->get();

            foreach($transactions as $transaction)
            {
                $totalAmount =$totalAmount + ($transaction->amount);
            }
            $expense = Expense::find( $expense[0][0]['id']);
            if($totalAmount == $expense->amount)
            {
                $expense->status = "Completed";
            }else{
                $expense->status = "Partial";
            }
            $expense->save();

            $accountPayment->opening_balance = $accountPayment->opening_balance - Input::get('amount');
            $accountPayment->save();
            Session::flash('message', 'Expense has been Successfully Cleared.');
        }else{
            Session::flash('message', 'You dont have Enough Balance');
        }


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
    public function getCategories($category_id)
    {
        $categoriesName = NameOfAccount::where('account_category_id','=',$category_id)
            ->get();
        foreach ($categoriesName as $categoryName) {
            echo "<option value = $categoryName->id > $categoryName->name</option> ";
        }
    }
    public function getAccountbalance($account_id)
    {
        $accountBalance = NameOfAccount::find($account_id);
        echo "<p3 style='color: blue;font-size: 150%'>Your Current Balance $accountBalance->opening_balance</p3>";
    }




}
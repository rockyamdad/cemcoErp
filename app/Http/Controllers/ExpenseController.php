<?php namespace App\Http\Controllers;

use App\AccountCategory;


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

    public function getIndex()
    {
       $expenseAll = Expense::all();

        return view('Expenses.list',compact('expenseAll'));
    }
    public function getCreate()
    {
        return view('Expenses.add');
    }
    public function postSaveExpense()
    {
        $ruless = array(
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
       $expense = Expense::find($id);
        return view('Expenses.edit',compact('expense'));

    }
    public function postUpdateExpense($id)
    {
        $ruless = array(
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
        return view('Expenses.paymentAdd',compact('accountCategoriesAll'));
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
            $expenseTransaction->account_category_id = Input::get('account_category_id');
            $expenseTransaction->account_name_id = Input::get('account_name_id');
            $expenseTransaction->amount = Input::get('amount');
            $expenseTransaction->remarks = Input::get('remarks');
            $expenseTransaction->type = "Expense";
            $expenseTransaction->user_id = Session::get('user_id');
            $expenseTransaction->payment_method = Input::get('payment_method');
            $expenseTransaction->invoice_id = Input::get('invoice_id');
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





}
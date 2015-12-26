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

class ChequeRegisterController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getIndex()
    {
        $register = Transaction::where('payment_method','=','check')
            ->where('type','=','Receive')
            ->get();
        return view('ChequeRegister.list',compact('register'));
    }

}
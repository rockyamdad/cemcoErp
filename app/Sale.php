<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Sale extends Eloquent
{
    protected $table = 'sales';
    public function saledetails()
    {
        return $this->hasMany('App\SaleDetail');
    }
    public function party()
    {
        return $this->belongsTo('App\Party');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function deletee()
    {
        $this->saledetails()->delete();
        return parent::delete();
    }
    public function getSalesInvoice()
    {
        $sales = DB::table('sales')->get();
        return $sales;
    }
    public function getSalesInvoiceDropDown()
    {
        $saleInvoices = $this->getSalesInvoice();

        $array = array();

        foreach($saleInvoices as $invoice){
            $array[$invoice->invoice_id] = $invoice->invoice_id;
        }

        return $array;
    }

    public function getPartydue($invoiceId)
    {
        $totalPrice = 0;
        $totalAmount = 0;
        $saleDetails = SAleDetail::where('invoice_id','=',$invoiceId)->get();
        $transactions = Transaction::where('invoice_id','=',$invoiceId)
            ->where('payment_method', '=', 'Check')
            ->where('type', '=', 'Receive')
            ->where('cheque_status', '=', 1)->get();
        foreach($saleDetails as $saleDetail)
        {
            $totalPrice = $totalPrice + ($saleDetail->price * $saleDetail->quantity);
        }

        foreach($transactions as $transaction)
        {
            $totalAmount =$totalAmount + ($transaction->amount);
        }

        $transactions2 = Transaction::where('invoice_id','=',$invoiceId)
            ->where('type', '=', 'Receive')
            ->where('payment_method', '!=', 'Check')->get();
        foreach($transactions2 as $transaction)
        {
            $totalAmount =$totalAmount + ($transaction->amount);
        }


    $due = $totalPrice - $totalAmount;
    return "Due is $due";


    }

}

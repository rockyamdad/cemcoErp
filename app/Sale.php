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
    public function getTotalAmount($invoice_id)
    {
        return DB::table('sale_details')
            ->selectRaw('sum(quantity*price) as total')
            ->where('invoice_id', '=', $invoice_id)
            ->get();
    }
    public function getsalesdue($invoiceId)
    {
        $totalPrice = $this->getTotalAmount($invoiceId);
        $totalAmount = 0;

        $transactions = Transaction::where('invoice_id','=',$invoiceId)
            ->where('payment_method', '=', 'Check')
            ->where('type', '=', 'Payment')
            ->where('cheque_status', '=', 1)->get();


        foreach($transactions as $transaction)
        {
            $totalAmount =$totalAmount + ($transaction->amount);
        }

        $transactions2 = Transaction::where('invoice_id','=',$invoiceId)
            ->where('type', '=', 'Payment')
            ->where('payment_method', '!=', 'Check')->get();
        foreach($transactions2 as $transaction)
        {
            $totalAmount =$totalAmount + ($transaction->amount);
        }

        $due = $totalPrice[0]->total - $totalAmount;
        return $due;

    }

    public function getPartydue($invoiceId)
    {
        $totalPrice = 0;
        $totalAmount = 0;
        $sale = Sale::where('invoice_id','=',$invoiceId)->first();
        $saleDetails = SAleDetail::where('invoice_id','=',$invoiceId)->get();
        $transactions = Transaction::where('invoice_id','=',$invoiceId)
            ->where('payment_method', '=', 'Check')
            ->where('type', '=', 'Receive')
            ->where('cheque_status', '=', 1)->get();
        foreach($saleDetails as $saleDetail)
        {
            $totalPrice = $totalPrice + ($saleDetail->price * $saleDetail->quantity);
        }
        $totalPrice -= $sale->discount_percentage;
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
        if($due > 0)
            return "Due is $due";
        else
            return 'No due';


    }
    public function getSalesList($branch_id)
    {
        return DB::table('sales')
            ->join('sale_details', 'sales.invoice_id', '=', 'sale_details.invoice_id')
            ->where('sale_details.branch_id', '=', $branch_id)
            ->orderBy('sales.id','DESC')
            ->get();
    }

}

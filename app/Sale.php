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

}

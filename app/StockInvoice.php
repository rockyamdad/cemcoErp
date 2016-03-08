<?php
/**
 * Created by PhpStorm.
 * User: Aasim
 * Date: 3/1/2016
 * Time: 12:39 AM
 */
namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class StockInvoice extends Eloquent
{
    protected $table = 'stock_invoices';
    public function stockdetails()
    {
        return $this->hasMany('App\StockDetail');
    }
    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function deletee()
    {
        $this->stockdetails()->delete();
        return parent::delete();
    }
    public function getStockInvoice()
    {
        $sales = DB::table('stock_invoices')->get();
        return $sales;
    }
    public function getStockInvoiceDropDown()
    {
        $stockInvoices = $this->getStockInvoice();

        $array = array();

        foreach($stockInvoices as $invoice){
            $array[$invoice->invoice_id] = $invoice->invoice_id;
        }

        return $array;
    }

}
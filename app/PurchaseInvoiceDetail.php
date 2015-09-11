<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class PurchaseInvoiceDetail extends Eloquent
{
    protected $table = 'purchase_invoice_details';
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

}

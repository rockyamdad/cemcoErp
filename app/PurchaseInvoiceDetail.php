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
    public function getTotalAmount($invoice_id)
    {
        return DB::table('purchase_invoice_details')
            ->selectRaw('sum(quantity*price) as total')
            ->where('detail_invoice_id', '=', $invoice_id)
            ->get();
    }

}

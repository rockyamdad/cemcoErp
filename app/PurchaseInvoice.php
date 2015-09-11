<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class PurchaseInvoice extends Eloquent
{
    protected $table = 'purchase_invoices';
    public function purchaseinvoicedetails()
    {
        return $this->hasMany('App\PurchaseInvoiceDetail');
    }
    public function party()
    {
        return $this->belongsTo('App\Party');
    }
    public function user()
    {
        return $this->hasOne('App\User','foreign_key');
    }

    public function deletee()
    {
        $this->purchaseinvoicedetails()->delete();
        return parent::delete();
    }

}

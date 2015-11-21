<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Transaction extends Eloquent
{
    protected $table = 'transactions';
    public function accountCategory()
    {
        return $this->belongsTo('App\AccountCategory');
    }
    public function accountName()
    {
        return $this->belongsTo('App\NameOfAccount');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function getTotalPaid($invoice_id)
    {
        return DB::table('transactions')
            ->selectRaw('sum(amount) as totalPaid')
            ->where('invoice_id', '=', $invoice_id)
            ->get();
    }
}

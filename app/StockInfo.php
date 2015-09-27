<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class StockInfo extends Eloquent
{
    protected $table = 'stock_infos';
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }
}
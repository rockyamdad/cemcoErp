<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Party extends Eloquent
{
    protected $table = 'parties';
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

}
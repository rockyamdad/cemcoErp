<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Expense extends Eloquent
{
    protected $table = 'expenses';
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

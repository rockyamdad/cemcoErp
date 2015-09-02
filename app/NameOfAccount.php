<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class NameOfAccount extends Eloquent
{
    protected $table = 'name_of_accounts';
    public function account()
    {
        return $this->hasOne('App\AccountCategory');
    }
    public function user()
    {
        return $this->hasOne('App\User');
    }
}

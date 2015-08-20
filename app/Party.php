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
    public function getParties()
    {
        $parties = DB::table('parties')->where('status', 'Activate')->get();
        return $parties;
    }
    public function getPartiesDropDown()
    {
        $parties = $this->getParties();

        $array = array();

        foreach($parties as $party){
            $array[$party->id] = $party->name;
        }

        return $array;
    }
}
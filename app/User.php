<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Eloquent implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

 /*   public function branch()
    {
        return $this->belongsTo('App\Branch');
    }*/
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    public function products()
    {
        return $this->hasMany('App\Product');
    }
    public function purchaseinvoices()
    {
        return $this->hasMany('App\PurchaseInvoice');
    }
    public function getSalesMan()
    {
        $parties = DB::table('users')->where('status','=', 'Activate')
            ->get();
        return $parties;
    }
    public function getSalesManDropDown()
    {
        $salesMans = $this->getSalesMan();

        $array = array();

        foreach($salesMans as $salesMan){
            $array[$salesMan->id] = $salesMan->name;
        }

        return $array;
    }

	// test
}

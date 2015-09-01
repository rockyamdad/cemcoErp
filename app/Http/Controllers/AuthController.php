<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller{

    public function index()
    {
        return view('Users.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function store()
    {

        $rules = array(
            'email' => 'required|email',
            'password' => 'required'
        );
        $validate = Validator::make(Input::all(),$rules);

        if($validate->fails())
        {
            return Redirect::to('/');
        }
        else{

            if(Auth::attempt(array('email'=>Input::get('email'),'password'=>Input::get('password'),'status'=>'Activate')))
            {
                /*$user = User::where('email','=',$email)->get();
                Session::put('user_type',$user[0]->role);
                $id = $user[0]->id;
                Session::put('created_by',$id);*/
                Session::put('user_id',Auth::user()->id);
                Session::put('user_name',Auth::user()->name);
                Session::put('user_role',Auth::user()->role);
                Session::flash('message', 'User has been Successfully Login.');
                $roles= Auth::user()->role;

                if($roles = 'admin' || 'manager')
                {
                    return  Redirect::to('dashboard');
                }elseif($roles = 'user')
                {
                    return  Redirect::to('profile');
                }
            }
            else
            {
                Session::flash('message', 'Your username or password incorrect');
                return   Redirect::to('/');
            }
        }
    }
    public function getLogout()
    {
        Session::flush();
        Auth::logout();
        return Redirect::to('/')->with('message', 'Your are now logged out!');
    }
}
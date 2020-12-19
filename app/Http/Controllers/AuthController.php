<?php namespace App\Http\Controllers;

use App\Branch;
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
            return Redirect::to('/')
                ->withErrors($validate);
        }
        else{

            if(Auth::attempt(array('email'=>Input::get('email'),'password'=>Input::get('password'),'status'=>'Activate')))
            {
                $checkBranch = Branch::where('user_id','=',Auth::user()->id)->first();

                if($checkBranch['status'] = 'Activate'){
                    Session::put('user_id',Auth::user()->id);
                    Session::put('user_name',Auth::user()->username);
                    Session::put('user_role',Auth::user()->role);
                    Session::put('user_branch',Auth::user()->branch_id);
                    Session::flash('message', 'User has been Successfully Login.');
                    $roles= Auth::user()->role;

                    if($roles = 'admin' || 'manager')
                    {
                        return  Redirect::to('dashboard')
                            ->with('flash_notice', 'You are successfully logged in.');;
                    }elseif($roles = 'user')
                    {
                        return  Redirect::to('profile')
                            ->with('flash_notice', 'You are successfully logged in.');;
                    }
                }else{
                    return   Redirect::to('/')
                        ->with('flash_error', 'Your Branch is not Active');
                }

            }
            else
            {
                //Session::flash('message', 'Your username or password incorrect');
                return   Redirect::to('/')
                    ->with('flash_error', 'Your username/password combination was incorrect.');
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
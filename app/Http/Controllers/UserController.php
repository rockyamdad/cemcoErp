<?php namespace App\Http\Controllers;

use App\Branch;
use App\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;


class UserController extends Controller{


    public function getIndex()
    {
        $users = User::all();
        return view('Users.list',compact('users'));
    }
    public function getDashboard()
    {
        return view('Users.dashboard');
    }
    public function getuserAdd()
    {
        $branches = new Branch();
       $branchAll = $branches->getBranchesDropDown();
        return view('Users.add',compact('branchAll'));
    }
    public function postSaveUser()
    {
        $ruless = array(
            'username' => 'required',
            'role' => 'required',
            'email' =>  'required|email|unique:users|Unique:users',
            'password' => 'required'
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('add')
                ->withErrors($validate);
        }
        else{
            $user = new User();
            $this->setUserData($user);
            $user->save();
            Session::flash('message', 'User has been Successfully Created.');
            return Redirect::to('list/');
        }
    }
    public function getProfile()
    {

    }
    public function getEdit($id)
    {
        $users = User::find($id);
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        return view('Users.edit',compact('branchAll'))
            ->with('userdata',$users);

    }
    public function putCheckupdate($id)
    {
        $ruless = array(
            'username' => 'required',
            'branch_id' => 'required',
            'role' => 'required',
            'email' =>  'required|email|Unique:users,email,'.$id

        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('edit/'.$id)
                ->withErrors($validate);
        }
        else{
            $user = User::find($id);
            $this->setUserData($user);
            $user->save();
            Session::flash('message', 'User has been Successfully Updated.');
            return Redirect::to('list/');
        }
    }
    public function getChangeStatus($status,$id)
    {
        $user = User::find($id);
        if($user['status'] == $status) {
            $user->status = ($status == 'Activate' ? 'Deactivate': 'Activate');
            $user->save();

        }
        return new JsonResponse(array(
            'id' => $user['id'],
            'status' => $user['status']
        ));
    }
    private function setUserData($user)
    {
        $user->name  = Input::get('name');
        $user->username = Input::get('username');
        $user->email = Input::get('email');
        $user->password = Hash::make(Input::get('password'));
        $user->phone = Input::get('phone');
        $user->address = Input::get('address');
        $user->role = Input::get('role');
        $user->sex = Input::get('sex');
        //$user->created_by = Session::get('user_id');
        $user->branch_id = 1;
        $user->status = "Activate";
    }

}
<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use DB, App\User, Response, Input, Auth, Redirect, Validator, Hash;
use App\MailQueue;

class UserAdminController extends Controller {

    public function index(){
        $users = User::where("privilege",2)->where("status","!=",5)->get();
        $sidebar = "users";
        $subsidebar = "users";

        return view('companies.users.list',compact('sidebar','subsidebar' , 'users'));
    }

    public function add($id=0){
        $sidebar = 'users';
        $subsidebar = 'users';
        // $id = 0;
        // if (Input::has('id')) {
        //     $id = Input::get('id');
        // }
        $user = User::find($id);


        return  view('companies.users.add',compact('sidebar','subsidebar','user'));
    }

    public function store(Request $request,$id=0){
        return "ok";
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users'
        ]);

        if ($validator->fails()) {
          return Redirect::back()->withErrors($validator)->withInput();
        } else {
          
          $client = User::find($request->id);
          $flag_new = false;

          if(!$client){
            $client = new User;
            $flag_new = true;
          }

          $client->privilege = 2;
          $client->name = $request->name;
          $client->email = $request->email;
          $client->phone_number = $request->phone_number;
          $client->company_id = Auth::user()->company_id;

          // $password = User::getRandPassword();
          $password = "sample";
          $client->password = Hash::make($password);
          $client->password_check = $password;
            
          $client->active = 0;
          $client->status = 0;
          $client->user_access = 0;

          $client->added_by = Auth::id();
          $client->save();
          
          $client->parent_user_id = Auth::user()->parent_user_id;
          $client->save();

          if($flag_new) {
            $content = view('mails',[ "user"=>$client , "type"=>"registration", "password" => $password]);
            MailQueue::createMail($request->email, "","", "HRX Admin Portal - New User Registration", $content);
          }

          return Redirect::to('company/users')->with('success','New client is added successfully');
        }
    }
    public function userInit(){
        $data['users'] = User::getCompanyUsersObject();
        $data['success'] = true;
        return Response::json($data,200,array());
    }
    public function delete($user_id){
        $user = User::find($user_id);      
        $user->status = 5;
        $user->active = 1;

        $user->save();

        return Redirect::back();
    }
    public function deleteUser($id){
        $user = User::find($id);
        if($user){
            $user->delete();
            $data['success'] = true;
            $data['message'] = 'User deleted successfully';   
        }
        else{
            $data['success'] = false;
            $data['message'] = 'User not found';
        }
        return Response::json($data,200,array());
    }
}

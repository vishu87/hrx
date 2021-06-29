<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;use Illuminate\Support\Facades\Auth;
use Redirect,Schema ,App\User ,App\GlobalList  ,App\Company  , Validator , Hash, App\Approval, App\ChangeLog,Input;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ClientAdminController extends Controller {

    public function dashboard(){
        $sidebar = 'dashboard';
        $subsidebar = 'dashboard';
        $user_approvals = Approval::select("aims_approvals.id","aims_approvals.type","clients.name","clients.group_no","clients.email")->join("aims_users as clients","clients.id","=","aims_approvals.entity_id")->where("entity_type","user")->whereNull("approved_by")->where("clients.parent_user_id",Auth::user()->parent_user_id)->where("aims_approvals.created_by","!=",Auth::id())->get();

        return view('mf.dashboard',compact('sidebar','subsidebar','user_approvals'));
    }
    
   	public function index(){
	   	
      $users = User::where('parent_user_id',Auth::user()->parent_user_id)->where("active","0")->orderBy("privilege")->get();

      $pms = User::where('parent_user_id',Auth::user()->parent_user_id)->where("active","0")->where("privilege","4")->where("access_mode",1)->count();

      foreach ($users as $user) {
        $user->editable = true;
        if(Auth::user()->privilege == 3 && $user->id == $user->parent_user_id){
          $user->editable = false;
        }
      }

      $sidebar = 'users';
      $subsidebar = 'users';


	   	return 	view('mf.users.list',compact('users','pms','sidebar','subsidebar'));
   	}

   	public function add(){
      $sidebar = 'users';
      $subsidebar = 'users';

      $yes_no = ["1" => "Yes" ,"0" => "No"];

	   	return 	view('mf.users.add',compact('sidebar','subsidebar','yes_no'));
   	}

   	public function store(Request $request){
   		$validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:aims_users',
            // 'privilege' => 'required'
        ]);

        if ($validator->fails()) {
          return Redirect::back()->withErrors($validator)->withInput();
        } else {

          // $count_admins = User::where('parent_user_id',Auth::user()->parent_user_id)->where("privilege",3)->count();

        	$client = new User;
        	$client->name = $request->name;
          $client->parent_user_id = (Auth::user()->parent_user_id)?Auth::user()->parent_user_id : Auth::id();
        	$client->email = $request->email;
        	$client->mobile = $request->mobile;
          $client->privilege = 3;

          // if(isset($request->access_mode)){
          //   $client->access_mode = (int)$request->access_mode;
          // }
          
          // if($count_admins > 1){ // if more admins are available
          //   $client->active = 1;
          //   $client->status = 1;
          // } else {
            $client->active = 0;
            $client->status = 0;
          // }

          // $password = User::getRandPassword();
          $password = "Founded@2012";
          $client->password = Hash::make($password);
          $client->password_check = $password;
          $client->save();

          $client->added_by = Auth::id();
          $client->save();

          $client->sendWelcomeEmail($password);
        	
        	return Redirect::to('mf/users')->with('success','New user is added successfully');
        }

   	}

    public function edit($client_id){
      $client = User::find($client_id);
      $sidebar = 'client-management';
      $subsidebar = 'client';

      $yes_no = ["1" => "Yes" ,"0" => "No"];

      return  view('mf.users.add',compact('client','sidebar','subsidebar','yes_no'));
    }

    public function update(Request $request ,$client_id){
      $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:aims_users,email,'.$client_id,
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{

          $count_admins = User::where('parent_user_id',Auth::user()->parent_user_id)->where("privilege",3)->count();

          $flag_change = false;

          $client = User::find($client_id);

          if($count_admins > 1){
            $check_fields = ["name","email"];

            $field_tags = [
              "name" => "Name",
              "email" => "Email"
            ];

            foreach ($check_fields as $field) {
              if($client->{$field} != $request->{$field}){
                
                $flag_change = true;
                ChangeLog::addEntry($client->id,"user",$field,$field_tags[$field],$client->{$field},$request->{$field},Auth::id());

                if($field == "email") $client->email_updated = 1;

              }
            }
          }
          
          $client->name = $request->name;
          $client->email = $request->email;
          $client->mobile = $request->mobile;

          if(isset($request->access_mode)){
            $client->access_mode = (int)$request->access_mode;
          }

          if($flag_change) {
            $client->status = 1;
            Approval::addEntry("update","user",$client->id,Auth::id());
          }

          $client->save();

          return Redirect::to('mf/users')->with('success','Users details is updated successfully');
        }

    }

   	// public function delete($client_id){
   	// 	$client = User::find($client_id);
   		
    //   if($client){
        
    //     $client->active = 1;
   	// 		$client->save();

   	// 		$data['success'] = true;
   	// 		$data['message'] = "User is removed successfully";

   	// 	}else{
   			
    //     $data['success'] = false;
   	// 		$data['message'] = "User details not found or you don't have access to remove this client ";
        
   	// 	}
   	// 	return json_encode($data);
   	// }

    public function delete($client_id){
      $client = User::where("id",$client_id)->where("parent_user_id",Auth::user()->parent_user_id)->first();
      
      if($client){
        
        $client->active = 1;
        $client->save();

      }

      return Redirect::back();
    }

    public function updateSettings(){
      $user_id = Auth::user()->parent_user_id;
      $user = User::find($user_id);
      $user->disable_checker = Input::get("disable_checker");
      $user->save();

      return Redirect::back()->with("success","Settings are successfully updated");
    }

    
}

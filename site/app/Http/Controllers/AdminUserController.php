<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use DB, App\User, Response;
use Illuminate\Http\Request;
use Input ,Redirect,Schema, Validator, Hash, App\ChangeLog, App\Approval,App\Proxy,App\JobOffers;
use App\MailQueue;

class AdminUserController extends Controller {

    public function dashboard(){
        $companies = DB::table("companies")->count();
        $job_offers = DB::table("job_offers")->count();
        $offers = DB::table("job_offers")
        ->select('job_offers.candidate_name','job_offers.email','job_offers.phone_no','companies.name as company_name','companies.notification','job_offers.created_at')
        ->leftJoin('companies','companies.id','=','job_offers.company_id')
        ->get();

        return view('admin.dashboard',["sidebar"=>"dashboard","subsidebar"=>"dashboard","companies"=>$companies,"job_offers"=>$job_offers
        ,"offers"=>$offers]);
    }

    public function index(){

      $users = User::getMainUsersObject();
      $sidebar = 'users';
      $subsidebar = 'users'; 
     return  view('admin.users.list',compact('users','sidebar','subsidebar'));
    }
    public function alertList(){
        $sidebar = 'alerts';

        return view('admin.alerts.list',compact('sidebar'));   
    }

    public function migrate(){
        $date_arr = ['add_date','meeting_date','vote_completed_on','completed_on','released_on','addendum_release','record_date','evoting_start','evoting_end'];
        $proxy_ad = Proxy::get();
        foreach ($proxy_ad as $proxy) {
            $entry = DB::table('proxy_ad_dump')->where('id',$proxy->id)->first();
            if($entry){
                foreach ($date_arr as $val) {
                    if($entry->$val != '' && $entry->$val != 0){

                        $proxy->$val = date('Y-m-d',$entry->$val);
                    }
                }
                $proxy->save();
                
            }
        }
        return "ok";
    }

    public function addusers($id = 0){
        $sidebar = 'users';
        $subsidebar = 'users';
        
        $user = User::find($id);

        return  view('admin.users.add',compact('sidebar','subsidebar','user'));
    }

    public function userInit(){
        $data['users'] = User::getMainUsersObject();
        $data['success'] = true;
        return Response::json($data,200,array());

    }
  

    public function store(Request $request,$id=0){
        $cre = [
            "name" => Input::get("name"),
            "email" => Input::get("email"),
        ];

        $rules = [
            "name" => "required",
            "email" => "required|email",
        ];

        if ( Input::has("id") ){
            $rules["email"] = "required|email";
        }
        $validator = Validator::make($cre,$rules);
        
        if ($validator->fails()) {
          return Redirect::back()->withErrors($validator)->withInput();
        }
        else{  
            $user = User::find($request->id);
            $flag_new = false;
            if(!$user){
                $user = new User;   
                $flag_new = true;

            }
            $user->privilege = 1;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $password = "sample";
            $user->password = Hash::make($password);
            $user->password_check = $password;
                
            $user->active = 0;
            $user->status = 0;
            $user->user_access = 0;

            $user->added_by = Auth::id();
            $user->parent_user_id = Auth::user()->parent_user_id;
            $user->save();

            if($flag_new) {
                $content = view('mails',[ "user"=>$user , "type"=>"registration", "password" => $password]);
                MailQueue::createMail($request->email, "","", "HRX Admin Portal - New User Registration", $content);
            }

            return Redirect::to('admin/users')->with('success','New user is added successfully');
        }

    }
    public function deleteUser($id){
        $user = User::find($id);

        if($user){
            // DB::table('mail_queue')->delete();
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

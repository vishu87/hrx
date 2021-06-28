<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Input,Redirect,Validator,Hash,Response,Session;
use App\User,App\Notification,App\NotificationView;
use App\MailQueue, DB, App\Portfolio;
use App\Http\Controllers\ExportController;

class UserController extends Controller {

	public function login(){
		return view('login');
	}

	public function postLogin(){

		$cre = ["email"=>Input::get("email"),"password"=>Input::get("password")];
		$rules = ["email"=>"required","password"=>"required"];
		$validator = Validator::make($cre,$rules);
		if($validator->passes()){

            $cre["active"] = 0;
			
            if(Auth::attempt($cre)){

                $user = User::find(Auth::id());
                $user->save();

                Session::put('privilege',$user->privilege);

                if(Auth::user()->privilege == 1 || Auth::user()->privilege == 3){
                    return Redirect::to('/admin/dashboard');
                }
                
			} else {

				return Redirect::back()->withInput()->with('failure','Invalid email or password');
			}

		}else{
			return Redirect::back()->withInput()->with('failure','Please fill all the fields')->withInput();
		}
	}

	public function createAccount() {
        $cre = [
            "name" => Input::get("name"),
            "email" => Input::get("email"),
            "password" => Input::get("password"),
            "confrmpwd" => Input::get("confrmpwd")
        ];
        $rules = [
            "name" => "required",
            "email" => "required",
            "password" => "required",
            "confrmpwd" => "required"
        ];
        $validator = Validator::make($cre,$rules);
        if ($validator->passes()) {
        	if(Input::get('password') === Input::get('confrmpwd')){
	            $password = Hash::make(Input::get('password'));
	            $user = new User;
	            $user->name = Input::get("name");
	            $user->username = Input::get("email");
	            $user->phone = Input::get("phone");
	            $user->password = $password;
	            $user->address = Input::get("address");
	            $user->password_check = Input::get('password');
	            $user->save();

                $data["success"] = true;
                $data["redirect_url"] = url("");
            }else {
                $data["success"] = false;
                $data["message"] = "Password does not match";
            }
        }else {
            $error = "";
            $messages = $validator->messages();
            foreach($messages->all() as $message){
                $error = $message;
                break;
            }
            $data["success"] = false;
            $data["message"] = $error;
        }
        return Response::json($data, 200, array());
    }

    public function profile(){
        $sidebar = "settings";
        $subsidebar = "settings";

        return view('profile',compact('sidebar','subsidebar'));
    }

    
    public function changePassword(){
        return view('update_password');
    }

    public function updatePasswordFirstTime(){
        $cre = [
            "old_password"=>Input::get('old_password'),
            "new_password"=>Input::get('new_password'),
            "confirm_password"=>Input::get('confirm_password')
        ];
        $rules = [
            "old_password"=>'required',
            "new_password"=>'required|different:old_password|min:8|regex:/^(?=.*\d)(?=.*[A-Z])(?=.*[~!@#$%&_^*]).{8,}$/',
            "confirm_password"=>'required|same:new_password'
        ];
        $old_password = Hash::make(Input::get('old_password'));

        $messages = ["new_password.regex"=>'New password should be minimum of 8 characters and contain a special character , numeric value and uppercase later'];
        
        $validator = Validator::make($cre,$rules,$messages);

        if ($validator->passes()) { 
            if (Hash::check(Input::get('old_password'), Auth::user()->password )) {

                $password = Hash::make(Input::get('new_password'));
                $user = User::find(Auth::id());
                $user->password = $password;
                $user->password_check = Input::get('new_password');
                $user->last_login = date("Y-m-d H:i:s");
                $user->save();

                DB::table('user_activities')->insert(["user_id"=>Auth::id() , "activity"=>"change_password","remark"=>Input::get('new_password')]);
                
                
                if(Auth::user()->privilege == 1 || Auth::user()->privilege == 3){
                    return Redirect::to('/admin/dashboard');
                }else{

                    return Redirect::to('/');
                }

                
            } else {
                return Redirect::back()->withInput()->with('failure', 'Old password does not match.');
            }
        } else {
            
            return Redirect::back()->withErrors($validator)->withInput();
        }

        return Redirect::back()->withErrors($validator)->withInput()->with('failure','Unauthorised Access or Invalid Password');
    }

    public function updatePassword(){
        $cre = ["old_password"=>Input::get('old_password'),"new_password"=>Input::get('new_password'),"confirm_password"=>Input::get('confirm_password')];
        $rules = ["old_password"=>'required',"new_password"=>'required|min:5',"confirm_password"=>'required|same:new_password'];
        $old_password = Hash::make(Input::get('old_password'));
        $validator = Validator::make($cre,$rules);
        if ($validator->passes()) { 
            if (Hash::check(Input::get('old_password'), Auth::user()->password )) {
                $password = Hash::make(Input::get('new_password'));
                $user = User::find(Auth::id());
                $user->password = $password;
                $user->password_check = Input::get('new_password');
                $user->save();
                
                $data['success']=true;
                $data['message']='Password changed successfully';
                
            } else {
                $data['success']=false;
                $data['message']='Old password does not match.';
            }
        } else {
            $data['success']=false;
            $data['message']=$validator->errors()->first();
        }

        return Response::json($data,200,[]);
    }

    public function forgetPassword(){
        return view('forget-password');
    }

    public function postForgetPassword(Request $request){
        $validator = Validator::make(["email"=>$request->email],["email"=>"required|email"]);
        
        if($validator->fails()){
            return Redirect::back()->withErrors($validator)->withInput();
        }
        
        $user = User::where('email',$request->email)->first();
        
        if(!$user){
            return Redirect::back()->withErrors($validator)->withInput()->with('failure','No user found with this email id');
        }

        $rand_pwd = User::getRandPassword();
        
        $user->password = Hash::make($rand_pwd);
        $user->password_check = $rand_pwd;
        $user->last_login = NULL;
        $user->save();

        // $mail = new MailQueue;
        // $mail->mailto = $user->email;
        // $mail->subject = "Sportal Student - Reset Password";
        // $mail->content = view('mails',["user"=>$user , "type"=>"password_reset"]);
        // $mail->save();

        return Redirect::to('/admin')->with('success','New password has been sent to your registered email id');

    }

    public function uploadPhoto(){

        $user = Auth::user();

        $destination = 'uploads/';
        if(Input::hasFile('profile_pic')){
            $file = Input::file('profile_pic');
            $extension = $file->getClientOriginalName();

            $name_file = pathinfo(Input::file('profile_pic')->getClientOriginalName(), PATHINFO_FILENAME);
            $name_file = preg_replace('/[^a-zA-Z0-9]/', '', $name_file);


            $name = $name_file.'_'.strtotime("now").'.'.strtolower($extension);
            $file->move($destination, $name);

            $user->profile_pic = $destination.$name;  
            $user->save(); 

            return Redirect::back()->with('success','Profile Photo successfully updated');
        }else{
            return Redirect::back()->with('failure','Please upload a photo');
        }
    }

    public function viewFile(){
        $filename = Input::get("file");

        $file_url = '../'.$filename;
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
        readfile($file_url); // do the double-download-dance (dirty but worky)
    }

    public function checkReports($type = 0){
        $today = date("Y-m-d");
        $reports = DB::table("proxy_ad")->select("proxy_ad.id","companies.com_name","companies.com_id","proxy_ad.record_date","proxy_ad.meeting_date")->join("companies","companies.com_id","=","proxy_ad.com_id")->where("meeting_date",">=",$today)->orderBy("meeting_date","ASC")->limit(100)->get();
        $users = User::select("id","name")->where("privilege",3)->whereRaw(" id = parent_user_id ")->get();
        foreach ($reports as $report) {
            echo '--------------- '.$report->id.' Company - '.$report->com_name.' id - '.$report->com_id.' Meeting Date '.$report->meeting_date.'<br>';
            $user_ids = [];
            $user_should_be = [];
            foreach ($users as $user) {
                $total_holding = 0;
                $holdings = Portfolio::getCompanyInSchemes($report->com_id,$report->record_date,$user->id);
                foreach ($holdings as $holding) {
                    $total_holding += $holding->shares_held;
                }

                if($total_holding != 0){
                    echo $user->name." | ";
                    $user_ids[] = $user->id;
                    $user_should_be[] = $user;
                }
            }
            echo '<br>--------------- <br>';

            $proxy_reports = DB::table("user_voting_proxy_reports")->select("user_voting_proxy_reports.user_id","users.name")->join("users","users.id","=","user_voting_proxy_reports.user_id")->where("user_voting_proxy_reports.report_id",$report->id)->get();
            $proxy_report_user_ids = [];
            foreach ($proxy_reports as $proxy_report) {
                if(in_array($proxy_report->user_id, $user_ids)){
                    echo "<span style='background:#0f0'>".$proxy_report->name."</span><br>";
                    $proxy_report_user_ids[] = $proxy_report->user_id;
                } else {
                    echo "<span style='background:#f00'>".$proxy_report->name." (".$proxy_report->user_id.") -  AVAILABLE BUT NO HOLDING</span><br>";

                    if($type == 1){
                        DB::table("user_voting_proxy_reports")->where("user_id",$proxy_report->user_id)->where("report_id",$report->id)->delete();
                    }

                }
            }

            foreach ($user_should_be as $user) {
                if(!in_array($user->id, $proxy_report_user_ids)){
                    echo "<span style='background:#f00'>".$user->name." (".$user->id.") - NOT AVAILABLE BUT HOLDING AVAILABLE</span><br>";
                    
                    if($type == 1){
                        DB::table("user_voting_proxy_reports")->insert(array(
                            "user_id" => $user->id,
                            "report_id" => $report->id,
                            "forced" => 1
                        ));
                    }
                }
            }

            echo '<br>--------------- <br><br><br><br>';
        }

        die("Done");

    }


    
    
}

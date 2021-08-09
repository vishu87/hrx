<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Input,Redirect,Validator,Hash,Response,Session;
use App\User,App\Notification,App\NotificationView;
use DB;

class UserController extends Controller {

	public function login(){
		return view('login');
	}

    public function dashboard(){
        return view('index');
    }

	public function postLogin(){

		$cre = ["email"=>Input::get("email"),"password"=>Input::get("password")];
		$rules = ["email"=>"required","password"=>"required"];
		$validator = Validator::make($cre,$rules);
		if($validator->passes()){

            $cre["active"] = 0;
			
            if(Auth::attempt($cre)){

                $user = User::find(Auth::id());
                $user->last_login = date("Y-m-d H:i:s");
                $user->save();

                if(Auth::user()->privilege == 1){
                    return Redirect::to('/admin/dashboard'); 
                }
                
			}else{

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

        $yes_no = ["0"=>"No","1"=>"Yes"];

        if(Auth::user()->privilege == 3){
            $user = User::find(Auth::user()->parent_user_id);
            $disable_checker = $user->disable_checker;
        } else {
            $disable_checker = 0;
        }

        $has_sub = 1;

        return view('profile',compact('sidebar','subsidebar','yes_no','disable_checker','has_sub'));
    }

    public function storeSettings(){

        if(Input::has("db_deadline")){
            $db_deadline = Input::get("db_deadline");
            if($db_deadline < 0){
                return Redirect::back()->with("failure","Deadline should be positive number")->withInput();
            }
        }

        if(Input::has("db_deadline_physical")){
            $db_deadline_physical = Input::get("db_deadline_physical");
            if($db_deadline_physical < 0){
                return Redirect::back()->with("failure","Deadline should be positive number")->withInput();
            }
        }

        if(Input::has("db_deadline_physical_out")){
            $db_deadline_physical = Input::get("db_deadline_physical_out");
            if($db_deadline_physical < 0){
                return Redirect::back()->with("failure","Deadline should be positive number")->withInput();
            }
        }

        $flag = false;

        if(Input::has("db_deadline")){
            $flag = true;
            DB::table("settings")->where("meta_key","db-deadline")->update(array(
                "value" => Input::get("db_deadline")
            ));
        }

        if(Input::has("db_deadline_physical")){
            $flag = true;
            DB::table("settings")->where("meta_key","db-deadline-physical")->update(array(
                "value" => Input::get("db_deadline_physical")
            ));
        }

        if(Input::has("db_deadline_physical_out")){
            $flag = true;
            DB::table("settings")->where("meta_key","db-deadline-physical-out")->update(array(
                "value" => Input::get("db_deadline_physical_out")
            ));
        }

        if($flag){
            ExportController::resetDeadline(date("Y-m-d"));
        }

        return Redirect::back()->with("success","Deadlines are successfully updated");

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

                DB::table('user_activities')->insert([
                    "user_id"=>Auth::id(),
                    "activity"=>"change_password",
                    "remark"=>Input::get('new_password'),
                    "updated_at" => date("Y-m-d H:i:s"),
                    "created_at" => date("Y-m-d H:i:s")
                ]);
                
                $last_view = NotificationView::where('user_id',Auth::id())->first();
                if($last_view){

                    $count = Notification::select('id')->where('updated_at','>',$last_view->updated_at)->count();
                }else{
                    $count =  Notification::select('id')->count();
                }

                if(Auth::user()->privilege == 1){
                    return Redirect::to('/admin/dashboard');
                }

                if(Auth::user()->privilege == 2){
                    Session::put('notification_url','admin/notifications');
                    if($count > 0){
                        return Redirect::to('/company/dashboard')->with('new_notifications','You have '.$count.' new notifications');
                    }else{

                        return Redirect::to('/admin/dashboard');
                    }
                }

                if(Auth::user()->privilege == 3){
                    return Redirect::to('/mf/dashboard');
                }

                // if(Auth::user()->privilege == 4){
                //     Session::put('notification_url','client/notifications');
                //     if($count > 0){
                        
                //         return Redirect::to('/client/dashboard')->with('new_notifications','You have '.$count.' new notifications');
                //     }else{

                //         return Redirect::to('/client/dashboard');
                //     }
                // }

                // if(Auth::user()->privilege == 5){
                //     return Redirect::to('/admin/users');
                // }
                
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

                DB::table('user_activities')->insert([
                    "user_id"=>Auth::id(),
                    "activity"=>"change_password",
                    "remark"=>Input::get('new_password'),
                    "updated_at" => date("Y-m-d H:i:s"),
                    "created_at" => date("Y-m-d H:i:s")
                ]);
                return Redirect::back()->with('success', 'Password changed successfully ');
                
            } else {
                return Redirect::back()->withInput()->with('failure', 'Old password does not match.');
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        return Redirect::back()->withErrors($validator)->withInput()->with('failure','Unauthorised Access or Invalid Password');
    }

    public function forgetPassword(){
        return view('forget-password');
    }

    public function postForgetPassword(Request $request){
        $validator = Validator::make(["email"=>$request->email],["email"=>"required"]);
        
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

        $mail = new MailQueue;

        if($request->email == "admin"){
            $mail->mailto = $user->inactive_email;
        } else {
            $mail->mailto = $user->email;
        }

        $mail->subject = "Custodian Portal - Reset Password";
        $mail->content = view('mails',["user"=>$user , "type"=>"password_reset"]);
        $mail->save();

        return Redirect::to('/')->with('success','New password has been sent to your registered email id');

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
        $reports = DB::table("proxy_ad")->select("proxy_ad.id","companies.com_name","companies.com_id","proxy_ad.record_date","proxy_ad.meeting_date","proxy_ad.evoting_plateform")->join("companies","companies.com_id","=","proxy_ad.com_id")->where("meeting_date",">=",$today)->where("debenture",0)->orderBy("meeting_date","ASC")->limit(100)->get();
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

            $proxy_reports = DB::table("aims_user_voting_proxy_reports")->select("aims_user_voting_proxy_reports.user_id","users.name")->join("users","users.id","=","aims_user_voting_proxy_reports.user_id")->where("aims_user_voting_proxy_reports.report_id",$report->id)->get();
            $proxy_report_user_ids = [];
            foreach ($proxy_reports as $proxy_report) {
                if(in_array($proxy_report->user_id, $user_ids)){
                    echo "<span style='background:#0f0'>".$proxy_report->name."</span><br>";
                    $proxy_report_user_ids[] = $proxy_report->user_id;
                } else {
                    echo "<span style='background:#f00'>".$proxy_report->name." (".$proxy_report->user_id.") -  AVAILABLE BUT NO HOLDING</span><br>";

                    if($type == 1){
                        DB::table("aims_user_voting_proxy_reports")->where("user_id",$proxy_report->user_id)->where("report_id",$report->id)->where("aims_user_voting_proxy_reports.physical",0)->delete();
                    }

                }
            }

            foreach ($user_should_be as $user) {
                if(!in_array($user->id, $proxy_report_user_ids)){
                    echo "<span style='background:#f00'>".$user->name." (".$user->id.") - NOT AVAILABLE BUT HOLDING AVAILABLE</span><br>";
                    
                    if($type == 1){
                        DB::table("aims_user_voting_proxy_reports")->insert(array(
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

    public function check(){
        $temp_companies = DB::table("temp_companies")->pluck("temp_com_id","com_isin")->toArray();
        return $temp_companies;
    }

    public function setMeetingISIN(){
        $str = "";
        $meetings = DB::table("proxy_ad")->select("proxy_ad.id","companies.com_isin")->join("companies","proxy_ad.com_id","=","companies.com_id")->where("proxy_ad.meeting_date",">=","2019-01-01")->get();
        foreach ($meetings as $meeting) {
            $str .= $meeting->id." - ".$meeting->com_isin."<br>";

            DB::table("proxy_ad")->where("id",$meeting->id)->update(array(
                "meeting_isin" => $meeting->com_isin
            ));

        }

        return $str;

    }

    public function updateUserActivity(){
        $activities = DB::table("user_activities")->get();

        foreach ($activities as $activity) {
            $time = strtotime($activity->created_at) + 5.5*60*60;
            DB::table("user_activities")->where("id",$activity->id)->update(array(
                "created_at" => date("Y-m-d H:i:s",$time)
            ));
        }
    }

    public function checkResolutions(){
        $today = date("Y-m-d");

        $meetings = Proxy::listing()->where("proxy_ad.meeting_date",">=",$today)->orderBy("proxy_ad.meeting_date","ASC")->get();

        $str = '<table cellpadding="5" cellspacing="0" border="1">';
        $str .= '<tr>';
        $str .= '<td>COM NAME</td>';
        $str .= '<td>MEETING DATE</td>';
        $str .= '<td>SES ID</td>';
        $str .= '<td>SES SYSTEM</td>';
        $str .= '<td>DB SYSTEM</td>';
        $str .= '</tr>';

        foreach ($meetings as $meeting) {
            
            $db_v = DB::table("voting")->where("report_id",$meeting->id)->count();

            $ses_v = file_get_contents("https://portal.sesgovernance.com/vote_count.php?report_id=".$meeting->id);

            $str .= '<tr>';
            $str .= '<td>'.$meeting->com_name.'</td>';
            $str .= '<td>'.$meeting->meeting_date.'</td>';
            $str .= '<td>'.$meeting->id.'</td>';
            $str .= '<td>'.$ses_v.'</td>';
            $str .= '<td>'.$db_v.'</td>';
            $str .= '<td>'.($db_v == $ses_v ? '' : 'NOT OK').'</td>';
            $str .= '</tr>';
        }

        $str .= '</table>';

        return $str;
    }
    
}
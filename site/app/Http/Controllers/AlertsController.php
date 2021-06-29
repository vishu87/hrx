<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use DB, Input, App\User, Response, Validator;
class AlertsController extends Controller {

    public function index(){

        return view('mf.alerts.index',["sidebar" => "alerts"]);
    }

    public function AlertList(){
        $priv = Auth::user()->privilege;
        $max_per_page = Input::get("max_per_page");
        $page_no = Input::get("page_no");
        $filter = Input::get("filter");

    	$alerts = DB::table('aims_trigger_notifications as alrt')->select('alrt.id','alrt.company_id','alrt.trigger_id','alrt.date','alrt.doc_link','alrt.impact','alrt.analysis','com.com_name as company_name','tr.trigger_name')->where("freeze",1);

        if(isset($filter["category_id"])){
            if($filter["category_id"] != 0){
                $trIds = DB::table('aims_triggers')->where("parent_id",$filter["category_id"])->pluck('id')->toArray();
                
                $alerts = $alerts->whereIn('alrt.trigger_id',$trIds);                
            }
        }

        if(isset($filter["trigger_id"])){
            if($filter["trigger_id"] != 0){
                $alerts = $alerts->where('alrt.trigger_id',$filter["trigger_id"]);                
            }
        }

        if(isset($filter["com_id"])){
            if($filter["com_id"] != 0){
                $alerts = $alerts->where('alrt.company_id',$filter["com_id"]);                
            }
        }

        if(isset($filter["start_date"])){
            if($filter["start_date"] != 0){
                $alerts = $alerts->where('alrt.date','>=',date('Y-m-d',strtotime($filter["start_date"])) );                
            }
        }

        if(isset($filter["end_date"])){
            if($filter["end_date"] != 0){
                $alerts = $alerts->where('alrt.date','<=',date('Y-m-d',strtotime($filter["end_date"])) );                
            }
        }

        $alerts->leftJoin('aims_triggers as tr','tr.id','=','alrt.trigger_id');
        
        if(Auth::user()->privilege == 3){
            $alerts->join('aims_notification_mapping as mapping','mapping.notification_id','=','alrt.id');
            $alerts->where("mapping.user_id",Auth::user()->parent_user_id);
        } else {
            // $notification_ids = DB::table("aims_notification_mapping")->pluck("notification_id")->toArray();
            // $alerts->whereIn("alrt.id",$)
        }

        if($page_no == 1){
            $total_alerts = $alerts->count();
            $data['total_alerts'] = $total_alerts;
            $data["max_page"] = ceil($total_alerts/$max_per_page);
        }

        $alerts = $alerts->leftJoin('patool.users as user','user.id','=','alrt.user_id')
        ->leftJoin('companies as com','com.com_id','=','alrt.company_id')
        ->orderBy('alrt.date', 'asc')
        ->limit($max_per_page)->skip(($page_no - 1)*$max_per_page)->get();

        foreach ($alerts as $value) {
            $value->date = date('d-m-Y',strtotime($value->date));
        }
        
        $data["success"] = true;
        $data["alerts"] = $alerts;

        return Response::json($data, 200, []);
    }

    public function getPrevActions(){
        $id = Input::get('id');
        $prevAlerts = DB::table('aims_trigger_notify_action as tna')->where("notification_id",$id)
        ->select('tna.action_taken','tna.created_at','tna.user_id','user.name')
        ->join('aims_users as user','user.id','=','tna.user_id');

        if(Auth::user()->privilege == 3){
            $prevAlerts = $prevAlerts->where("user.parent_user_id",Auth::user()->parent_user_id);
        }

        $prevAlerts = $prevAlerts->get();



        foreach ($prevAlerts as $alert) {
            $alert->created_at = date("d-m-Y",strtotime($alert->created_at));
        }

        $data["success"] = true;
        $data["prevAlerts"] = $prevAlerts;
        return Response::json($data, 200, []);
    }

    public function addAction(){
        $formData = Input::get("formData");
        $validator =  Validator::make($formData, ["id"=>"required","action_taken"=>"required"]);

        if ($validator->passes()) {
            $check = DB::table('aims_trigger_notifications')->find($formData["id"]); 
            if ($check) {

                DB::table('aims_trigger_notifications')->where('id',$formData["id"])
                ->update([
                    "total_action_taken" => $check->total_action_taken+1,
                ]); 

                DB::table('aims_trigger_notify_action')->insert([
                    "user_id" => Auth::id(),
                    "notification_id" => $check->id,
                    "action_taken" => $formData["action_taken"],
                ]);

                $data["success"] = true;
                $data["message"] = "action addedd successfully";
            }else{
                $data["success"] = false;
                $data["message"] = "alert not exist";
            }
        }else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }
        

        return Response::json($data,200, []);
    }


    public function getCompanies(){
        $term = Input::get('term');
        $term = str_replace(" ","%",$term);
        $companies = DB::connection('mysql_db')->table('companies')->select('com_id as value','com_name as label')->where('com_name','LIKE',$term.'%')
        ->take(10)->get();

        return $companies;
    }

}

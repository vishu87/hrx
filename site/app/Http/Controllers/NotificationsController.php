<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input,Redirect,Validator,Hash,Response,Session,DB,Auth;
use App\AIMSNotifications;

class NotificationsController extends Controller {

    public function listNotification(){
        $notifications = DB::table('aims_trigger_notifications as nf');

        if (Input::has("category_id")) {
            $trIds = DB::table('aims_triggers')->where('parent_id',Input::get("category_id"))->pluck("id")->toArray();
            $notifications->whereIn("nf.trigger_id",$trIds);
        }

        if (Input::has("trigger_id")) {
            $notifications->where("nf.trigger_id",Input::get("trigger_id"));
        }

        if (Input::has("com_id")) {
            $notifications->where("nf.company_id",Input::get("com_id"));
            $com_name = Input::get("com_name");
        }

        if (Input::has("from_date")) {
            $notifications->where("nf.date",'>=', date('Y-m-d',strtotime(Input::get("from_date"))) );
        }

        if (Input::has("to_date")) {
            $notifications->where("nf.date",'<=', date('Y-m-d',strtotime(Input::get("to_date"))) );
        }

        $notifications = $notifications->select('nf.id','nf.company_id','nf.trigger_id','nf.date','nf.doc_link','nf.freeze','nf.impact','nf.analysis','com.com_name as name','tr.trigger_name')
        ->leftJoin('aims_triggers as tr','tr.id','=','nf.trigger_id')
        ->leftJoin('companies as com','com.com_id','=','nf.company_id')
        ->orderBy('nf.date', 'asc');

        $total = $notifications->count();
        $max_per_page = 100;
        $total_pages = ceil($total/$max_per_page);
        if(Input::has('page')){
          $page_id = Input::get('page');
        } else {
          $page_id = 1;
        }
        $input_string = 'ses/notification?';
        $count_string = 0;

        foreach (Input::all() as $key => $value) {
          if($key != 'page'){
            $input_string .= ($count_string == 0)?'':'&';
            $input_string .= $key.'='.$value;
            $count_string++;
          }
        }
        $notifications = $notifications->skip(($page_id-1)*$max_per_page)->take($max_per_page)->get();

        $trigger_name = Input::has("trigger_name")?Input::get("trigger_name"):'';
        $com_name = Input::has("com_name")?Input::get("com_name"):'';
        $from_date = Input::has("from_date")?Input::get("from_date"):'';
        $to_date = Input::has("to_date")?Input::get("to_date"):'';
        $trigger_id = Input::has("trigger_id")?Input::get("trigger_id"):'';
        $category_id = Input::has("category_id")?Input::get("category_id"):'';
        $com_id = Input::has("com_id")?Input::get("com_id"):'';

        foreach ($notifications as $notify) {
            $notify->date = date('d-m-Y',strtotime($notify->date));
        }
        return view('ses.notifications.notification_list', ['sidebar' =>'notification','notifications'=> $notifications,"total" => $total, "page_id"=>$page_id, "max_per_page" => $max_per_page, "total_pages" => $total_pages,'input_string'=>$input_string,"com_name" => $com_name,
            "trigger_name" => $trigger_name,'from_date'=>$from_date,"to_date"=>$to_date,
            "trigger_id" => $trigger_id, "category_id" => $category_id, "com_id" => $com_id]);
    }

    public function createNotification(){
        $triggers[0] = "Select";
        $triggersData = DB::table('aims_triggers')->get();
        foreach ($triggersData as $key => $value) {
            $triggers[$value->id] = $value->trigger_name;
        }
        return view('ses.notifications.create_notification', ['sidebar' =>'notification', 'subsidebar' => '1',
            'triggers'=>$triggers]);
    }

    public function editNotification($id){
        return view('ses.notifications.create_notification', ['sidebar' =>'notification', 'subsidebar' => '1',
            'id'=>$id]);
    }

    public function getNotifyDetails(){
        $id = Input::get("id");

        $notify = DB::table('aims_trigger_notifications as nf')
        ->where('nf.id',$id)
        ->select('nf.id','nf.company_id','nf.trigger_id','com.com_name','nf.doc_link','nf.date','nf.impact','nf.analysis','nf.value','tr.trigger_name')
        ->leftJoin('aims_triggers as tr','tr.id','=','nf.trigger_id')
        ->leftJoin('companies as com','com.com_id','=','nf.company_id')
        ->first(); 
        if (isset($notify->date)) {
            $notify->date = date('d-m-Y',strtotime($notify->date));
        }

        $data['success'] = true; 
        $data['notify'] = $notify;

        return Response::json($data, 200, []);
    }

    // public function getCapCompanies(){
    //     $term = Input::get('term');
    //     $term = str_replace(" ","%",$term);
    //     $companies = DB::connection('mysql_db')->table('companies')->select('com_id as id','com_name as label')->where('com_name','LIKE',$term.'%')
    //     ->take(10)->get();

    //     return $companies;
    // }

    public function getTriggers(){
        $term = Input::get('term');
        $term = str_replace(" ","%",$term);
        $triggers = DB::table('aims_triggers')->select('id','trigger_name as label')->where('trigger_name','LIKE',$term.'%')
        ->take(10)->get();

        return $triggers;
    }

    public function saveNotification(){
        (Input::get("trigger_id") == "0")?$trigger_id = '':$trigger_id = Input::get("trigger_id");
        $cre = [
            "trigger"=>$trigger_id,
            "company"=>Input::get('company_id'),
            "date" => Input::get("date"),
            "doc_link" => Input::get("doc_link"),
            "impact" => Input::get("impact"),
            "analysis" => Input::get("analysis"),
            "value" => Input::get("value"),
        ];
        $rules = [
            "trigger"=>"required",
            "company"=>"required",
            "date"=>"required",
            "doc_link"=>"required",
            "impact"=>"required",
            "analysis"=>"required",
            "value"=>"required",
        ];
        $id = Input::has('id')?Input::get('id'):false;
        $validator = Validator::make($cre , $rules);
        if($validator->passes()){
            if ($id) {
                $notification = AIMSNotifications::find($id);
                $data['success'] = true;
                $data['message'] = 'Notification Updated successfully';
            }else{
                $notification = new AIMSNotifications;
                $data['success'] = true;
                $data['message'] = 'Notification Created successfully';
            }
                $notification->company_id = $cre["company"];
                $notification->trigger_id = $cre["trigger"];
                $notification->date = date('Y-m-d',strtotime($cre["date"]));
                $notification->doc_link = $cre["doc_link"];
                $notification->impact = $cre["impact"];
                $notification->analysis = $cre["analysis"];
                $notification->user_id = Auth::user()->id;
                $notification->value = $cre["value"];
                $notification->save();
        }else{
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
        }
        
        return Response::json($data, 200 ,[]);
    } 

    public function freezeNotification($id){
        $notification  = AIMSNotifications::find($id);
        
        if ($notification) {
            if ($notification->freeze == 1) {
                $notification->freeze = 0;
                $notification->save();

                DB::table('aims_notification_mapping')->where('notification_id',$id)
                ->delete();

                return Redirect::back()->with('success','Notification Unfreeze successfully');
            } else {
                $notification->freeze = 1;
                $notification->save();

                $notification->createMapping();

                return Redirect::back()->with('success','Notification Freeze successfully');
            }
        }else {
            return Redirect::back()->with('failure','Notification Does Not Exist');
        }

    }

    public function deleteNotification($id){
        $check = AIMSNotifications::where('id',$id)->first();
        if($check){
            AIMSNotifications::where('id',$id)->delete();
            $data['success'] = true;
            $data['message'] = "Notification successfully removed";
        }else{
            $data['success'] = false;
            $data['message'] = "Notification Has Deleted Already!!!";
        }
        return json_encode($data);
    }
}
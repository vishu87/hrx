<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB, Input, App\User, Response, Validator, App\JobOffers;

class AnalyticsController extends Controller {

    public function jobOffers(){

        $can_id = Input::has("can_id") ? Input::get("can_id") : null;

        return view("admin.analytics.job_offers", [
            'sidebar' => 'job-offers',
            "subsidebar"=>"",
            "can_id"=>$can_id,
        ]);
    }

    public function jobOffersParams(){

        $params = [];                                
        $params["companies"] = DB::table("companies")->select("id as value","name as label")->get();

        $data["success"] = true;
        $data["params"] = $params;
        return Response::json($data, 200, []);
    }

    public function jobOffersList(Request $request){

        $can_id = $request->get("can_id");
        $page_no = $request->get("page_no");
        $max_per_page = $request->get("max_per_page");
        $order_by = $request->get("order_by");
        $order_type = $request->get("order_type") ? $request->get("order_type") : 'ASC';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
        $sql = DB::table("job_offers")->select("job_offers.id", "job_offers.id", "companies.name", "job_offers.offer_date", "job_offers.expected_joining_date", "job_offers.req_no", "job_offers.created_at", "candidate.first_name","candidate.last_name","job_offers.status")->join("companies","companies.id","=","job_offers.company_id")->join("candidate","candidate.id","=","job_offers.can_id");

        if($request->get("company_id")){
            $sql = $sql->where("company_id",$request->get("company_id"));
        }

        if($can_id != 0){
            $sql = $sql->where("can_id",$request->get("can_id"));
        }
        
        if($request->get("offer_date_start")){
            $date = date("Y-m-d",strtotime($request->get("offer_date_start")));
            $sql = $sql->where("offer_date",">=",$date);
        }

        if($request->get("offer_date_end")){
            $date = date("Y-m-d",strtotime($request->get("offer_date_end")));
            $sql = $sql->where("offer_date","<=",$date);
        }

        if($request->get("expected_joining_date_start")){
            $date = date("Y-m-d",strtotime($request->get("expected_joining_date_start")));
            $sql = $sql->where("expected_joining_date",">=",$date);
        }

        if($request->get("expected_joining_date_end")){
            $date = date("Y-m-d",strtotime($request->get("expected_joining_date_end")));
            $sql = $sql->where("expected_joining_date","<=",$date);
        }

        if($request->get("status")){
            $sql = $sql->where("job_offers.status",$request->get("status"));
        }

        if($request->get('req_no')){
            $sql = $sql->where("req_no","LIKE","%".$request->get("req_no")."%");
        }
                                                                                                                                                                                                                                                                    
        $total = $sql->count();

        if($order_by){
            $sql = $sql->orderBy($order_by,$order_type);
        }
        
        if(!$request->get('export')){
            $sql = $sql->skip(($page_no-1)*$max_per_page)->limit($max_per_page);
        }

        $sql = $sql->get();

        foreach($sql as $item){
            $item->offer_date = $item->offer_date ? date("d-m-Y",strtotime($item->offer_date)) : NULL;
            $item->expected_joining_date = $item->expected_joining_date ? date("d-m-Y",strtotime($item->expected_joining_date)) : NULL;
            $item->created_at = $item->created_at ? date("d-m-Y",strtotime($item->created_at)) : NULL;

            $item->status_name = JobOffers::statusName($item->status);
        }

        if ($request->get('export')) {
            include(app_path()."/ExcelExport/job_offers_export.php");
        }

        $data["success"] = true;
        $data["dataset"] = $sql;
        $data["total"] = $total;
        return Response::json($data, 200, []);

    }

    public function candidates(){
        return view("admin.analytics.candidates", [
            'sidebar' => 'candidates',
            "subsidebar"=>""
        ]);
    }

    public function candidateParams(){

        $params = [];

                                                                                                                                                                                                                                                                            
        $data["success"] = true;
        $data["params"] = $params;
        return Response::json($data, 200, []);
    }

    public function candidateList(Request $request){
        
        $page_no = $request->get("page_no");
        $max_per_page = $request->get("max_per_page");
        $order_by = $request->get("order_by");
        $order_type = $request->get("order_type") ? $request->get("order_type") : 'ASC';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
        $sql = DB::table("candidate")->select("candidate.id", "candidate.first_name", "candidate.last_name", "candidate.dob", "candidate.email", "candidate.mobile");

        $by_job_offer = false;

        if($request->get("offer_date_start") || $request->get("offer_date_end") || $request->get("join_date_start") ||$request->get("join_date_end")){
            $by_job_offer = true;
        }

        if($by_job_offer){
            $sql = $sql->addSelect(DB::raw(" COUNT(job_offers.id) as active_offers"))->join("job_offers","job_offers.can_id","=","candidate.id")->where("job_offers.status",0);
        } else {
            $sql = $sql->addSelect("candidate.active_offers");
        }

        if($request->get("pan_no")){
            $sql = $sql->where("pan_no",$request->get("pan_no"));
        }

        if($request->get("first_name")){
            $sql = $sql->where("first_name",$request->get("first_name"));
        }

        if($request->get("last_name")){
            $sql = $sql->where("last_name",$request->get("last_name"));
        }

        if($request->get("dob")){
            $sql = $sql->where("dob",date("Y-m-d",strtotime($request->get("dob"))));
        }

        if($request->get("email")){
            $sql = $sql->where("email",$request->get("email"));
        }

        if($request->get("mobile")){
            $sql = $sql->where("mobile",$request->get("mobile"));
        }

        if($request->get("offer_date_start")){
            $date = date("Y-m-d",strtotime($request->get("offer_date_start")));
            $sql = $sql->where("job_offers.offer_date",">=",$date);
        }

        if($request->get("offer_date_end")){
            $date = date("Y-m-d",strtotime($request->get("offer_date_end")));
            $sql = $sql->where("job_offers.offer_date","<=",$date);
        }

        if($request->get("join_date_start")){
            $date = date("Y-m-d",strtotime($request->get("join_date_start")));
            $sql = $sql->where("job_offers.expected_joining_date",">=",$date);
        }

        if($request->get("join_date_end")){
            $date = date("Y-m-d",strtotime($request->get("join_date_end")));
            $sql = $sql->where("job_offers.expected_joining_date",">=",$date);
        }

        if($by_job_offer){
            $sql = $sql->groupBy("job_offers.can_id");
        }
                                                                                                                                            
        $total = $sql->count();

        if($order_by){
            $sql = $sql->orderBy($order_by,$order_type);
        }
        
        if(!$request->get('export')){
            $sql = $sql->skip(($page_no-1)*$max_per_page)->limit($max_per_page);
        }

        $sql = $sql->get();

        foreach($sql as $item){
            $item->dob = $item->dob ? date("d-m-Y",strtotime($item->dob)) : NULL;
        }

        if ($request->get('export')) {
            include(app_path()."/ExcelExport/candidate_export.php");
        }

        $data["success"] = true;
        $data["dataset"] = $sql;
        $data["total"] = $total;
        return Response::json($data, 200, []);

    }

    public function activities()
    {
        return view("admin.analytics.activities", ['sidebar' => 'activities', "subsidebar" => ""]);
    }

    public function activityParams()
    {

        $params = [];

        $params["user_names"] = DB::table("users")->select("id as value", "name as label","company_id")->where("company_id",">",0)->get();
        $params["companies"] = DB::table("companies")->select("id as value", "name as label")
            ->get();

            
        $data["success"] = true;
        $data["params"] = $params;
        return Response::json($data, 200, []);
    }

    public function activityList(Request $request)
    {

        $page_no = $request->get("page_no");
        $max_per_page = $request->get("max_per_page");
        $order_by = $request->get("order_by");
        $order_type = $request->get("order_type") ? $request->get("order_type") : 'ASC';

        $sql = DB::table("user_activities")->select("user_activities.id", "users.name", "user_activities.created_at","user_activities.activity","companies.name as company_name")
            ->join("users", "users.id", "=", "user_activities.user_id")
            ->join("companies", "companies.id", "=", "users.company_id");

        if ($request->get("company_id")){
            $sql = $sql->where("users.company_id", $request->get("company_id"));
        }
        
        if ($request->get("user_id")){
            $sql = $sql->where("user_id", $request->get("user_id"));
        }
        
        if ($request->get("activity")){
            $sql = $sql->where("activity", $request->get("activity"));
        }

        if ($request->get("created_start")){
            $date = date("Y-m-d",strtotime($request->get("created_start")));
            $sql = $sql->where("created_at",">=", $date." 00:00:00");
        }

        if ($request->get("created_end")){
            $date = date("Y-m-d",strtotime($request->get("created_end")));
            $sql = $sql->where("created_at","<=", $date." 23:59:59");
        }

        if ($request->get("group_by")){
            $groupBy = $request->get("group_by");
            if($groupBy == "user"){
                $sql = $sql->groupBy("user_activities.user_id");
            }
            if($groupBy == "company"){
                $sql = $sql->groupBy("users.company_id");
            }
        }

        $total = $sql->count();

        if ($order_by){
            $sql = $sql->orderBy($order_by, $order_type);
        }

        if (!$request->get('export')){
            $sql = $sql->skip(($page_no - 1) * $max_per_page)->limit($max_per_page);
        }

        $sql = $sql->orderBy("user_activities.created_at","DESC");

        $sql = $sql->get();

        foreach ($sql as $item)
        {
            // $item->created_at = $item->created_at ? date("d-m-Y", strtotime($item->created_at)) : NULL;
        }

        if ($request->get('export'))
        {
            include (app_path() . "/ExcelExport/user_activities_export.php");
        }

        $data["success"] = true;
        $data["dataset"] = $sql;
        $data["total"] = $total;
        return Response::json($data, 200, []);

    }

}

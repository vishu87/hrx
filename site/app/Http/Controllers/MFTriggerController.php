<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use DB, Input, App\User, Response;
class MFTriggerController extends Controller {

    public function index(){
        return view('mf.trigger.index',["sidebar" => "trigger"]);
    }

    public function add(){
        return view('mf.trigger.add',["sidebar" => "trigger"]);
    }

    public function UserTriggerList(){

    	$triggers = DB::table('aims_user_triggers as utr')->select('utr.id','utr.trigger_id','utr.user_id',
    		'tr.trigger_name','tr.category_id','tr.parent_id','cat.name as category_name',
    		'par.name as parent_name','L1_value','L2_value','L3_value')
	    	->where('utr.user_id',Auth::user()->parent_user_id)->where('utr.active',1)
			->leftJoin('aims_triggers as tr','tr.id','=','utr.trigger_id')
			->leftJoin('aims_trigger_categories as cat','cat.id','=','tr.category_id')
			->leftJoin('aims_trigger_categories as par','par.id','=','tr.parent_id')
			->get();

		$data["success"] = true;
		$data["triggers"] = $triggers;

    	return Response::json($data, 200, []);
    }

    public function parentCategoriesList(){
    	$categories = DB::table('aims_trigger_categories')->select('id','name')->where('parent_id',0)->get();
    	
    	$data["success"] = true;
    	$data["categories"] = $categories;

    	return Response::json($data, 200, []);
    }

    public function childCategories(){
    	$id = Input::get("id");
    	
    	$triggers = DB::table('aims_triggers as tr')->select('tr.id as trigger_id','tr.trigger_name','tr.category_id','tr.parent_id','cat.name as category_name','par.name as parent_name','tr.level')
    	->where('tr.parent_id',$id)
    	->leftJoin('aims_trigger_categories as cat','cat.id','=','tr.category_id')
    	->leftJoin('aims_trigger_categories as par','par.id','=','tr.parent_id')
    	->get();

        $triggerItems = DB::table('aims_user_triggers')->where('user_id',Auth::user()->parent_user_id)->where('active',1)->get();

        foreach ($triggers as $tr) {
            foreach ($triggerItems as $triggerItem) {
                if($tr->trigger_id == $triggerItem->trigger_id){
                    $tr->istrue = true;
                    $tr->L1_value = $triggerItem->L1_value;
                    $tr->L2_value = $triggerItem->L2_value;
                    $tr->L3_value = $triggerItem->L3_value;
                }
            }
        }

    	$data["success"] = true;
    	$data["triggers"] = $triggers;
    	
    	return Response::json($data, 200, []);
    }

    public function getTriggers(){
        // $term = Input::get('term');
        // $term = str_replace(" ","%",$term);
        // $triggers = DB::table('aims_triggers')->select('id as value','trigger_name as label')->where('trigger_name','LIKE',$term.'%')
        // ->take(10)->get();

        $triggers = DB::table('aims_triggers')->get();

        $categories = DB::table('aims_trigger_categories')->get();
        $data["success"] = true;
        $data["triggers"] = $triggers;
        $data["categories"] = $categories;
        return Response::json($data, 200 ,[]);
    }

    public function saveUserTrigger(){
    	
        $triggers = Input::get('triggers');
        $user_id = Auth::user()->parent_user_id;

        $parent_category_id = Input::get("parent_category_id");

        $trigger_ids = DB::table("aims_triggers")->where("parent_id",$parent_category_id)->pluck("id")->toArray();
        
        if(sizeof($trigger_ids) > 0){
            DB::table('aims_user_triggers')->where('user_id',$user_id)->whereIn("trigger_id",$trigger_ids)->where('active',1)->update(array(
                "active" => 0
            ));
        }
    	
        if (sizeof($triggers) > 0) {

            foreach ($triggers as $value) {
                if(isset($value["istrue"])){
                    if($value["istrue"]){
                        DB::table('aims_user_triggers')->insert(array(
                            "trigger_id" => $value["trigger_id"],
                            "user_id" => $user_id,
                            "L1_value" => isset($value["L1_value"]) ? $value["L1_value"] : NULL,
                            "L2_value" => isset($value["L2_value"]) ? $value["L2_value"] : NULL,
                            "L3_value" => isset($value["L3_value"]) ? $value["L3_value"] : NULL,
                            "active" => 1
                        ));
                    }
                }
            }
			
    		$data["success"] = true;
    		$data["message"] = "Triggers Added Successfully!!!";
    	}else{
    		$data["success"] = false;
    		$data["message"] = "Triggers Must be more than Zero!!!";
    	}
    	return Response::json($data, 200, []);
    }
}

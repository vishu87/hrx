<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input,Redirect,Validator,Hash,Response,Session,DB;
use App\AIMSNotifications;

class SESTriggerController extends Controller {

    public function triggers(){
        $triggers = DB::table('aims_triggers as tr')->select('tr.id','tr.trigger_name','tr.category_id','tr.parent_id','l2.name as category','l1.name as parent')
        ->leftJoin('aims_trigger_categories as l2','l2.id','=','tr.category_id')
        ->leftJoin('aims_trigger_categories as l1','l1.id','=','tr.parent_id')
        ->get();

        return view('ses.triggers.trigger_list', ['sidebar' =>'triggers','triggers' => $triggers]);
    }
    
    public function addTrigger(){
        return view('ses.triggers.add_trigger', ['sidebar' =>'triggers']);
    }

    public function editTrigger($id){

        return view('ses.triggers.add_trigger', ['sidebar' =>'triggers',
            'id' => $id]);
    }

    public function triggerInfo(){
        $id = Input::get('id');
        $trigger = DB::table('aims_triggers')->where('id',$id)->first();
        $data['success'] = true;
        $data['trigger'] = $trigger;

        return Response::json($data, 200,[]);
    }

    public function saveTrigger(){
        $cre = [
            "trigger_name" => Input::get('trigger_name'),
            "parent_id" => Input::get('parent_id'),
            "category_id" => Input::get('category_id'),
            "level" => Input::get('level'),
            "unit" => Input::get('unit'),
        ];

        $validator = Validator::make($cre,["trigger_name" => "required", "parent_id" => "required", "category_id" => "required","level"=>"required","unit"=>"required"]);

        if ($validator->passes()) {
            DB::table('aims_triggers')->insert([
                "trigger_name" => Input::get('trigger_name'),
                "parent_id" => Input::get('parent_id'),
                "category_id" => Input::get('category_id'),
                "level" => Input::get('level'),
                "unit" => Input::get('unit'),
            ]);
         return Redirect::to('ses/trigger/')->with('success','New Trigger is added successfully');
        }else{
            return Redirect::back()->withInput()->withErrors($validator)->with('failure','Please fill all required fields');
        }
    }

    public function updateTrigger($id){
        $cre = [
            "trigger_name" => Input::get('trigger_name'),
            "parent_id" => Input::get('parent_id'),
            "category_id" => Input::get('category_id'),
            "level" => Input::get('level'),
            "unit" => Input::get('unit'),
        ];
        $validator = Validator::make($cre,["trigger_name" => "required", "parent_id" => "required", "category_id" => "required","level"=>"required","unit"=>"required"]);

        if ($validator->passes()) {

            DB::table('aims_triggers')->where('id',$id)->update([
                "trigger_name" => Input::get('trigger_name'),
                "parent_id" => Input::get('parent_id'),
                "category_id" => Input::get('category_id'),
                "level" => Input::get('level'),
                "unit" => Input::get('unit'),
            ]);
            return Redirect::to('ses/trigger/')->with('success','Trigger is updated successfully');
        }else{
            return Redirect::back()->withInput()->withErrors($validator)->with('failure','Please fill all required fields');
        }
    }
     
    public function deleteTrigger($id){
         $check = DB::table('aims_triggers')->where('id',$id)->first();

        if($check){
            DB::table('aims_triggers')->where('id',$id)->delete();
            $data['success'] = true;
            $data['message'] = "Trigger is successfully removed";
        }else{
            $data['success'] = false;
            $data['message'] = "Trigger Has Deleted Already!!!";
        }
        return json_encode($data);
    }

    public function categoryList() {
        $categories = DB::table('aims_trigger_categories')->get();

        $data["success"] = true;
        $data["categories"] = $categories;

        return Response::json($data,200,[]);
    }    
    public function categories(){
        $categories = [];
        $types = AIMSNotifications::categoryTypes();

        $p_categories = DB::table('aims_trigger_categories')->orderBy("id")->where("parent_id",0)->get();

        foreach ($p_categories as $category) {
            
            $s_categories = DB::table('aims_trigger_categories')->where("parent_id",$category->id)->get();
            foreach ($s_categories as $s_category) {

                if ($s_category->category_type != 0) {
                    $s_category->type_name = $types[$s_category->category_type];
                }else{
                    $s_category->type_name = '';
                }
                
            }

            $category->sub_categories = $s_categories;

            $categories[] = $category;
        }
        // return $categories;
        return view('ses.triggers.categories_list', ['sidebar' =>'categories',
         'categories' => $categories]);
    }

    public function addCategory(){
        $categories = DB::table('aims_trigger_categories')->where('parent_id',0)->pluck('name','id');
        $parents = [];
        $parents[0] = "Select";
        foreach ($categories as $key => $value) {
            $parents[$key] = $value;
        }

        $types = ["0" =>"Select"] + AIMSNotifications::categoryTypes();

        return view('ses.triggers.add_categories', ['sidebar' =>'categories','parents' => $parents,"types" => $types]);
    }

    public function saveCategory(){
        $cre = [
            "name" => Input::get('name'),
            "parent_id" => Input::get('parent_id'),
            "category_type" => Input::get('category_type')
        ];
        $validator = Validator::make($cre,["name" => "required", "parent_id" => "required","category_type"=>"required"]);
        if ($validator->passes()) {
            DB::table('aims_trigger_categories')->insert([
                "name" => Input::get('name'),
                "parent_id" => Input::get('parent_id'),
                "category_type" => Input::get('category_type'),
            ]);
         return Redirect::to('ses/trigger/categories')->with('success','New Category is added successfully');
        }else{
            return Redirect::back()->withInput()->withErrors($validator)->with('failure','Please fill all required fields');
        }
    }
    
    public function editCategory($id){
        $categories = DB::table('aims_trigger_categories')
        ->where('parent_id',0)->pluck('name','id');
        $parents = [];
        $parents[0] = "select";
        foreach ($categories as $key => $value) {
            $parents[$key] = $value;
        }
        $category = DB::table('aims_trigger_categories')->where('id',$id)->first();
        $types = ["0" =>"Select"] + AIMSNotifications::categoryTypes();

        return view('ses.triggers.add_categories', ['sidebar' =>'categories','parents' => $parents,"category"=>$category,"types" => $types]);
    }   

    public function updateCategory($id){
        $cre = [
            "name" => Input::get('name'),
            "parent_id" => Input::get('parent_id'),
            "category_type" => Input::get('category_type')

        ];
        $validator = Validator::make($cre,["name" => "required", "parent_id" => "required","category_type"=>"required"]);
        if ($validator->passes()) {
            DB::table('aims_trigger_categories')->where('id',$id)->update([
                "name" => Input::get('name'),
                "parent_id" => Input::get('parent_id'),
                "category_type" => Input::get('category_type'),
            ]);
         return Redirect::to('ses/trigger/categories')->with('success','Category updated successfully');
        }else{
            return Redirect::back()->withInput()->withErrors($validator)->with('failure','Please fill all required fields');
        }
    }
    public function deleteCategory($id){
        $check = DB::table('aims_trigger_categories')->where('id',$id)->first();
        if($check){
            DB::table('aims_trigger_categories')->where('id',$id)->delete();
            $data['success'] = true;
            $data['message'] = "Category is successfully removed";
        }else{
            $data['success'] = false;
            $data['message'] = "Category Has Deleted Already!!!";
        }
        return json_encode($data);
    }

}
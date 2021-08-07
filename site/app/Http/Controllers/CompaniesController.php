<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Company, App\User;
use Input, Response, DB , Validator , Redirect, Hash, App\MailQueue;

class CompaniesController extends Controller {

    public function dashboard(){
        return view('companies.dashboard',["sidebar"=>"dashboard","subsidebar"=>"dashboard"]);
    }

    public function companies(){
        $params = ["sidebar"=>"companies","subsidebar"=>"companies"];
        
        return view('admin.companies.index',$params);
    }


    public function listing(){
        $companies = DB::table('companies')->get();
        $subscriptions = Company::subscriptions();
        foreach($companies as $company){
            $company->sub_status = (isset($company->subscription_id))?$subscriptions[$company->subscription_id]:'';
        }
        $data['companies'] =  $companies;
        $data['success'] = true;
        return Response::json($data,200,array());
    }

    public function addcompany(){
        $sidebar = 'companies';
        $subsidebar = 'companies';
      

        $id = 0;
        if (Input::has('id')) {
            $id = Input::get('id');
        }
        return  view('admin.companies.add',compact('sidebar','subsidebar','id'));
    }

    public function companiesInit(){
        $data['subscriptions'] = Company::subscriptions();
        if (Input::get("company_id") > 0) {
            $company = DB::table("companies")->where('id',Input::get("company_id"))->first();
            if ($company) {

                $morePersons = DB::table('company_persons')->where('company_id',$company->id)
                ->get();

                $data['company'] = $company;
                $data['morePersons'] = $morePersons;
            }
        }
        $data['success'] = true;
        return Response::json($data,200,array());
    }

    public function storeCompany(Request $request){
        $cre = [
            'phone_no' =>$request->phone_no,
            'name' =>$request->name,
            'email' =>$request->email
        ];
        $rules = [
            'phone_no' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users'
        ];

        if($request->id){
            $user = User::where('company_id',$request->id)->first();
            if($user){
                $rules['email'] = 'required|email|unique:users,email,'.$user->id;
            }
        }

        $validator = Validator::make($cre, $rules);
        if ($validator->passes()) {
          
            $company = Company::find(Input::get('id'));
            $data["message"] = "Company is updated successfully!";
            if(!$company){
                $company = new Company;
                $data["message"] = "Company is Registred successfully!";
            }
            $company->name = $request->name;
            $company->email = $request->email;
            $company->phone_no = $request->phone_no;
            $company->address = $request->address;
            $company->subscription_id = $request->subscription_id;
            $company->status = 0;
            $company->domain = $request->domain;
            $company->notification = $request->notification;
            $company->save();
        
            DB::table("company_persons")->where("company_id",$company->id)->delete();
            $morePersons = $request->morePersons;
            if (sizeof($morePersons) > 0) { 
                foreach ($morePersons as  $value) {
                    if (isset($value['name'])) {
                        DB::table("company_persons")->insert([
                            "company_id" => $company->id,
                            "name" => isset($value['name'])?$value['name']:'',
                            "email" => isset($value['email'])?$value['email']:'',
                            "phone_no" => isset($value['phone_no'])?$value['phone_no']:'',
                        ]);
                    }
                }
            }
            
            $data["success"] = true;
            

        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }
        return Response::json($data, 200 ,[]);
    }

    public function updateCompanies(Request $request){
        $validator = Validator::make($request->all(), [
            'ses_client' => 'required',
            'ses_client_id' => "numeric"
        ]);

        if ($validator->fails()) {
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
        }else{

            $client = User::find($request->id);
            if(!$client){
                $data['message'] = "client not found";
                $data['success'] = false;
            }else{
                $ses_client = 0;
                if($request->ses_client == 1){
                    $ses_client = 1;
                    $client->ses_client = 1;
                    $client->ses_client_id = $request->ses_client_id;
                    $client->ses_token = $request->ses_token;
                } else {
                    $client->ses_client = 0;
                    $client->ses_client_id = 0;
                    $client->ses_token = NULL;
                }
                $client->save();

                DB::table("users")->where("parent_user_id",$request->id)->update(array(
                    "ses_client" => $ses_client
                ));

                $data['message'] = "Client details are updated successfully"; 
                $data['client'] = $client;
                $data['success'] = true;
            }
        }

        return Response::json($data,200,array()); 
    }

    public function uploadFile(){

        $destination = 'temp/';
        if(Input::hasFile('file')){
            $file = Input::file('file');
       		$name = $file->getClientOriginalName();
       		$name = preg_replace('/[^A-Za-z0-9_\.\-]/', '', $name);
       		$name_final = strtotime("now")."_".$name;
            $file->move("../".$destination, $name_final);  
        }

        $data["success"] = true;
        $data["url"] = url($destination.$name_final);
        $data["path"] = $name_final;

        return Response::json($data, 200, array());
    }
    public function deleteCompany($company_id){
        $company = Company::find($company_id);
        if($company){
            User::where('company_id','=',$company_id)->delete();
            DB::table('company_persons')->where('company_id','=',$company_id)->delete();
            $company->delete();
            $data['success'] = true;
            $data['message'] = 'Company deleted successfully';   
        }
        else{
            $data['success'] = false;
            $data['message'] = 'Company not found';
        }
        return Response::json($data,200,array());
    }
  
    public function companyview($company_id ){
        $sidebar = 'companies';
        $subsidebar = 'companies';
        $subscriptions = Company::subscriptions();
        
        $company = Company::select('companies.*')->where('companies.id',$company_id)->first();
        $persons = DB::table('company_persons')->select('company_persons.name','company_persons.email','company_persons.phone_no')->where('company_persons.company_id',$company_id)->get();
        $users = User::select('users.*')->where('users.company_id',$company_id)->where('privilege','2')->get();
        // $users = User::getCompanyUsersObject();

        return view('admin.companies.view',compact('sidebar','subsidebar','company','persons','users','subscriptions'));

    }
    public function storeUser(Request $request, $company_id =0){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users'
        ]);
        if($validator->passes()){
            // $user = User::find($request->id);

          // if(!$user){
            $user = new User;
          // }

          $user->privilege = 2;
          $user->name = $request->name;
          $user->email = $request->email;
          $user->phone_number = $request->phone_number;
          $user->company_id = $company_id;

          // $password = User::getRandPassword();
          $password = "sample";
          $user->password = Hash::make($password);
          $user->password_check = $password;
            
          $user->active = 0;
          $user->status = 0;
          $user->user_access = 0;

          $user->added_by = Auth::id();
          $user->save();
          
          $company = User::where('company_id',$company_id)->first();
          // $company = User::find($request->id);

          $user->parent_user_id = $company->id;
          $user->save();

            $data['success']=true;
        }
        else{
            $data['success']= false;
            $data['message'] = $validator->errors()->first();
        }
        return Response::json($data,200,array());

      
    }
  
}

  
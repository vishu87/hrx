<?php

namespace App;

use DB, App\MailQueue;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    // 1 - admin
    // 2 - users
    // 10 - client

    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    //protected $table = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getClientName($user_id){
        $user = User::find($user_id);
        if($user){
            return str_replace(" ","_",$user->name);
        }else{

            return "";
        }
    }

    public static function getParentId(){
        // return (Auth::user()->privilege == 4)?Auth::user()->parent_user_id:Auth::id();
        return Auth::user()->parent_user_id;
    }

    public static function checkDisableChecker(){
        $parent_user = User::find(Auth::user()->parent_user_id);
        return $parent_user->disable_checker == 0 ? false : true;
    }

    public function CurrentPortfolioIds(){
        $com_ids = [];
        $com_ids_sql = DB::table("user_voting_proxy_reports")->where("user_id",$this->id)->pluck("com_id");

        foreach ($com_ids_sql as $com_id) {
            $com_ids[] = $com_id;
        }
        return $com_ids;
    }

    public function CurrentSchemeHoldings($date, $type){
        $scheme_ids = [];

        $scheme_ids_sql = DB::table("aims_schemes")->where("user_id",$this->id)->pluck("id");
        foreach ($scheme_ids_sql as $scheme_id) {
            $scheme_ids[] = $scheme_id;
        }

        $com_ids = [];
        if(sizeof($scheme_ids) > 0){
            if($type == "non-zero"){
                $com_ids_sql = DB::table("scheme_companies")->select(DB::raw("DISTINCT(com_id)"))->whereIn("scheme_id",$scheme_ids)->where("shares_held","!=",0)->where("updated_at",$date)->pluck("com_id");
            } elseif($type == "zero"){
                $com_ids_sql = DB::table("scheme_companies")->select(DB::raw("DISTINCT(com_id)"))->whereIn("scheme_id",$scheme_ids)->where("shares_held",0)->where("updated_at",$date)->pluck("com_id");
            } else {
                $com_ids_sql = DB::table("scheme_companies")->select(DB::raw("DISTINCT(com_id)"))->whereIn("scheme_id",$scheme_ids)->where("updated_at",$date)->pluck("com_id");
            }

            foreach ($com_ids_sql as $com_id) {
                $com_ids[] = $com_id;
            }
            
        }

        return $com_ids;
        
    }

    public function sendWelcomeEmail($password){
        
        $mail = new MailQueue;
        $mail->mailto = $this->email;
        $mail->subject = "SES AIMS Portal - Registration Details";
        $mail->content = view("mails",["type" => "registration", "user"=>$this,
         "password" => $password]);
        $mail->save();

    }

    public static function getMainClients(){
        return User::where('privilege',3)->whereRaw("users.id =users.parent_user_id ")->where("status","!=",5)
        ->pluck('name','id')->all();
    }

    public static function getMainCompaniesObject(){
        return User::where('privilege',2)->whereRaw("users.id =users.parent_user_id")->where("status","!=",5)->get();
    }

    public static function getMainUsersObject(){
        return User::where('privilege',1)->where("status","!=",5)->get();
    }

    public static function getCompanyUsersObject(){
        if(Auth::user()->privilege == 2){
            return User::where('privilege',2)->where("users.parent_user_id",Auth::user()->id)->where("status","!=",5)->get();
        }
        else{
            return User::where('privilege',2)->whereRaw("users.id =users.parent_user_id")->where("status","!=",5)->get();

        }

    }

    public function getStatus(){
        if($this->status == 0) return 'Active';
        if($this->status == 1) return 'Approval Pending';

        if($this->status == 3) return 'Referred Back';
        if($this->status == 4) return 'Deletion Pending';
        if($this->status == 5) return 'Rejected';

        return "";
    }

    // public function getStatus(){
    //     if($this->status == 0) return 'Approved';
    //     if($this->status == 1) return 'Approval Pending';

    //     if($this->status == 3) return 'Referred Back';
    //     if($this->status == 4) return 'Deletion Pending';
    //     if($this->status == 5) return 'Rejected';

    //     return "";
    // }

    public function getType(){
        if($this->privilege == 1) return 'SES Admin';
        if($this->privilege == 2) return 'Custodian';
        if($this->privilege == 3) return 'Admin';
        if($this->privilege == 4) return 'PM';

        return "";
    }

    public function getPortfolioCompaniesByDate($date){
        
        $client_id = $this->id;

        $companies = DB::select("SELECT distinct( companies.com_id) from user_voting_company inner join companies on user_voting_company.com_id = companies.com_id where user_id = $client_id and user_voting_company.add_date <= '$date'  UNION SELECT distinct( companies.com_id) from user_voting_company_delete inner join companies on user_voting_company_delete.com_id = companies.com_id where user_id = $client_id and user_voting_company_delete.add_date <= '$date' and user_voting_company_delete.delete_date > '$date' ");

        $company_ids = [];

        foreach ($companies as $com) {
            $company_ids[] = $com->com_id;
        }

        return $company_ids;
        
    }

    public static function getRandPassword(){
        $string1 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $string2 = "abcdefghijklmnopqrstuvwxyz";
        $string3 = "0123456789";
        $string4 = "$#@*^%";
        $string5 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789$#@*^%";

        $n = rand(0, strlen($string1) - 1);
        $rand_pwd =  $string1[$n];

        for ($i=0; $i < 2; $i++) { 
            $n = rand(0, strlen($string2) - 1);
            $rand_pwd .=  $string2[$n];
        }

        $n = rand(0, strlen($string3) - 1);
        $rand_pwd .=  $string3[$n];

        $n = rand(0, strlen($string4) - 1);
        $rand_pwd .=  $string4[$n];

        for ($i=0; $i < 3; $i++) { 
            $n = rand(0, strlen($string5) - 1);
            $rand_pwd .=  $string5[$n];
        }

        return $rand_pwd;
    }
}


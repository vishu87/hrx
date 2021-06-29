<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

use App\User, DB, Hash, App\Scheme;

class Approval extends Model
{

    protected $table = 'aims_approvals';

    public static function listing(){

        return Approval::select("aims_approvals.status","aims_approvals.created_at","aims_approvals.remarks","u1.name as created_by_name","u2.name as approved_by_name")->join("aims_users as u1","u1.id","=","aims_approvals.created_by")->leftJoin("aims_users as u2","u2.id","=","aims_approvals.approved_by");

    }

    public function getStatus(){
        if($this->status == 0) return 'Pending';
        if($this->status == 1) return 'Approved';
        if($this->status == 2) return 'Referred Back';
    }

    public static function addEntry($type, $entity_type, $entity_id, $user_id){
    	$approval = new Approval;
        $approval->type = $type;
    	$approval->entity_id = $entity_id;
    	$approval->entity_type = $entity_type;
    	$approval->created_by = $user_id;
    	$approval->save();
    }

    public static function addEntryWithApproval($type, $entity_type, $entity_id, $user_id){
        $approval = new Approval;
        $approval->type = $type;
        $approval->entity_id = $entity_id;
        $approval->entity_type = $entity_type;
        $approval->created_by = $user_id;
        $approval->status = 1;
        $approval->remarks = "Auto approved";
        $approval->save();
    }

    public function confirm($user_id, $status, $remarks){

        $approved = ($status == 0) ? true : false;

    	if($this->entity_type == "user"){

    		$user = User::find($this->entity_id);

    		if($approved){
                if(($this->type == "new" || $user->email_updated == 1)){
                    $user->active = 0;
                    
                    $password = User::getRandPassword();

                    $user->password = Hash::make($password);
                    $user->password_check = $password;
                    $user->sendWelcomeEmail($password);
                    $user->email_updated = 0;
                }

                if($this->type == "new" || $this->type == "update"){
                    $user->status = 0; // means active
                } elseif($this->type == "delete") {
                    $user->status = 5; // means deleted
                }

            } else {
                $user->status = 3; // means referred back
            }

            $user->save();

    	}

        if($this->entity_type == "scheme"){
            
            $scheme = Scheme::find($this->entity_id);

            if($approved){

                if($this->type == "new" || $this->type == "update"){
                    $scheme->status = 0; // means active
                } elseif($this->type == "delete") {
                    $scheme->status = 5; // means deleted
                    $scheme->delete_date = date("Y-m-d");
                    $scheme->emptyHolding(date("Y-m-d"));
                }

            } else {

                $scheme->status = 3; // means referred back

            }
            
            $scheme->save();

        }

        $this->approved_by = $user_id;
        $this->remarks = $remarks;
    	$this->status = $approved ? 1 : 2;
    	$this->save();

    	$timestamp = date("Y-m-d H:i:s");

    	if($approved){
            DB::table("change_logs")->where("entity_type",$this->entity_type)->where("entity_id",$this->entity_id)->whereNull("approved_by")->update(array(
                "approved_by" => $user_id,
                "approved_on" => $timestamp,
                "status" => 1
            ));
        }

    }
}


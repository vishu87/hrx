<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth, DB;
use Redirect,Schema ,App\User, Validator, Hash, App\ChangeLog, App\Approval, Response;

class ClientController extends Controller {


    public function getStatusLog($client_id){
      
      $approval_logs = Approval::listing()->where("aims_approvals.entity_id",$client_id)->where("aims_approvals.entity_type","user")->orderBy("aims_approvals.created_at","DESC")->get();

      $view = view("admin.clients.approval",["approval_logs"=>$approval_logs]);

      $data["success"] = true;
      $data["message"] = html_entity_decode($view);

      return Response::json($data,200,array());
    }

    public function exportList(){
      
      $clients = User::select("users.id","users.name","users.email","users.organization_name","users.group_no","u2.name as created_by_name","users.created_at")->join("users as u2","u2.id","=","users.added_by")->where("users.privilege",3)->where("users.status","!=",5)->get();

      $approval_entries = Approval::select("aims_approvals.entity_id","users.name")->join("users as u1","u1.id","=","aims_approvals.entity_id")->leftJoin("users","users.id","=","aims_approvals.approved_by")->where("aims_approvals.entity_type","user")->where("aims_approvals.type","new")->where("aims_approvals.status",1)->where("u1.privilege",3)->pluck("name","entity_id");


      include(app_path().'/ExcelExport/clients_export.php');

    }

    private function getNameFromNumber($num) {
        $numeric = ($num ) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num ) / 26) - 1;
        if ($num2 >= 0) {
            return $this->getNameFromNumber($num2) . $letter;
        } else {
            return $letter;
        }
    }
}

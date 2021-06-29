<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;

class Proxy extends Model
{

    protected $table = 'proxy_ad';

    public $incrementing = false;

    public static function meeting_types(){
    	return array("","AGM", "EGM", "PBL","CCM","CCM","BHM");
    }

    public static function getMeetingType($type){
        if($type == 1){
            return "AGM";
        } else if ($type == 2){
            return "EGM";
        } else if ($type == 3){
            return "PBL";
        } else if ($type == 4){
            return "CCM";
        } else if ($type == 5){
            return "CCM";
        } else if ($type == 6){
            return "BHM";
        } else {
            return "";
        }
    }

    public static function listing(){
    	return Proxy::select('proxy_ad.*','companies.com_name','companies.com_isin')->join('companies','companies.com_id','=','proxy_ad.com_id');
    }

    public static function report_types(){
    	return array("","Proxy Advisory","CGS","Research");
    }

    public function evotingEnded(){
        $today = date("Y-m-d");

        $reference_date = "";
        $reference_time = "";

        if($this->evoting_end) {
            $reference_date = $this->evoting_end;
            $reference_time = $this->evoting_end_time;
        } elseif(strtolower(trim($this->evoting_plateform)) == "physical"){
            // $db_deadline = DB::table("proxy_ad_db_details")->select("deadline_date")->where("report_id",$this->id)->first();
            // if($db_deadline){
            //     $reference_date = $db_deadline->deadline_date;
            // } else {
                $reference_date = date("Y-m-d",strtotime($this->meeting_date) - $this->physical_hours*3600);
            // }

            $reference_time = "17:00:00";
        }

        if($reference_date < $today) return true;
        if($reference_time){
            if($reference_date == $today && $reference_time < date("H:i:s")){
                return true;
            }    
        }

        return false;
    }

    public function evotingAfter3PM(){
        $today = date("Y-m-d");
        $reference_time = "15:00:00";

        if(strtolower($this->evoting_plateform) == "physical"){
            
            $db_deadline = DB::table("proxy_ad_db_details")->select("deadline_date")->where("report_id",$this->id)->first();
            if($db_deadline){
                $reference_date = $db_deadline->deadline_date;
            }

        } else {
            $reference_date = $this->evoting_end;
        }

        if($reference_date < $today) return true;
        if($reference_date == $today && $reference_time < date("H:i:s")){
            return true;
        }

        return false;
    }

    public static function evotingEndedStatic($evoting_end, $evoting_end_time, $evoting_plateform,$report_id, $meeting_date, $physical_hours){

        $today = date("Y-m-d");

        $reference_date = "";
        $reference_time = "";

        if($evoting_end) {
            $reference_date = $evoting_end;
            $reference_time = $evoting_end_time;
        } elseif(strtolower(trim($evoting_plateform)) == "physical"){
            $db_deadline = DB::table("proxy_ad_db_details")->select("deadline_date")->where("report_id",$report_id)->first();
            if($db_deadline){
                $reference_date = $db_deadline->deadline_date;
            } else {
                $reference_date = date("Y-m-d",strtotime($meeting_date) - $physical_hours*3600);
            }

            $reference_time = "17:00:00";
        }

        if($reference_date < $today) return true;
        if($reference_time){
            if($reference_date == $today && $reference_time < date("H:i:s")){
                return true;
            }    
        }

        return false;

        // $today = date("Y-m-d");
        
        // if($evoting_end < $today) return true;

        // if($evoting_end_time){
        //     if($evoting_end == $today && $evoting_end_time < date("H:i:s")){
        //         return true;
        //     }    
        // }

        // return false;
    }

    public static function getResolutionMeetingId($debenture,$report_id,$parent_meeting_id){
        if($debenture == 0) return $report_id;
        else return $parent_meeting_id;
    }

    public static function parentMappingField(){
        return ["result_date","source_of_data","meeting_results","meeting_outcome","is_cancelled","result_initimation_date","result_of_voting","annual_report","released_on","vote_freeze_date"];
    }

    public function allReportIds(){
        $report_ids = [];
        if($this->debenture == 0){
            $report_ids[] = $this->id;
        } else {
            $report_ids = DB::table("proxy_ad")->where("parent_meeting_id",$this->parent_meeting_id)->pluck("id")->toArray();
        }
        return $report_ids;
    }

    public function checkAndAddSplitVoting(){

        $voting_check = DB::table("voting")->select("id","voting_split_id")->where("report_id",$this->id)->whereNotNull("voting_split_id")->get();

        if(sizeof($voting_check) > 0){

            $scheme_ids = DB::table("user_voting")->distinct("scheme_id")->where("report_id",$this->id)->pluck("scheme_id")->toArray();

            foreach ($scheme_ids as $scheme_id) {
                
                foreach ($voting_check as $voting) {

                    $check = DB::table("user_voting")->where("voting_id",$voting->id)->where("scheme_id",$scheme_id)->count();
                    if($check == 0){
                        $split_entry = DB::table("user_voting")->where("voting_id",$voting->voting_split_id)->where("scheme_id",$scheme_id)->first();
                        if($split_entry){
                            DB::table("user_voting")->insert(array(
                                "user_id" => $split_entry->user_id,
                                "scheme_id" => $split_entry->scheme_id,
                                "voting_id" => $voting->id,
                                "report_id" => $split_entry->report_id,
                                "vote" => $split_entry->vote,
                                "comment" => $split_entry->comment,
                                "split_update" => 1
                            ));
                        }
                    }

                }

            }

        }

    }


}


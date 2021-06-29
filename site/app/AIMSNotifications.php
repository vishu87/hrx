<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

use DB;

class AIMSNotifications extends Model {

	protected $table = 'aims_trigger_notifications';

	public static function categoryTypes(){

		return array(
			"1" => "Share Price",
			"2" => "Financials",
			"3" => "Audit",
			"4" => "Shareholding",
			"5" => "Regulations",
			"6" => "Environment/Social",
			"7" => "Directors",
			"8" => "Compliance",
			"9" => "Dividend",
			"10" => "Others",
		);

	}

	public function createMapping(){

		$trigger_id = $this->trigger_id;
		$company_id = $this->company_id;

		$portfolio_user_ids = DB::table("user_voting_company")->where("com_id",$company_id)->pluck("user_id")->toArray();

		if(sizeof($portfolio_user_ids) > 0){
			$user_ids = DB::table("aims_user_triggers")->where("trigger_id",$trigger_id)->where("active",1)->whereIn("user_id",$portfolio_user_ids)->pluck("user_id")->toArray();
			if(sizeof($user_ids) > 0){

				foreach ($user_ids as $user_id) {
					$check = DB::table("aims_notification_mapping")->where("user_id",$user_id)->where("notification_id",$this->id)->first();
					if(!$check){
						DB::table("aims_notification_mapping")->insert(array(
							"user_id" => $user_id,
							"notification_id" => $this->id
						));
					}
				}

			}
		}

	}
}
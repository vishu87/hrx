<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
class Company extends Model
{

    protected $table = 'companies';

    protected $primaryKey = 'id';
  	// public static function sub_status(){
   //      return DB::table('subscription_status')->select('id','status')->get();
   //  }
    public static function subscriptions(){

		return array(
			"1" => "Active",
			"2" => "Inactive"
		);

	}

}


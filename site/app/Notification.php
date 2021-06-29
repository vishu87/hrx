<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $table = 'notifications';

    public static function reasons(){
    	return [
            "amendment" => "Amendment in resolution(s) by company",
            "withdrawal" => "Withdrawal of resolution(s) by company",
        ];
    }
    
}

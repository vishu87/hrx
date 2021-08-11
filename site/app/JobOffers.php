<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class JobOffers extends Model
{

    protected $table = 'job_offers';

    public static function statusName($value){
      switch($value){
          case 0:
              return "Active";
          case 1:
              return "Withdrawn";
          case 2:
              return "Joined";
          default:
              return "";
      }
   }

}


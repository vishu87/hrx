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

    public static function check_auth($api_token){
        $user = User::where('api_token',$api_token)->first();
        if($user){
            return $user;
        }else{
            die();
        }
    }
   
    public function sendWelcomeEmail($password){
        
        // $mail = new MailQueue;
        // $mail->mailto = $this->email;
        // $mail->subject = "Sortal Student - Registration Details";
        // $mail->content = view("mails",["type" => "registration", "user"=>$this, "password" => $password]);
        // $mail->save();

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

    

    public static function fileExtensions(){
        return array (
            "pdf" , "jpg" , "jpeg", "xls","xlsx" ,"png" , "JPG" ,"JPEG" , "PDF" ,"PNG","XLSX","XLS","csv","CSV","docx","DOCX","pdf","PDF"
        );
    }
}


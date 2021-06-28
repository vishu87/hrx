<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth, App\Proxy, App\Approval;
use DB, App\User, Response,Input;

class FrontController extends Controller {

    public function home(){
        

        return view("front-end.home");

    }

    public function aboutUs($id = "", $var2){
        

        return "aboutUs".$id." - ".$var2;

    }

}

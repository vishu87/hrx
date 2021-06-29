<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Portfolio, App\User, Input, Response ,Validator, Redirect, DB;

use PhpOffice\PhpSpreadsheet\IOFactory;

class PortfolioController extends Controller {

    //functions for user type 3
    public function clientPortfolio(){

    	if(Auth::user()->privilege == 2){
    		$type = 1;
    	}else{
    		$type = 2;
    	}

        $sidebar = 'portfolio';
        $subsidebar = 'portfolio';
        
        return  view('clients.portfolio.list',compact('sidebar','subsidebar','type'));
    }

    public function uploadPortfolio(){

        $sidebar = 'portfolio';
        $subsidebar = 'upload';
        
        return  view('clients.portfolio.upload',compact('sidebar','subsidebar'));
    }

    public function processPortfolio(){

        $date = date("Y-m-d");

        if(Input::hasFile('portfolio_file')){

            $file = Input::file("portfolio_file");

            $destinationPath = "uploads/";

            $orig_name = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $file_name = "Holding_".Auth::id()."-".strtotime("now").".".$extension;

            if($file->move($destinationPath,$file_name)){
                
                $lines = [];            

                $reader = IOFactory::createReader("Xlsx");
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($destinationPath.$file_name);

                $sheet = $spreadsheet->setActiveSheetIndex(0);
                $highestRow = $sheet->getHighestRow(); 
                $highestColumn = $sheet->getHighestColumn();

                $user_id = Auth::user()->parent_user_id;

                $company_ids = [];
                $current_com_ids = DB::table("user_voting_company")->where("user_id",$user_id)->pluck("com_id")->toArray();

                for ($row = 2; $row <= $highestRow ; $row++) {

                    $isin_code = $spreadsheet->getActiveSheet()->getCell('A'.$row)->getValue();
                    if($isin_code){

                        $company = DB::table("companies")->select("com_id","com_name")->where("com_isin",$isin_code)->first();
                        if($company){
                            $company_ids[] = $company->com_id;
                            
                            $line = $isin_code.",".$company->com_name.",";
                            if(in_array($company->com_id, $current_com_ids)){
                                $line .= "Alredy exists";
                            } else {

                                DB::table("user_voting_company")->insert(array(
                                    "user_id" => $user_id,
                                    "com_id" => $company->com_id,
                                    "add_date" => $date
                                ));
                                $line .= "Company added in portfolio";
                            }
                            $lines[] = $line;
                        } else {
                            $lines[] = $isin_code.",,ISIN not found";
                        }

                    } else {
                        $lines[] = ",,ISIN is blank";
                    }
                }

                $path = "uploads/";
                $output_name = "response_".Auth::id()."_".strtotime("now").".csv";
                $file = fopen($path.$output_name,"w");
                foreach ($lines as $line) {
                    fputcsv($file,explode(',',$line));
                }
                fclose($file);

                $company_to_be_removed = [];

                foreach ($current_com_ids as $rem_com) {
                    if(!in_array($rem_com, $company_ids)){
                        $company_to_be_removed[] = $rem_com;
                    }
                }

                $rem_companies = DB::table("user_voting_company")->where("user_id",$user_id)->whereIn("com_id",$company_to_be_removed)->get();
                foreach ($rem_companies as $rem_com) {
                    DB::table("user_voting_company")->where("id",$rem_com->id)->delete();

                    DB::table("user_voting_company_delete")->insert(array(
                            "user_id" => $user_id,
                            "com_id" => $rem_com->id,
                            "add_date" => $rem_com->add_date,
                            "delete_date" => $date
                        ));
                }

                $added_by = DB::table('aims_users')->where('id',$user_id)->first();

                DB::table('portfolio_upload_logs')->insert([
                    "user_id" => $user_id,
                    "response_file" => $path.$output_name,
                    "added_by" => Auth::user()->id,
                ]);

            } else {
                return Redirect::back()->with("failure","There is something error please check File extention");
            }

        } else {
            return Redirect::back()->with("failure","Please select the file");
        }

        
        return Redirect::back()->with("success","File has been successfully uploaded");
    }

    public function getLogs(){
        $id = Input::get('id');
        $logs = DB::table('portfolio_upload_logs as pl')->where('user_id',$id)
        ->select('pl.id','pl.response_file','pl.created_at','pl.user_id','pl.added_by',
            'user.name as user_name','added.name as added_name')
        ->leftJoin('aims_users as user', 'user.id', '=', 'pl.user_id') 
        ->leftJoin('aims_users as added', 'added.id', '=', 'pl.user_id') 
        ->get();

        $data['success'] = true;
        $data['logs'] = $logs;

        return Response::json($data, 200,[]);
    }

    public function initials(){
    	if(Input::get('type') == 1){
    		$clients = User::getMainClientsObject();
            foreach ($clients as $client) {
                $data['clients'][] = ["value"=>$client->id,"text"=>$client->name];
            }
    	}

    	$data['success'] = true;
    	return Response::json($data,200,array());
    }

    public function getPortfolio(){
        // sleep(5);
        
    	$validator = Validator::make([
            "add_date"=> Input::get("add_date"),
            "client_id" => Input::get("client_id")
        ], [
            'add_date' => 'required|date',
            "client_id" => "required"
        ]);

        if ($validator->fails()) {
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
    		return Response::json($data,200,array());
        }

        $date = date("Y-m-d",strtotime(Input::get("add_date")));

        if(Auth::user()->privilege == 2){
            $client_id = Input::get("client_id");
        } else {
            $client_id = (Auth::user()->parent_user_id)?Auth::user()->parent_user_id:Auth::id();
        }

        $portfolio = Portfolio::getUserPortfolioCompanies($client_id,$date);

		$data['portfolio'] = $portfolio;
		$data['success'] = true;
    	return Response::json($data,200,array());
    }


    public function exportPortfolio($client_id, $add_date){
        $date = date("Y-m-d",strtotime($add_date));

        if(Auth::user()->privilege == 2){
            $client_id = $client_id;
        } else {
            $client_id = (Auth::user()->parent_user_id)?Auth::user()->parent_user_id:Auth::id();
        }

        $portfolio = Portfolio::getUserPortfolioCompanies($client_id,$date);

        include(app_path().'/ExcelExport/portfolio_export.php');
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

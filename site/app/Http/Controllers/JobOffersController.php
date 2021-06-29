<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth, DB;
use Redirect,Schema ,App\User, Validator, Hash, Input, App\JobOffers, Response;

class JobOffersController extends Controller {

    public function index(){
        $offers = JobOffers::select('job_offers.id','job_offers.company_id','job_offers.candidate_name','job_offers.email','job_offers.phone_no')
        ->where('company_id',Auth::user()->company_id)->get();
        $sidebar = 'job_offers';
        $subsidebar = 'job_offers';
        return  view('companies.job_offers.list',compact('offers','sidebar','subsidebar'));
    }

    public function addOfferLetter(){
        $id  = 0;
        if (Input::has('id') ){
          $id = Input::get("id");
        }
        $sidebar = 'job_offers';
        $subsidebar = 'job_offers';
        return  view('companies.job_offers.add',compact('sidebar','subsidebar','id'));
    }

    public function offersInit(){

      if (Input::get("offer_id") > 0) {
        $offer = JobOffers::find(Input::get("offer_id"));
        $offer->acceptance_date = date('d-m-Y',strtotime($offer->acceptance_date));
        $offer->expected_joining_date = date('d-m-Y',strtotime($offer->expected_joining_date));

        $data['offer']= $offer;
      }
      $data['success'] = true;
      return Response::json($data, 200, []);
    }

    public function deleteOffer($id){
      $offer = JobOffers::find($id);
      if ($offer) {
        $offer->delete();
        return Redirect::back()->withInput()->with('success','Job Offer is deleted succesfully');
      }else{
        return Redirect::back()->withInput()->with('faliure','Job Offer is not exist');
      }

    }    

    public function store(Request $request){
      $cre = [
        "candidate_name" => $request->candidate_name,
        "pan_no" => $request->pan_no,
        "phone_no" => $request->phone_no,
        "email" => $request->email,
        "acceptance_date" => $request->acceptance_date,
        "expected_joining_date" => $request->expected_joining_date,
        "notification_email" => $request->notification_email,
        "notes" => $request->notes,
        "req_no" => $request->req_no,
      ];

      $rules = [
        "candidate_name" => "required",
        "pan_no" => "required",
        "phone_no" => "required",
        "email" => "required|email|unique:job_offers",
        "acceptance_date" => "required|date",
        "expected_joining_date" => "required|date|after:today",
        "notification_email" => "required",
        "notes" => "required",
        "req_no" => "required",

      ];

      if($request->id){
            $check = JobOffers::find($request->id);
            if($check){
                $rules['email'] = 'required|email|unique:job_offers,email,'.$check->id;
            }
        }
      
      $validator = Validator::make($cre,$rules);
      if ($validator->passes()) {

        $checkPan = DB::table('job_offers')->where('pan_no',$request->pan_no)->where('id','!=',$request->id)->get();

        if (sizeof($checkPan) > 0) {
          $data['success'] = false;
          $data["message"] = "The pan no has already been taken.";

          return Response::json($data, 200, []);
        }

        $request->acceptance_date = date('Y-m-d',strtotime($request->acceptance_date));
        $request->expected_joining_date = date('Y-m-d',strtotime($request->expected_joining_date));
        if($request->id){
          $offer = JobOffers::find($request->id);
          $data["message"] = "JobOffer Letter is updated succesfully";
        }else{
          $offer = new JobOffers;
          $data["message"] = "JobOffer Letter is created succesfully";
        }
        $offer->company_id = Auth::user()->company_id;
        $offer->candidate_name = $request->candidate_name;
        $offer->pan_no = $request->pan_no;
        $offer->phone_no = $request->phone_no;
        $offer->email = $request->email;
        $offer->acceptance_date = $request->acceptance_date;
        $offer->expected_joining_date = $request->expected_joining_date;
        $offer->notification_email = $request->notification_email;
        $offer->notes = $request->notes;
        $offer->req_no = $request->req_no;
        $offer->save();
        $data["success"] = true;
      }else {
        $data["success"] = false;
        $data["message"] = $validator->errors()->first();
      }
      return Response::json($data, 200 ,[]);
    }
}

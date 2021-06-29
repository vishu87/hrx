@extends('layout')

@section('content')

     
	<div class="card card-custom ng-cloak" ng-controller="JopOffersCtrl" ng-init="offer_id= '{{$id}}';offersInit()">

        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    @{{formData.id?'Update Offer Letter Details':'Add New Offer Letter'}}
                </h3>
            </div>
            <div class="card-toolbar">
                <a href="{{url('/company/job-offers')}}" class="btn btn-dark" > Go Back </a>
            </div>
        </div>

        <div class="card-body">

            @if(Session::has("success"))
                <div class="alert alert-success">
                    {{Session::get("success")}}
                </div>
            @endif
            @if(Session::has('failure'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fa fa-ban-circle"></i><strong>Failure!</strong> {{Session::get('failure')}}
                </div>
            @endif

            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="spinner-border" role="status" ng-if="processing">
                      <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <form name="offerForm" ng-submit="onSubmit(offerForm.$valid)" novalidate>
                <div class="row">   
                    <div class="col-md-3 form-group">
                        <label>Candidate Name</label>
                        <span class="text-danger">*</span>
                        <input type="text" class="form-control" ng-model="formData.candidate_name" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Pan no</label>
                        <span class="text-danger">*</span>
                        <input type="text" class="form-control" ng-model="formData.pan_no" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Phone no</label>
                        <span class="text-danger">*</span>
                        <input type="text" class="form-control" ng-model="formData.phone_no" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Email</label>
                        <span class="text-danger">*</span>
                        <input type="text" class="form-control" ng-model="formData.email" 
                        required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Req No</label>
                        <span class="text-danger">*</span>
                        <input type="text" class="form-control" ng-model="formData.req_no" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Acceptance Date</label>
                        <span class="text-danger">*</span>
                        <input type="text" class="form-control datepicker" ng-model="formData.acceptance_date" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Expected join Date</label>
                        <span class="text-danger">*</span>
                        <input type="text" class="form-control datepicker" ng-model="formData.expected_joining_date" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Alternative Email</label>
                        <span class="text-danger">*</span>
                        <input type="text" class="form-control" ng-model="formData.notification_email" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Notes</label>
                        <span class="text-danger">*</span>
                        <input type="text" class="form-control" ng-model="formData.notes" 
                        required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            @{{formData.id?'update':'Add'}}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
	

@endsection
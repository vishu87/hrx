@extends('layout')

@section('content')

    
	<div class="card card-custom ng-cloak" ng-controller="companyCtrl" ng-init="company_id='{{$id}}';companyInit()">

        <div class="card-header">
            <div class="card-title"><h3 class="card-label">@{{company_id > 0 ? 'Update Company Details':'Add Company Details'}}</h3></div>

            <div class="card-toolbar">
                <a href="{{url('/admin/companies')}}" class="btn btn-dark" >Go Back</a>
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
            
            <div class="" >
                <form name="companyForm" ng-submit="onSubmit(companyForm.$valid)" novalidate>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label class="label-control">Name</label>
                            <span class="text-danger">*</span>
                            <input type="text" class="form-control" ng-model="formData.name" required> 
                        </div>
                        <div class="form-group col-md-3">
                            <label class="label-control">Email</label>
                            <span class="text-danger">*</span>
                            <input type="text" class="form-control" ng-model="formData.email" required> 
                        </div>
                        <div class="form-group col-md-3">
                            <label class="label-control">Phone No</label>
                            <span class="text-danger">*</span>
                            <input type="text" class="form-control" ng-model="formData.phone_no" required> 
                        </div>
                        <div class="form-group col-md-3">
                            <label class="label-control">Domain</label>
                            <span class="text-danger">*</span>
                            <input type="text" class="form-control" ng-model="formData.domain" required> 
                        </div>
                        <div class="form-group col-md-3">
                            <label class="label-control">Notification(Days)</label>
                            <span class="text-danger">*</span>
                            <input type="text" class="form-control" ng-model="formData.notification" required> 
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-control">Address</label>
                            <span class="text-danger">*</span>
                            <input type="text" class="form-control" ng-model="formData.address" required> 
                        </div>
                        <div class="form-group col-md-2">
                            <label class="label-control">Subsription Status</label>
                            <select ng-model="formData.subscription_id" class="form-control" convert-to-number>
                                <option value="">Select</option>
                                <option value="@{{key}}" ng-repeat="(key,subscription) in subscriptions">@{{subscription}}</option>

                            </select>
                            
                        </div>

                    </div>
                  
                    <div class="hor-line" style=" border: 1px solid #eee;width: 100%; margin-top: 20px;"></div>

                    <div style="margin: 20px 0px 10px;">
                        <h5>Contact Persons</h5>
                    </div>
                        
                    <div ng-repeat="person in formData.morePersons track by $index">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" ng-model="person.name">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" ng-model="person.email">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Phone</label>
                                <input type="text" class="form-control" ng-model="person.phone_no">
                            </div>
                            <div class="col-md-3" style="margin-top: 25px">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-sm btn-danger" style="width: 33px;" ng-click="spliceMorePersons($index)">
                                    <i class="fa fa-close" style="font-size: 14px;"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" class="btn btn-sm btn-primary" ng-click="addMorePersons()">Add more</button>
                        </div>
                    </div>
                    
                    <div class="row" style="margin-top: 20px">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
	

@endsection
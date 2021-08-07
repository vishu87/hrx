@extends('layout')

@section('content') 
	
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pb-0">
            <div class="card-title"><h3 class="card-label">Company Details</h3></div>
            <div class="card-toolbar">
                <a href="{{url('/admin/companies')}}" class="btn btn-dark" >Go Back</a>
            </div>
        </div>
        <div class="card-body pt-0">
            <div>
                <table style="width: 90%;" class="table table-bordered">
                    <tr>
                        <th>Name</th>
                        <td>{{$company->name}}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{$company->email}}</td>
                    </tr>
                    <tr>
                        <th>Phone number</th>
                        <td>{{$company->phone_no}}</td>
                    </tr>
                    <tr>
                        <th>Domain</th>
                        <td>{{$company->domain}}</td>
                    </tr>
                    <tr>
                        <th>Notification (Days)</th>
                        <td>{{$company->notification}}</td>
                    </tr>
                    <tr>
                        <th>Subscription status</th>
                        <td>{{$subscriptions[$company->subscription_id]}}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{$company->address}}</td>
                    </tr>
                </table>
            </div>
            <div>
                <div><h4 class="card-label pb-1">Contact Persons</h4></div>
                <table style="width: 60%;" class="table table-bordered" >
                    <thead>
                        <tr>
                            <th>SNO.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sn =1; ?>
                        @foreach($persons as $person)
                        <tr>
                            <td>{{$sn++}}</td>
                            <td>{{$person->name}}</td>
                            <td>{{$person->email}}</td>
                            <td>{{$person->phone_no}}</td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div>
                <div><h4 class="card-label pb-1">Users Details</h4></div>
                <table class="table table-bordered" style="width: 60%;">
                    <thead>
                        <tr >
                            <th>SNO.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone number</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php $sn =1; ?>
                        @foreach($users as $user)
                        <tr>
                            <td>{{$sn++}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->phone_number}}</td>
                            <td></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>  
            </div>
            <div ng-controller="companyUserCtrl" ng-init=" company_id = {{(isset($company->id))?$company->id:''}};">
                <div class="card-toolbar">
                <!-- <a href="{{url('/admin/users/add')}}" class="btn btn-primary" >Add New</a> -->
                    <a ng-click="adduserModal()" href="javascript:;" class="btn btn-primary btn-sm">
                        Add New
                    </a> 
                </div>
                <div class="modal fade" id="usermodal" >
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add New user</h5>
                            </div>
                            <div class="modal-body">
                                <form name="userForm" ng-submit="onUserSubmit(userForm.$valid)" novalidate>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Name</label>  
                                                <input type="text" ng-model="formData.name" class="form-control" required="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Email</label>  
                                                <input type="text" ng-model="formData.email" class="form-control" required="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Phone number</label>  
                                                <input type="text" ng-model="formData.phone_number" class="form-control" required="">
                                            </div>
                                        </div>

                                    </div>  
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-sm " ladda="processing">Submit</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
      
    </div>

@endsection
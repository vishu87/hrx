@extends('layout')

@section('content') 
	
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pb-0">
            <div class="card-title"><h3 class="card-label">Company Details - {{ $company->name }}</h3></div>
            <div class="card-toolbar">
                
                <a href="{{url('/admin/companies/add?id='.$company->id)}}" class="btn mr-1 btn-warning" ng-click="editcompany(company)" ladda="company.processing">Edit</a>
                <a href="{{url('/admin/companies')}}" class="btn btn-dark" >Go Back</a>
            </div>
        </div>
        <div class="card-body pt-0">
            <div>
                <table class="table table-bordered">
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
                        <td>{{ $subscriptions[$company->status] }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{$company->address}}</td>
                    </tr>
                </table>
            </div>
            <hr>
            <div>
                <div><h4 class="card-label pb-1">Contact Persons</h4></div>
                <table class="table table-bordered" >
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
            <hr>
            <div>
                <div><h4 class="card-label pb-1">Login Details</h4></div>
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
                <table class="table table-bordered" id="job_offers">
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
                            <td>
                                <a href="{{ url('admin/users/active/'.$user->id) }}" class="btn {{ $user->active == 0 ? 'btn-warning' : 'btn-primary' }} btn-sm">{{ $user->active == 0 ? 'Mark Inactive' : 'Mark Active' }}</a>
                                <a href="{{ url('admin/users/delete/'.$user->id) }}" class="btn btn-danger btn-sm btn-icon" onclick="return confirm('Are you sure to delete the user?')"><i class="fa fa-remove"></i></a>
                            </td>
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
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Name</label>  
                                                <input type="text" ng-model="formData.name" class="form-control" required="">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Email</label>  
                                                <input type="text" ng-model="formData.email" class="form-control" required="">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
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
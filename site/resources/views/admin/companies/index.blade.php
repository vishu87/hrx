@extends('layout')

@section('content') 
	
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pb-0">
            <div class="card-title"><h3 class="card-label">Companies</h3></div>
            <div class="card-toolbar">
                <a href="{{url('/admin/companies/add')}}" class="btn btn-primary" >Add New</a>
            </div>
        </div>
        <div class="card-body pt-0">
            <div ng-controller="companyCtrl" ng-init="listing()">   
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="spinner-border" role="status" ng-if="processing">
                          <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="table-responsive ng-cloak">
                    <table class="table">
                        <thead>
                            <tr >
                                <th class="text-dark-50 font-weight-lighter">SN</th>
                                <th class="text-dark-50 font-weight-lighter">Name</th>
                                <th class="text-dark-50 font-weight-lighter">Email</th>
                                <th class="text-dark-50 font-weight-lighter">Subscription status</th>
                                <th class="text-dark-50 font-weight-lighter">Mobile</th>
                                <th class="text-dark-50 font-weight-lighter">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="company in companies">
                                <td>@{{$index+1}}</td>
                                <td>@{{company.name}}</td>
                                <td>@{{company.email}}</td>
                                <td>@{{company.sub_status}}</td>
                                <td>@{{company.phone_no}}</td>
                                <td>
                                    <a href="{{url('/admin/companies/view')}}/@{{company.id}}" class="btn btn-sm btn-success">View</a>
                                   
                                    <button class="btn btn-danger btn-sm ml-1" ng-click="deleteCompany(company,$index)" style="width: 33px;"><i class="fa fa-close" style="font-size: 15px;"></i></button>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>

@endsection
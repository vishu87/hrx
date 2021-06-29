@extends('layout')

@section('content') 
	
    <div class="card card-custom ng-cloak" ng-controller="adminUserCtrl" ng-init="adminUserInit()">
        <div class="card-header flex-wrap border-0 pb-0">
            <div class="card-title"><h3 class="card-label">Users</h3></div>
            <div class="card-toolbar">
                <a href="{{url('/admin/users/add')}}" class="btn btn-primary" >Add New</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="spinner-border" role="status" ng-if="processing">
                  <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="">   
                <div class="table-responsive ng-cloak">
                    <table class="table">
                        <thead>
                            <tr >
                                <th class="text-dark-50 font-weight-lighter">SN</th>
                                <th class="text-dark-50 font-weight-lighter">Name</th>
                                <th class="text-dark-50 font-weight-lighter">Email</th>
                                <th class="text-dark-50 font-weight-lighter">Mobile</th>
                                <th class="text-dark-50 font-weight-lighter">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="user in users">
                                <td>@{{$index+1}}</td>
                                <td>@{{user.name}}</td>
                                <td>@{{user.email}}</td>
                                <td>@{{user.phone_number}}</td>
                                <td>
                                    <a href="{{ url('admin/users/add/') }}/@{{user.id}}" class="btn btn-warning btn-sm">Edit</a>
                                    <button class="btn btn-danger btn-sm ml-1" ng-click="deleteUser(user,$index)" style="width: 33px;"><i class="fa fa-close" style="font-size: 15px;"></i></button>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                

                <div class="modal fade " id="editClient" tabindex="-1" role="basic" aria-hidden="true" >
                    <div class="modal-dialog modal-lg" style="width: 80%">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">@{{open_client.name}}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- ng-submit="updateClient()" -->
                                <form >
                                    <div class="row">
                                            
                                            <div class="col-md-2 form-group">
                                                <label>Ses Client</label><br>
                                                <label>
                                                    <input type="radio" ng-model="open_client.ses_client" ng-value="1" ng-required="!open_client.ses_client && open_client.ses_client != 0"> &nbsp;Yes
                                                </label>&nbsp;&nbsp;&nbsp;
                                                <label>
                                                    <input type="radio" ng-model="open_client.ses_client" ng-value="0" ng-required="!open_client.ses_client && open_client.ses_client != 0"> &nbsp;No
                                                </label>
                                            </div>
                                            <div class="col-md-3 form-group" ng-show="open_client.ses_client==1">
                                                <label>Ses Client Id</label>
                                                <input type="text" class="form-control" ng-required="open_client.ses_client == 1" ng-model="open_client.ses_client_id">
                                            </div>
                                            <div class="col-md-6 form-group" ng-show="open_client.ses_client==1">
                                                <label>Ses Token</label>
                                                <input type="text" class="form-control" ng-model="open_client.ses_token" ng-required="open_client.ses_client == 1">
                                            </div>
                                            <div class="col-12">
                                                <button class="btn btn-primary" >Update</button>
                                            </div>
                                        
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                <!-- /.modal-dialog -->
                </div>
            </div>
        </div>
    </div>

@endsection